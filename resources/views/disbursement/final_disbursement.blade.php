
@extends('layout.main') @section('content')
    <section class="forms">
        <div class="container-fluid">
            <div class="card">
                <div class="card-header mt-2">
                    <h3 class="text-center">{{trans('file.Warehouse Report')}}</h3>
                </div>
                {{--{!! Form::open(['route' => 'disbursement.set', 'method' => 'post']) !!}
                <div class="row mb-3">
                    <div class="col-md-5 offset-md-1 mt-3">
                        <div class="form-group row">
                            <label class="d-tc mt-2"><strong>{{trans('file.Choose Your Date')}}</strong> &nbsp;</label>
                            <div class="d-tc">
                                <div class="input-group">
                                    <input type="text" class="daterangepicker-field form-control" value="{{$start_date}} To {{$end_date}}" required />
                                    <input type="hidden" name="start_date" value="{{$start_date}}" />
                                    <input type="hidden" name="end_date" value="{{$end_date}}" />
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
                {!! Form::close() !!}--}}


            </div>
        </div>

        <div class="card-body">
            Batch ID: {{$request->batchID}}<br>
            Store ID: {{$request->storeName}}<br>
            Total amount: {{$request->totalAmount}}<br>
<           <form action="{{ route('disbursement.confirmation') }}" enctype="multipart/form-data"
                  method="post">
                @csrf
                Image:<br><input type="file" name="image"> <br><br>
                Comment:<br><textarea name="comment" cols="38" rows="4"></textarea> <br>
                <input type="hidden" name="batchID" value="{{$request->batchID}}">
                <input type="hidden" name="storeID" value="{{$request->storeID}}">
                <input type="hidden" name="totalAmount" value="{{$request->totalAmount}}">
                <button type="submit" class="btn btn-sm btn-success">Submit</button>
            </form>

        </div>

    </section>

    <script>
        $(document).ready(function () {
            $('#select-all').click(function () {
                $('input[type="checkbox"]').prop('checked', this.checked);
            })
        });
    </script>



@endsection