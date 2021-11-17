<div class="card bg-none card-box">
    {{ Form::open(array('url' => 'bank-account')) }}
    <div class="row">
        <div class="form-group col-md-6">
            {{ Form::label('holder_name', __('Bank Holder Name'),['class'=>'form-control-label']) }}
            <div class="form-icon-user">
                <span><i class="fas fa-address-card"></i></span>
                {{ Form::text('holder_name', '', array('class' => 'form-control','required'=>'required')) }}
            </div>
        </div>
        <div class="form-group  col-md-6">
            {{ Form::label('bank_name', __('Bank Name'),['class'=>'form-control-label']) }}
            <div class="form-icon-user">
                <span><i class="fas fa-university"></i></span>
                {{ Form::text('bank_name', '', array('class' => 'form-control','required'=>'required')) }}
            </div>
        </div>
        <div class="form-group  col-md-6">
            {{ Form::label('account_number', __('Account Number'),['class'=>'form-control-label']) }}
            <div class="form-icon-user">
                <span><i class="fas fa-notes-medical"></i></span>
                {{ Form::text('account_number', '', array('class' => 'form-control','required'=>'required')) }}
            </div>
        </div>
        <div class="form-group  col-md-6">
            {{ Form::label('opening_balance', __('Opening Balance'),['class'=>'form-control-label']) }}
            <div class="form-icon-user">
                <span><i class="fas fa-dollar-sign"></i></span>
                {{ Form::number('opening_balance', '', array('class' => 'form-control','required'=>'required','step'=>'0.01')) }}
            </div>
        </div>
        <div class="form-group  col-md-12">
            {{ Form::label('contact_number', __('Contact Number'),['class'=>'form-control-label']) }}
            <div class="form-icon-user">
                <span><i class="fas fa-mobile-alt"></i></span>
                {{ Form::text('contact_number', '', array('class' => 'form-control','required'=>'required')) }}
            </div>
        </div>
        <div class="form-group  col-md-12">
            {{ Form::label('bank_address', __('Bank Address'),['class'=>'form-control-label']) }}
            {{ Form::textarea('bank_address', '', array('class' => 'form-control','rows'=>3,'required'=>'required')) }}
        </div>
        @if(!$customFields->isEmpty())
            <div class="col-md-12">
                <div class="tab-pane fade show" id="tab-2" role="tabpanel">
                    @include('customFields.formBuilder')
                </div>
            </div>
        @endif
        <div class="col-md-12">
            <input type="submit" value="{{__('Create')}}" class="btn-create badge-blue">
            <input type="button" value="{{__('Cancel')}}" class="btn-create bg-gray" data-dismiss="modal">
        </div>
    </div>
    {{ Form::close() }}
</div>
