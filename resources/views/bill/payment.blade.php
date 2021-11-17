<div class="card bg-none card-box">
    {{ Form::open(array('route' => array('bill.payment', $bill->id),'method'=>'post')) }}
    <div class="row">
        <div class="form-group col-md-6">
            {{ Form::label('date', __('Date'),['class'=>'form-control-label']) }}
            <div class="form-icon-user">
                <span><i class="fas fa-calendar"></i></span>
                {{ Form::text('date', '', array('class' => 'form-control datepicker','required'=>'required')) }}
            </div>
        </div>
        <div class="form-group col-md-6">
            {{ Form::label('amount', __('Amount'),['class'=>'form-control-label']) }}
            <div class="form-icon-user">
                <span><i class="fas fa-money-bill-alt"></i></span>
                {{ Form::text('amount',$bill->getDue(), array('class' => 'form-control','required'=>'required')) }}
            </div>
        </div>
        <div class="form-group col-md-6">
            <div class="input-group">
                {{ Form::label('account_id', __('Account'),['class'=>'form-control-label']) }}
                {{ Form::select('account_id',$accounts,null, array('class' => 'form-control select2','required'=>'required')) }}
            </div>
        </div>

        <div class="form-group col-md-6">
            {{ Form::label('reference', __('Reference'),['class'=>'form-control-label']) }}
            <div class="form-icon-user">
                <span><i class="fas fa-sticky-note"></i></span>
                {{ Form::text('reference', '', array('class' => 'form-control')) }}
            </div>
        </div>
        <div class="form-group  col-md-12">
            {{ Form::label('description', __('Description'),['class'=>'form-control-label']) }}
            {{ Form::textarea('description', '', array('class' => 'form-control','rows'=>3)) }}
        </div>
        <div class="col-md-12">
            <input type="submit" value="{{__('Add')}}" class="btn-create badge-blue">
            <input type="button" value="{{__('Cancel')}}" class="btn-create bg-gray" data-dismiss="modal">
        </div>
    </div>
    {{ Form::close() }}
</div>
