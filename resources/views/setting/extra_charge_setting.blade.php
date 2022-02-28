@extends('layout.main') @section('content')
    @if (session()->has('message'))
        <div class="alert alert-success alert-dismissible text-center"><button type="button" class="close"
                data-dismiss="alert" aria-label="Close"><span
                    aria-hidden="true">&times;</span></button>{{ session()->get('message') }}</div>
    @endif
    @if (session()->has('not_permitted'))
        <div class="alert alert-danger alert-dismissible text-center"><button type="button" class="close"
                data-dismiss="alert" aria-label="Close"><span
                    aria-hidden="true">&times;</span></button>{{ session()->get('not_permitted') }}</div>
    @endif
    <section class="forms">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header d-flex align-items-center">
                            {{-- <h4>{{ trans('file.General Setting') }}</h4> --}}
                            <h4>Charges Setting</h4>
                        </div>
                        <div class="card-body">
                            <p class="italic">
                                <small>{{ trans('file.The field labels marked with * are required input fields') }}.</small>
                            </p>
                            {!! Form::open(['route' => 'setting.extraChargeStore', 'files' => true, 'method' => 'post']) !!}
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Payment gateway commissions (%) *</label>
                                        <input type="number" name="service_charge" class="form-control" min="0" step="0.1"
                                            value="{{ $lims_general_setting_data ? $lims_general_setting_data->service_charge : 0 }}"
                                            required />
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>VAT (%) *</label>
                                        <input type="number" name="vat" class="form-control" min="0" step="0.1"
                                            value="{{ $lims_general_setting_data ? $lims_general_setting_data->vat : 0 }}"
                                            required />
                                    </div>
                                    <div class="form-group">
                                        <input type="submit" value="{{ trans('file.submit') }}" class="btn btn-primary">
                                    </div>
                                </div>
                            </div>
                            {!! Form::close() !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script type="text/javascript">
        $("ul#setting").siblings('a').attr('aria-expanded', 'true');
        $("ul#setting").addClass("show");
        $("ul#setting #general-setting-menu").addClass("active");

        if ($("input[name='timezone_hidden']").val()) {
            $('select[name=timezone]').val($("input[name='timezone_hidden']").val());
            $('select[name=staff_access]').val($("input[name='staff_access_hidden']").val());
            $('select[name=date_format]').val($("input[name='date_format_hidden']").val());
            $('.selectpicker').selectpicker('refresh');
        }

        $('.theme-option').on('click', function() {
            $.get('general_setting/change-theme/' + $(this).data('color'), function(data) {});
            var style_link = $('#custom-style').attr('href').replace(/([^-]*)$/, $(this).data('color'));
            $('#custom-style').attr('href', style_link);
        });
    </script>
@endsection
