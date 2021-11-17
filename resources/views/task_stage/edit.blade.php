<div class="card bg-none card-box">
    {{ Form::model($taskStage, array('route' => array('project-task-stages.update', $taskStage->id), 'method' => 'PUT')) }}
    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                {{Form::label('name',__('Project Task Stage Title'),['class'=>'form-control-label'])}}
                {{Form::text('name',null,array('class'=>'form-control','placeholder'=>__('Enter project stage title')))}}
            </div>
        </div>
        <div class="form-group col-12">
            {{ Form::label('color', __('Color'),['class'=>'form-control-label']) }}
            <input class="jscolor form-control " value="{{ $taskStage->color }}" name="color" id="color" required>
            <small class="small">{{ __('For chart representation') }}</small>
        </div>
        <div class="col-12">
            <input type="submit" value="{{__('Update')}}" class="btn-create badge-blue">
            <input type="button" value="{{__('Cancel')}}" class="btn-create bg-gray" data-dismiss="modal">
        </div>
    </div>
    {{Form::close()}}
</div>
