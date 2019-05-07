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
    <script src="{{asset('js/app.js')}}"></script>
    <script>
    var APP_URL = {!! json_encode(url('/')) !!}, dataTable, columns = {!! $columns !!}, id = {!! array_search('id', array_column(json_decode($columns, true), 'data')) !!}, name = {!! array_search('name', array_column(json_decode($columns, true), 'data')) !!}, income = {!! array_search('annualincome', array_column(json_decode($columns, true), 'data')) !!}, age = {!! array_search('age', array_column(json_decode($columns, true), 'data')) !!}, created_at = {!! array_search('created_at', array_column(json_decode($columns, true), 'data')) !!}, updated_at = {!! array_search('updated_at', array_column(json_decode($columns, true), 'data')) !!};
    $(document).ready(function() {
        dataTable = $('#example').DataTable({
            "dom": '<"card"<"card-header"<"boxtitle float-left"><"float-right"B>><"card-body p-0"t><"card-footer"<"float-left"i><"float-right"p>>>',
            "processing": true,
            "serverSide": true,
            "orderCellsTop": true,
            // "fixedHeader": true,
            // "ajax": APP_URL+"/home/datatable",
            "ajax": {
                "url": APP_URL+"/home/datatable",
                "data": function( d ) {
                    d.myKey = "myValue";
                    // d.custom = $('#myInput').val();
                },
                "beforeSend": function (request) {
                    request.setRequestHeader("X-CSRF-TOKEN", $('meta[name="csrf-token"]').attr('content'));
                }
            },
            "columns": columns,
            "order": [[ name, "asc" ]],
            "select": {
                "style":    'multi',
                "selector": 'table tbody td input[type="checkbox"]'
            },
            "lengthMenu": [
                [ 10, 25, 50, 100, 500 ],
                [ '10 rows', '25 rows', '50 rows', '100 rows', '500 rows' ]
            ],
            "buttons": {
                dom: {
                    button: {
                        tag: 'button',
                        className: 'btn btn-sm'
                    }
                },
                buttons: [
                    {
                        text: '<i class="fas fa-plus fa-fw text-white"></i>Create',
                        titleAttr: 'Add New',
                        className: 'btn-primary',
                        action: function ( e, dt, node, config ) {            
                        }
                    },
                    {
                        text: '<i class="fas fa-folder-open fa-fw text-white"></i>View',
                        titleAttr: 'Open',
                        className: 'btn-primary',
                        action: function ( e, dt, node, config ) {            
                        }
                    },
                    {
                        extend: 'collection',
                        text: '<i class="fas fa-check-square fa-fw text-white"></i>Selection',
                        className: 'btn-primary',
                        autoClose: true,
                        buttons: [
                            {
                                text: '<i class="fas fa-toggle-on fa-fw text-primary"></i>Enable',
                                titleAttr: 'Activate / On',
                                className: 'btn-primary',
                                action: function ( e, dt, node, config ) {            
                                }
                            },
                            {
                                text: '<i class="fas fa-toggle-off fa-fw text-primary"></i>Disable',
                                titleAttr: 'Deactivate / off',
                                className: 'btn-primary',
                                action: function ( e, dt, node, config ) {            
                                }
                            },
                            {
                                text: '<i class="fas fa-edit fa-fw text-primary"></i>Edit',
                                titleAttr: 'Update / Modify',
                                className: 'btn-primary',
                                action: function ( e, dt, node, config ) {            
                                }
                            },
                            {
                                text: '<i class="fas fa-copy fa-fw text-primary"></i>Copy',
                                titleAttr: 'Duplicate',
                                className: 'btn-primary',
                                action: function ( e, dt, node, config ) {            
                                }
                            },
                            {
                                text: '<i class="fas fa-trash fa-fw text-primary"></i>Delete',
                                titleAttr: 'Remove',
                                className: 'btn-primary',
                                action: function ( e, dt, node, config ) {
                                }
                            }
                        ]
                    },
                    {
                        extend:    'colvis',
                        text:      '<i class="fa fa-columns fa-fw text-white"></i> Columns',
                        titleAttr: 'Show/Hide Column(s)',
                        className: 'btn-primary',
                        autoClose: true,
                        columns: ':not(.disableColhide)'
                    },
                    {
                        extend:    'pageLength',
                        titleAttr: 'Rows per Page',
                        className: 'btn-primary',
                        autoClose: true,
                    },
                    {
                        extend: 'collection',
                        text: '<i class="fas fa-download fa-fw text-white"></i>Export',
                        className: 'btn-primary',
                        autoClose: true,
                        buttons: [
                            {
                            extend:    'excelHtml5',
                            exportOptions: {
                                columns: ':visible:not(.disableColhide)',
                                rows: { selected: true }
                            },
                            text: '<i class="fas fa-file-excel fa-fw text-primary"></i>Excel',
                            titleAttr: 'Excel',
                            },
                            {
                            extend: 'csvHtml5',
                            exportOptions: {
                                columns: ':visible:not(.disableColhide)',
                                rows: { selected: true }
                            },
                            text: '<i class="fas fa-file-csv fa-fw text-primary"></i>CSV',
                            titleAttr: 'CSV',
                            },
                            {
                            extend: 'pdfHtml5',
                            exportOptions: {
                                columns: ':visible:not(.disableColhide)',
                                rows: { selected: true }
                            },
                            orientation: 'landscape',
                            pageSize: 'A4',
                            title: 'Summary',
                            /* message: 'downloaded on '+moment().format("DD-MM-YYYY h:mm A"), */
                            text: '<i class="fas fa-file-pdf fa-fw text-primary"></i>PDF',
                            titleAttr: 'PDF',
                            }/* ,
                            {
                            extend: 'print',
                            exportOptions: {
                                columns: ':visible:not(.disableColhide)',
                                rows: { selected: true }
                            },
                            text: '<i class="fa fa-print fa-fw fa-lg text-primary"></i>Print',
                            titleAttr: 'Print',
                            className: 'btn-sm'
                            } */
                        ]
                    },
                    {
                        text: '<i class="fas fa-upload fa-fw text-white"></i> Import',
                        titleAttr: 'Bulk Upload',
                        className: 'btn-primary',
                        action: function ( e, dt, node, config ) {
                        }
                    }
                ]
            },
            "columnDefs": [
                {
                    "targets": id,
                    "className": 'disableColhide text-center',
                    "searchable": false,
                    "orderable": false,
                    "render": function ( data, type, row, meta ) {
                        return '<div class="form-check abc-checkbox abc-checkbox-primary"><input class="form-check-input checkrow" id="checkbox'+data+'" type="checkbox"><label class="form-check-label" for="checkbox'+data+'"></label></div>';
                        // return data;
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
            ],
            "initComplete": function(settings, json) {
                $.each($('th', '#example tr:first'), function(i) {
                    $(this).css("border-top", "unset");
                });
            },
            "drawCallback": function( settings ) {
                if($('#checkall').prop("checked") == true)
                {
                    $('#checkall').prop('checked', false);
                }
            },
            "language": {
                "loadingRecords": '&nbsp;',
                "processing": '<div class="text-center"><div class="spinner-border" role="status"><span class="sr-only">Loading...</span></div></div>',
                "buttons": {
                    pageLength: {
                        _: '<i class="fas fa-th-list fa-fw text-white" aria-hidden="true"></i> %d Rows',
                        '-1': '<i class="fas fa-th-list fa-fw text-white" aria-hidden="true"></i> Show All'
                    }
                }
            }
        });
        $("div.boxtitle").html('<h4 class="m-0">{{$heading}}</h4>');
        $.each($('.input-filter', '#example tr'), function(i) {
            var column = dataTable.columns($(this).index());
            if ($('input', this).attr('filter-type') == 'datetimerange')
            {
                $('input', this).daterangepicker({
                    showDropdowns: true,
                    autoUpdateInput: false,
                    timePicker: true,
                    startDate: moment().startOf('day').subtract(8, 'days'),
                    endDate: moment().endOf('day').subtract(1, 'days'),
                    drops: "down",
                    opens: "left",
                    locale: {
                        format: 'YY/M/DD hh:mm A'
                    }
                }).on('apply.daterangepicker', function(ev, picker) {
                    $(this).val(picker.startDate.format('DD MMM YY HH:mm') + ' - ' + picker.endDate.format('DD MMM YY HH:mm')).change();
                }).on('cancel.daterangepicker', function(ev, picker) {
                    $(this).val('');
                    dataTable.column(i).search('').draw();
                }).bind("keydown cut copy paste",function(e) {
                    e.preventDefault();      
                });
            }
            $("input[type=text]", this).on('keyup change', function(e) {
                e.preventDefault();
                // if ( dataTable.column(i).search() !== this.value ) {
                    // dataTable.column(i).search( this.value ).draw();
                    column.search( this.value ).draw();
                // }
            });
            $('.clearfilter', this).on('click', function(e) {
                e.preventDefault();
                $(this).parent().parent().find("input[type=text]:first").val('');
                dataTable.column(i).search('').draw();
            });
        });
        $('#example').on('change', '#checkall', function(e) {
            e.preventDefault();
            that = this.checked;
            $('.checkrow').each(function( index ) {
                $( this ).prop('checked', that).change();
            });
            if (that) {
                dataTable.rows().select();
            }
            else {
                dataTable.rows().deselect();
            }
        });
    });
    </script>
</body>
</html>