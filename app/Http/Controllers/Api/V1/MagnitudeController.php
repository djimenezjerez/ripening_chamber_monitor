<?php

namespace App\Http\Controllers\Api\V1;

use App\Magnitude;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Schema;

class MagnitudeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $list = Magnitude::query();
        if ($request->has('search')) {
            if ($request->search != 'null' && $request->search != '') {
                $search = $request->search;
                $list = $list->where(function ($query) use ($search) {
                foreach (Schema::getColumnListing(Magnitude::getTableName()) as $column) {
                    $query = $query->orWhere($column, 'ilike', '%' . $search . '%');
                }
                });
            }
        }
        if ($request->has('sortBy') && $request->has('sortDesc')) {
            if (count($request->sortDesc) > 0 && count($request->sortBy) == count($request->sortDesc)) {
                foreach($request->sortDesc as $i => $sortDesc) {
                    $list = $list->orderBy($request->sortBy[$i], filter_var($sortDesc, FILTER_VALIDATE_BOOLEAN) ? 'desc' : 'asc');
                }
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
        return Magnitude::create($request->all());
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Magnitude  $magnitude
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return Magnitude::findOrFail($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Magnitude  $magnitude
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $item = Magnitude::findOrFail($id);
        $item->fill($request->all());
        $item->save();
        return $item;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Magnitude  $magnitude
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $item = Magnitude::findOrFail($id);
        $item->delete();
        return $item;
    }

    public function get_rooms($id)
    {
        $item = Magnitude::findOrFail($id);
        return $item->rooms;
    }
}
