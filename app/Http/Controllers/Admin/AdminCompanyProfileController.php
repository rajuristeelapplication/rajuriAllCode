<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\CompanyProfile;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use App\Http\Controllers\Controller;

class AdminCompanyProfileController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index()
    {
        return view('admin.companyProfiles.company_profile_list');
    }

     /**
     * Search OR Sorting Company Profile (DataTable).
     *
     * @param Request $request
     *
     * @return json
     */

    public function search(Request $request)
    {

        if ($request->ajax()) {

            $currentPage = ($request->start == 0) ? 1 : (($request->start / $request->length) + 1);

            Paginator::currentPageResolver(function () use ($currentPage) {
                return $currentPage;
            });

            $startNo = ($request->start == 0) ? 1 : (($request->length) * ($currentPage - 1)) + 1;

            $query = CompanyProfile::selectRaw('*');

            $orderDir      = $request->order[0]['dir'];
            $orderColumnId = $request->order[0]['column'];
            $orderColumn   = str_replace('"', '', $request->columns[$orderColumnId]['name']);

            $query->where(function ($query) use ($request) {
                $query->orWhere('title', 'like', '%' . $request->search['value'] . '%');
                $query->orWhere('subTitle', 'like', '%' . $request->search['value'] . '%');
                $query->orWhere('description', 'like', '%' . $request->search['value'] . '%');
            });

            $rows = $query->orderBy($orderColumn, $orderDir)
                ->paginate($request->length)->toArray();

            $rows['recordsFiltered'] = $rows['recordsTotal'] = $rows['total'];

            foreach ($rows['data'] as $key => $row) {

                $params = [
                    'companyProfile' => $row['id'],
                ];

                $rows['data'][$key]['sr_no'] = $startNo + $key;

                $viewRoute   = route('companyProfiles.show', $params);
                $editRoute   = route('companyProfiles.edit', $params);
                $deleteRoute = route('companyProfiles.destroy', $params);
                $statusRoute = route('companyProfiles.status', $params);
                $isActiveStatus = ($row['isActive'] == 1) ? 'checked' : '';

                $description = Str::limit($row['description'], 40, $end = '...');

                $rows['data'][$key]['title'] = Str::limit($row['title'], 100, $end = '...');
                $rows['data'][$key]['subTitle'] = '<span title= "'.$row['subTitle'].'">'.Str::limit($row['subTitle'], 30, $end = '...').'</span>';
                $rows['data'][$key]['description'] = '<span title= "'.$row['description'].'">'.$description.'</span>';
                $rows['data'][$key]['isActive'] = '<input name="isActive" type="checkbox" class="input input--switch border mr-4 btnChangeStatus" data-url="' . $statusRoute . '"  ' . $isActiveStatus . ' >';
                $rows['data'][$key]['action']   = '<a href="' . $viewRoute . '" class="btn btn-primary" title="Company Profile Information"><i class="feather feather-eye block mx-auto"></i></a>&nbsp&nbsp';
                $rows['data'][$key]['action'] .= '<a href="' . $editRoute . '" class="btn btn-success" title="Edit"><i class="feather feather-edit block mx-auto"></i></a>&nbsp&nbsp';
                $rows['data'][$key]['action'] .= '<a href="javascript:void();" onclick=deleteData("' . $deleteRoute . '") class="btn btn-danger btnDelete" data-title="Branch" title="Delete"><i class="feather feather-trash-2 block mx-auto"></i></a>';
            }

            return response()->json($rows);
        }
    }

     /**
     * Display the specified resource.
     *
     * @param  string  $id
     * @return \Illuminate\Http\Response
     */

    public function show($id)
    {
        $companyProfileInfo = CompanyProfile::find($id);
        if (!empty($companyProfileInfo)) {


          return view('admin.companyProfiles.company_profile_view',compact('companyProfileInfo'));
        }

        return redirect(route('companyProfiles.index'))->with('error', trans('messages.company profile CMS.not_found', ['module' => 'company profile CMS']));
    }


    /**
     * Change status of the Company Profile.
     *
     * @param string $id
     *
     * @return json
     */
    public function changeStatus($id)
    {
        $companyProfile = CompanyProfile::find($id);

        if (empty($companyProfile)) {
            return $this->toJson([], trans('messages.msg.not_found', ['module' => 'company profile CMS']), 0);
        }

        $companyProfile->isActive = !$companyProfile->isActive;
        $status = '';
        if ($companyProfile->save()) {
            $status = $companyProfile->isActive ? 'active' : 'inactive';
            return $this->toJson([], trans('messages.msg.status.success', ['module' => 'company profile CMS', 'type' => $status]), 1);
        }
        return $this->toJson([], trans('messages.msg.status.error', ['module' => 'company profile CMS', 'type' => $status]), 0);
    }

    /**
     * Show the form for creating a new resource.
     *
     *  @return \Illuminate\Http\Response
     */

    public function create()
    {
        return view('admin.companyProfiles.company_profile_create');
    }

   /**
     * Store or Update a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if ($request->id) {
            $message = trans('messages.msg.updated.success', ['module' => 'company profile CMS']);
            $action = 'Edit';

            $companyProfile = CompanyProfile::find($request->id);

        } else {
            $action = 'Add';
            $message = trans('messages.msg.created.success', ['module' => 'company profile CMS']);

            $companyProfile = new CompanyProfile();
        }

        $companyProfile->title = $request->title;
        $companyProfile->subTitle = $request->subTitle;
        $companyProfile->description = $request->description;

        if ($companyProfile->save()) {

            return redirect()->route('companyProfiles.index')->with('success', $message);
        }
        $errorMsg  = $action == 'Add' ? 'created' : 'updated';
        return redirect()->route('companyProfiles.index')->with('error', trans('messages.msg.' . $errorMsg . '.error', ['module' => 'company profile CMS']), 0);
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  string  $id
     * @return \Illuminate\Http\Response
     */

    public function edit($id)
    {
        $companyProfileDetail = CompanyProfile::where('id', $id)->first();
        return view('admin.companyProfiles.company_profile_create', compact('companyProfileDetail'));
    }

      /**
     * Remove the specified resource from storage.
     *
     * @param  string  $id
     * @return json
     */

    public function destroy($id)
    {
        $companyProfile = CompanyProfile::where('id', $id)->first();
        if ($companyProfile) {

            $companyProfile->delete();
            return $this->toJson([], trans('messages.msg.deleted.success', ['module' => 'company profile CMS']), 1);
        }
        return $this->toJson([], trans('messages.msg.deleted.error', ['module' => 'company profile CMS']), 0);
    }
}
