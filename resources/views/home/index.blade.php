<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="{{asset('vendor/bootstrap/dist/css/bootstrap.min.css')}}">
    <link rel="stylesheet" href="{{asset('vendor/datatables/css/dataTables.bootstrap4.min.css')}}">
    <link rel="stylesheet" href="{{asset('vendor/fontawesome/css/all.css')}}" type="text/css">
    <title>{{config('app.name','Laravel DataTable')}}</title>
</head>
<body>
    <main role="main" class="flex-shrink-0">
        <div class="container-fluid">
            <h1 class="mt-5">{{$title}}</h1>
            <h2 class="my-4">{{$heading}}</h2>
            <div class="table-responsive">
                <table id="example" class="table table-striped table-bordered" style="width:100%">
                    <thead>
                        <tr>
                            @foreach($columnmapping as $column)
                            <th>{{$column['label']}}</th>
                            @endforeach
                        </tr>
                        <tr>
                            @foreach($columnmapping as $column)
                            <th class="input-filter">
                                <div class="input-group input-group-sm">
                                    <input type="{{$column['type']}}" class="form-control" placeholder="Search {{$column['label']}}">
                                    <div class="input-group-append">
                                        <button class="btn btn-outline-secondary clearfilter" type="button"><i class="fas fa-times-circle"></i></button>
                                    </div>
                                </div>
                            </th>
                            @endforeach
                        </tr>
                    </thead>
                    {{-- <tbody>
                    @foreach($staffs as $staff)
                        <tr>
                            <td>{{$staff->id}}</td>
                            <td>{{$staff->name}}</td>
                            <td>{{$staff->address}}</td>
                            <td>{{$staff->contactno}}</td>
                            <td>{{$staff->annualincome}}</td>
                            <td>{{$staff->age}}</td>
                            <td>{{$staff->created_at}}</td>
                            <td>{{$staff->updated_at}}</td>
                        </tr>
                    @endforeach                    
                    </tbody> --}}
                </table>
            </div>
        </div>
    </main>
    <script src="{{asset('vendor/jquery/jquery.min.js')}}"></script>
    <script src="{{asset('vendor/datatables/js/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('vendor/datatables/js/dataTables.bootstrap4.min.js')}}"></script>
    <script src="{{asset('vendor/bootstrap/dist/js/bootstrap.min.js')}}"></script>
    <script>
    var APP_URL = {!! json_encode(url('/')) !!}, dataTable, columns = {!! $columns !!}, name = {!! array_search('name', array_column(json_decode($columns, true), 'data')) !!};
    $(document).ready(function() {
        dataTable = $('#example').DataTable({
            "processing": true,
            "serverSide": true,
            orderCellsTop: true,
            "ajax": APP_URL+"/home/datatable",
            "columns": columns,
            "order": [[ name, "asc" ]]
        });
        $.each($('.input-filter', '#example tr'), function(i) {
            var column = dataTable.columns($(this).index());
            $('input', this).on('keyup change', function(e) {
                e.preventDefault();
                // if ( dataTable.column(i).search() !== this.value ) {
                    // dataTable.column(i).search( this.value ).draw();
                    column.search( this.value ).draw();
                // }
            });
            $('.clearfilter', this).on('click', function(e) {
                e.preventDefault();
                $(this).parent().parent().find("input[type=text]:first").val("");
                dataTable.column(i).search( '').draw();
            });
        });
    });
    </script>
</body>
</html>
