<div class="card bg-none card-box">
    @if(isset($task))
        {{ Form::model($task, array('route' => array('deals.tasks.update', $deal->id, $task->id), 'method' => 'PUT')) }}
    @else
        {{ Form::open(array('route' => ['deals.tasks.store',$deal->id])) }}
    @endif
    <div class="row">
        <div class="col-12 form-group">
            {{ Form::label('name', __('Name'),['class'=>'form-control-label']) }}
            {{ Form::text('name', null, array('class' => 'form-control','required'=>'required')) }}
        </div>
        <div class="col-6 form-group">
            {{ Form::label('date', __('Date'),['class'=>'form-control-label']) }}
            {{ Form::text('date', null, array('class' => 'form-control datepicker','required'=>'required')) }}
        </div>
        <div class="col-6 form-group">
            {{ Form::label('time', __('Time'),['class'=>'form-control-label']) }}
            {{ Form::text('time', null, array('class' => 'form-control','required'=>'required')) }}
        </div>
        <div class="col-6 form-group">
            {{ Form::label('priority', __('Priority'),['class'=>'form-control-label']) }}
            <select class="form-control select2" name="priority" required>
                @foreach($priorities as $key => $priority)
                    <option value="{{$key}}" @if(isset($task) && $task->priority == $key) selected @endif>{{__($priority)}}</option>
                @endforeach
            </select>
        </div>
        <div class="col-6 form-group">
            {{ Form::label('status', __('Status'),['class'=>'form-control-label']) }}
            <select class="form-control select2" name="status" required>
                @foreach($status as $key => $st)
                    <option value="{{$key}}" @if(isset($task) && $task->status == $key) selected @endif>{{__($st)}}</option>
                @endforeach
            </select>
        </div>
        <div class="col-12 form-group text-right">
            @if(isset($task))
                <input type="submit" value="{{__('Edit')}}" class="btn-create badge-blue">
            @else
                <input type="submit" value="{{__('Save')}}" class="btn-create badge-blue">
            @endif
            <input type="button" value="{{__('Cancel')}}" class="btn-create bg-gray" data-dismiss="modal">
        </div>
    </div>
    {{ Form::close() }}
</div>
<script>
    $('#date').daterangepicker({
        locale: {format: 'YYYY-MM-DD'},
        singleDatePicker: true,
    });
    $("#time").timepicker({
        icons: {
            up: 'fas fa-chevron-up',
            down: 'fas fa-chevron-down'
        }
    });
</script>
