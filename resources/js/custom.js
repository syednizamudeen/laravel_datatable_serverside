(function ( $ ) {
    $.fn._DataTables = function( options ) {
        var dT,settings = $.extend({
            "dom": '<"card"<"card-header"<"boxtitle float-left"><"float-right"B>><"card-body p-0"tr><"card-footer"<"float-left"i><"float-right"p>>>',
            "processing": true,
            "serverSide": true,
            "orderCellsTop": true,
            // "fixedHeader": true,
            "ajax": {
                "url": '',
                "data": function( d ) {
                    d.myKey = "myValue";
                    // d.custom = $('#myInput').val();
                },
                "beforeSend": function (request) {
                    request.setRequestHeader("X-CSRF-TOKEN", $('meta[name="csrf-token"]').attr('content'));
                }
            },
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
                        text: '<i class="fas fa-plus fa-fw"></i>Create',
                        titleAttr: 'Add New',
                        className: 'btn-outline-secondary',
                        attr:  {
                            "data-toggle": 'modal',
                            "data-target": '#createModal'
                        }
                    },
                    {
                        text: '<i class="fas fa-folder-open fa-fw"></i>View',
                        titleAttr: 'Open',
                        className: 'btn-outline-secondary',
                        enabled: false,
                        action: function ( e, dt, node, config ) {            
                        }
                    },
                    {
                        extend: 'collection',
                        text: '<i class="fas fa-check-square fa-fw"></i>Selection',
                        className: 'btn-outline-secondary',
                        autoClose: true,
                        buttons: [
                            {
                                text: '<i class="fas fa-toggle-on fa-fw text-primary"></i>Enable',
                                titleAttr: 'Activate / On',
                                action: function ( e, dt, node, config ) {            
                                }
                            },
                            {
                                text: '<i class="fas fa-toggle-off fa-fw text-primary"></i>Disable',
                                titleAttr: 'Deactivate / off',
                                action: function ( e, dt, node, config ) {            
                                }
                            },
                            {
                                text: '<i class="fas fa-edit fa-fw text-primary"></i>Edit',
                                titleAttr: 'Update / Modify',
                                action: function ( e, dt, node, config ) {            
                                }
                            },
                            {
                                text: '<i class="fas fa-copy fa-fw text-primary"></i>Copy',
                                titleAttr: 'Duplicate',
                                action: function ( e, dt, node, config ) {            
                                }
                            },
                            {
                                text: '<i class="fas fa-trash fa-fw text-primary"></i>Delete',
                                titleAttr: 'Remove',
                                action: function ( e, dt, node, config ) {
                                    var rows = dt.rows( { selected: true } ).count();
                                    if(rows==0)
                                    {
                                        console.log("Select atleast 1 row");
                                    }
                                    else
                                    {
                                        console.log("Are you sure want to Delete?");
                                    }
                                }
                            }
                        ]
                    },
                    {
                        extend:    'colvis',
                        text:      '<i class="fa fa-columns fa-fw"></i> Columns',
                        titleAttr: 'Show/Hide Column(s)',
                        className: 'btn-outline-secondary',
                        autoClose: true,
                        columns: ':not(.disableColhide)'
                    },
                    {
                        extend:    'pageLength',
                        titleAttr: 'Rows per Page',
                        className: 'btn-outline-secondary',
                        autoClose: true,
                    },
                    {
                        extend: 'collection',
                        text: '<i class="fas fa-download fa-fw"></i>Export',
                        className: 'btn-outline-secondary',
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
                        text: '<i class="fas fa-upload fa-fw"></i> Import',
                        titleAttr: 'Bulk Upload',
                        className: 'btn-outline-secondary',
                        action: function ( e, dt, node, config ) {
                        }
                    }
                ]
            },
            "initComplete": function(settings, json) {
                $.each($('th', this.find('tr:first')), function(i) {
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
                        _: '<i class="fas fa-th-list fa-fw" aria-hidden="true"></i> %d Rows',
                        '-1': '<i class="fas fa-th-list fa-fw" aria-hidden="true"></i> Show All'
                    }
                }
            }
        }, options );

        dT = this.DataTable(settings);
        this.on('change', '#checkall', function(e) {
            e.preventDefault();
            that = this.checked;
            $('.checkrow').each(function( index ) {
                $( this ).prop('checked', that).change();
            });
            if (that) {
                dT.rows().select();
            }
            else {
                dT.rows().deselect();
            }
        });    
        dT.on( 'select deselect', function(e, dt, type, indexes) {
            var selectedRows = dT.rows( { selected: true } ).count();        
            dT.button( 1 ).enable( selectedRows === 1 );
        });
        $('.input-filter', this.find('tr')).each(function(i) {
            var column = dT.columns($(this).index());
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
                    dT.column(i).search('').draw();
                }).bind("keydown cut copy paste",function(e) {
                    e.preventDefault();      
                });
            }
            $("input[type=text]", this).on('keyup change', function(e) {
                e.preventDefault();
                if ( column.search() !== this.value ) {
                    column.search( this.value ).draw();
                }
            });
            $('.clearfilter', this).on('click', function(e) {
                e.preventDefault();
                $(this).parent().parent().find("input[type=text]:first").val('');
                dT.column(i).search('').draw();
            });
        });        
        return dT;
    };
}( jQuery ));