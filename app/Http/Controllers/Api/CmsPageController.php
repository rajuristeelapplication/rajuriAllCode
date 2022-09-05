<?php

namespace App\Http\Controllers\Api;

use App\Models\PageContent;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\CmsValidation;

class CmsPageController extends Controller
{
    /**
     * CMS Page
     *
     * @param  Request $request
     *
     * @return json
     */
    public function cmsPage(CmsValidation $request)
    {
        $cmsPage = PageContent::where('slug',$request->slug)->first();

        if(!empty($cmsPage))
        {
            return $this->toJson([
                'cmsPage' => $cmsPage
            ]);
        }

        return $this->toJson([], trans('api.page_not_available'), 0);
    }
}
