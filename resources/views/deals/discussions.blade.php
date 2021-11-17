<div class="card bg-none card-box">
    {{ Form::model($deal, array('route' => array('deals.discussion.store', $deal->id), 'method' => 'POST')) }}
    <div class="row">
        <div class="col-12 form-group">
            {{ Form::label('comment', __('Message'),['class'=>'form-control-label']) }}
            {{ Form::textarea('comment', null, array('class' => 'form-control')) }}
        </div>
        <div class="col-12 form-group text-right">
            <input type="submit" value="{{__('Save')}}" class="btn-create badge-blue">
            <input type="button" value="{{__('Cancel')}}" class="btn-create bg-gray" data-dismiss="modal">
        </div>
    </div>
    {{ Form::close() }}
</div>
