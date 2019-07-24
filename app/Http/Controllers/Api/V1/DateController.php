<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Carbon\Carbon;

class DateController extends Controller
{
  /**
   * Display the actual server's date.
   *
   * @return \Illuminate\Http\Response
   */
  public function show()
  {
    return response()->json([
      'date' => Carbon::now()->format('Y-m-d'),
      'time' => Carbon::now()->format('H:m:s'),
    ]);
  }
}
