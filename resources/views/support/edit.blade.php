<div class="card bg-none card-box">
    {{ Form::model($support,array('route' => array('support.update',$support->id),'method'=>'PUT','enctype'=>"multipart/form-data")) }}
    <div class="row">
        <div class="form-group col-md-12">
            {{ Form::label('subject', __('Subject'),['class'=>'form-control-label']) }}
            {{ Form::text('subject', null, array('class' => 'form-control','required'=>'required')) }}
        </div>
        @if(\Auth::user()->type=='company')
            <div class="form-group col-md-6">
                {{Form::label('user',__('Support for User'),['class'=>'form-control-label'])}}
                {{Form::select('user',$users,null,array('class'=>'form-control select2'))}}
            </div>
        @endif
        <div class="form-group col-md-6">
            {{Form::label('priority',__('Priority'),['class'=>'form-control-label'])}}
            {{Form::select('priority',$priority,null,array('class'=>'form-control select2'))}}
        </div>

        <div class="form-group col-md-6">
            {{ Form::label('end_date', __('End Date'),['class'=>'form-control-label']) }}
            {{ Form::date('end_date', null, array('class' => 'form-control','required'=>'required')) }}
        </div>
        <div class="form-group col-md-6">
            {{Form::label('attachment',__('Attachment'),['class'=>'form-control-label'])}}
            <div class="choose-file form-group">
                <label for="document" class="form-control-label">
                    <div>{{__('Choose file here')}}</div>
                    <input type="file" class="form-control" name="attachment" id="attachment" data-filename="attachment_create">
                </label>
                <p class="attachment_create"></p>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="form-group col-md-12">
            {{ Form::label('description', __('Description'),['class'=>'form-control-label']) }}
            {!! Form::textarea('description', null, ['class'=>'form-control','rows'=>'3']) !!}
        </div>
    </div>
    <div class="col-md-12">
        <input type="submit" value="{{__('Update')}}" class="btn-create badge-blue">
        <input type="button" value="{{__('Cancel')}}" class="btn-create bg-gray" data-dismiss="modal">
    </div>
    {{ Form::close() }}

</div>
