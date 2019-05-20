<?php

namespace App\Http\Controllers;

use App\Model\home;
use App\Model\Detail;
use Illuminate\Http\Request;
use App\Services\DataTablesService;

class HomeController extends Controller
{
    public function __construct(DataTablesService $dataTables)
    {
        $this->dataTablesService = $dataTables;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = array(
            'title' =>'DataTable Home',
            'heading' =>'Server Side - Example',
            'columnmapping' => home::$dataTableColumnMapping,
            'columns' => json_encode(array_map(function($n) {
                    return ["data" => $n];
                }, array_column(home::$dataTableColumnMapping, 'database')
            )),
            'relationalcolumnmapping' => home::$dataTableRelationalColumnMapping,
            'relationalcolumns' => json_encode(array_map(function($n) {
                    return ["data" => $n];
                }, array_column(home::$dataTableRelationalColumnMapping, 'datatable')
            )),
        );
        return view('home.index')->with($data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        abort(404, 'The resource you are looking for could not be found');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Model\home  $home
     * @return \Illuminate\Http\Response
     */
    public function show(home $home)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Model\home  $home
     * @return \Illuminate\Http\Response
     */
    public function edit(home $home)
    {
        abort(404, 'The resource you are looking for could not be found');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Model\home  $home
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, home $home)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Model\home  $home
     * @return \Illuminate\Http\Response
     */
    public function destroy(home $home)
    {
        //
    }

    /**
     * Get Data from Database and Return as JSON to DataTable
     *
     * @param Request $request
     * @return void
     */
    public function datatable(Request $request)
    {
        return response()->json($this->dataTablesService->init(new home, $request));
    }

    /**
     * Get Data from Database with Relation Table and Return as JSON to DataTable
     *
     * @param Request $request
     * @return void
     */
    public function datatablerelation(Request $request)
    {
        return response()->json($this->dataTablesService->init(new home, $request, ['homes.*','details.id as detail_id','details.email'], ['details', 'homes.id', '=', 'details.home_id']));
    }
}