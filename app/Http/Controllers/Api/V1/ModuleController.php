<?php

namespace App\Http\Controllers\Api\V1;
use App\Http\Controllers\Controller;
use App\Module;
use Illuminate\Http\Request;

class ModuleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Module::get();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Module  $module
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return Module::findOrFail($id);
    }

    public function permissions($id)
    {
        $module = Module::findOrFail($id);
        return $module->permissions;
    }
}
