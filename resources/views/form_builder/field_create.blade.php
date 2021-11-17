<div class="card bg-none card-box">
{{ Form::open(array('route' => ['form.field.store',$formbuilder->id])) }}
<div class="row" id="frm_field_data">
    <div class="col-12 form-group">
        {{ Form::label('name', __('Question Name'),['class'=>'form-control-label']) }}
        {{ Form::text('name[]', '', array('class' => 'form-control','required'=>'required')) }}
    </div>
    <div class="col-12 form-group">
        {{ Form::label('type', __('Type'),['class'=>'form-control-label']) }}
        {{ Form::select('type[]', $types,null, array('class' => 'form-control','data-toggle'=>'select','required'=>'required')) }}
    </div>
    <div class="col-md-12">
        <input type="submit" value="{{__('Create')}}" class="btn-create badge-blue">
        <input type="button" value="{{__('Cancel')}}" class="btn-create bg-gray" data-dismiss="modal">
    </div>
</div>
{{ Form::close() }}
</div>
