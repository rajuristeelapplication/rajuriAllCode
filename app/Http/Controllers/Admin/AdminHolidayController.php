<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use App\Models\HolidayList;
use Illuminate\Http\Request;
use App\Imports\HolidaysImport;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\Paginator;
use App\Http\Controllers\Controller;


class AdminHolidayController extends Controller
{
   /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index()
    {
        $getYear = HolidayList::select(DB::raw('YEAR(hDate) year'))
                   ->groupby('year')
                   ->get();

        return view('admin.holiday.list', compact('getYear'));
    }


     /**
     * Search OR Sorting Holiday (DataTable).
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

            $query = HolidayList::getSelectQuery()->selectRaw('isActive, DATE_FORMAT(hDate, "' . config('constant.dob_format') . '") as dateFormat');

            if (!empty($request->yearType)) {
                $query = $query->whereYear('hDate','=',$request->yearType);
            }

            $orderDir      = $request->order[0]['dir'];
            $orderColumnId = $request->order[0]['column'];
            $orderColumn   = str_replace('"', '', $request->columns[$orderColumnId]['name']);

            $query->where(function ($query) use ($request) {
                $query->orWhere('title', 'like', '%' . $request->search['value'] . '%');
                $query->orWhere('hDate', 'like', '%' . $request->search['value'] . '%');
            });

            $rows = $query->orderBy($orderColumn, $orderDir)
                ->paginate($request->length)->toArray();

            $rows['recordsFiltered'] = $rows['recordsTotal'] = $rows['total'];

            foreach ($rows['data'] as $key => $row) {

                $params = [
                    'holiday' => $row['id'],
                ];

                $rows['data'][$key]['sr_no'] = $startNo + $key;

                $viewRoute   = route('holidays.show', $params);
                $editRoute   = route('holidays.edit', $params);
                $deleteRoute = route('holidays.destroy', $params);
                $statusRoute = route('holidays.status', $params);
                $isActiveStatus = ($row['isActive'] == 1) ? 'checked' : '';

                $rows['data'][$key]['title'] = $row['title']??"-";
                $rows['data'][$key]['hDate'] = $row['dateFormat']??"-";
                $rows['data'][$key]['hDateDay'] = $row['hDateDay']??"-";
                $rows['data'][$key]['isActive'] = '<input name="isActive" type="checkbox" class="input input--switch border mr-4 btnChangeStatus" data-url="' . $statusRoute . '"  ' . $isActiveStatus . ' >';
                // $rows['data'][$key]['action']   = '<a href="' . $viewRoute . '" class="btn btn-primary" title="Holiday Information"><i class="feather feather-eye block mx-auto"></i></a>&nbsp&nbsp';
                $rows['data'][$key]['action'] = '<a href="' . $editRoute . '" class="btn btn-success" title="Edit"><i class="feather feather-edit block mx-auto"></i></a>&nbsp&nbsp';
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
        $holidayInfo = HolidayList::find($id);
        if (!empty($holidayInfo)) {


          return view('admin.holiday.view',compact('holidayInfo'));
        }

        return redirect(route('holidays.index'))->with('error', trans('messages.holiday.not_found', ['module' => 'holiday']));
    }

   /**
     * Change status of the Holiday.
     *
     * @param string $id
     *
     * @return json
     */

    public function changeStatus($id)
    {
        $holiday = HolidayList::find($id);

        if (empty($holiday)) {
            return $this->toJson([], trans('messages.msg.not_found', ['module' => 'holiday']), 0);
        }

        $holiday->isActive = !$holiday->isActive;
        $status = '';
        if ($holiday->save()) {
            $status = $holiday->isActive ? 'active' : 'inactive';
            return $this->toJson([], trans('messages.msg.status.success', ['module' => 'holiday', 'type' => $status]), 1);
        }
        return $this->toJson([], trans('messages.msg.status.error', ['module' => 'holiday', 'type' => $status]), 0);
    }

     /**
     * Show the form for creating a new resource.
     *
     *  @return \Illuminate\Http\Response
     */

    public function create()
    {
        return view('admin.holiday.create');
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
            $message = trans('messages.msg.updated.success', ['module' => 'holiday']);
            $action = 'Edit';

            $holiday = HolidayList::find($request->id);

        } else {
            $action = 'Add';
            $message = trans('messages.msg.created.success', ['module' => 'holiday']);

            $holiday = new HolidayList();
        }

        $holiday->title = $request->title;
        $holiday->hDate = Carbon::createFromFormat('d/m/Y',$request->hDate)->format('Y-m-d');

        if ($holiday->save()) {

            return redirect()->route('holidays.index')->with('success', $message);
        }
        $errorMsg  = $action == 'Add' ? 'created' : 'updated';
        return redirect()->route('holidays.index')->with('error', trans('messages.msg.' . $errorMsg . '.error', ['module' => 'holiday']), 0);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  string  $id
     * @return \Illuminate\Http\Response
     */

    public function edit($id)
    {
        $holidayDetail = HolidayList::where('id', $id)->first();
        return view('admin.holiday.create', compact('holidayDetail'));
    }

     /**
     * Remove the specified resource from storage.
     *
     * @param  string  $id
     * @return json
     */

    public function destroy($id)
    {
        $holiday = HolidayList::where('id', $id)->first();

        if ($holiday) {

            $holiday->delete();

            return $this->toJson([], trans('messages.msg.deleted.success', ['module' => 'holiday']), 1);
        }
        return $this->toJson([], trans('messages.msg.deleted.error', ['module' => 'holiday']), 0);
    }

    /**
     * import Excel (Holiday Excel)
     *
     * @param  Request $request
     *
     * @return Redirect
     */
    public function importExcel(Request $request)
    {
        if ($request->file == null) {
            return redirect()->route('holidays.index')->with('error', trans('messages.msg.not_found', ['module' => 'Holiday File']));
        }

        \Excel::import(new HolidaysImport,request()->file('file'));

        return back();
    }
}
