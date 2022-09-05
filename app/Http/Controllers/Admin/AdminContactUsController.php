<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\ContactUs;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use App\Http\Controllers\Controller;

class AdminContactUsController extends Controller
{
   /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.contactUs.list');
    }


     /**
     * Search OR Sorting Contact Us (DataTable).
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

            $query = ContactUs::selectRaw('contact_us.id, contact_us.email, contact_us.mobileNumber, contact_us.createdAt, contact_us.message, users.fullName, DATE_FORMAT(contact_us.createdAt, "' . config('constant.in_out_date_time_format') . '") as dateFormat')
                ->join('users', 'users.id', 'contact_us.userId');


            $orderDir      = $request->order[0]['dir'];
            $orderColumnId = $request->order[0]['column'];
            $orderColumn   = str_replace('"', '', $request->columns[$orderColumnId]['name']);

            $query->where(function ($query) use ($request) {

                $query->orWhere('users.fullName', 'like', '%' . $request->search['value'] . '%')
                    ->orWhere('contact_us.email', 'like', '%' . $request->search['value'] . '%')
                    ->orWhere('contact_us.mobileNumber', 'like', '%' . $request->search['value'] . '%')
                    ->orWhere('contact_us.message', 'like', '%' . $request->search['value'] . '%');
            });

            $contactUs = $query->orderBy($orderColumn, $orderDir)
                ->paginate($request->length)->toArray();

            $contactUs['recordsFiltered'] = $contactUs['recordsTotal'] = $contactUs['total'];

            foreach ($contactUs['data'] as $key => $contact) {

                $params = [
                    'contactUs' => $contact['id'],
                ];

                $contactUs['data'][$key]['sr_no'] = $startNo + $key;

                $viewRoute   = route('contactUs.show', $params);
                $deleteRoute = route('contactUs.destroy', $params);


                $contactUs['data'][$key]['fullName'] = $contact['fullName'] ? ucfirst($contact['fullName']) : '-';
                $contactUs['data'][$key]['email'] = $contact['email'] ?? '-';
                $contactUs['data'][$key]['mobileNumber'] = $contact['mobileNumber'] ?? '-';
                $contactUs['data'][$key]['message'] = !empty($contact['message']) ? '<span title="'.$contact['message'].'">'. Str::limit(strip_tags($contact['message']), 21, $end = '...').'</span>' : '-';

                $contactUs['data'][$key]['createdAt'] = $contact['dateFormat'] ?? '-';

                $contactUs['data'][$key]['action']   = '<a href="' . $viewRoute . '" class="btn btn-primary" title="Contact Us Information"><i class="feather feather-eye block mx-auto"></i></a>&nbsp&nbsp';
                $contactUs['data'][$key]['action'] .= '<a href="javascript:void(0);" onclick=deleteData("' . $deleteRoute . '") class="btn btn-danger btnDelete" data-title="post" title="Delete"><i class="feather feather-trash-2 block mx-auto"></i></a>&nbsp&nbsp';

            }
            return response()->json($contactUs);
        }
    }


    /**
     * Change status of the Contact Us.
     *
     * @param string $id
     *
     * @return json
     */
    public function changeStatus($id)
    {
        $contactUs = ContactUs::find($id);

        if (empty($contactUs)) {
            return $this->toJson([], trans('messages.msg.not_found', ['module' => 'contactUs']), 0);
        }

        $contactUs->isActive = !$contactUs->isActive;
        $status = '';
        if ($contactUs->save()) {
            $status = $contactUs->isActive ? 'active' : 'inactive';
            return $this->toJson([], trans('messages.msg.status.success', ['module' => 'contactUs', 'type' => $status]), 1);
        }
        return $this->toJson([], trans('messages.msg.status.error', ['module' => 'contactUs', 'type' => $status]), 0);
    }


    /**
     * Display the specified resource.
     *
     * @param  string  $id
     * @return \Illuminate\Http\Response
     */

    public function show($id)
    {
        $contactUsDetail = ContactUs::selectRaw('departments.dName, head_departments.hdName, contact_us.id, contact_us.email, contact_us.mobileNumber, contact_us.createdAt, contact_us.message, users.fullName, DATE_FORMAT(contact_us.createdAt, "' . config('constant.in_out_date_time_format') . '") as dateFormat')
            ->join('users', 'users.id', 'contact_us.userId')
            ->join('departments', 'departments.id', 'contact_us.departmentId')
            ->join('head_departments', 'head_departments.id', 'contact_us.headDepartmentId')
            ->where('contact_us.id', $id)
            ->first();

            if (!empty($contactUsDetail)) {
            return view('admin.contactUs.view', compact('contactUsDetail'));
        }

        return redirect(route('contactUs.index'))->with('error', trans('messages.contact us.not_found', ['module' => 'contact us']));
    }

   /**
     * Remove the specified resource from storage.
     *
     * @param  string  $id
     * @return json
     */

    public function destroy($id)
    {
        $contactUs = ContactUs::where('id', $id)->first();
        if ($contactUs) {

            $contactUs->delete();

            return $this->toJson([], trans('messages.msg.deleted.success', ['module' => 'contactUs']), 1);
        }
        return $this->toJson([], trans('messages.msg.deleted.error', ['module' => 'contactUs']), 0);
    }
}
