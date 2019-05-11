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
                }, array_column(home::$dataTableRelationalColumnMapping, 'database')
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
        return response()->json((new DataTable(new home, $request, 'detail'))->get());
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
    function __construct($className, $requestParam, $alias = '')
    {
        $this->requestParam = $requestParam;
        $this->columnMapping = $className::$dataTableColumnMapping;
        $this->isQueryBind = false;
        $this->query = $className;

        if(!empty($alias))
        {
            $this->columnMapping = $className::$dataTableRelationalColumnMapping;
            $this->join($alias);
        }
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
        $formattedOutput = [];
        $dbCol = array_column($this->columnMapping, 'database');
        foreach($this->recordSet as $k)
        {
            $rowTemp = [];
            foreach($dbCol as $col)
            {
                $temp = [];
                if (!array_key_exists($col, $k->toArray()))
                {
                    $arrayFormat = explode(".", $col);
                    $x = count($arrayFormat) - 1;
                    for($i = $x; $i >= 0; $i--)
                    {
                        $temp = $i == $x ? array($arrayFormat[$i] => '') : array($arrayFormat[$i] => $temp);                        
                    }
                    $intersectRow = $this->recursive_array_intersect_key($k->toArray(), $temp);
                    $filtered = array_filter($intersectRow);
                    $rowTemp = empty($filtered) ? array_merge_recursive($rowTemp, $temp) : array_replace_recursive($temp, $intersectRow);
                }
            }
            $formattedOutput[] = empty($rowTemp) ? $k->toArray() : array_replace_recursive($k->toArray(), $rowTemp);
        }
        return $formattedOutput;
    }

    /**
     * Undocumented function
     *
     * @param string $alias
     * @param array $columns
     * @return void
     */
    private function join($alias)
    {
        if ($this->isQueryBind)
        {
            // $this->query->with($alias);
            $this->query->with([$alias => function ($query) {
                // $query->where('title', 'like', '%first%');
                // $query->orderBy('created_at', 'desc');
            }]);
        }
        else
        {
            // $this->query = $this->query->with($alias);
            $this->query = $this->query->with([$alias => function ($query) {
                // $query->where('title', 'like', '%first%');
                // $query->orderBy('created_at', 'desc');
            }]);
            $this->isQueryBind = true;
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
                $columntype = array_search($v['data'], array_column($this->columnMapping, 'database'));
                if ($this->columnMapping[$columntype]['type'] == 'text')
                {
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
                elseif ($this->columnMapping[$columntype]['type'] == 'datetimerange')
                {
                    $daterange = explode("-", $v['search']['value']);
                    $daterange = array_map('trim', $daterange);
                    $from = date_format(date_create_from_format('d M y H:s', $daterange[0]), 'Y-m-d H:s:00');
                    $to = date_format(date_create_from_format('d M y H:s', $daterange[1]), 'Y-m-d H:s:59');
                    if ($this->isQueryBind)
                    {
                        $this->query->whereBetween($v['data'], [$from, $to]);
                    }
                    else
                    {
                        $this->query = $this->query::whereBetween($v['data'], [$from, $to]);
                        $this->isQueryBind = true;
                    }
                }
            }
        }
    }

    function recursive_array_intersect_key(array $array1, array $array2) {
        $array1 = array_intersect_key($array1, $array2);
        foreach ($array1 as $key => &$value) {
            if (is_array($value) && is_array($array2[$key])) {
                $value = $this->recursive_array_intersect_key($value, $array2[$key]);
            }
        }
        return $array1;
    }
}