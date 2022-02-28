
@extends('layout.main') @section('content')
    <section class="forms">
        <div class="container-fluid">
            <div class="card">
                <div class="card-header mt-2">
                    <h3 class="text-center">{{trans('file.Warehouse Report')}}</h3>
                </div>
                <div class="table-responsive mb-4">
                    <table id="sale-table" class="table table-hover">
                        <thead>
                        <tr>
                            <th>Disburse ID</th>
                            <th>Store Name</th>
                            <th>Paid Amount</th>
                            <th>images</th>
                            <th>Comment</th>
                            <th>Date</th>

                        </thead>
                        <tbody>
                        @foreach($disbursements as $disbursement)

                            <tr class="gradeX">
                                <td><a href=""
                                       style="color: red"> {{$disbursement->disburse_id}}</a></td>
                                {{--<td>{{$disbursement->disburse_id}}</td>--}}
                                <td>{{\App\Warehouse::find($disbursement->warehouse_id)->name}}</td>
                                <td>{{$disbursement->paid_amount}}</td>
                                <td>
                                    @if($disbursement->photos==null)
                                        <img src="{{asset('uploads/no_image.png')}}" width="40"
                                             height="40">
                                    @else
                                        <img src="{{asset('uploads/'.$disbursement->photos)}}" width="40"
                                             height="40">
                                    @endif
                                </td>
                                <td>{{$disbursement->comment}}</td>
                                <td class="center hidden-phone">
                                    {{ Carbon\Carbon::parse($disbursement->created_at)->format('d/m/Y') }}
                                </td>
                                {{--<td class="center hidden-phone">
                                    @if($disbursement->is_disbursement==1)
                                        <span class="badge badge-success label-mini">Yes</span>
                                    @else
                                        <span class="badge badge-danger label-mini">No</span>
                                    @endif
                                </td>--}}
                                {{--@can('isSuperAdmin')
                                <td>
                                    <button class="btn btn-success btn-sm" type="button" data-toggle="modal" data-target="#exampleModalCenter"><i class="fa fa-check"></i></button>
                                    <a href="{{route('disbursement.edit', $disbursement->id)}}"><button class="btn btn-primary btn-sm"><i class="fa fa-pencil"></i></button></a>




                                    <form id="delete-form-{{ $disbursement->id }}" action="{{ route('disbursement.destroy',$disbursement->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                    <button type="button" class="btn btn-danger btn-sm"  onclick="if(confirm('Are you sure? You want to delete this?')){
                                        event.preventDefault();
                                        document.getElementById('delete-form-{{ $disbursement->id }}').submit();
                                        }else {
                                        event.preventDefault();
                                        }"><i class="fa fa-trash-o "></i></button>

                                </td>
                                @endcan--}}
                            </tr>
                        @endforeach
                        </tbody>

                </div>
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


    </script>



@endsection