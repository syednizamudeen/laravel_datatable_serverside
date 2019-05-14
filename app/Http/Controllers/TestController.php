<?php

namespace App\Http\Controllers;
use App\Model\home;
use App\Model\Detail;
use Illuminate\Http\Request;

class TestController extends Controller
{
    function test()
    {
        $query = home::leftJoin('details', 'homes.id', '=', 'details.home_id')->orderBy('details.email', 'desc')->select('homes.*', 'details.id as detail_id', 'details.email')->get();
        return '<pre>'.print_r($query->toArray(), 1);
    }
}
