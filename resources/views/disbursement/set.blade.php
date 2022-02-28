@extends('layout.main') @section('content')

    <section class="forms">
        <div class="container-fluid">
            <div class="card">
                <div class="card-header mt-2">
                    <h3 class="text-center">{{trans('file.Warehouse Report')}}</h3>
                </div>
                {!! Form::open(['route' => 'disbursement.set', 'method' => 'post']) !!}
                <div class="row mb-3">
                    <div class="col-md-5 offset-md-1 mt-3">
                        <div class="form-group row">
                            <label class="d-tc mt-2"><strong>{{trans('file.Choose Your Date')}}</strong> &nbsp;</label>
                            <div class="d-tc">
                                <div class="input-group">
                                    <input type="text" name="daterange" class="daterangepicker-field form-control" value="{{$start_date}} To {{$end_date}}" required />
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mt-3">
                        <div class="form-group row">
                            <label class="d-tc mt-2"><strong>{{trans('file.Choose Warehouse')}}</strong> &nbsp;</label>
                            <div class="d-tc">
                                <input type="hidden" name="warehouse_id_hidden" value="{{$warehouse_id}}" />
                                <select id="warehouse_id" name="warehouse_id" class="selectpicker form-control" data-live-search="true" data-live-search-style="begins">
                                    @foreach($lims_warehouse_list as $warehouse)
                                        <option value="{{$warehouse->id}}">{{$warehouse->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2 mt-3">
                        <div class="form-group">
                            <button class="btn btn-primary" type="submit">{{trans('file.submit')}}</button>
                        </div>
                    </div>
                </div>
                <input type="hidden" name="warehouse_id_hidden" value="{{$warehouse_id}}" />
                {!! Form::close() !!}
            </div>

            <div class="table-responsive mb-4">
                {!! Form::open(['route' => 'disbursement.batch'], ['method'=>'post']) !!}
                <button class="btn btn-info">Submit</button><br>
                <table id="sale-table" class="table table-hover">
                    <thead>
                    <tr>
                        <th><input type="checkbox" id="select-all"></th>
                        <th>Transaction ID</th>
                        <th>Disbursement</th>
                        <th>Date</th>
                        <th>Amount</th>
                    </tr>
                    </thead>

                    @foreach($sale_list_by_shops as $result)
                        <tr>
                            <th><input type="checkbox" name="transID[]" value="{{$result->id}}"></th>
                            <input type="hidden" name="storeID" value="{{$result->wearhouse_id}}">
                            <td>{{$result->reference_no}}</td>
                            <td>
                                @if($result->is_disburse==1)
                                    <span class="badge badge-info label-mini">Yes</span>
                                @else
                                    <span class="badge badge-danger label-mini">No</span>
                            @endif
                            <td>{{ Carbon\Carbon::parse($result->created_at)->format('d/m/Y') }}</td>
                            <td style="text-align: right">{{$result->paid_amount}}</td>

                        </tr>
                    @endforeach
<!--                    <tr>
                        <td colspan="4"></td>
                        <td style="text-align: right">Total:  </td>
                    </tr>-->
                    <tfoot class="tfoot active">
                    <tr>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th style="text-align: right; font-weight: bold;">Total:</th>
                        <th style="text-align: right; font-weight: bold;"></th>
                    </tr>
                    </tfoot>
                </table>
                {!! Form::close() !!}
            </div>
        </div>
    </section>

    <script>
        $(document).ready(function () {
            $('#select-all').click(function () {
                $('input[type="checkbox"]').prop('checked', this.checked);
            })

        });
        $('#warehouse_id').val($('input[name="warehouse_id_hidden"]').val());
        $('.selectpicker').selectpicker('refresh');

        $('#sale-table').DataTable( {
            "order": [],
            'columnDefs': [
                {
                    "orderable": false,
                    'targets': 0
                },
                {
                    'render': function(data, type, row, meta){
                        if(type === 'display'){
                            data = '<div class="checkbox"><input type="checkbox" class="dt-checkboxes"><label></label></div>';
                        }

                        return data;
                    },
                }
            ],
            'select': { style: 'multi',  selector: 'td:first-child'},
            'lengthMenu': [[10, 25, 50, -1], [10, 25, 50, "All"]],
            dom: '<"row"lfB>rtip',
            buttons: [
                {
                    extend: 'pdf',
                    exportOptions: {
                        columns: ':visible:Not(.not-exported-sale)',
                        rows: ':visible'
                    },
                    action: function(e, dt, button, config) {
                        datatable_sum_sale(dt, true);
                        $.fn.dataTable.ext.buttons.pdfHtml5.action.call(this, e, dt, button, config);
                        datatable_sum_sale(dt, false);
                    },
                    footer:true
                },
                {
                    extend: 'csv',
                    exportOptions: {
                        columns: ':visible:Not(.not-exported-sale)',
                        rows: ':visible'
                    },
                    action: function(e, dt, button, config) {
                        datatable_sum_sale(dt, true);
                        $.fn.dataTable.ext.buttons.csvHtml5.action.call(this, e, dt, button, config);
                        datatable_sum_sale(dt, false);
                    },
                    footer:true
                },
                {
                    extend: 'print',
                    exportOptions: {
                        columns: ':visible:Not(.not-exported-sale)',
                        rows: ':visible'
                    },
                    action: function(e, dt, button, config) {
                        datatable_sum_sale(dt, true);
                        $.fn.dataTable.ext.buttons.print.action.call(this, e, dt, button, config);
                        datatable_sum_sale(dt, false);
                    },
                    footer:true
                },
                {
                    extend: 'colvis',
                    columns: ':gt(0)'
                }
            ],
            drawCallback: function () {
                var api = this.api();
                datatable_sum_sale(api, false);
            },

                "footerCallback": function ( row, data, start, end, display ) {
                var api = this.api();
                nb_cols = api.columns().nodes().length;
                var j = 4;
                while(j < nb_cols){
                    var pageTotal = api
                        .column( j, { page: 'current'} )
                        .data()
                        .reduce( function (a, b) {
                            return Number(a) + Number(b);
                        }, 0 );
                    var pgTotal = pageTotal.toFixed(2)
                    // Update footer
                    $( api.column( j ).footer() ).html(pgTotal);
                    j++;
                }
            }


        } );

        function datatable_sum_sale(dt_selector, is_calling_first) {
            if (dt_selector.rows( '.selected' ).any() && is_calling_first) {
                var rows = dt_selector.rows( '.selected' ).indexes();

                $( dt_selector.column( 5 ).footer() ).html(dt_selector.cells( rows, 5, { page: 'current' } ).data().sum().toFixed(2));
                $( dt_selector.column( 6 ).footer() ).html(dt_selector.cells( rows, 6, { page: 'current' } ).data().sum().toFixed(2));
                $( dt_selector.column( 7 ).footer() ).html(dt_selector.cells( rows, 7, { page: 'current' } ).data().sum().toFixed(2));
            }
            else {
                $( dt_selector.column( 5 ).footer() ).html(dt_selector.column( 5, {page:'current'} ).data().sum().toFixed(2));
                $( dt_selector.column( 6 ).footer() ).html(dt_selector.column( 6, {page:'current'} ).data().sum().toFixed(2));
                $( dt_selector.column( 7 ).footer() ).html(dt_selector.cells( rows, 7, { page: 'current' } ).data().sum().toFixed(2));
            }
        }

        $('input[name="daterange"]').daterangepicker({
            opens: 'left'
        }, function(start, end, label) {
            console.log("A new date selection was made: " + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD'));
        });
    </script>
@endsection