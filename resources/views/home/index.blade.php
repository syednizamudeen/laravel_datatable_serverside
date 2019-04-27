<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="{{asset('vendor/bootstrap/dist/css/bootstrap.min.css')}}">
    <link rel="stylesheet" href="{{asset('vendor/datatables/css/dataTables.bootstrap4.min.css')}}">
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
    var APP_URL = {!! json_encode(url('/')) !!}, columns = {!! $columns !!}, name = {!! array_search('name', array_column(json_decode($columns, true), 'data')) !!};
    $(document).ready(function() {
        $('#example').DataTable({
            "processing": true,
            "serverSide": true,
            "ajax": APP_URL+"/home/datatable",
            "columns": columns,
            "order": [[ name, "asc" ]]
        });
    });
    </script>
</body>
</html>