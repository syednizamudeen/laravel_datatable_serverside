<?php

namespace App\Http\Controllers;

use App\Model\home;
use Illuminate\Http\Request;

class HomeController extends Controller
{
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
            ))
        );
        // error_log(print_r(array_search('name', array_column(json_decode($data['columns'], true), 'data')),1));
        return view('home.index')->with($data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
        //
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
        // error_log($request);
        // error_log($request->search);
        // error_log($request->order);
        // error_log($request->columns);
        // error_log(print_r($request->toArray(),1));
        $dt = new DataTable(home::class, $request);
        return response()->json($dt->get());
    }
}

class DataTable
{
    private $query;
    private $requestParam;
    private $recordSet;
    private $recordsTotal;
    private $recordsFiltered;
    private $limit;
    private $offset;
    private $columnMapping;

    /**
     * Constructor
     *
     * @param class $className
     * @param request $requestParam
     */
    function __construct($className, $requestParam)
    {
        $this->requestParam = $requestParam;
        $this->columnMapping = $className::$dataTableColumnMapping;
        $this->limit();
        $this->query = $className::limit($this->limit)->offset($this->offset);
        $this->order();
        $this->recordSet = $this->query->get();
        $this->recordsTotal = $className::count();
        $this->recordsFiltered = $className::count();
    }

    /**
     * Undocumented function
     *
     * @return array
     */
    function get()
    {
        return [
            'draw' => $this->requestParam->draw,
            'recordsTotal' => $this->recordsTotal,
            'recordsFiltered' => $this->recordsFiltered,
            'data' => $this->recordSet->toArray()
        ];
    }

    /**
     * Undocumented function
     *
     * @return void
     */
    private function limit()
    {
        $this->limit = isset($this->requestParam->length) && $this->requestParam->length != -1 ? (int)$this->requestParam->length : 10;
        $this->offset = isset($this->requestParam->start) ? (int)$this->requestParam->start : 0;
    }

    /**
     * Undocumented function
     *
     * @return void
     */
    private function order()
    {
        // error_log(print_r($this->requestParam->order, 1));
        // error_log(print_r(array_column($this->columnMapping, 'database'), 1));
        $mapping = array_column($this->columnMapping, 'database');
        $order = array_map(function($n) use($mapping) {
            if (array_key_exists($n['column'], $mapping))
            {
                return ['column' => $mapping[$n['column']], 'dir' => $n['dir']];
            }
            else
            {
                return $n;
            }
        }, $this->requestParam->order);
        // error_log(print_r($order, 1));
        foreach ($order as $k)
        {
            $this->query->orderBy($k['column'], $k['dir']);
        }
    }

    /**
     * Undocumented function
     *
     * @return void
     */
    private function filter()
    {

    }
}