<div class="card bg-none card-box">
    {{ Form::open(array('route' => ['deals.labels.store',$deal->id])) }}
    <div class="row">
        <div class="col-12 form-group">
            <div class="row gutters-xs">
                @foreach ($labels as $label)
                    <div class="col-12 custom-control custom-checkbox mt-2 mb-2">
                        {{ Form::checkbox('labels[]',$label->id,(array_key_exists($label->id,$selected))?true:false,['class' => 'custom-control-input','id'=>'labels_'.$label->id]) }}
                        {{ Form::label('labels_'.$label->id, ucfirst($label->name),['class'=>'custom-control-label ml-4 badge badge-pill text-white badge-'.$label->color]) }}
                    </div>
                @endforeach
            </div>
        </div>
        <div class="form-group col-12 text-right">
            <input type="submit" value="{{__('Save')}}" class="btn-create badge-blue">
            <input type="button" value="{{__('Cancel')}}" class="btn-create bg-gray" data-dismiss="modal">
        </div>
    </div>
    {{ Form::close() }}
</div>
