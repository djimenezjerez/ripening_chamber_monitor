<?php

namespace App\Exports;

use Carbon;
use App\Measurement;
use App\Magnitude;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class MeasurementSheet implements FromQuery, WithMapping, WithHeadings, WithTitle
{
    use Exportable;

    private $room, $magnitude, $from, $to;

    public function __construct($room, $magnitude, $from, $to)
    {
        $this->room = $room;
        $this->magnitude = $magnitude;
        $this->from = Carbon::parse($from)->startOfDay();
        $this->to = Carbon::parse($to)->endOfDay();
    }

    public function query()
    {
        return Measurement::whereRoomId($this->room)->whereMagnitudeId($this->magnitude)->whereBetween('created_at', [$this->from, $this->to])->orderBy('created_at', 'ASC');
    }

    public function map($measurement): array
    {
        $date = Carbon::parse($measurement->created_at);

        return [
            $date->format('d/m/Y'),
            $date->format('H:m:s'),
            $measurement->value
        ];
    }

    public function headings(): array
    {
        return [
            'Fecha',
            'Hora',
            'Medición'
        ];
    }

    public function title(): string
    {
        return Magnitude::find($this->magnitude)->display_name;
    }
}
