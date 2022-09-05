<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Brochure;
use App\Helpers\ImageHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Pagination\Paginator;
use App\Http\Controllers\Controller;

class AdminBrochuresController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.brochures.brochure_list');
    }

    /**
     * Search OR Sorting Brochures (DataTable).
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

            $query = Brochure::getSelectQuery();

            $orderDir      = $request->order[0]['dir'];
            $orderColumnId = $request->order[0]['column'];
            $orderColumn   = str_replace('"', '', $request->columns[$orderColumnId]['name']);

            $query->where(function ($query) use ($request) {
                $query->orWhere('title', 'like', '%' . $request->search['value'] . '%');
            });

            $rows = $query->orderBy($orderColumn, $orderDir)
                ->paginate($request->length)->toArray();

            $rows['recordsFiltered'] = $rows['recordsTotal'] = $rows['total'];

            foreach ($rows['data'] as $key => $row) {

                $params = [
                    'brochure' => $row['id'],
                ];

                $rows['data'][$key]['sr_no'] = $startNo + $key;

                $editRoute   = route('brochures.edit', $params);
                $deleteRoute = route('brochures.destroy', $params);
                $statusRoute = route('brochures.status', $params);
                $isActiveStatus = ($row['isActive'] == 1) ? 'checked' : '';

                $rows['data'][$key]['title'] = Str::limit($row['title'], 100, $end = '...');
                $rows['data'][$key]['image'] = '<a class="fancybox" href="' . $row['image'] . '" ><img src="' . $row['image'] . '" style="height:50px;width:50px;"></a>';
                $rows['data'][$key]['isActive'] = '<input name="isActive" type="checkbox" class="input input--switch border mr-4 btnChangeStatus" data-url="' . $statusRoute . '"  ' . $isActiveStatus . ' >';
                $rows['data'][$key]['action']   = '<a href="' . $row['pdf'] . '" target="__blank" class="btn btn-dark" title="View PDF"><i class="fa fa-file-pdf-o block mx-auto"></i></a>&nbsp&nbsp';
                $rows['data'][$key]['action'] .= '<a href="' . $editRoute . '" class="btn btn-success" title="Edit"><i class="feather feather-edit block mx-auto"></i></a>&nbsp&nbsp';
                $rows['data'][$key]['action'] .= '<a href="javascript:void();" onclick=deleteData("' . $deleteRoute . '") class="btn btn-danger btnDelete" data-title="Branch" title="Delete"><i class="feather feather-trash-2 block mx-auto"></i></a>';
            }

            return response()->json($rows);
        }
    }


    /**
     * Change status of the Brochures.
     *
     * @param object $id
     *
     * @return json
     */
    public function changeStatus($id)
    {
        $brochure = Brochure::find($id);

        if (empty($brochure)) {
            return $this->toJson([], trans('messages.msg.not_found', ['module' => 'brochure']), 0);
        }

        $brochure->isActive = !$brochure->isActive;
        $status = '';
        if ($brochure->save()) {
            $status = $brochure->isActive ? 'active' : 'inactive';
            return $this->toJson([], trans('messages.msg.status.success', ['module' => 'brochure', 'type' => $status]), 1);
        }
        return $this->toJson([], trans('messages.msg.status.error', ['module' => 'brochure', 'type' => $status]), 0);
    }


    /**
     * Show the form for creating a new resource.
     *
     *  @return \Illuminate\Http\Response
     */

    public function create()
    {
        return view('admin.brochures.brochure_create');
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
            $message = trans('messages.msg.updated.success', ['module' => 'brochure']);
            $action = 'Edit';

            $brochure = Brochure::find($request->id);

            if ($request->hasFile('pdf')) {

                $brochure->pdf = ImageHelper::uploadImage($request->file('pdf'), 'brochures');
            }

            if ($request->hasFile('image')) {

                $brochure->image = ImageHelper::uploadImage($request->file('image'), 'brochures');
            }

        } else {
            $action = 'Add';
            $message = trans('messages.msg.created.success', ['module' => 'brochure']);

            $brochure = new Brochure();
            $brochure->pdf   = ImageHelper::uploadImage($request->file('pdf'), 'brochures');
            $brochure->image = ImageHelper::uploadImage($request->file('image'), 'brochures');
        }

        $brochure->title = $request->title;

        if ($brochure->save()) {
            return redirect()->route('brochures.index')->with('success', $message);
        }
        $errorMsg  = $action == 'Add' ? 'created' : 'updated';
        return redirect()->route('brochures.index')->with('error', trans('messages.msg.' . $errorMsg . '.error', ['module' => 'brochure']), 0);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  string  $id
     * @return \Illuminate\Http\Response
     */

    public function edit($id)
    {
        $brochureDetail = Brochure::getSelectQuery()->where('id', $id)->first();
        return view('admin.brochures.brochure_create', compact('brochureDetail'));
    }

   /**
     * Remove the specified resource from storage.
     *
     * @param  string  $id
     * @return \Illuminate\Http\Response
     */

    public function destroy($id)
    {
        $brochure = Brochure::where('id', $id)->first();
        if ($brochure) {

            $imageName1 = config('constant.voice_recording_image') .'/'.$brochure->image;
            User::bucketRemoveImage($imageName1);

            $imageName = config('constant.voice_recording_image') .'/'.$brochure->pdf;
            User::bucketRemoveImage($imageName);


            $brochure->delete();
            return $this->toJson([], trans('messages.msg.deleted.success', ['module' => 'brochure']), 1);
        }
        return $this->toJson([], trans('messages.msg.deleted.error', ['module' => 'brochure']), 0);
    }
}
