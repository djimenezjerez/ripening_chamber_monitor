<?php

namespace App\Http\Controllers\Api\V1;

use Carbon;
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
        $from = Carbon::parse($request->input('from'))->startOfDay();
        $to = Carbon::parse($request->input('to'))->endOfDay();

        $list = Measurement::whereRoomId($request->input('room'))->whereMagnitudeId($request->input('magnitude'))->whereBetween('created_at', [$from, $to]);
        if ($request->has('sortBy')) {
            if ($request->sortBy != 'null') {
                $list = $list->orderBy($request->sortBy, $request->input('direction') ?? 'asc');
            }
        }
        return $list->orderBy('created_at', 'ASC')->paginate($request->input('per_page') ?? 10);
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
