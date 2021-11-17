<div class="card bg-none card-box">
    {{ Form::model($deal, array('route' => array('deals.update', $deal->id), 'method' => 'PUT')) }}
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
                    {{ Form::number('price', null, array('class' => 'form-control')) }}
                </div>
                <div class="col-6 form-group">
                    {{ Form::label('pipeline_id', __('Pipeline'),['class'=>'form-control-label']) }}
                    {{ Form::select('pipeline_id', $pipelines,null, array('class' => 'form-control select2','required'=>'required')) }}
                </div>
                <div class="col-6 form-group">
                    {{ Form::label('stage_id', __('Stage'),['class'=>'form-control-label']) }}
                    {{ Form::select('stage_id', [''=>__('Select Stage')],null, array('class' => 'form-control select2','required'=>'required')) }}
                </div>
                <div class="col-12 form-group">
                    {{ Form::label('sources', __('Sources'),['class'=>'form-control-label']) }}
                    {{ Form::select('sources[]', $sources,null, array('class' => 'form-control select2','multiple'=>'','required'=>'required')) }}
                </div>
                <div class="col-12 form-group">
                    {{ Form::label('products', __('Products'),['class'=>'form-control-label']) }}
                    {{ Form::select('products[]', $products,null, array('class' => 'form-control select2','multiple'=>'','required'=>'required')) }}
                </div>
                <div class="col-12 form-group">
                    {{ Form::label('notes', __('Notes'),['class'=>'form-control-label']) }}
                    {{ Form::textarea('notes',null, array('class' => 'summernote-simple')) }}
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
        <input type="submit" value="{{__('Update')}}" class="btn-create badge-blue">
        <input type="button" value="{{__('Cancel')}}" class="btn-create bg-gray" data-dismiss="modal">
    </div>
    {{ Form::close() }}
</div>


<script>
    var stage_id = '{{$deal->stage_id}}';

    $(document).ready(function () {
        $("#commonModal select[name=pipeline_id]").trigger('change');
    });

    $(document).on("change", "#commonModal select[name=pipeline_id]", function () {
        $.ajax({
            url: '{{route('stages.json')}}',
            data: {pipeline_id: $(this).val(), _token: $('meta[name="csrf-token"]').attr('content')},
            type: 'POST',
            success: function (data) {
                $('#stage_id').empty();
                $("#stage_id").append('<option value="" selected="selected">{{__('Select Stage')}}</option>');
                $.each(data, function (key, data) {
                    var select = '';
                    if (key == '{{ $deal->stage_id }}') {
                        select = 'selected';
                    }
                    $("#stage_id").append('<option value="' + key + '" ' + select + '>' + data + '</option>');
                });
                $("#stage_id").val(stage_id);
                $('#stage_id').select2({
                    placeholder: "{{__('Select Stage')}}"
                });
            }
        })
    });
</script>
