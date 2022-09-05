<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\ExpenseType;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Pagination\Paginator;

class AdminExpenseTypeReimbursementController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index()
    {
        return view('admin.expense_type_reimbursement.list');
    }

    /**
     * Search Brand.
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

            $query = ExpenseType::selectRaw('*')->where(['type' => 'Reimbursement']);

            $orderDir      = $request->order[0]['dir'];
            $orderColumnId = $request->order[0]['column'];
            $orderColumn   = str_replace('"', '', $request->columns[$orderColumnId]['name']);

            $query->where(function ($query) use ($request) {
                $query->orWhere('eName', 'like', '%' . $request->search['value'] . '%');
            });

            $rows = $query->orderBy($orderColumn, $orderDir)
                ->paginate($request->length)->toArray();

            $rows['recordsFiltered'] = $rows['recordsTotal'] = $rows['total'];

            foreach ($rows['data'] as $key => $row) {

                $params = [
                    'expense_type_reimbursement' => $row['id'],
                ];

                $rows['data'][$key]['sr_no'] = $startNo + $key;

                $editRoute   = route('expense-type-reimbursements.edit', $params);
                $deleteRoute = route('expense-type-reimbursements.destroy', $params);
                $statusRoute = route('expense-type-reimbursements.status', $params);
                $isActiveStatus = ($row['isActive'] == 1) ? 'checked' : '';

                $rows['data'][$key]['isActive'] = '<input name="isActive" type="checkbox" class="input input--switch border mr-4 btnChangeStatus" data-url="' . $statusRoute . '"  ' . $isActiveStatus . ' >';
                $rows['data'][$key]['action'] = '<a href="' . $editRoute . '" class="btn btn-success" title="Edit"><i class="feather feather-edit block mx-auto"></i></a>&nbsp&nbsp';
                $rows['data'][$key]['action'] .= '<a href="javascript:void();" onclick=deleteData("' . $deleteRoute . '") class="btn btn-danger btnDelete" data-title="Branch" title="Delete"><i class="feather feather-trash-2 block mx-auto"></i></a>';
            }

            return response()->json($rows);
        }
    }

    /**
     * Change status of the Brand.
     *
     * @param string $id
     *
     * @return json
     */
    public function changeStatus($id)
    {
        $companyProfile = ExpenseType::find($id);

        if (empty($companyProfile)) {
            return $this->toJson([], trans('messages.msg.not_found', ['module' => 'Expense Type Reimbursement']), 0);
        }

        $companyProfile->isActive = !$companyProfile->isActive;
        $status = '';
        if ($companyProfile->save()) {
            $status = $companyProfile->isActive ? 'active' : 'inactive';
            return $this->toJson([], trans('messages.msg.status.success', ['module' => 'Expense Type Reimbursement', 'type' => $status]), 1);
        }
        return $this->toJson([], trans('messages.msg.status.error', ['module' => 'Expense Type Reimbursement', 'type' => $status]), 0);
    }

    /**
     * Show the form for creating a new resource.
     *
     *  @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.expense_type_reimbursement.create');
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
            $message = trans('messages.msg.updated.success', ['module' => 'Expense Type Reimbursement']);
            $action = 'Edit';
            $companyProfile = ExpenseType::find($request->id);
        } else {
            $action = 'Add';
            $message = trans('messages.msg.created.success', ['module' => 'Expense Type Reimbursement']);

            $companyProfile = new ExpenseType();
        }

        $companyProfile->eName = $request->eName;
        $companyProfile->type = 'Reimbursement';

        if ($companyProfile->save()) {
            return redirect()->route('expense-type-reimbursements.index')->with('success', $message);
        }
        $errorMsg  = $action == 'Add' ? 'created' : 'updated';
        return redirect()->route('expense-type-reimbursements.index')->with('error', trans('messages.msg.' . $errorMsg . '.error', ['module' => 'Expense Type Reimbursement']), 0);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  string  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $companyProfileDetail = ExpenseType::where('id', $id)->first();

        if (empty($companyProfileDetail)) {
            return redirect()->route('expense-type-reimbursements.index')->with('success',  trans('messages.msg.not_found', ['module' => 'Expense Type Reimbursement']));
        }

        return view('admin.expense_type_reimbursement.create', compact('companyProfileDetail'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  string  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $companyProfile = ExpenseType::where('id', $id)->first();
        if ($companyProfile) {
            $companyProfile->delete();
            return $this->toJson([], trans('messages.msg.deleted.success', ['module' => 'Expense Type Reimbursement']), 1);
        }
        return $this->toJson([], trans('messages.msg.deleted.error', ['module' => 'Expense Type Reimbursement']), 0);
    }
}
