<div class="card bg-none card-box">
{{ Form::model($milestone, array('route' => array('project.milestone.update', $milestone->id), 'method' => 'POST')) }}
<div class="row">
    <div class="form-group col-md-6">
        {{ Form::label('title', __('Title'),['class' => 'form-control-label']) }}
        {{ Form::text('title', null, array('class' => 'form-control','required'=>'required')) }}
        @error('title')
        <span class="invalid-title" role="alert">
            <strong class="text-danger">{{ $message }}</strong>
        </span>
        @enderror
    </div>
    <div class="form-group  col-md-6">
        {{ Form::label('status', __('Status'),['class' => 'form-control-label']) }}
        {!! Form::select('status',\App\Project::$project_status, null,array('class' => 'form-control selectric select2','required'=>'required')) !!}
        @error('client')
        <span class="invalid-client" role="alert">
            <strong class="text-danger">{{ $message }}</strong>
        </span>
        @enderror
    </div>
</div>
<div class="row">
    <div class="form-group  col-md-12">
        {{ Form::label('description', __('Description'),['class' => 'form-control-label']) }}
        {!! Form::textarea('description', null, ['class'=>'form-control','rows'=>'2']) !!}
        @error('description')
        <span class="invalid-description" role="alert">
            <strong class="text-danger">{{ $message }}</strong>
        </span>
        @enderror
    </div>
    <div class="col-12 pt-5 text-right">
        <input class="btn btn-sm btn-primary btn-icon rounded-pill" type="submit" value="Update">
    </div>
</div>
{{ Form::close() }}
</div>
