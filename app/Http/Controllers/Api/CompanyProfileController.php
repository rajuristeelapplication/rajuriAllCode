<?php

namespace App\Http\Controllers\Api;

use App\Models\CompanySlider;
use App\Models\CompanyProfile;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CompanyProfileController extends Controller
{
    /**
     * Get Company Sliders And Profile Details
     *
     * @param Request $request
     *
     * @return json
     */
    public function getCompanyProfile(Request $request)
    {
        $getCompanySliders = CompanySlider::getSelectQuery()->isActive()->get();

        $getCompanyProfiles = CompanyProfile::getSelectQuery()->isActive()->get();

        return $this->toJson(['getCompanySliders' => $getCompanySliders,'getCompanyProfiles' => $getCompanyProfiles ]);
    }
}
