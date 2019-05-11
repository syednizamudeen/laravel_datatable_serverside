<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{asset('css/app.css')}}">    
    <title>{{config('app.name','Laravel DataTable')}}</title>
</head>
<body>
    <main role="main" class="flex-shrink-0">
        <div class="container-fluid">
            <h1 class="mt-5">{{$title}}</h1>
            <div class="table-responsive">
                <table id="example" class="table table-striped" style="width:100%; margin-top: unset; margin-bottom: unset;">
                    <thead>
                        <tr>
                            @foreach($columnmapping as $column)
                            <th>{{$column['label']}}</th>
                            @endforeach
                        </tr>
                        <tr>
                            @foreach($columnmapping as $column)
                            @if($column['type'] == 'checkbox')
                            <th class="input-filter text-center">
                                <div class="form-check abc-checkbox abc-checkbox-primary">
                                    <input class="form-check-input" id="checkall" type="checkbox">
                                    <label class="form-check-label" for="checkall"></label>
                                </div>
                            </th>
                            @else
                            <th class="input-filter">
                                <div class="input-group input-group-sm">
                                    <input type="text" class="form-control" placeholder="Search {{$column['label']}}" filter-type="{{$column['type']}}">
                                    <div class="input-group-append">
                                        <button class="btn clearfilter" type="button"><i class="fas fa-times-circle"></i></button>
                                    </div>
                                </div>
                            </th>
                            @endif
                            @endforeach
                        </tr>
                    </thead>
                </table>
            </div>
            <div class="table-responsive mt-3">
                <table id="example1" class="table table-striped" style="width:100%; margin-top: unset; margin-bottom: unset;">
                    <thead>
                        <tr>
                            @foreach($relationalcolumnmapping as $column)
                            <th>{{$column['label']}}</th>
                            @endforeach
                        </tr>
                        <tr>
                            @foreach($relationalcolumnmapping as $column)
                            @if($column['type'] == 'checkbox')
                            <th class="input-filter text-center">
                                <div class="form-check abc-checkbox abc-checkbox-primary">
                                    <input class="form-check-input" id="checkall" type="checkbox">
                                    <label class="form-check-label" for="checkall"></label>
                                </div>
                            </th>
                            @else
                            <th class="input-filter">
                                <div class="input-group input-group-sm">
                                    <input type="text" class="form-control" placeholder="Search {{$column['label']}}" filter-type="{{$column['type']}}">
                                    <div class="input-group-append">
                                        <button class="btn clearfilter" type="button"><i class="fas fa-times-circle"></i></button>
                                    </div>
                                </div>
                            </th>
                            @endif
                            @endforeach
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </main>
    @include('home.create')
    <script src="{{asset('js/app.js')}}"></script>
    <script>
    var APP_URL = {!! json_encode(url('/')) !!}, dataTable, columns = {!! $columns !!}, id = {!! array_search('id', array_column(json_decode($columns, true), 'data')) !!}, name = {!! array_search('name', array_column(json_decode($columns, true), 'data')) !!}, income = {!! array_search('annualincome', array_column(json_decode($columns, true), 'data')) !!}, age = {!! array_search('age', array_column(json_decode($columns, true), 'data')) !!}, created_at = {!! array_search('created_at', array_column(json_decode($columns, true), 'data')) !!}, updated_at = {!! array_search('updated_at', array_column(json_decode($columns, true), 'data')) !!}, relationalcolumns = {!! $relationalcolumns !!};
    $(document).ready(function() {
        $('#example')._DataTables({
            "ajax": {
                "url": APP_URL+"/home/datatable"
            },
            "columns": columns,
            "order": [[ name, "asc" ]],
            "columnDefs": [
                {
                    "targets": id,
                    "className": 'disableColhide text-center',
                    "searchable": false,
                    "orderable": false,
                    "render": function ( data, type, row, meta ) {
                        return '<div class="form-check abc-checkbox abc-checkbox-primary"><input class="form-check-input checkrow" id="checkbox'+data+'" type="checkbox"><label class="form-check-label" for="checkbox'+data+'"></label></div>';
                    }
                },
                {
                    "targets": income,
                    "className": 'text-right',
                    "render": $.fn.dataTable.render.number( ',', '.', 2, '$' )
                },
                {
                    "targets": age,
                    "className": 'text-center'
                },
                {
                    "targets": [created_at, updated_at],
                    "render": function ( data, type, row, meta ) {
                        return moment(data).format("Do MMM YYYY h:mm a");
                    }
                }
            ]
        });        
        $("div.boxtitle:first").html('<h4 class="m-0">{{$heading}}</h4>');
        $('#example1')._DataTables({
            "ajax": {
                "url": APP_URL+"/home/datatablerelation"
            },
            "columns": relationalcolumns,
            "order": [[ name, "asc" ]],
            "columnDefs": [
                {
                    "targets": id,
                    "className": 'disableColhide text-center',
                    "searchable": false,
                    "orderable": false,
                    "render": function ( data, type, row, meta ) {
                        return '<div class="form-check abc-checkbox abc-checkbox-primary"><input class="form-check-input checkrow" id="checkbox'+data+'" type="checkbox"><label class="form-check-label" for="checkbox'+data+'"></label></div>';
                    }
                },
                {
                    "targets": income,
                    "className": 'text-right',
                    "render": $.fn.dataTable.render.number( ',', '.', 2, '$' )
                },
                {
                    "targets": age,
                    "className": 'text-center'
                },
                {
                    "targets": [created_at, updated_at],
                    "render": function ( data, type, row, meta ) {
                        return moment(data).format("Do MMM YYYY h:mm a");
                    }
                }
            ]
        });  
        $("div.boxtitle:last").html('<h4 class="m-0">Server Side (Join) - Example</h4>');      
    });
    </script>
</body>
</html>