<div class="card bg-none card-box">
    {{Form::model($indicator,array('route' => array('indicator.update', $indicator->id), 'method' => 'PUT')) }}
    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                {{Form::label('branch',__('Branch'),['class'=>'form-control-label'])}}
                {{Form::select('branch',$brances,null,array('class'=>'form-control select2','required'=>'required'))}}
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                {{Form::label('department',__('Department'),['class'=>'form-control-label'])}}
                {{Form::select('department',$departments,null,array('class'=>'form-control select2','required'=>'required','id'=>'department_id'))}}
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                {{Form::label('designation',__('Designation'),['class'=>'form-control-label'])}}
                <select class="select2 form-control select2-multiple" id="designation_id" name="designation" data-toggle="select2" data-placeholder="{{ __('Select Designation ...') }}" required>
                </select>
            </div>
        </div>

    </div>
    <div class="row">
        <div class="col-md-12 mt-3">
            <h6>{{__('Technical Competencies')}}</h6>
            <hr class="mt-0">
        </div>
        @foreach($technicals as $technical )

            <div class="col-6">
                {{$technical->name}}
            </div>
            <div class="col-6">
                <fieldset id='demo1' class="rating">
                    <input class="stars" type="radio" id="technical-5-{{$technical->id}}" name="rating[{{$technical->id}}]" value="5" {{ (isset($ratings[$technical->id]) && $ratings[$technical->id] == 5)? 'checked':''}}>
                    <label class="full" for="technical-5-{{$technical->id}}" title="Awesome - 5 stars"></label>
                    <input class="stars" type="radio" id="technical-4-{{$technical->id}}" name="rating[{{$technical->id}}]" value="4" {{ (isset($ratings[$technical->id]) && $ratings[$technical->id] == 4)? 'checked':''}}>
                    <label class="full" for="technical-4-{{$technical->id}}" title="Pretty good - 4 stars"></label>
                    <input class="stars" type="radio" id="technical-3-{{$technical->id}}" name="rating[{{$technical->id}}]" value="3" {{ (isset($ratings[$technical->id]) && $ratings[$technical->id] == 3)? 'checked':''}}>
                    <label class="full" for="technical-3-{{$technical->id}}" title="Meh - 3 stars"></label>
                    <input class="stars" type="radio" id="technical-2-{{$technical->id}}" name="rating[{{$technical->id}}]" value="2" {{ (isset($ratings[$technical->id]) && $ratings[$technical->id] == 2)? 'checked':''}}>
                    <label class="full" for="technical-2-{{$technical->id}}" title="Kinda bad - 2 stars"></label>
                    <input class="stars" type="radio" id="technical-1-{{$technical->id}}" name="rating[{{$technical->id}}]" value="1" {{ (isset($ratings[$technical->id]) && $ratings[$technical->id] == 1)? 'checked':''}}>
                    <label class="full" for="technical-1-{{$technical->id}}" title="Sucks big time - 1 star"></label>
                </fieldset>
            </div>
        @endforeach
    </div>
    <div class="row">
        <div class="col-md-12 mt-3">
            <h6>{{__('Organizational Competencies')}}</h6>
            <hr class="mt-0">
        </div>
        @foreach($organizationals as $organizational )
            <div class="col-6">
                {{$organizational->name}}
            </div>
            <div class="col-6">
                <fieldset id='demo1' class="rating">
                    <input class="stars" type="radio" id="organizational-5-{{$organizational->id}}" name="rating[{{$organizational->id}}]" value="5" {{ (isset($ratings[$organizational->id]) && $ratings[$organizational->id] == 5)? 'checked':''}}>
                    <label class="full" for="organizational-5-{{$organizational->id}}" title="Awesome - 5 stars"></label>
                    <input class="stars" type="radio" id="organizational-4-{{$organizational->id}}" name="rating[{{$organizational->id}}]" value="4" {{ (isset($ratings[$organizational->id]) && $ratings[$organizational->id] == 4)? 'checked':''}}>
                    <label class="full" for="organizational-4-{{$organizational->id}}" title="Pretty good - 4 stars"></label>
                    <input class="stars" type="radio" id="organizational-3-{{$organizational->id}}" name="rating[{{$organizational->id}}]" value="3" {{ (isset($ratings[$organizational->id]) && $ratings[$organizational->id] == 3)? 'checked':''}}>
                    <label class="full" for="organizational-3-{{$organizational->id}}" title="Meh - 3 stars"></label>
                    <input class="stars" type="radio" id="organizational-2-{{$organizational->id}}" name="rating[{{$organizational->id}}]" value="2" {{ (isset($ratings[$organizational->id]) && $ratings[$organizational->id] == 2)? 'checked':''}}>
                    <label class="full" for="organizational-2-{{$organizational->id}}" title="Kinda bad - 2 stars"></label>
                    <input class="stars" type="radio" id="organizational-1-{{$organizational->id}}" name="rating[{{$organizational->id}}]" value="1" {{ (isset($ratings[$organizational->id]) && $ratings[$organizational->id] == 1)? 'checked':''}}>
                    <label class="full" for="organizational-1-{{$organizational->id}}" title="Sucks big time - 1 star"></label>
                </fieldset>
            </div>
        @endforeach
    </div>
    <div class="row">
        <div class="col-md-12 mt-3">
            <h6>{{__('Behavioural Competencies')}}</h6>
            <hr class="mt-0">
        </div>
        @foreach($behaviourals as $behavioural )
            <div class="col-6">
                {{$behavioural->name}}
            </div>
            <div class="col-6">
                <fieldset id='demo1' class="rating">
                    <input class="stars" type="radio" id="behavioural-5-{{$behavioural->id}}" name="rating[{{$behavioural->id}}]" value="5" {{ (isset($ratings[$behavioural->id]) && $ratings[$behavioural->id] == 5)? 'checked':''}}>
                    <label class="full" for="behavioural-5-{{$behavioural->id}}" title="Awesome - 5 stars"></label>
                    <input class="stars" type="radio" id="behavioural-4-{{$behavioural->id}}" name="rating[{{$behavioural->id}}]" value="4" {{ (isset($ratings[$behavioural->id]) && $ratings[$behavioural->id] == 4)? 'checked':''}}>
                    <label class="full" for="behavioural-4-{{$behavioural->id}}" title="Pretty good - 4 stars"></label>
                    <input class="stars" type="radio" id="behavioural-3-{{$behavioural->id}}" name="rating[{{$behavioural->id}}]" value="3" {{ (isset($ratings[$behavioural->id]) && $ratings[$behavioural->id] == 3)? 'checked':''}}>
                    <label class="full" for="behavioural-3-{{$behavioural->id}}" title="Meh - 3 stars"></label>
                    <input class="stars" type="radio" id="behavioural-2-{{$behavioural->id}}" name="rating[{{$behavioural->id}}]" value="2" {{ (isset($ratings[$behavioural->id]) && $ratings[$behavioural->id] == 2)? 'checked':''}}>
                    <label class="full" for="behavioural-2-{{$behavioural->id}}" title="Kinda bad - 2 stars"></label>
                    <input class="stars" type="radio" id="behavioural-1-{{$behavioural->id}}" name="rating[{{$behavioural->id}}]" value="1" {{ (isset($ratings[$behavioural->id]) && $ratings[$behavioural->id] == 1)? 'checked':''}}>
                    <label class="full" for="behavioural-1-{{$behavioural->id}}" title="Sucks big time - 1 star"></label>
                </fieldset>
            </div>
        @endforeach
    </div>

    <div class="row">
        <div class="col-12">
            <input type="submit" value="{{__('Update')}}" class="btn-create badge-blue">
            <input type="button" value="{{__('Cancel')}}" class="btn-create bg-gray" data-dismiss="modal">
        </div>
    </div>
    {{Form::close()}}
</div>
<script type="text/javascript">
    function getDesignation(did) {
        $.ajax({
            url: '{{route('employee.json')}}',
            type: 'POST',
            data: {
                "department_id": did, "_token": "{{ csrf_token() }}",
            },
            success: function (data) {
                console.log(data);
                $('#designation_id').empty();
                $('#designation_id').append('<option value="">Select any Designation</option>');
                $.each(data, function (key, value) {
                    var select = '';
                    if (key == '{{ $indicator->designation }}') {
                        select = 'selected';
                    }

                    $('#designation_id').append('<option value="' + key + '"  ' + select + '>' + value + '</option>');
                });
            }
        });
    }

    $(document).ready(function () {
        var d_id = $('#department_id').val();
        getDesignation(d_id);
    });

</script>



