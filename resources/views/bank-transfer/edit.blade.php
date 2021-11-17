<div class="card bg-none card-box">
    {{ Form::model($transfer, array('route' => array('bank-transfer.update', $transfer->id), 'method' => 'PUT')) }}
    <div class="row">
        <div class="form-group  col-md-6">
            {{ Form::label('from_account', __('From Account'),['class'=>'form-control-label']) }}
            {{ Form::select('from_account', $bankAccount,null, array('class' => 'form-control select2','required'=>'required')) }}
        </div>
        <div class="form-group  col-md-6">
            {{ Form::label('to_account', __('To Account'),['class'=>'form-control-label']) }}
            {{ Form::select('to_account', $bankAccount,null, array('class' => 'form-control select2','required'=>'required')) }}
        </div>
        <div class="form-group  col-md-6">
            {{ Form::label('amount', __('Amount'),['class'=>'form-control-label']) }}
            <div class="form-icon-user">
                <span><i class="fas fa-money-bill-alt"></i></span>
                {{ Form::number('amount', null, array('class' => 'form-control','required'=>'required','step'=>'0.01')) }}
            </div>
        </div>
        <div class="form-group  col-md-6">
            {{ Form::label('date', __('Date'),['class'=>'form-control-label']) }}
            <div class="form-icon-user">
                <span><i class="fas fa-calendar"></i></span>
                {{ Form::text('date', null, array('class' => 'form-control datepicker','required'=>'required')) }}
            </div>
        </div>

        <div class="form-group  col-md-6">
            {{ Form::label('reference', __('Reference'),['class'=>'form-control-label']) }}
            <div class="form-icon-user">
                <span><i class="fas fa-clipboard"></i></span>
                {{ Form::text('reference', null, array('class' => 'form-control')) }}
            </div>
        </div>

        <div class="form-group  col-md-12">
            {{ Form::label('description', __('Description'),['class'=>'form-control-label']) }}
            {{ Form::textarea('description', null, array('class' => 'form-control','rows'=>3)) }}
        </div>
        <div class="col-md-12">
            <input type="submit" value="{{__('Update')}}" class="btn-create badge-blue">
            <input type="button" value="{{__('Cancel')}}" class="btn-create bg-gray" data-dismiss="modal">
        </div>
    </div>
    {{ Form::close() }}
</div>
