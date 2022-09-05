<?php

namespace App\Http\Controllers\Api;

use App\Models\Knowledge;
use Illuminate\Http\Request;
use App\Helpers\NotificationHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\knowledgeValidation;



class KnowledgeController extends Controller
{

    /**
     * Get Knowledges
     *
     * @param string $search (optional)
     *
     * @return json
     */

    public  function getKnowledges(Request $request)
    {
        $knowledgePagination = config('constant.knowledgePagination');
        $user = \Auth::user();

        $search = $request->search ?? '';

        $getKnowledges = Knowledge::getSelectQuery($user->id)

            ->when(!empty($search), function ($query) use ($request) {
                return $query->where(function ($query) use ($request) {
                    $query->where('knowledges.kStartLocation', "like", "%" . $request->search . "%")
                    ->orwhere('knowledges.kDestination', "like", "%" . $request->search . "%")
                    ->orwhere('dealers.firmName', "like", "%" . $request->search . "%")
                    ->orwhere('dealers.name', "like", "%" . $request->search . "%");
                });
            })
            ->where(['knowledges.userId' => $user->id])
            ->orderBy('knowledges.createdAt','desc')
            ->paginate($knowledgePagination);

        return $this->toJson([
            'hasMore' => $getKnowledges->hasMorePages(),
            'totalCount' => $getKnowledges->total(),
            'getKnowledges' => $getKnowledges->items(),

        ]);
    }

     /**
     * Create  Update Knowledge
     *
     * @param  knowledgeValidation $request
     *
     * @return json
     */

    public function createKnowledge(knowledgeValidation $request)
    {
        $user       = \Auth::user();
        $userId     =  $user->id;
        $id         =  $request->id;

        $typeMsg = 'Create';

        if(!empty($id))
        {
            $checkStatus = Knowledge::where(['id' => $request->id,'userId' => $userId])->first();

            if(empty($checkStatus))
            {
                return $this->toJson([],trans('api.knowledge.not_found'),0);
            }

            if($checkStatus->kStatus != "Approved")
            {
                return $this->toJson([],trans('api.knowledge.not_update'),0);
            }

            $typeMsg = 'Update';

        }

        $knowledgeRecord = Knowledge::updateOrCreate(['id' => $request->id],$request->merge(['userId' => $userId])->all());

        if($typeMsg == "Create")
        {
            NotificationHelper::knowledgeNotification($knowledgeRecord,1);
        }

        return $this->toJson([
            'knowledgeRecord' => $knowledgeRecord,
        ], trans('api.knowledge.success',['type' => $typeMsg]));
    }

    /**
     * Get Knowledge Details
     *
     * @param  string $id  (knowledge id)
     *
     * @return json
     */

    public  function knowledgeDetails(Request $request)
    {
        $user = \Auth::user();

        $id = $request->id ?? '';
        $knowledgeDetails =  Knowledge::knowledgeDetails()->where(['knowledges.id' => $id,'knowledges.userId' => $user->id])->first();

        if(empty($knowledgeDetails))
        {
            return $this->toJson([],trans('api.knowledge.not_found'),0);
        }

        return $this->toJson([
            'knowledgeDetails' => $knowledgeDetails,
        ]);
    }



}
