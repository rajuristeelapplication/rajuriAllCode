<?php

namespace App\Http\Controllers\Admin;

use App\Models\PageContent;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Auth;

class AdminPageContentController extends Controller
{
    /*
     |--------------------------------------------------------------------------
     | Admin Page Content Controller
     |--------------------------------------------------------------------------
     |
     | This controller handles admin page related data.
     |
    */

     /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index()
    {
        return view('admin.page_contents.page_content_list');
    }

   /**
     * Search OR Sorting Page Content (DataTable).
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

            $orderDir = $request->order[0]['dir'];
            $orderColumnId = $request->order[0]['column'];
            $orderColumn = str_replace('"', '', $request->columns[$orderColumnId]['name']);

            $rows = PageContent::select('*');

            $rows = $rows->where(function ($query) use ($request) {
                $query->orWhere('name', 'like', '%' . $request->search['value'] . '%');
            });

            $rows = $rows->orderBy($orderColumn, $orderDir)
                ->paginate($request->length)->toArray();

            $rows['recordsFiltered'] = $rows['recordsTotal'] = $rows['total'];

            foreach ($rows['data'] as $key => $pagecontent) {

                $params = [
                    'page_content' => $pagecontent['id']
                ];
                $editRoute = route('page-contents.edit', $params);
                $viewRoute   = route('page-contents.show', $params);

                $rows['data'][$key]['sr_no'] = $startNo + $key;
                $rows['data'][$key]['action'] = '<a href="' . $viewRoute . '" class="btn btn-primary" title="Page Content Information"><i class="feather feather-eye block mx-auto"></i></a>&nbsp&nbsp';

                if (Auth::user()->roleId != config('constant.hr_id')) {
                $rows['data'][$key]['action'] .= '<a  href="' . $editRoute . '" class="btn btn-success" title="Edit"><i class="feather feather-edit block mx-auto"></i></a>&nbsp&nbsp';
                }
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
        $contentDetail = PageContent::where('id',$id)->first();

        if (!empty($contentDetail)) {
          return view('admin.page_contents.view',compact('contentDetail'));
        }

        return redirect(route('page-contents.index'))->with('error', trans('messages.page content.not_found', ['module' => 'page content']));
    }

   /**
     * Store or Update a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function store(Request $request)
    {
        $save = PageContent::updateOrCreate(['id' => $request->id], $request->all());

        if ($save) {
            return $this->toJson([], trans('messages.msg.updated.success', ['module' => 'page content']), 1);
        }
        return $this->toJson([], trans('messages.msg.updated.error', ['module' => 'page content']), 0);
    }

   /**
     * Show the form for editing the specified resource.
     *
     * @param  string  $id
     * @return \Illuminate\Http\Response
     */

    public function edit($id)
    {
        $pageContent = PageContent::where('id', $id)->first();

        return view('admin.page_contents.page_content_edit', ['pageContent' => $pageContent]);

        return abort(404);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\PageContent  $pageContent
     * @return \Illuminate\Http\Response
     */

    public function update(Request $request, PageContent $pageContent)
    {

        $this->validate($request, [
            'name' => 'required',
            'content' => 'required'
        ]);

        $pageContent->fill($request->all());

        if ($pageContent->save()) {
            return redirect(route('page-contents.index'))->with('success', trans('messages.msg.updated.success', ['module' => 'page content']));
        }
        return redirect(route('page-contents.index'))->with('error', trans('messages.msg.updated.error', ['module' => 'page content']));
    }
}
