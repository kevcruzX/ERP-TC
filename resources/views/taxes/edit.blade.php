<div class="card bg-none card-box">
    {{ Form::model($tax, array('route' => array('taxes.update', $tax->id), 'method' => 'PUT')) }}
    <div class="row">
        <div class="form-group col-md-6">
            {{ Form::label('name', __('Tax Rate Name'),['class'=>'form-control-label']) }}
            {{ Form::text('name', null, array('class' => 'form-control font-style','required'=>'required')) }}
            @error('name')
            <small class="invalid-name" role="alert">
                <strong class="text-danger">{{ $message }}</strong>
            </small>
            @enderror
        </div>
        <div class="form-group col-md-6">
            {{ Form::label('rate', __('Tax Rate %'),['class'=>'form-control-label']) }}
            {{ Form::number('rate', null, array('class' => 'form-control','required'=>'required','step'=>'0.01')) }}
            @error('rate')
            <small class="invalid-rate" role="alert">
                <strong class="text-danger">{{ $message }}</strong>
            </small>
            @enderror
        </div>
        <div class="col-md-12">
            <input type="submit" value="{{__('Update')}}" class="btn-create badge-blue">
            <input type="button" value="{{__('Cancel')}}" class="btn-create bg-gray" data-dismiss="modal">
        </div>
    </div>
    {{ Form::close() }}
</div>
