<?php

namespace App\Http\Controllers\Api;

use App\Models\Lead;
use App\Models\Dealer;
use App\Models\Complaint;
use App\Models\LeadJson;
use App\Models\MaterialType;
use App\Models\MaterialReport;
use Illuminate\Http\Request;
use App\Helpers\UtilityHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\LeadValidation;


class LeadController extends Controller
{
    /**
     * Get Leads
     *
     * (optional parameters)
     *
     * @param string $lType
     * @param string $stateId
     * @param string $cityId
     * @param string $talukaId
     * @param string $search
     *
     * @return json
     */

    public  function getLeads(Request $request)
    {

        $leadPagination = config('constant.leadPagination');
        $user = \Auth::user();

        $lType = $request->lType ?? '';
        $stateId = $request->stateId ?? '';
        $cityId = $request->cityId ?? '';
        $talukaId = $request->talukaId ?? '';
        $search = $request->search ?? '';

        $getSchedules = Lead::getSelectQuery($user->id)

            ->when(!empty($lType), function ($query) use ($request) {
                return $query->whereIn('leads.lType',  $request->lType);
            })
            ->when(!empty($stateId), function ($query) use ($request) {
                return $query->whereIn('leads.pStateId',  $request->stateId);
            })
            ->when(!empty($cityId), function ($query) use ($request) {
                return $query->whereIn('leads.pCityId',  $request->cityId);
            })
            ->when(!empty($talukaId), function ($query) use ($request) {
                return $query->whereIn('leads.pTalukaId',  $request->talukaId);
            })
            ->when(!empty($search), function ($query) use ($request) {
                return $query->where(function ($query) use ($request) {
                    $query->where('leads.lFullName', "like", "%" . $request->search . "%");
                });
            })
            ->where(['leads.userId' => $user->id])
            ->orderby('leads.createdAt','desc')
            ->paginate($leadPagination);

        return $this->toJson([
            'hasMore' => $getSchedules->hasMorePages(),
            'totalCount' => $getSchedules->total(),
            'getLeads' => $getSchedules->items(),

        ]);
    }

    /**
     * Get Lead Details
     *
     * @param string $id  (lead Id)
     *
     * @return json
     */

    public  function leadDetails(Request $request)
    {
        $user = \Auth::user();
        $pathPhoto = config('constant.baseUrlS3') . config('constant.dealer_image');

        $id = $request->id ?? '';
        $leadDetails =  Lead::leadDetails()
                            ->selectRaw('dealers.address2,dealers.wpMobileNumber,
                            IF(ISNULL(photo) or photo = "", "", CONCAT("'.$pathPhoto.'","/",photo)) as photo,
                            photo as shortNamePhoto,
                            dealers.dob,
                            DATE_FORMAT(dealers.dob, "' . config('constant.schedule_date_format') . '") as dobFormate,
                            yearOfIncorporation,aadharNo,familyDetails,msRequriedMaterial,
                            piTypeOfSite,piStatusOfSite,piArea,piEstimateCost,piProjectEngineer,
                            piArchitect,piExecutor,cdPan,bdBankName,bdBankAddress,
                            bdAccountNumber,bdIfscCode,bdNatureOfAccount')
                            ->leftjoin('dealers','dealers.id','leads.rjDealerId')
                            ->where(['leads.id' => $id,'leads.userId' => $user->id])
                            ->first();

        if(empty($leadDetails))
        {
            return $this->toJson([],trans('api.lead.not_found'),0);
        }

        return $this->toJson([
            'leadDetails' => $leadDetails,
        ]);
    }

    /**
     * Get Material Type Array(Straight Or Bend)
     *
     * @param string $id  (lead Id or complaint Id) (option)
     *
     * @return json
     *
     */
    public function getMaterialType(Request $request)
    {

        $this->validate($request, [
            'action' => 'required|in:create,update',
            'module' => 'required|in:lead,complaint',
            'id' => 'required_if:action,update',
        ]);

        $user = \Auth::user();

        $materialType = MaterialType::getMaterialType()->get();

        $twoArrays = ['Straight','Bend'];

        $output = "";

        $id = $request->id;

        if(!empty($id) && $request->module == "lead")
        {
            $leadDetails =  Lead::leadDetails()->where(['leads.id' => $request->id,'leads.userId' => $user->id])->first();


            if(!empty($leadDetails))
            {
                $materialTypeJson = collect(json_decode($leadDetails->materialType,true));
                $output = $materialTypeJson->keyBy('materialId');
            }
        }

        if(!empty($id) && $request->module == "complaint")
        {
            $leadDetails =  Complaint::where(['complaints.id' => $request->id,'complaints.userId' => $user->id])->first();


            if(!empty($leadDetails))
            {
                $materialTypeJson = collect(json_decode($leadDetails->materialType,true));
                $output = $materialTypeJson->keyBy('materialId');
            }
        }

        foreach($materialType as $key=>$values)
        {
            if(!empty($output) && isset($output[$values->id]))
            {
                $values->totalQty = (float) $output[$values->id]['totalQty'];

                foreach($values->straightBend as $straightBends)
                {
                    if(isset($output[$straightBends->id]))
                    {
                        $straightBends->totalQty = (float) $output[$straightBends->id]['totalQty'];
                    }
                }
            }

            $grouped = $values->straightBend->groupBy('msType');


            if(count($grouped) > 0 )
            {
                foreach($twoArrays as $key1=>$twoArray)
                {
                    $values->straightBend[$key1] = ["msType" => $twoArray,'size' => $grouped[$twoArray]];
                }

                for($i=2;$i<=18;$i++)
                {
                    unset($values->straightBend[$i]);
                }
            }

        }

        return $this->toJson([
            'materialTypeList' => $materialType,
        ], '');
    }

     /**
     * Create Lead
     *
     * @param  LeadValidation $request
     *
     * @return json
     */

    public function createLead(LeadValidation $request)
    {
        $user       = \Auth::user();
        $userId     =  $user->id;

        $leadRecord = Lead::create($request->merge(['userId' => $userId])->all());

        $this->materialTypeCheck($leadRecord,$request->materialType,$userId);

        return $this->toJson([
            'leadRecord' => $leadRecord,
        ], trans('api.lead.success',['type' => 'Create']));
    }

    /**
     * material Type Check
     *
     * @param  leadRecord  (lead Record Object)
     * @param  materialType $materialType (Request user side selected material)
     * @param string  $userId
     * @return boolean
     */
    public function materialTypeCheck($leadRecord,$materialType,$userId)
    {
        $materialTypes = $materialType;

        $mtArray    = [];
        $mtListView = '';
        $mtTitle    = '';
        $orderQty = 0;
        if(!empty($materialTypes))
        {
            foreach($materialTypes as $key=>$materialType)
            {
                if(!empty($materialType['totalQty']))
                {
                    $mtArray[] = [
                        'id'         => \Str::uuid(),
                        'mrType' => 'Lead',
                        'userId'     => $userId,
                        'leadId'     => $leadRecord->id,
                        'materialId' => $materialType['id'],
                        'totalQty'   => $materialType['totalQty'],
                        'mName' => $materialType['mName'],
                        'msType' => NULL,
                        'msName' => NULL,
                        'materialTypeId' => NULL,
                        // 'isParent' => !empty($materialType['straightBend']) ? 1 :0,
                        'isParent' =>  1,
                    ];
                    $mtListView .= $materialType['mName'] .', '.$materialType['totalQty'] .' mt.  | ';
                    $mtTitle    .=  $materialType['mName'] .',';

                    $orderQty += $materialType['totalQty'];


                        for($i=0;$i<count($materialType['straightBend']);$i++)
                        {
                            foreach($materialType['straightBend'][$i]['size'] as $key1 => $sizes)
                            {
                                if(!empty($sizes['totalQty']))
                                {
                                    $mtArray[] = [
                                        'id'         => \Str::uuid(),
                                        'mrType' => 'Lead',
                                        'userId'     => $userId,
                                        'leadId'     => $leadRecord->id,
                                        'materialId' => $sizes['id'],
                                        'totalQty'   => $sizes['totalQty'],
                                        'mName' => $materialType['mName'],
                                        'msType' => $sizes['msType'],
                                        'msName'   => $sizes['msName'],
                                        'materialTypeId' => $materialType['id'],
                                        'isParent' => 0,
                                    ];
                                }

                            }
                        }
                }
            }

                if(!empty($mtArray))
                {
                    MaterialReport::insert($mtArray);
                }

                $leadRecord->materialType = json_encode($mtArray) ?? "";
                $leadRecord->mtListView = substr_replace($mtListView ,"", -4)?? "";
                $leadRecord->mtListEdit = substr_replace($mtTitle ,"", -1) ?? "";
                $leadRecord->orderQty = $orderQty;
                $leadRecord->save();

            LeadJson::updateOrCreate(
                ['leadId' => $leadRecord->id],
                ['leadId'=> $leadRecord->id,'lead_mt_jsons' => json_encode($materialTypes)]
            );
        }

        return true;
    }

    /**
     * move Lead
     *
     * @param  id  (leadid)
     * @return json
     */
    public function moveLead(Request $request)
    {

        // echo "<pre>";
        // print_r($request->all());
        // exit;
        $user = \Auth::user();
        $userId =  $user->id;

        $leadDetails =  Lead::leadDetails()->where(['leads.id' => $request->id,'userId' => $user->id])->first();



        if(empty($leadDetails))
        {
            return $this->toJson([],trans('api.lead.not_found'),0);
        }

        if($leadDetails->moveStatus !='Pending')
        {
            return $this->toJson([],trans('api.lead.already_moved'),0);
        }

        $msg = '';

        if($leadDetails->lType == "Material Lead")
        {
            $leadDetails->moveStatus = 'Sales';

            $msg = trans('api.lead.material_lead');
        }

        if($leadDetails->lType == "Dealership Lead")
        {
            $leadDetails->moveStatus = 'Dealer';

            $dealer = $this->makeDealer($leadDetails,$request);

            $dealerCreate =Dealer::create($dealer);

            $leadDetails->rjDealerId = $dealerCreate->id;

            $msg = trans('api.lead.dealership_lead');
        }

        $leadDetails->save();

        return $this->toJson([
            'leadDetails' => $leadDetails,
        ], $msg);


    }

    /**
     * makeDealer
     *
     * @param  mixed $leadDetails
     * @return array
     */
    public function makeDealer($leadDetails,$request)
    {


        return [
            'userId' => $leadDetails->userId,
            'fType' => 'Dealer',
            'dType' => 'Main Dealer',
            'name' => $leadDetails->lFullName,
            'dealerId' => UtilityHelper::generateUniqueCode('dealers', 'dealerId'),
            'firmName' => $leadDetails->firmType ?? '',
            'address1' => $leadDetails->pAddress ?? '',
            'address2' => $leadDetails->pAddress2 ?? '',
            'stateId' => $leadDetails->pStateId,
            'sName' => $leadDetails->pSName,
            'cityId' => $leadDetails->pCityId,
            'cName' => $leadDetails->pCName,
            'talukaId' => $leadDetails->pTalukaId,
            'tName' => $leadDetails->pTName,
            'pinCode' => $leadDetails->pPincode,
            'regionId' => $leadDetails->dRegionId,
            'rName' => $leadDetails->dRName,
            'wpMobileNumber' => $request->wpMobileNumber ?? '',
            'mobileNumber' => $leadDetails->lMobileNumber,
            'email' => $leadDetails->lEmail,
            'dob' => !empty($request->dob) ? date('Y-m-d',strtotime($request->dob)) : NULL,
            'photo'=>$request->photo ?? '',
            'shopPhoto' => $leadDetails->attachmentImage ?? '',
            'yearOfIncorporation' => $request->yearOfIncorporation ?? '',
            'aadharNo' => $request->aadharNo ?? '',
            'familyDetails' => $request->familyDetails ?? '',
            'msRequriedMaterial' => $request->msRequriedMaterial ?? '',
            'piTypeOfSite' => $request->piTypeOfSite ?? '',
            'piStatusOfSite' => $request->piStatusOfSite ?? '',
            'piArea'=> $request->piArea ?? '',
            'piEstimateCost' => $request->piEstimateCost ?? '',
            'piProjectEngineer' => $request->piProjectEngineer ?? '',
            'piArchitect' => $request->piArchitect ?? '',
            'piExecutor' => $request->piExecutor ?? '',
            'cdFirmRegistrationNumber' => $leadDetails->firmRegistrationNumber,
            'cdShopActLicenceNumber' => $leadDetails->actLicenceNumber,
            'cdGstNumber' => $leadDetails->gstTinNumber,
            'cdPan' => $request->piArea ?? '',
            'cdFirmType' => $leadDetails->firmType,
            'cdCin' => $leadDetails->lcin,
            'cdShopWarehouseArea' => $leadDetails->shopWarehouseArea,
            'bdModeOfPayment'=> $leadDetails->modeOfPayment,
            'bdBankName' => $request->bdBankName ?? '',
            'bdBankAddress' => $request->bdBankAddress ?? '',
            'bdAccountNumber' => $request->bdAccountNumber ?? '',
            'bdIfscCode' => $request->bdIfscCode ?? '',
            'bdNatureOfAccount' => $request->bdNatureOfAccount ?? ''
        ];
    }
}
