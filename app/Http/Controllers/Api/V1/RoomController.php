<?php

namespace App\Http\Controllers\Api\V1;

use Carbon;
use App\Room;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Schema;
use App\Exports\MeasurementExport;

class RoomController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $list = Room::query();
        if ($request->has('search')) {
            if ($request->search != 'null' && $request->search != '') {
                $search = $request->search;
                $list = $list->where(function ($query) use ($search) {
                foreach (Schema::getColumnListing(Room::getTableName()) as $column) {
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
        return Room::create($request->all());
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Room  $room
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return Room::findOrFail($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Room  $room
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $item = Room::findOrFail($id);
        $item->fill($request->all());
        $item->save();
        return $item;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Room  $room
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $item = Room::findOrFail($id);
        $item->delete();
        return $item;
    }

    public function get_magnitudes($id)
    {
        $item = Room::findOrFail($id);
        return $item->magnitudes;
    }

    public function set_magnitudes(Request $request, $id)
    {
        $item = Room::findOrFail($id);
        $item->magnitudes()->sync($request->input('magnitudes'));
        return $item->magnitudes;
    }

    public function export($id, $from, $to)
    {
        $room = Room::findOrFail($id);
        $filename = implode('_', [str_replace(' ', '_', $room->display_name), Carbon::parse($from)->format('d_m_Y'), Carbon::parse($to)->format('d_m_Y')]);
        $filename .= '.xlsx';
        return (new MeasurementExport($id, $from, $to))->download($filename);
    }
}