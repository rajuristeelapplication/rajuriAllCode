<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Department;
use App\Models\HeadDepartment;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use App\Http\Controllers\Controller;


class AdminDepartmentsController extends Controller
{
     /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index()
    {
        return view('admin.departments.department_list');
    }

    /**
     * Search OR Sorting Department (DataTable).
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

            $query = Department::selectRaw('*');

            $orderDir      = $request->order[0]['dir'];
            $orderColumnId = $request->order[0]['column'];
            $orderColumn   = str_replace('"', '', $request->columns[$orderColumnId]['name']);

            $query->where(function ($query) use ($request) {
                $query->orWhere('dName', 'like', '%' . $request->search['value'] . '%');
            });

            $rows = $query->orderBy($orderColumn, $orderDir)
                ->paginate($request->length)->toArray();

            $rows['recordsFiltered'] = $rows['recordsTotal'] = $rows['total'];

            foreach ($rows['data'] as $key => $row) {

                $params = [
                    'department' => $row['id'],
                ];

                $rows['data'][$key]['sr_no'] = $startNo + $key;

                $viewRoute   = route('departments.show', $params);
                $editRoute   = route('departments.edit', $params);
                $deleteRoute = route('departments.destroy', $params);
                $statusRoute = route('departments.status', $params);
                $isActiveStatus = ($row['isActive'] == 1) ? 'checked' : '';

                $rows['data'][$key]['dName'] = Str::limit($row['dName'], 100, $end = '...');
                $rows['data'][$key]['isActive'] = '<input name="isActive" type="checkbox" class="input input--switch border mr-4 btnChangeStatus" data-url="' . $statusRoute . '"  ' . $isActiveStatus . ' >';
                $rows['data'][$key]['action']   = '<a href="' . $viewRoute . '" class="btn btn-primary" title="Department Information"><i class="feather feather-eye block mx-auto"></i></a>&nbsp&nbsp';
                $rows['data'][$key]['action'] .= '<a href="' . $editRoute . '" class="btn btn-success" title="Edit"><i class="feather feather-edit block mx-auto"></i></a>&nbsp&nbsp';
                $rows['data'][$key]['action'] .= '<a href="javascript:void();" onclick=deleteData("' . $deleteRoute . '") class="btn btn-danger btnDelete" data-title="Branch" title="Delete"><i class="feather feather-trash-2 block mx-auto"></i></a>';
            }

            return response()->json($rows);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     *  @return \Illuminate\Http\Response
     */

    public function create()
    {
        return view('admin.departments.department_create');
    }

     /**
     * Display the specified resource.
     *
     * @param  string  $id
     * @return \Illuminate\Http\Response
     */

    public function show($id)
    {
        $departmentInfo = Department::find($id);
        $headDepartmentInfo = HeadDepartment::getSelectQuery()->where(['departmentId' => $id])->get();
        if (!empty($departmentInfo)) {

            return view('admin.departments.department_view', compact('departmentInfo', 'headDepartmentInfo'));
        }

        return redirect(route('departments.index'))->with('error', trans('messages.company profile CMS.not_found', ['module' => 'company profile CMS']));
    }


     /**
     * Change status of the Department.
     *
     * @param string $id
     *
     * @return json
     */

    public function changeStatus($id)
    {
        $department = Department::find($id);

        if (empty($department)) {
            return $this->toJson([], trans('messages.msg.not_found', ['module' => 'department']), 0);
        }

        $department->isActive = !$department->isActive;
        $status = '';
        if ($department->save()) {
            $status = $department->isActive ? 'active' : 'inactive';
            return $this->toJson([], trans('messages.msg.status.success', ['module' => 'department', 'type' => $status]), 1);
        }
        return $this->toJson([], trans('messages.msg.status.error', ['module' => 'department', 'type' => $status]), 0);
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
            $message = trans('messages.msg.updated.success', ['module' => 'department']);
            $action = 'Edit';
        } else {
            $action = 'Add';
            $message = trans('messages.msg.created.success', ['module' => 'department']);
        }

        $save = Department::updateOrCreate(['id' => $request->id], ['dName'=> $request->dName]);

        //First Delete all head or add again

        if (isset($request->removeHeadDepartment)) {
            $getHeadId =  explode(',', $request->removeHeadDepartment);
            HeadDepartment::whereIn('id', $getHeadId)->delete();
        }

        foreach ($request->hdName as $key => $value) {
            $data = [
                'departmentId' => $save->id,
                'hdName' => $value
            ];
            HeadDepartment::updateOrCreate(['id' => $key], $data);
        }

        if ($save) {
            return redirect()->route('departments.index')->with('success', $message);
        }

        $errorMsg  = $action == 'Add' ? 'created' : 'updated';
        return redirect()->route('departments.index')->with('error', trans('messages.msg.' . $errorMsg . '.error', ['module' => 'company profile CMS']), 0);
    }

     /**
     * Show the form for editing the specified resource.
     *
     * @param  string  $id
     * @return \Illuminate\Http\Response
     */

    public function edit($id)
    {
        $departmentDetail = Department::where('id', $id)->first();
        $headDepartmentDetail = HeadDepartment::getSelectQuery()->where(['departmentId' => $id])->get();

        return view('admin.departments.department_create', compact('departmentDetail', 'headDepartmentDetail'));
    }

     /**
     * Remove the specified resource from storage.
     *
     * @param  string  $id
     * @return json
     */

    public function destroy($id)
    {
        $department = Department::where('id', $id)->first();
        if ($department) {

            $department->delete();
            return $this->toJson([], trans('messages.msg.deleted.success', ['module' => 'department']), 1);
        }
        return $this->toJson([], trans('messages.msg.deleted.error', ['module' => 'department']), 0);
    }
}
