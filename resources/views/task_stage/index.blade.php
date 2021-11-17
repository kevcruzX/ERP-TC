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
                            url: "{{route('project-task-stages.order')}}",
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
    {{__('Manage Project Task Stages')}}
@endsection
@section('action-button')
<div class="all-button-box row d-flex justify-content-end">
    @can('create project task stage')
        <div class="col-xl-2 col-lg-2 col-md-4 col-sm-6 col-6">
            <a href="#" data-url="{{ route('project-task-stages.create') }}" class="btn btn-xs btn-white btn-icon-only width-auto" data-ajax-popup="true" data-title="{{__('Create Project Task Stage')}}">
                <i class="fa fa-plus"></i> {{__('Create')}}
            </a>
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
                        @foreach ($task_stages as $task_stage)
                            <li class="list-group-item" data-id="{{$task_stage->id}}">
                                {{$task_stage->name}}
                                @can('edit project task stage')
                                    <span class="float-right">
                                      <a href="#" data-url="{{ URL::to('project-task-stages/'.$task_stage->id.'/edit') }}" data-ajax-popup="true" data-title="{{__('Edit Bug Status')}}" class="edit-icon">
                                          <i class="fas fa-pencil-alt"></i>
                                      </a>
                                @endcan
                                @can('delete project task stage')
                                      <a href="#!" class="delete-icon" data-toggle="tooltip" data-original-title="{{__('Delete')}}" data-confirm="Are You Sure?|This action can not be undone. Do you want to continue?" data-confirm-yes="document.getElementById('delete-form-{{$task_stage->id}}').submit();">
                                            <i class="fas fa-trash"></i>
                                      </a>
                                            {!! Form::open(['method' => 'DELETE', 'route' => ['project-task-stages.destroy', $task_stage->id],'id'=>'delete-form-'.$task_stage->id]) !!}
                                            {!! Form::close() !!}
                                    </span>
                                @endcan
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
            <p class="text-muted mt-4"><strong>{{__('Note')}} : </strong>{{__('You can easily change order of project task stage using drag & drop.')}}</p>
        </div>
    </div>
@endsection
