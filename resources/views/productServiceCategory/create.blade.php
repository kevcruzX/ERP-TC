<div class="card bg-none card-box">
    {{ Form::open(array('url' => 'product-category')) }}
    <div class="row">
        <div class="form-group col-md-12">
            {{ Form::label('name', __('Category Name'),['class'=>'form-control-label']) }}
            {{ Form::text('name', '', array('class' => 'form-control','required'=>'required')) }}
        </div>
        <div class="form-group col-md-12">
            <div class="input-group">
                {{ Form::label('type', __('Category Type'),['class'=>'form-control-label']) }}
                {{ Form::select('type',$types,null, array('class' => 'form-control select2 ','required'=>'required')) }}
            </div>
        </div>
        <div class="form-group col-md-12">
            {{ Form::label('color', __('Category Color'),['class'=>'form-control-label']) }}
            {{ Form::text('color', '', array('class' => 'form-control jscolor','required'=>'required')) }}
            <small>{{__('For chart representation')}}</small>
        </div>
        <div class="col-md-12 text-right">
            <input type="submit" value="{{__('Create')}}" class="btn-create badge-blue">
            <input type="button" value="{{__('Cancel')}}" class="btn-create bg-gray" data-dismiss="modal">
        </div>
    </div>
    {{ Form::close() }}
</div>
