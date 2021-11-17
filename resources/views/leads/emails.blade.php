<div class="card bg-none card-box">
    {{ Form::open(array('route' => ['leads.emails.store',$lead->id])) }}
    <div class="row">
        <div class="col-6 form-group">
            {{ Form::label('to', __('Mail To'),['class'=>'form-control-label']) }}
            {{ Form::email('to', null, array('class' => 'form-control','required'=>'required')) }}
        </div>
        <div class="col-6 form-group">
            {{ Form::label('subject', __('Subject'),['class'=>'form-control-label']) }}
            {{ Form::text('subject', null, array('class' => 'form-control','required'=>'required')) }}
        </div>
        <div class="col-12 form-group">
            {{ Form::label('description', __('Description'),['class'=>'form-control-label']) }}
            {{ Form::textarea('description', null, array('class' => 'summernote-simple','id'=>'emails-summernote')) }}
        </div>
        <script>
          $('#emails-summernote').summernote();
        </script>
        <div class="form-group col-12 text-right">
            <input type="submit" value="{{__('Create')}}" class="btn-create badge-blue">
            <input type="button" value="{{__('Cancel')}}" class="btn-create bg-gray" data-dismiss="modal">
        </div>
    </div>
    {{ Form::close() }}
</div>
