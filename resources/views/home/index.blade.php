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
        </div>
    </main>
    <div class="modal fade" id="createModal" tabindex="-1" role="dialog" aria-labelledby="createModalTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createModalTitle"><i class="fas fa-plus-circle fa-lg fa-fw text-primary"></i>New</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true"><i class="fas fa-times"></i></span>
                    </button>
                </div>
                <form>
                    <div class="modal-body">
                        <div class="form-group row">
                            <label for="fullname" class="col-sm-2 col-form-label">Name</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" name="fullname" placeholder="Enter...">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="address" class="col-sm-2 col-form-label">Address</label>
                            <div class="col-sm-10">
                                <textarea class="form-control" name="address" rows="3" placeholder="Enter..." style="resize: none;"></textarea>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="contact" class="col-sm-2 col-form-label">Contact</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" name="contact" placeholder="Enter...">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="income" class="col-sm-2 col-form-label">Income</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" name="income" placeholder="Enter...">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="age" class="col-sm-2 col-form-label">Age</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" name="age" placeholder="Enter...">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="far fa-window-close fa-fw"></i>Close</button>
                        <button type="submit" class="btn btn-primary"><i class="far fa-save fa-fw"></i>Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script src="{{asset('js/app.js')}}"></script>
    <script>
    var APP_URL = {!! json_encode(url('/')) !!}, dataTable, columns = {!! $columns !!}, id = {!! array_search('id', array_column(json_decode($columns, true), 'data')) !!}, name = {!! array_search('name', array_column(json_decode($columns, true), 'data')) !!}, income = {!! array_search('annualincome', array_column(json_decode($columns, true), 'data')) !!}, age = {!! array_search('age', array_column(json_decode($columns, true), 'data')) !!}, created_at = {!! array_search('created_at', array_column(json_decode($columns, true), 'data')) !!}, updated_at = {!! array_search('updated_at', array_column(json_decode($columns, true), 'data')) !!};
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
        $("div.boxtitle").html('<h4 class="m-0">{{$heading}}</h4>');
    });
    </script>
</body>
</html>