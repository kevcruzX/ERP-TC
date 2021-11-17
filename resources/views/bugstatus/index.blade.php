@extends('layouts.admin')
@push('script-page')
    <script src="{{asset('js/jquery-ui.min.js')}}"></script>
    @if(\Auth::user()->type=='company')
        <script>
            $(function () {
                $(".sortable").sortable();
                $(".sortable").disableSelection();
                $(".sortable").sortable({
                    stop: function () {
                        var order = [];
                        $(this).find('li').each(function (index, data) {
                            order[index] = $(data).attr('data-id');
                        });

                        $.ajax({
                            url: "{{route('bugstatus.order')}}",
                            data: {order: order, _token: $('meta[name="csrf-token"]').attr('content')},
                            type: 'POST',
                            success: function (data) {
                            },
                            error: function (data) {
                                data = data.responseJSON;
                                toastr('Error', data.error, 'error')
                            }
                        })
                    }
                });
            });
        </script>
    @endif
@endpush
@section('page-title')
    {{__('Manage Project Bug Status')}}
@endsection
@section('action-button')
<div class="all-button-box row d-flex justify-content-end">
@can('create bug status')
        <div class="col-xl-2 col-lg-2 col-md-4 col-sm-6 col-6">
            <a href="#" class="btn btn-xs btn-white btn-icon-only width-auto" data-url="{{ route('bugstatus.create') }}" data-ajax-popup="true" data-title="{{__('Create Bug Status')}}">
              <i class="fas fa-plus"></i>{{__(' Create')}}</a>
        </div>
@endcan
</div>
@endsection
@section('content')
    <div class="card">
        <div class="card-body">
            <div class="tab-content tab-bordered">
                <div class="tab-pane fade show active" role="tabpanel">
                    <ul class="list-group sortable">
                        @foreach ($bugStatus as $bug)
                            <li class="list-group-item" data-id="{{$bug->id}}">
                                {{$bug->title}}
                                @can('edit bug status')
                                    <span class="float-right">
                                      <a href="#" data-url="{{ URL::to('bugstatus/'.$bug->id.'/edit') }}" data-ajax-popup="true" data-title="{{__('Edit Bug Status')}}" class="edit-icon">
                                          <i class="fas fa-pencil-alt"></i>
                                      </a>
                                         @endcan
                                        @can('delete bug status')
                                            <a href="#!" class="delete-icon" data-toggle="tooltip" data-original-title="{{__('Delete')}}" data-confirm="Are You Sure?|This action can not be undone. Do you want to continue?" data-confirm-yes="document.getElementById('delete-form-{{$bug->id}}').submit();">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                            {!! Form::open(['method' => 'DELETE', 'route' => ['bugstatus.destroy', $bug->id],'id'=>'delete-form-'.$bug->id]) !!}
                                            {!! Form::close() !!}
                                        </span>
                                @endcan
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
            <p class="text-muted mt-4"><strong>{{__('Note')}} : </strong>{{__('You can easily change order of bug status using drag & drop.')}}</p>
        </div>
    </div>
@endsection
