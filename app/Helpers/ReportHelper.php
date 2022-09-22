<?php

namespace App\Helpers;

use App\Models\User;
use App\Models\Complaint;
use App\Models\Merchandise;
use App\Models\Lead;
use App\Models\Schedule;
use App\Models\Knowledge;
use App\Models\Dealer;
use App\Models\InOut;
use App\Models\Reimbursement;
use App\Models\LeaveRequest;
use App\Models\MaterialReport;
use Carbon\Carbon;


class ReportHelper
{
    /**
     *
     * Get Report User Contact
     *
     * @param $request
     * @return
     **/
    public static function reportInfo($request)
    {
        $rangedate = '';

        $daterange = explode(' - ', $request->daterange);
        $startDate = Carbon::createFromFormat('d/m/Y',$daterange[0])->format('Y-m-d');
        $endDate = Carbon::createFromFormat('d/m/Y',$daterange[1])->format('Y-m-d');


        if (!empty($startDate)) {
            $startDate = date(config('constant.admin_dob_format'), strtotime($startDate));
            $endDate = date(config('constant.admin_dob_format'), strtotime($endDate));
            $rangedate = $startDate . ' to ' . $endDate;
        }
        return  $levelName = [
            'rangeDate' => $rangedate,
        ];
    }


    public static function reportInfo1($startDate,$endDate)
    {
        $rangedate = '';


        if (!empty($startDate)) {
            $startDate = date(config('constant.admin_dob_format'), strtotime($startDate));
            $endDate = date(config('constant.admin_dob_format'), strtotime($endDate));
            $rangedate = $startDate . ' to ' . $endDate;
        }
        return  $levelName = [
            'rangeDate' => $rangedate,
        ];
    }

    /**
     *
     * User Report Query
     *
     * @param $request
     * @return $userInfos
     *
     **/
    public static function reportUserExport($request)
    {

        // $daterange = explode(' - ', $request->daterange);

        // $startDate = Carbon::createFromFormat('d/m/Y',$daterange[0])->format('Y-m-d');
        // $endDate = Carbon::createFromFormat('d/m/Y',$daterange[1])->format('Y-m-d');

        $result = User::getUserCommonQuery();


        if(!empty($request->userId))
        {
            $result =  $result->where('users.id',$request->userId);
        }

        if(!empty($request->cityId))
        {
            $result =  $result->where('users.cityId',$request->cityId);
        }

        if(!empty($request->userType))
        {
            $result =  $result->where('users.roleId',$request->userType);
        }else{

            $result = $result->whereNotIn('roleId', [config('constant.admin_id'), config('constant.hr_id'), config('constant.ma_id')]);
        }

        $result = $result->when(!empty(\Auth::user() && \Auth::user()->roleId == config('constant.ma_id')), function ($query)  {
            return $query->whereIn('users.id',  User::getMarketingAdminEmployee());
        });




        // $result =  $result->whereDate('users.createdAt', '>=', $startDate)
        //                   ->whereDate('users.createdAt', '<=', $endDate);

        // $result =  $result->orderBy('users.createdAt', 'desc')->get();
        $result =  $result->orderBy('users.createdAt', 'desc')->get();

        return $result;
    }

    /**
     *
     * Complaint Report Query
     *
     * @param $request
     * @return $userInfos
     *
     **/
    public static function reportComplaintExport($request)
    {
        $daterange = explode(' - ', $request->daterange);

        $startDate = Carbon::createFromFormat('d/m/Y',$daterange[0])->format('Y-m-d');
        $endDate = Carbon::createFromFormat('d/m/Y',$daterange[1])->format('Y-m-d');

        $result = Complaint::getSelectQuery()
                            ->selectRaw('users.fullName, dealers.name, complaints.createdAt,users.roleId,roles.roleName,dealers.fType,dealers.dealerId,dealers.firmName')
                            ->join('users', 'users.id', 'complaints.userId')
                            ->join('roles', 'roles.id', 'users.roleId')
                            ->join('dealers', 'dealers.id', 'complaints.rjDealerId');


        if( !empty(\Auth::user()) && \Auth::user()->roleId == config('constant.ma_id'))
        {
            $result = $result->where('roleId','=',config('constant.marketing_executive_id'));

            $result = $result->when(!empty(\Auth::user()->roleId == config('constant.ma_id')), function ($query)  {
                return $query->whereIn('complaints.userId',  User::getMarketingAdminEmployee());
            });
        }

        if(!empty($request->userType))
        {
            $result =  $result->where('users.roleId',$request->userType);
        }

        if(!empty($request->userId))
        {
            $result =  $result->where('complaints.userId',$request->userId);
        }

        if(!empty($request->cityId)){
            $result = $result->where('complaints.cCityId','=',$request->cityId);
        }

        if(!empty($request->talukaId)){
            $result = $result->where('complaints.cTalukaId','=',$request->talukaId);
        }
        if(!empty($request->stateId)){
            $result = $result->where('complaints.cStateId','=',$request->stateId);
        }

        if(!empty($request->complaintType))
        {
            $result =  $result->where('complaints.cType',$request->complaintType);
        }

        $result =  $result->whereDate('complaints.createdAt', '>=', $startDate)
            ->whereDate('complaints.createdAt', '<=', $endDate);

        // $result =  $result->orderBy('users.fullName', 'ASC')->get();
        $result =  $result->orderBy('complaints.createdAt', 'desc')->get();

        return $result;
    }


    /**
     *
     * Merchandises Gift Report Query
     *
     * @param $request
     * @return $userInfos
     *
     **/
    public static function merchandisesReport($request,$type)
    {

        if(!empty($type))
        {
            $startDate = $request->startDate;
            $endDate = $request->endDate;
        }else{
            $daterange = explode(' - ', $request->daterange);

            $startDate = Carbon::createFromFormat('d/m/Y',$daterange[0])->format('Y-m-d');
            $endDate = Carbon::createFromFormat('d/m/Y',$daterange[1])->format('Y-m-d');
        }

        $result = Merchandise::getSelectQuery()
                ->when(!empty($request->cityId), function ($query) use ($request) {
                    return $query->where('merchandises.mCityId',  $request->cityId);
                })
                ->when(!empty($request->stateId), function ($query) use ($request) {
                    return $query->where('merchandises.mStateId',  $request->stateId);
                })
                ->when(!empty($request->talukaId), function ($query) use ($request) {
                    return $query->where('merchandises.mTalukaId',  $request->talukaId);
                })
                ->when(!empty($request->userType), function ($query) use ($request) {
                    return $query->where('users.roleId',  $request->userType);
                })
                ->when(!empty($request->userId), function ($query) use ($request) {
                    return $query->where('merchandises.userId',  $request->userId);
                })
                ->when(!empty($request->giftItem), function ($query) use ($request) {
                    return $query->where('merchandises.itemNames', 'like', '%' . $request->giftItem . '%');
                })
                ->when(!empty($request->dealerType), function ($query) use ($request) {
                    return $query->where('merchandises.rjDealerId',  $request->dealerType );
                })
                ->when(!empty($request->formType), function ($query) use ($request) {
                    return $query->where('dealers.fType',  $request->formType );
                })

                ->when(!empty($request->cityId), function ($query) use ($request) {
                    return $query->where('merchandises.mCityId',  $request->cityId);
                })
                ->when(!empty($request->stateId), function ($query) use ($request) {
                    return $query->where('merchandises.mStateId',  $request->stateId);
                })
                ->when(!empty($request->talukaId), function ($query) use ($request) {
                    return $query->where('merchandises.mTalukaId',  $request->talukaId);
                })

                ->when(!empty($request->search), function ($query) use ($request) {
                    // return $query->where('merchandises.rjDealerId',  $request->search);
                    return $query->where('dealers.name', 'like', '%' . $request->search . '%');
                });


        if(!empty(\Auth::user()) && \Auth::user()->roleId == config('constant.ma_id'))
        {
            $result = $result->where('roleId','=',config('constant.marketing_executive_id'));

            $result = $result->when(!empty(\Auth::user()->roleId == config('constant.ma_id')), function ($query)  {
                return $query->whereIn('merchandises.userId',  User::getMarketingAdminEmployee());
            });
        }


        if(!empty($request->merchandiseType))
        {
            $result =  $result->where('merchandises.mType',$request->merchandiseType);
        }

        $result =  $result->whereDate('merchandises.createdAt', '>=', $startDate)
            ->whereDate('merchandises.createdAt', '<=', $endDate);

            // $result =  $result->orderBy('users.fullName', 'ASC');
            $result =  $result->orderBy('merchandises.createdAt', 'desc');

        return $result;
    }


    /**
     *
     * Merchandises Order Report Query
     *
     * @param $request
     * @return $userInfos
     *
     **/
    public static function merchandisesOrderReport($request,$type)
    {

        if(!empty($type))
        {
            $startDate = $request->startDate;
            $endDate = $request->endDate;
        }else{
            $daterange = explode(' - ', $request->daterange);

            $startDate = Carbon::createFromFormat('d/m/Y',$daterange[0])->format('Y-m-d');
            $endDate = Carbon::createFromFormat('d/m/Y',$daterange[1])->format('Y-m-d');
        }

        $result = Merchandise::getSelectQuery()

                        ->when(!empty($request->cityId), function ($query) use ($request) {
                            return $query->where('merchandises.mCityId',  $request->cityId);
                        })
                        ->when(!empty($request->stateId), function ($query) use ($request) {
                            return $query->where('merchandises.mStateId',  $request->stateId);
                        })
                        ->when(!empty($request->talukaId), function ($query) use ($request) {
                            return $query->where('merchandises.mTalukaId',  $request->talukaId);
                        })
                        ->when(!empty($request->userType), function ($query) use ($request) {
                            return $query->where('users.roleId',  $request->userType);
                        })
                        ->when(!empty($request->userId), function ($query) use ($request) {
                            return $query->where('merchandises.userId',  $request->userId);
                        })
                        ->when(!empty($request->orderStatus), function ($query) use ($request) {
                            return $query->where('merchandises.mStatus',  $request->orderStatus);
                        })
                        ->when(!empty($request->dealerType), function ($query) use ($request) {
                            return $query->where('merchandises.rjDealerId',  $request->dealerType );
                        })
                        ->when(!empty($request->formType), function ($query) use ($request) {
                            return $query->where('dealers.fType',  $request->formType );
                        })
                        ->when(!empty($request->giftItem), function ($query) use ($request) {
                            return $query->where('merchandises.itemNames', 'like', '%' . $request->giftItem . '%');
                        })
                        ->when(!empty($request->cityId), function ($query) use ($request) {
                            return $query->where('merchandises.mCityId',  $request->cityId);
                        })
                        ->when(!empty($request->stateId), function ($query) use ($request) {
                            return $query->where('merchandises.mStateId',  $request->stateId);
                        })
                        ->when(!empty($request->talukaId), function ($query) use ($request) {
                            return $query->where('merchandises.mTalukaId',  $request->talukaId);
                        })

                        ->when(!empty($request->search), function ($query) use ($request) {
                            // return $query->where('merchandises.rjDealerId',  $request->search);
                            return $query->where('dealers.name', 'like', '%' . $request->search . '%');
                        });


        if(!empty(\Auth::user()) && \Auth::user()->roleId == config('constant.ma_id'))
        {
            $result = $result->where('roleId','=',config('constant.marketing_executive_id'));

            $result = $result->when(!empty(\Auth::user()->roleId == config('constant.ma_id')), function ($query)  {
                return $query->whereIn('merchandises.userId',  User::getMarketingAdminEmployee());
            });
        }


        if(!empty($request->merchandiseType))
        {
            $result =  $result->where('merchandises.mType',$request->merchandiseType);
        }

        // $result =  $result->orderBy('users.fullName', 'ASC');
        $result =  $result->whereDate('merchandises.createdAt', '>=', $startDate)
            ->whereDate('merchandises.createdAt', '<=', $endDate);

        $result =  $result->orderBy('merchandises.createdAt', 'desc');

        return $result;
    }

    /**
     *
     * lead Report Query
     *
     * @param $request
     * @return $userInfos
     *
     **/
    public static function leadReport($request,$type)
    {

        if(!empty($type))
        {
            $startDate = $request->startDate;
            $endDate = $request->endDate;
        }else{
            $daterange = explode(' - ', $request->daterange);

            $startDate = Carbon::createFromFormat('d/m/Y',$daterange[0])->format('Y-m-d');
            $endDate = Carbon::createFromFormat('d/m/Y',$daterange[1])->format('Y-m-d');
        }



        $result = Lead::getSelectQuery()
                        ->selectRaw('users.fullName,leads.userId,leads.createdAt, leads.isActive, leads.lCompanyName, leads.lShopName,
                                        leads.lMobileNumber,leads.pAddress,leads.cAddress,leads.projectName,leads.dateOfDelivery,
                                        firmRegistrationNumber,actLicenceNumber,gstTinNumber,panText,firmType,lcin,shopWarehouseArea,
                                        modeOfPayment,budget,
                                        leads.lEmail,leads.orderQty,users.roleId,roles.roleName')
                        ->leftjoin('users', 'users.id', 'leads.userId')
                        ->leftjoin('roles', 'roles.id', 'users.roleId')

                        ->when(!empty($request->userType), function ($query) use ($request) {
                            return $query->where('users.roleId',  $request->userType);
                        })

                        ->when(!empty($request->userId), function ($query) use ($request) {
                            return $query->where('leads.userId',  $request->userId);
                        })

                        ->when(!empty($request->lType), function ($query) use ($request) {
                            if($request->lType != "All")
                            {
                                return $query->where('leads.lType',  $request->lType);
                            }
                        })

                        ->when(!empty($request->moveStatus), function ($query) use ($request) {

                            if($request->moveStatus == "Pending")
                            {
                                $query->where('leads.moveStatus',  'Pending');
                            }else{
                                $query->whereIn('leads.moveStatus', ['Sales','Dealer']);
                            }
                            // return $query->where('leads.pCityId',  $request->cityId);
                        })



                        ->when(!empty($request->cityId), function ($query) use ($request) {
                            return $query->where('leads.pCityId',  $request->cityId);
                        })
                        ->when(!empty($request->stateId), function ($query) use ($request) {
                            return $query->where('leads.pStateId',  $request->stateId);
                        })
                        ->when(!empty($request->talukaId), function ($query) use ($request) {
                            return $query->where('leads.pTalukaId',  $request->talukaId);
                        })
                        ->when(!empty($request->search), function ($query) use ($request) {
                            return $query->where( 'leads.lFullName', 'like', '%' . $request->search . '%');
                        });



                        $result = $result->when(!empty(\Auth::user() && \Auth::user()->roleId == config('constant.ma_id')), function ($query)  {
                            return $query->whereIn('leads.userId',  User::getMarketingAdminEmployee());
                        });

        // $result =  $result->orderBy('users.fullName', 'ASC');
        $result =  $result->whereDate('leads.createdAt', '>=', $startDate)
            ->whereDate('leads.createdAt', '<=', $endDate);

        $result =  $result->orderBy('leads.createdAt', 'desc');

        return $result;
    }


    /**
     *
     * Visit Report Query
     *
     * @param $request
     * @return $userInfos
     *
     **/
    public static function dailyReport($request,$type)
    {

        // dd($request->all());
        if(!empty($type))
        {
            $startDate = $request->startDate;
            $endDate = $request->endDate;
        }else{
            $daterange = explode(' - ', $request->daterange);

            $startDate = Carbon::createFromFormat('d/m/Y',$daterange[0])->format('Y-m-d');
            $endDate = Carbon::createFromFormat('d/m/Y',$daterange[1])->format('Y-m-d');
        }

        $result = Schedule::getSelectQuery()
                        ->selectRaw('users.fullName, schedules.userId, schedules.createdAt, schedules.schedulesStatus, schedules.dAddress1, schedules.construction, schedules.feedback,  schedules.ourMaterialsAvailable, schedules.otherBrandName, schedules.dCName, schedules.dTName, dealers.mobileNumber,users.roleId,roles.roleName')
                        ->join('users', 'users.id', 'schedules.userId')
                        ->join('roles', 'roles.id', 'users.roleId')
                        ->when(!empty($request->userType), function ($query) use ($request) {
                            return $query->where('users.roleId',  $request->userType);
                        })
                        ->when(!empty($request->userId), function ($query) use ($request) {
                            return $query->where('schedules.userId',  $request->userId);
                        })
                        ->when(!empty($request->dealerId), function ($query) use ($request) {
                            return $query->where('schedules.rjDealerId',  $request->dealerId);
                        })
                        ->when(!empty($request->fType), function ($query) use ($request) {
                            return $query->where('dealers.fType',  $request->fType);
                        })
                        ->when(!empty($request->dealerType), function ($query) use ($request) {
                            return $query->where('dealers.dType',  $request->dealerType);
                        })
                        ->when(!empty($request->cityId), function ($query) use ($request) {
                            return $query->where('schedules.dCityId',  $request->cityId);
                        })
                        ->when(!empty($request->stateId), function ($query) use ($request) {
                            return $query->where('schedules.dStateId',  $request->stateId);
                        })
                        ->when(!empty($request->talukaId), function ($query) use ($request) {
                            return $query->where('schedules.dTalukaId',  $request->talukaId);
                        })
                        ->when(!empty($request->purpose), function ($query) use ($request) {
                            return $query->where('schedules.purpose',  $request->purpose);
                        })
                        ->when(!empty($request->otherBrand), function ($query) use ($request) {
                            return $query->where('schedules.otherBrandName', 'like', '%' . $request->otherBrand . '%');
                        })


                        ->when(!empty($request->search), function ($query) use ($request) {
                            // return $query->where('schedules.rjDealerId',  $request->search);
                            return $query->where('dealers.name', 'like', '%' . $request->search . '%');
                        });

        if(!empty(\Auth::user()) && \Auth::user()->roleId == config('constant.ma_id'))
        {
            $result = $result->where('roleId','=',config('constant.marketing_executive_id'));

            $query = $query->when(!empty(\Auth::user()->roleId == config('constant.ma_id')), function ($query)  {
                return $query->whereIn('schedules.userId',  User::getMarketingAdminEmployee());
            });
        }

        if(!empty($request->visitType))
        {
            $result =  $result->where('schedules.sType',$request->visitType);
        }
        // $result =  $result->orderBy('users.fullName', 'ASC');
        $result =  $result->whereDate('schedules.createdAt', '>=', $startDate)
            ->whereDate('schedules.createdAt', '<=', $endDate);

        $result =  $result->orderBy('schedules.createdAt', 'desc');

        return $result;
    }


    /**
     *
     * Knowledge Report Query
     *
     * @param $request
     * @return $userInfos
     *
     **/
    public static function reportKnowledgeExport($request)
    {
        $daterange = explode(' - ', $request->daterange);

        $startDate = Carbon::createFromFormat('d/m/Y',$daterange[0])->format('Y-m-d');
        $endDate = Carbon::createFromFormat('d/m/Y',$daterange[1])->format('Y-m-d');

        $result = Knowledge::getSelectQuery()
                            ->selectRaw('knowledges.createdAt, knowledges.kCurrentLocation, knowledges.isActive, users.fullName,users.roleId,roles.roleName')
                            ->join('users', 'users.id', 'knowledges.userId')
                            ->join('roles', 'roles.id', 'users.roleId');

        if( !empty(\Auth::user()) && \Auth::user()->roleId == config('constant.ma_id'))
        {
            $result = $result->where('roleId','=',config('constant.marketing_executive_id'));

            $result = $result->when(!empty(\Auth::user()->roleId == config('constant.ma_id')), function ($query)  {
                return $query->whereIn('knowledges.userId',  User::getMarketingAdminEmployee());
            });
        }

        if(!empty($request->userType))
        {
            $result =  $result->where('users.roleId',$request->userType);
        }

        if(!empty($request->userId))
        {
            $result =  $result->where('knowledges.userId',$request->userId);
        }

        if(!empty($request->cityId)){
            $result = $result->where('knowledges.ksCityId','=',$request->cityId);
        }

        if(!empty($request->talukaId)){
            $result = $result->where('knowledges.ksTalukaId','=',$request->talukaId);
        }
        if(!empty($request->stateId)){
            $result = $result->where('knowledges.ksStateId','=',$request->stateId);
        }

        if(!empty($request->vehicleNumber1)){
            $result = $result->where('knowledges.vehicleNumber','=',$request->vehicleNumber1);
        }


        if(!empty($request->kStatus))
        {
            $result =  $result->where('knowledges.kStatus',$request->kStatus);
        }

        $result =  $result->whereDate('knowledges.kdate', '>=', $startDate)
            ->whereDate('knowledges.kdate', '<=', $endDate);

        // $result =  $result->orderBy('knowledges.createdAt', 'desc')->get();
        // $result =  $result->orderBy('users.fullName', 'ASC')->get();

        $result =  $result->orderBy('knowledges.createdAt', 'desc')->get();

        return $result;
    }


    /**
     *
     * Dealer Report Query
     *
     * @param $request
     * @return $userInfos
     *
     **/
    public static function reportDealerExport($request)
    {
        $daterange = explode(' - ', $request->daterange);

        $startDate = Carbon::createFromFormat('d/m/Y',$daterange[0])->format('Y-m-d');
        $endDate = Carbon::createFromFormat('d/m/Y',$daterange[1])->format('Y-m-d');

        $result = Dealer::dealerAndUsersDetails();

        if(!empty($request->formType))
        {
            $result =  $result->where('dealers.fType',$request->formType);
        }

        if(!empty($request->actorType) && $request->actorType == "other")
        {
            $result =  $result->where('dealers.fType','!=','Dealer');
        }

        // Role Base Condition

        if( !empty(\Auth::user()) && \Auth::user()->roleId == config('constant.ma_id'))
        {
            // $result = $result->where('roleId','=',config('constant.marketing_executive_id'));

            $result = $result->when(!empty(\Auth::user()->roleId == config('constant.ma_id')), function ($query)  {
                return $query->whereIn('dealers.userId',  User::getMarketingAdminEmployee());
            });
        }

        if(!empty($request->dob) && !empty($request->dob1)){


                $dayMonth  = explode('-', $request->dob);


                $dobMonth = $dayMonth[0];
                $dobDay =  $dayMonth[1];

                $dayMonth1  = explode('-', $request->dob1);

                $dobMonth1 = $dayMonth1[0];
                $dobDay1 =  $dayMonth1[1];

                $result =  $result->whereMonth('dealers.dob', '>=', $dobMonth)
                                ->whereDay('dealers.dob', '>=', $dobDay)
                                ->whereMonth('dealers.dob', '<=', $dobMonth1)
                                ->whereDay('dealers.dob', '<=', $dobDay1);

            // $result =  $result->whereDate('dealers.dob', '>=', $request->dob)
            // ->whereDate('dealers.dob', '<=', $request->dob1);

        }


        if(!empty($request->userType))
        {
            $result =  $result->where('users.roleId',$request->userType);
        }

        if(!empty($request->userId))
        {
            $result =  $result->where('dealers.userId',$request->userId);
        }
        if(!empty($request->cityId)){
            $result = $result->where('dealers.cityId','=',$request->cityId);
        }

        if(!empty($request->talukaId)){
            $result = $result->where('dealers.talukaId','=',$request->talukaId);
        }
        if(!empty($request->stateId)){
            $result = $result->where('dealers.stateId','=',$request->stateId);
        }


        if(!empty($request->regionId))
        {
            $result =  $result->where('dealers.regionId',$request->regionId);
        }


        if(!empty($request->dealerType))
        {
            $result =  $result->where('dealers.dType',$request->dealerType);
        }

        $result =  $result->whereDate('dealers.createdAt', '>=', $startDate)
            ->whereDate('dealers.createdAt', '<=', $endDate);


         $result =  $result->orderBy('dealers.createdAt', 'desc');

        // $result =  $result->orderBy('dealers.createdAt', 'desc')->get();
        $result =  $result->get();

        return $result;
    }


    /**
     *
     * Reimbursement Report Query
     *
     * @param $request
     * @return $userInfos
     *
     **/
    public static function reimbursementReport($request,$type)
    {
        if(!empty($type))
        {
            $startDate = $request->startDate;
            $endDate = $request->endDate;
        }else{
            $daterange = explode(' - ', $request->daterange);

            $startDate = Carbon::createFromFormat('d/m/Y',$daterange[0])->format('Y-m-d');
            $endDate = Carbon::createFromFormat('d/m/Y',$daterange[1])->format('Y-m-d');
        }

        $result = Reimbursement::getSelectQuery();

           // Role Base Condition

        if( !empty(\Auth::user()) && \Auth::user()->roleId == config('constant.ma_id'))
        {
            $result = $result->where('roleId','=',config('constant.marketing_executive_id'));

            $result = $result->when(!empty(\Auth::user()->roleId == config('constant.ma_id')), function ($query)  {
                return $query->whereIn('reimbursements.userId',  User::getMarketingAdminEmployee());
            });

        }

        if(!empty($request->expense_type))
        {
            $result =  $result->where('expense_types.id',$request->expense_type);
        }

        if(!empty($request->reimbursement_status))
        {
            $result =  $result->where('reimbursements.rStatus',$request->reimbursement_status);
        }

        // $result =  $result->orderBy('users.fullName', 'ASC');

        $result =  $result->whereDate('reimbursements.createdAt', '>=', $startDate)
            ->whereDate('reimbursements.createdAt', '<=', $endDate);

        $result =  $result->orderBy('reimbursements.createdAt', 'desc');


        return $result;
    }


    /**
     *
     * Leave Report Query
     *
     * @param $request
     * @return $userInfos
     *
     **/
    public static function leaveReport($request,$type)
    {
        if(!empty($type))
        {
            $startDate = $request->startDate;
            $endDate = $request->endDate;
        }else{
            $daterange = explode(' - ', $request->daterange);

            $startDate = Carbon::createFromFormat('d/m/Y',$daterange[0])->format('Y-m-d');
            $endDate = Carbon::createFromFormat('d/m/Y',$daterange[1])->format('Y-m-d');
        }

        $result = LeaveRequest::getSelectQuery()
                                ->selectRaw('users.fullName, leave_requests.id, DATE_FORMAT(leave_requests.createdAt, "' . config('constant.in_out_date_time_format') . '") as createdAtFormate,users.roleId,roles.roleName')
                                ->join('users', 'users.id', 'leave_requests.userId')
                                ->join('roles', 'roles.id', 'users.roleId');


        if(!empty($request->userType))
        {
            $result =  $result->where('users.roleId',$request->userType);
        }

        if(!empty($request->userId))
        {
            $result =  $result->where('leave_requests.userId',$request->userId);
        }

        if(!empty($request->leave_type))
        {
            $result =  $result->where('leave_requests.lType',$request->leave_type);
        }

        if(!empty($request->leave_status))
        {
            $result =  $result->where('leave_requests.lRStatus',$request->leave_status);
        }

        $result = $result->when(!empty(\Auth::user() && \Auth::user()->roleId == config('constant.ma_id')), function ($query)  {
            return $query->whereIn('leave_requests.userId',  User::getMarketingAdminEmployee());
        });

        $result =  $result->whereDate('leave_requests.createdAt', '>=', $startDate)
            ->whereDate('leave_requests.createdAt', '<=', $endDate)
            ->where('leave_requests.lRStatus', '!=', 'Credit')
            ->orderBy('leave_requests.createdAt', 'desc')
            ->get();

        return $result;
    }

    /**
     *
     * Material Report Query
     *
     * @param $request
     * @return $userInfos
     *
     **/
    public static function materialReport($request,$type)
    {
        if(!empty($type))
        {
            $startDate = $request->startDate;
            $endDate = $request->endDate;
        }else{
            $daterange = explode(' - ', $request->daterange);

            $startDate = Carbon::createFromFormat('d/m/Y',$daterange[0])->format('Y-m-d');
            $endDate = Carbon::createFromFormat('d/m/Y',$daterange[1])->format('Y-m-d');
        }

        $result = MaterialReport::getSelectQuery();

         // Role Base Condition

         if( !empty(\Auth::user()) && \Auth::user()->roleId == config('constant.ma_id'))
         {
             $result = $result->where('roleId','=',config('constant.marketing_executive_id'));

             $query = $query->when(!empty(\Auth::user()->roleId == config('constant.ma_id')), function ($query)  {
                return $query->whereIn('material_reports.userId',  User::getMarketingAdminEmployee());
            });
         }

        $result = $result->whereDate('material_reports.createdAt', '>=', $startDate)
                         ->whereDate('material_reports.createdAt', '<=', $endDate);

        return $result;
    }


    /**
     *
     * Marketing Van Request Report Query
     *
     * @param $request
     * @return $userInfos
     *
     **/
    public static function marketingVanRequestReport($request,$type)
    {
        if(!empty($type))
        {
            $startDate = $request->startDate;
            $endDate = $request->endDate;
        }else{
            $daterange = explode(' - ', $request->daterange);

            $startDate = Carbon::createFromFormat('d/m/Y',$daterange[0])->format('Y-m-d');
            $endDate = Carbon::createFromFormat('d/m/Y',$daterange[1])->format('Y-m-d');
        }

        $result = Knowledge::knowledgeDetails();

         //Role Base Condition

         if( !empty(\Auth::user()) && \Auth::user()->roleId == config('constant.ma_id'))
         {
             $result = $result->where('roleId','=',config('constant.marketing_executive_id'));

             $result = $result->when(!empty(\Auth::user()->roleId == config('constant.ma_id')), function ($query)  {
                return $query->whereIn('knowledges.userId',  User::getMarketingAdminEmployee());
            });
         }

        $result = $result->whereDate('knowledges.kDate', '>=', $startDate)
                         ->whereDate('knowledges.kDate', '<=', $endDate);


       $result =  $result->orderBy('knowledges.createdAt', 'desc');

        return $result;
    }


    /**
     *
     * Marketing Van Request Report Query
     *
     * @param $request
     * @return $userInfos
     *
     **/
    public static function attendanceReport($request,$type)
    {
        if(!empty($type))
        {
            $startDate = $request->startDate;
            $endDate = $request->endDate;
        }else{
            $daterange = explode(' - ', $request->daterange);

            $startDate = Carbon::createFromFormat('d/m/Y',$daterange[0])->format('Y-m-d');
            $endDate = Carbon::createFromFormat('d/m/Y',$daterange[1])->format('Y-m-d');
        }

        $result = InOut::getSelectQuery()
                        ->selectRaw('users.fullName,roles.roleName,users.roleId')
                        ->join('users','users.id','in_outs.userId')
                        ->join('roles','roles.id','users.roleId');

            if(!empty($request->userType))
            {
                $result =  $result->where('users.roleId',$request->userType);
            }

            if(!empty($request->userId))
            {
                $result =  $result->where('in_outs.userId',$request->userId);
            }

         //Role Base Condition

         if( !empty(\Auth::user()) && \Auth::user()->roleId == config('constant.ma_id'))
         {
             $result = $result->where('users.roleId','=',config('constant.marketing_executive_id'));

             $result = $result->when(!empty(\Auth::user()->roleId == config('constant.ma_id')), function ($query)  {
                return $query->whereIn('in_outs.userId',  User::getMarketingAdminEmployee());
            });
         }

        $result = $result->whereDate('in_outs.createdAt', '>=', $startDate)
                         ->whereDate('in_outs.createdAt', '<=', $endDate);

       $result =  $result->orderBy('in_outs.createdAt', 'desc');


        return $result;
    }








}
