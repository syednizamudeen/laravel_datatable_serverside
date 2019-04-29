<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="{{asset('vendor/bootstrap/dist/css/bootstrap.min.css')}}">
    <link rel="stylesheet" href="{{asset('vendor/datatables/css/dataTables.bootstrap4.min.css')}}">
    {{-- <link rel="stylesheet" href="http://cdn.datatables.net/buttons/1.5.6/css/buttons.dataTables.css"> --}}
    <link rel="stylesheet" href="http://cdn.datatables.net/buttons/1.5.6/css/buttons.bootstrap4.min.css">
    <link rel="stylesheet" href="{{asset('vendor/fontawesome/css/all.css')}}" type="text/css">
    <style>
        .dt-button-collection li.buttons-columnVisibility a:before {
          display: inline-block;
          font: normal normal normal 14px/1 FontAwesome;
          font-size: inherit;
          text-rendering: auto;
          -webkit-font-smoothing: antialiased;
          transform: translate(0,0);
          content: "\f00d";
          margin-right: 13px;
        }
    
        .dt-button-collection li.buttons-columnVisibility.active a:before {
          display: inline-block;
          font: normal normal normal 14px/1 FontAwesome;
          font-size: inherit;
          text-rendering: auto;
          -webkit-font-smoothing: antialiased;
          transform: translate(0,0);
          content: "\f00c";
          margin-right: 10px;
        }
        .card-header > .datatable-box-tools {
          color: #ffffff;
          position: absolute;
          right: 10px;
          top: 5px;
        }
    </style>
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
                </table>
            </div>
        </div>
    </main>
    <script src="{{asset('vendor/jquery/jquery.min.js')}}"></script>
    <script src="{{asset('vendor/datatables/js/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('vendor/datatables/js/dataTables.bootstrap4.min.js')}}"></script>
    <script src="http://cdn.datatables.net/buttons/1.5.6/js/dataTables.buttons.min.js"></script>
    <script src="http://cdn.datatables.net/buttons/1.5.6/js/buttons.bootstrap4.min.js"></script>
    <script src="http://cdn.datatables.net/buttons/1.5.6/js/buttons.colVis.js"></script>
    <script src="{{asset('vendor/bootstrap/dist/js/bootstrap.min.js')}}"></script>
    <script src="{{asset('vendor/moment/moment.min.js')}}"></script>
    <script>
    var APP_URL = {!! json_encode(url('/')) !!}, dataTable, columns = {!! $columns !!}, name = {!! array_search('name', array_column(json_decode($columns, true), 'data')) !!}, income = {!! array_search('annualincome', array_column(json_decode($columns, true), 'data')) !!}, age = {!! array_search('age', array_column(json_decode($columns, true), 'data')) !!}, created_at = {!! array_search('created_at', array_column(json_decode($columns, true), 'data')) !!}, updated_at = {!! array_search('updated_at', array_column(json_decode($columns, true), 'data')) !!};
    $(document).ready(function() {
        dataTable = $('#example').DataTable({
            "dom": '<"card"<"card-header"<"boxtitle float-left"><"float-right"B>><"card-body p-0"t><"card-footer"<"row"<"col-6"i><"col-6"p>>>>',
            "processing": true,
            "serverSide": true,
            "orderCellsTop": true,
            "ajax": APP_URL+"/home/datatable",
            "columns": columns,
            "order": [[ name, "asc" ]],
            "buttons": [
                {
                    extend:    'colvis',
                    text:      '<i class="fa fa-columns fa-lg fa-fw text-primary"></i>Columns',
                    titleAttr: 'Show/Hide Column(s)',
                    className: 'btn btn-sm',
                    autoClose: true,
                    columns: ':not(.disableColhide)',
                },
                {
                    extend:    'pageLength',
                    titleAttr: 'Rows per Page',
                    className: 'btn btn-primary btn-sm',
                    autoClose: true,
                }
            ],
            "columnDefs": [
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
        $("div.boxtitle").html('<h3 class="box-title">{{$heading}}</h3>');
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