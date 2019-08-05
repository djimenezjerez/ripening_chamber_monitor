<?php

namespace App\Exports;

use Carbon;
use App\Measurement;
use App\Room;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class MeasurementExport implements WithMultipleSheets
{
    use Exportable;

    protected $room, $from, $to;

    public function __construct($room, $from, $to)
    {
        $this->room = $room;
        $this->from = Carbon::parse($from)->startOfDay();
        $this->to = Carbon::parse($to)->endOfDay();
    }

    public function sheets(): array
    {
        $sheets = [];

        foreach(Room::findOrFail($this->room)->magnitudes as $magnitude) {
            $sheets[] = new MeasurementSheet($this->room, $magnitude->id, $this->from, $this->to);
        }

        return $sheets;
    }
}
