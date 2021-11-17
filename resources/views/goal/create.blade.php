<div class="card bg-none card-box">
    {{ Form::open(array('url' => 'goal')) }}
    <div class="row">
        <div class="form-group col-md-6">
            {{ Form::label('name', __('Name'),['class'=>'form-control-label']) }}
            {{ Form::text('name', '', array('class' => 'form-control','required'=>'required')) }}
        </div>
        <div class="form-group col-md-6">
            {{ Form::label('amount', __('Amount'),['class'=>'form-control-label']) }}
            {{ Form::number('amount', '', array('class' => 'form-control','required'=>'required','step'=>'0.01')) }}
        </div>
        <div class="form-group  col-md-12">
            <div class="input-group">
                {{ Form::label('type', __('Type'),['class'=>'form-control-label']) }}
                {{ Form::select('type',$types,null, array('class' => 'form-control select2','required'=>'required')) }}
            </div>
        </div>
        <div class="form-group  col-md-6">
            {{ Form::label('from', __('From'),['class'=>'form-control-label']) }}
            {{ Form::text('from','', array('class' => 'form-control custom-datepicker')) }}
        </div>
        <div class="form-group  col-md-6">
            {{ Form::label('tp', __('To'),['class'=>'form-control-label']) }}
            {{ Form::text('to','', array('class' => 'form-control custom-datepicker')) }}
        </div>
        <div class="form-group col-md-12">
            <div class="custom-control custom-checkbox">
                <input class="custom-control-input" type="checkbox" name="is_display" id="is_display" checked>
                <label class="custom-control-label form-control-label" for="is_display">{{__('Display On Dashboard')}}</label>
            </div>
        </div>
        <div class="col-md-12">
            <input type="submit" value="{{__('Create')}}" class="btn-create badge-blue">
            <input type="button" value="{{__('Cancel')}}" class="btn-create bg-gray" data-dismiss="modal">
        </div>
    </div>
    {{ Form::close() }}
</div>
