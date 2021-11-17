@push('css-page')
    <link rel="stylesheet" href="{{asset('assets/libs/summernote/summernote-bs4.css')}}">
@endpush
@push('script-page')
    <script src="{{asset('assets/libs/summernote/summernote-bs4.js')}}"></script>
@endpush
<div class="card bg-none card-box">
    @if(isset($call))
        {{ Form::model($call, array('route' => array('deals.calls.update', $deal->id, $call->id), 'method' => 'PUT')) }}
    @else
        {{ Form::open(array('route' => ['deals.calls.store',$deal->id])) }}
    @endif
    <div class="row">
        <div class="col-6 form-group">
            {{ Form::label('subject', __('Subject'),['class'=>'form-control-label']) }}
            {{ Form::text('subject', null, array('class' => 'form-control','required'=>'required')) }}
        </div>
        <div class="col-6 form-group">
            {{ Form::label('call_type', __('Call Type'),['class'=>'form-control-label']) }}
            <select name="call_type" id="call_type" class="form-control select2" required>
                <option value="outbound" @if(isset($call->call_type) && $call->call_type == 'outbound') selected @endif>{{__('Outbound')}}</option>
                <option value="inbound" @if(isset($call->call_type) && $call->call_type == 'inbound') selected @endif>{{__('Inbound')}}</option>
            </select>
        </div>
        <div class="col-12 form-group">
            {{ Form::label('duration', __('Duration'),['class'=>'form-control-label']) }} <small class="font-weight-bold">{{ __(' (Format h:m:s i.e 00:35:20 means 35 Minutes and 20 Sec)') }}</small>
            {{ Form::time('duration', null, array('class' => 'form-control','placeholder'=>'00:35:20','step' => '2')) }}
        </div>
        <div class="col-12 form-group">
            {{ Form::label('user_id', __('Assignee'),['class'=>'form-control-label']) }}
            <select name="user_id" id="user_id" class="form-control select2" required>
                @foreach($users as $user)
                    <option value="{{ $user->getDealUser->id }}" @if(isset($call->user_id) && $call->user_id == $user->getDealUser->id) selected @endif>{{ $user->getDealUser->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-12 form-group">
            {{ Form::label('description', __('Description'),['class'=>'form-control-label']) }}
            {{ Form::textarea('description', null, array('class' => 'form-control')) }}
        </div>
        <div class="col-12 form-group">
            {{ Form::label('call_result', __('Call Result'),['class'=>'form-control-label']) }}
            {{ Form::textarea('call_result', null, array('class' => 'summernote-simple')) }}
        </div>
        <div class="col-12 form-group text-right">
            @if(isset($call))
                <input type="submit" value="{{__('Edit')}}" class="btn-create badge-blue">
            @else
                <input type="submit" value="{{__('Create')}}" class="btn-create badge-blue">
            @endif
            <input type="button" value="{{__('Cancel')}}" class="btn-create bg-gray" data-dismiss="modal">
        </div>
    </div>
    {{ Form::close() }}
</div>
