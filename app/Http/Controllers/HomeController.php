<?php

namespace App\Http\Controllers;

use App\Model\home;
use App\Model\Detail;
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
        return response()->json((new DataTable(new home, $request))->get());
    }

    /**
     * Get Data from Database with Relation Table and Return as JSON to DataTable
     *
     * @param Request $request
     * @return void
     */
    public function datatablerelation(Request $request)
    {
        return response()->json((new DataTable(new home, $request, ['homes.*','details.id as detail_id','details.email'], ['details', 'homes.id', '=', 'details.home_id']))->get());
    }
}

class DataTable
{
    private $query;
    private $order;
    private $filterQuery;
    private $requestParam;
    private $recordSet;
    private $recordsTotal;
    private $recordsFiltered;
    private $limit;
    private $offset;
    private $columnMapping;
    private $isQueryBind;
    private $join;

    /**
     * Constructor
     *
     * @param object $classObject
     * @param Request $requestParam
     * @param array $columns
     * @param array $join
     */
    function __construct($classObject, $requestParam, $columns = [], $join = [])
    {
        $this->requestParam = $requestParam;
        $this->columnMapping = $classObject::$dataTableColumnMapping;
        $this->isQueryBind = false;
        $this->query = $classObject;
        $this->join = $join;
        $mapping = array_column($this->columnMapping, 'database');
        $this->order = array_map(function($n) use($mapping) {
            if (array_key_exists($n['column'], $mapping))
            {
                return ['column' => $mapping[$n['column']], 'dir' => $n['dir']];
            }
            else
            {
                return $n;
            }
        }, $this->requestParam->order);        
        $this->query = $this->query::select(empty($columns) ? $mapping : $columns);

        if(!empty($join)) #for Relational Table
        {
            $this->columnMapping = $classObject::$dataTableRelationalColumnMapping;
            $mapping = array_column($this->columnMapping, 'database');
            $this->order = array_map(function($n) use($mapping) {
                if (array_key_exists($n['column'], $mapping))
                {
                    return ['column' => $mapping[$n['column']], 'dir' => $n['dir']];
                }
                else
                {
                    return $n;
                }
            }, $this->requestParam->order);
        }
        
        $this->join();
        $this->filter();
        $this->order();
        $this->recordsFiltered = $this->query->count();
        $this->limit();  
        $this->recordSet = $this->query->get();
        $this->recordsTotal = $classObject::count();
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
            'data' => $this->formatOutput()
        ];
    }

    /**
     * Undocumented function
     *
     * @return array
     */
    private function formatOutput()
    {
        return $this->recordSet->toArray();
    }
    
    /**
     * Undocumented function
     *
     * @return void
     */
    private function join()
    {
        if(!empty($this->join))
        {
            list($table, $first, $operator, $second) = $this->join;
            $this->query->leftJoin($table, $first, $operator, $second);
        }
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
        $this->query->limit($this->limit)->offset($this->offset);
    }

    /**
     * Undocumented function
     *
     * @return void
     */
    private function order()
    {
        if(!empty($this->order))
        {
            foreach ($this->order as $k)
            {
                $this->query->orderBy($k['column'], $k['dir'] == 'asc' ? 'ASC' : 'DESC');
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
                $columntype = array_search($v['data'], array_column($this->columnMapping, 'datatable'));
                if ($this->columnMapping[$columntype]['type'] == 'text')
                {
                    $this->query->where($this->columnMapping[$columntype]['database'], 'LIKE', '%' . $v['search']['value'] . '%');
                }
                elseif ($this->columnMapping[$columntype]['type'] == 'datetimerange')
                {
                    $daterange = explode("-", $v['search']['value']);
                    $daterange = array_map('trim', $daterange);
                    $from = date_format(date_create_from_format('d M y H:s', $daterange[0]), 'Y-m-d H:s:00');
                    $to = date_format(date_create_from_format('d M y H:s', $daterange[1]), 'Y-m-d H:s:59');
                    $this->query->whereBetween($this->columnMapping[$columntype]['database'], [$from, $to]);
                }
            }
        }
    }
}