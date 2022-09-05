<?php

namespace App\Http\Controllers\Api;

use App\Models\Department;
use App\Models\HeadDepartment;
use App\Models\OfficeLocation;
use App\Models\ContactUs;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\sendContactUsRequest;

class ContactUsController extends Controller
{

    /**
     * Get Departments
     *
     * @param  Request $request
     *
     * @return json
     *
     */

    public function getDepartments(Request $request)
    {
        $getDepartments = Department::getSelectQuery()->isActive()->get();
        return $this->toJson(['getDepartments' => $getDepartments]);
    }

     /**
     * Get Head Departments
     *
     * @param  Request $request
     *
     * @return json
     *
     */

    public function getHeadDepartments(Request $request)
    {
        $getHeadDepartments = HeadDepartment::getSelectQuery()->where(['departmentId' => $request->departmentId ])->isActive()->get();
        return $this->toJson(['getHeadDepartments' => $getHeadDepartments]);
    }

    /**
     * Get Office Location
     *
     * @return json
     *
     */

    public  function getOfficeAddress(Request $request)
    {
        $getOfficeAddress = OfficeLocation::getSelectQuery()->isActive()->get();

        return $this->toJson(['getOfficeAddress' => $getOfficeAddress]);
    }

    /**
     * Send contact us request to user
     *
     * @param  sendContactUsRequest $request
     *
     * @return json
     */
    public function sendContactUsRequest(sendContactUsRequest $request)
    {
        $user = \Auth::user();

        $inquiry = new ContactUs();
        $inquiry->userId = $user->id;
        $inquiry->fill($request->all());

        if($inquiry->save())
        {
            return $this->toJson([], trans('api.contact_us.request.success'));
        }

        return $this->toJson([], trans('api.contact_us.request.error'), 0);
    }
}
