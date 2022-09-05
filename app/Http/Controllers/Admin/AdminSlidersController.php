<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\CompanySlider;
use App\Helpers\ImageHelper;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use App\Http\Controllers\Controller;

class AdminSlidersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.sliders.slider_list');
    }

    /**
     * Search OR Sorting Sliders (DataTable).
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

            $query = CompanySlider::getSelectQuery();

            $orderDir      = $request->order[0]['dir'];
            $orderColumnId = $request->order[0]['column'];
            $orderColumn   = str_replace('"', '', $request->columns[$orderColumnId]['name']);



            $rows = $query->orderBy($orderColumn, $orderDir)
                ->paginate($request->length)->toArray();

            $rows['recordsFiltered'] = $rows['recordsTotal'] = $rows['total'];

            foreach ($rows['data'] as $key => $row) {

                $params = [
                    'slider' => $row['id'],
                ];

                $rows['data'][$key]['sr_no'] = $startNo + $key;

                $editRoute   = route('sliders.edit', $params);
                $deleteRoute = route('sliders.destroy', $params);
                $statusRoute = route('sliders.status', $params);
                $isActiveStatus = ($row['isActive'] == 1) ? 'checked' : '';

                $rows['data'][$key]['image'] = '<a class="fancybox" target="_blank" href="' . $row['photo'] . '" ><img src="' . $row['photo'] . '" style="height:50px;width:50px;"></a>';
                $rows['data'][$key]['isActive'] = '<input name="isActive" type="checkbox" class="input input--switch border mr-4 btnChangeStatus" data-url="' . $statusRoute . '"  ' . $isActiveStatus . ' >';
                $rows['data'][$key]['action'] = '<a href="' . $editRoute . '" class="btn btn-success" title="Edit"><i class="feather feather-edit block mx-auto"></i></a>&nbsp&nbsp';
                $rows['data'][$key]['action'] .= '<a href="javascript:void();" onclick=deleteData("' . $deleteRoute . '") class="btn btn-danger btnDelete" data-title="Branch" title="Delete"><i class="feather feather-trash-2 block mx-auto"></i></a>';
            }

            return response()->json($rows);
        }
    }


    /**
     * Change status of the sliders.
     *
     * @param string $id
     *
     * @return json
     */
    public function changeStatus($id)
    {
        $slider = CompanySlider::find($id);

        if (empty($slider)) {
            return $this->toJson([], trans('messages.msg.not_found', ['module' => 'slider']), 0);
        }

        $slider->isActive = !$slider->isActive;
        $status = '';
        if ($slider->save()) {
            $status = $slider->isActive ? 'active' : 'inactive';
            return $this->toJson([], trans('messages.msg.status.success', ['module' => 'slider', 'type' => $status]), 1);
        }
        return $this->toJson([], trans('messages.msg.status.error', ['module' => 'slider', 'type' => $status]), 0);
    }

    /**
     * Show the form for creating a new resource.
     *
     *  @return \Illuminate\Http\Response
     */

    public function create()
    {
        return view('admin.sliders.slider_create');
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
            $message = trans('messages.msg.updated.success', ['module' => 'slider']);
            $action = 'Edit';

            $slider = CompanySlider::find($request->id);

            if ($request->hasFile('image')) {

                $slider->image = ImageHelper::uploadImage($request->file('image'), 'company_sliders');
            }
        } else {
            $action = 'Add';
            $message = trans('messages.msg.created.success', ['module' => 'slider']);

            $slider = new CompanySlider();
            $slider->image = ImageHelper::uploadImage($request->file('image'), 'company_sliders');
        }

        if ($slider->save()) {
            return redirect()->route('sliders.index')->with('success', $message);
        }
        $errorMsg  = $action == 'Add' ? 'created' : 'updated';
        return redirect()->route('sliders.index')->with('error', trans('messages.msg.' . $errorMsg . '.error', ['module' => 'slider']), 0);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  string  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $sliderDetail = CompanySlider::getSelectQuery()->where('id', $id)->first();
        return view('admin.sliders.slider_create', compact('sliderDetail'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  string  $id
     * @return json
     */
    public function destroy($id)
    {
        $slider = CompanySlider::where('id', $id)->first();
        if ($slider) {


            $imageName = config('constant.company_sliders') .'/'.$slider->image;
            User::bucketRemoveImage($imageName);


            $slider->delete();
            return $this->toJson([], trans('messages.msg.deleted.success', ['module' => 'slider']), 1);
        }
        return $this->toJson([], trans('messages.msg.deleted.error', ['module' => 'slider']), 0);
    }
}
