<?php

namespace App\Http\Controllers\Admin;

use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use App\Http\Controllers\Controller;

class AdminNotificationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        Notification::where('isRead',0)->update(['isRead' => 1]);
        return view('admin.notification.notification_list');
    }

    /**
     * Search OR Sorting Notification (DataTable).
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

            $notificationModule = $request->notificationModule ?? '';

            $startNo = ($request->start == 0) ? 1 : (($request->length) * ($currentPage - 1)) + 1;

            $query = Notification::getSelectQuery()
                                    ->where('isAdmin',0);


            if(!empty($notificationModule))
            {
                $query = $query->where('notifications.module',$notificationModule);
            }


            $orderDir      = $request->order[0]['dir'];
            $orderColumnId = $request->order[0]['column'];
            $orderColumn   = str_replace('"', '', $request->columns[$orderColumnId]['name']);

            $query->where(function ($query) use ($request) {
                $query->orWhere('notifications.title', 'like', '%' . $request->search['value'] . '%')
                        ->orWhere('notifications.module', 'like', '%' . $request->search['value'] . '%')
                    ->orWhere(\DB::raw("CONCAT(users.firstName,' ',users.lastName)"), 'like', '%'.$request->search['value'].'%')
                    ->orWhere(\DB::raw("CONCAT(sender.firstName,' ',sender.lastName)"), 'like', '%'.$request->search['value'].'%');
            });

            $rows = $query->orderBy($orderColumn, $orderDir)
                ->paginate($request->length)->toArray();

            $rows['recordsFiltered'] = $rows['recordsTotal'] = $rows['total'];


            foreach ($rows['data'] as $key => $row) {



                $params = [
                    'notification' => $row['id'],
                ];

                $viewRoute   = route('notifications.show', $params);

                $rows['data'][$key]['sr_no'] = $startNo + $key;

                $rows['data'][$key]['action'] =  '<a href= "'.$viewRoute . '" class="btn btn-info" title="View"><i class="feather feather-eye block mx-auto"></i></a>&nbsp&nbsp';

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
        $query = Notification::getSelectQuery()->where('notifications.id',$id)->first();


        if (!empty($query)) {
            return view('admin.notification.view', ['query' => $query]);
        }
        return redirect(route('notifications.index'))->with('error', trans('messages.msg.not_found', ['module' => 'Notification']));
    }


}
