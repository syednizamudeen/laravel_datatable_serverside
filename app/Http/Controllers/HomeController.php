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
        return response()->json((new DataTable(new home, $request))->get());
    }
}

class DataTable
{
    private $query;
    private $filterQuery;
    private $requestParam;
    private $recordSet;
    private $recordsTotal;
    private $recordsFiltered;
    private $limit;
    private $offset;
    private $columnMapping;
    private $isQueryBind;

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
        $this->isQueryBind = false;
        $this->query = $className;

        $this->filter();
        $this->order();
        $this->recordsFiltered = $this->query->count();
        $this->limit();  
        $this->recordSet = $this->query->get();
        $this->recordsTotal = $className::count();
    }

    /**
     * Undocumented function
     *
     * @return array
     */
    function get()
    {
        return [
            'draw' => isset($this->requestParam->draw) ? $this->requestParam->draw : 0,
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
        $this->offset = isset($this->requestParam->start) ? (int)$this->requestParam->start : 0;
        $this->limit = isset($this->requestParam->length) && $this->requestParam->length != -1 ? (int)$this->requestParam->length : 10;        
        if ($this->isQueryBind)
        {
            $this->query->limit($this->limit)->offset($this->offset);
        }
        else
        {
            $this->query = $this->query->limit($this->limit)->offset($this->offset);
        }
    }

    /**
     * Undocumented function
     *
     * @return void
     */
    private function order()
    {
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
        foreach ($order as $k)
        {
            if ($this->isQueryBind)
            {
                $this->query->orderBy($k['column'], $k['dir']);
            }
            else
            {
                $this->query = $this->query->orderBy($k['column'], $k['dir']);
                $this->isQueryBind = true;
            }
        }
    }

    /**
     * Undocumented function
     *
     * @return void
     */
    private function filter()
    {
        foreach($this->requestParam->columns as $k => $v)
        {
            if (($v['searchable'] === true || $v['searchable'] === 'true') && $v['search']['value'] != '')
            {
                $this->query->where($v['data'], 'LIKE', '%' . $v['search']['value'] . '%');
                if ($this->isQueryBind)
                {
                    $this->query->where($v['data'], 'LIKE', '%' . $v['search']['value'] . '%');
                }
                else
                {
                    $this->query = $this->query::where($v['data'], 'LIKE', '%' . $v['search']['value'] . '%');
                    $this->isQueryBind = true;
                }
            }
        }
    }
}