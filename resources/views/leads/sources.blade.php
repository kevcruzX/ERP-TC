<div class="card bg-none card-box">
    {{ Form::model($lead, array('route' => array('leads.sources.update', $lead->id), 'method' => 'PUT')) }}
    <div class="row">
        <div class="col-12 form-group">
            <div class="row gutters-xs">
                @foreach ($sources as $source)
                    <div class="col-12 custom-control custom-checkbox mt-2 mb-2">
                        {{ Form::checkbox('sources[]',$source->id,($selected && array_key_exists($source->id,$selected))?true:false,['class' => 'custom-control-input','id'=>'sources_'.$source->id]) }}
                        {{ Form::label('sources_'.$source->id, ucfirst($source->name),['class'=>'custom-control-label ml-4 text-sm font-weight-bold']) }}
                    </div>
                @endforeach
            </div>
        </div>
        <div class="col-12 form-group text-right">
            <input type="submit" value="{{__('Save')}}" class="btn-create badge-blue">
            <input type="button" value="{{__('Cancel')}}" class="btn-create bg-gray" data-dismiss="modal">
        </div>
    </div>
    {{ Form::close() }}
</div>
