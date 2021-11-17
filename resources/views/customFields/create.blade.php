<div class="card bg-none card-box">
    {{ Form::open(array('url' => 'custom-field')) }}
    <div class="row">
        <div class="form-group col-md-12">
            {{Form::label('name',__('Custom Field Name'),['class'=>'form-control-label'])}}
            {{Form::text('name',null,array('class'=>'form-control','required'=>'required'))}}
        </div>
        <div class="form-group col-md-12">
            <div class="input-group">
                {{ Form::label('type', __('Type'),['class'=>'form-control-label']) }}
                {{ Form::select('type',$types,null, array('class' => 'form-control select2 ','required'=>'required')) }}
            </div>
        </div>
        <div class="form-group col-md-12">
            <div class="input-group">
                {{ Form::label('module', __('Module'),['class'=>'form-control-label']) }}
                {{ Form::select('module',$modules,null, array('class' => 'form-control select2 ','required'=>'required')) }}
            </div>
        </div>
        <div class="col-md-12">
            <input type="submit" value="{{__('Create')}}" class="btn-create badge-blue">
            <input type="button" value="{{__('Cancel')}}" class="btn-create bg-gray" data-dismiss="modal">
        </div>
    </div>
    {{ Form::close() }}
</div>
