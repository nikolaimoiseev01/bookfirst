<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Models\Col_status;
use App\Models\Collection;
use Illuminate\Http\Request;

class CollectionController extends Controller
{

    public function index(Request $request)
    {
        $collection = Collection::orderBY('id')->find($request->collection_id);
        $col_statuses = Col_status::orderBY('id');
        return view('portal.collection', [
            'collection' => $collection,
            'col_statuses' => $col_statuses
        ]);

    }
}
