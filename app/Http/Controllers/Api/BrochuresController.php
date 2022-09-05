<?php

namespace App\Http\Controllers\Api;

use App\Models\Brochure;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;



class BrochuresController extends Controller
{
    /**
     * Get Brochures
     * @param Request $request
     * @return json
     */

    public function getBrochures(Request $request)
    {
        $getBrochures = Brochure::getSelectQuery()->isActive()->orderBy('brochures.createdAt','desc')->get();

        return $this->toJson(['brochuresTitle' => 'Download - Brochure, Documents and Leaflets','getBrochures' => $getBrochures]);
    }
}
