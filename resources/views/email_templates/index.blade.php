@extends('layouts.admin')

@section('page-title')
    {{__('Email Templates')}}
@endsection

@push('script-page')
    <script type="text/javascript">
        @can('On-Off Email Template')
        $(document).on("click", ".email-template-checkbox", function () {
            var chbox = $(this);
            $.ajax({
                url: chbox.attr('data-url'),
                data: {_token: $('meta[name="csrf-token"]').attr('content'), status: chbox.val()},
                type: 'PUT',
                success: function (response) {
                    if (response.is_success) {
                        show_toastr('Success', response.success, 'success');
                        if (chbox.val() == 1) {
                            $('#' + chbox.attr('id')).val(0);
                        } else {
                            $('#' + chbox.attr('id')).val(1);
                        }
                    } else {
                        show_toastr('Error', response.error, 'error');
                    }
                },
                error: function (response) {
                    response = response.responseJSON;
                    if (response.is_success) {
                        show_toastr('Error', response.error, 'error');
                    } else {
                        show_toastr('Error', response, 'error');
                    }
                }
            })
        });
        @endcan
    </script>
@endpush

{{--@section('action-button')--}}
{{--    <div class="all-button-box row d-flex justify-content-end">--}}
{{--            @can('Create User')--}}
{{--            <div class="col-xl-2 col-lg-2 col-md-4 col-sm-6 col-6">--}}
{{--                <a href="#" class="btn btn-xs btn-white btn-icon-only width-auto" data-ajax-popup="true" data-title="{{__('Create New Email Template')}}" data-url="{{route('email_template.create')}}"><i class="fas fa-plus"></i> {{__('Add')}} </a>--}}
{{--            </div>--}}
{{--        @endcan--}}
{{--    </div>--}}
{{--@endsection--}}

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped dataTable">
                            <thead>
                            <tr>
                                <th width="92%"> {{__('Name')}}</th>
                                @if(\Auth::user()->type == 'Super Admin')
                                    <th> {{__('Action')}}</th>
                                @elseif(\Auth::user()->type == 'Owner')
                                    <th> {{__('On/Off')}}</th>
                                @endcan
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($EmailTemplates as $EmailTemplate)
                                <tr>
                                    <td>{{ $EmailTemplate->name }}</td>
                                    <td class="">
                                        @can('Edit Email Template Lang')
                                            <a href="{{ route('manage.email.language',[$EmailTemplate->id,\Auth::user()->lang]) }}" class="edit-icon">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        @endcan
                                        @can('On-Off Email Template')
                                            <div class="tab-pane">
                                                <label class="switch">
                                                    <input type="checkbox" class="email-template-checkbox" id="email_tempalte_{{$EmailTemplate->template->id}}" @if($EmailTemplate->template->is_active == 1) checked="checked" @endcan type="checkbox" value="{{$EmailTemplate->template->is_active}}" data-url="{{route('status.email.language',[$EmailTemplate->template->id])}}"/>
                                                    <span class="slider1 round"></span>
                                                </label>
                                            </div>
                                        @endcan
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
