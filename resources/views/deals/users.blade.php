<div class="card bg-none card-box">
    {{ Form::model($deal, array('route' => array('deals.users.update', $deal->id), 'method' => 'PUT')) }}
    <div class="row">
        <div class="col-12 form-group">
            {{ Form::label('users', __('User'),['class'=>'form-control-label']) }}
            {{ Form::select('users[]', $users,false, array('class' => 'form-control select2','multiple'=>'','required'=>'required')) }}
        </div>
        <div class="col-12 form-group text-right">
            <input type="submit" value="{{__('Save')}}" class="btn-create badge-blue">
            <input type="button" value="{{__('Cancel')}}" class="btn-create bg-gray" data-dismiss="modal">
        </div>
    </div>
    {{ Form::close() }}
</div>
