<div class="card bg-none card-box">
    {{ Form::open(array('url' => 'deals')) }}
      @if(!$customFields->isEmpty())
      <ul class="nav nav-tabs my-3" role="tablist">
            <li>
                <a class="active" data-toggle="tab" href="#tab-1" role="tab" aria-selected="true">{{__('Deal Detail')}}</a>
            </li>
            <li>
                <a data-toggle="tab" href="#tab-2" role="tab" aria-selected="true">{{__('Custom Fields')}}</a>
            </li>
      </ul>
      @endif
    <div class="tab-content tab-bordered">
        <div class="tab-pane fade show active" id="tab-1" role="tabpanel">
            <div class="row">
                <div class="col-6 form-group">
                    {{ Form::label('name', __('Deal Name'),['class'=>'form-control-label']) }}
                    {{ Form::text('name', null, array('class' => 'form-control','required'=>'required')) }}
                </div>
                <div class="col-6 form-group">
                    {{ Form::label('price', __('Price'),['class'=>'form-control-label']) }}
                    {{ Form::number('price', 0, array('class' => 'form-control','min'=>0)) }}
                </div>
                <div class="col-12 form-group">
                    {{ Form::label('company_id', __('Clients'),['class'=>'form-control-label']) }}
                    {{ Form::select('clients[]', $clients,null, array('class' => 'form-control select2','multiple'=>'','required'=>'required')) }}
                    @if(count($clients) <= 0 && Auth::user()->type == 'Owner')
                        <div class="text-muted text-xs">
                            {{__('Please create new clients')}} <a href="{{route('clients.index')}}">{{__('here')}}</a>.
                        </div>
                    @endif
                </div>
            </div>
        </div>
        @if(!$customFields->isEmpty())
            <div class="tab-pane fade show" id="tab-2" role="tabpanel">
                <div class="row">
                    @include('custom_fields.formBuilder')
                </div>
            </div>
        @endif
    </div>
    <div class="col-12 text-right">
        <input type="submit" value="{{__('Create')}}" class="btn-create badge-blue">
        <input type="button" value="{{__('Cancel')}}" class="btn-create bg-gray" data-dismiss="modal">
    </div>
    {{ Form::close() }}
</div>
