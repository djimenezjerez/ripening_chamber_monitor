<?php

namespace App\Http\Controllers\Api\V1;

use App\Measurement;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Schema;

class MeasurementController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $list = Measurement::query();
        if ($request->has('search')) {
            if ($request->search != 'null' && $request->search != '') {
                $search = $request->search;
                $list = $list->where(function ($query) use ($search) {
                foreach (Schema::getColumnListing(Measurement::getTableName()) as $column) {
                    $query = $query->orWhere($column, 'ilike', '%' . $search . '%');
                }
                });
            }
        }
        if ($request->has('sortBy')) {
            if ($request->sortBy != 'null') {
                $list = $list->orderBy($request->sortBy, $request->input('direction') ?? 'asc');
            }
        }
        return $list->paginate($request->input('per_page') ?? 10);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        return Measurement::create($request->all());
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Measurement  $measurement
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return Measurement::findOrFail($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Measurement  $measurement
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $item = Measurement::findOrFail($id);
        $item->fill($request->all());
        $item->save();
        return $item;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Measurement  $measurement
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $item = Measurement::findOrFail($id);
        $item->delete();
        return $item;
    }
}
