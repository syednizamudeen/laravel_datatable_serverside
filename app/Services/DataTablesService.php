<?php

namespace App\Services;

class DataTablesService
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
     * Undocumented function
     *
     * @param object $classObject
     * @param Request $requestParam
     * @param array $columns
     * @param array $join
     * @return void
     */
    function init($classObject, $requestParam, $columns = [], $join = [])
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
        return $this->get();
    }
    
    /**
     * Undocumented function
     *
     * @return array
     */
    private function get()
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