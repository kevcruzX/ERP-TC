<div class="card bg-none card-box">
    {{ Form::open(array('url' => 'bugstatus')) }}
    <div class="row">
        <div class="form-group col-12">
            {{ Form::label('title', __('Bug Status Title'),['class'=>'form-control-label']) }}
            {{ Form::text('title', '', array('class' => 'form-control','required'=>'required')) }}
        </div>
        <div class="col-12 text-right">
            <input type="submit" value="{{__('Create')}}" class="btn-create badge-blue">
            <input type="button" value="{{__('Cancel')}}" class="btn-create bg-gray" data-dismiss="modal">
        </div>
    </div>
    {{ Form::close() }}
</div>
