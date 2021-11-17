<div class="card bg-none card-box">
    <div class="row py-4">
        <div class="col-md-12 ">
            <div class="info text-sm">
                <strong>{{__('Branch')}} : </strong>
                <span>{{ !empty($indicator->branches)?$indicator->branches->name:''}}</span>
            </div>
        </div>
        <div class="col-md-6 mt-2">
            <div class="info text-sm font-style">
                <strong>{{__('Department')}} : </strong>
                <span>{{ !empty($indicator->departments)?$indicator->departments->name:'' }}</span>
            </div>
        </div>
        <div class="col-md-6 mt-3">
            <div class="info text-sm font-style">
                <strong>{{__('Designation')}} : </strong>
                <span>{{ !empty($indicator->designations)?$indicator->designations->name:'' }}</span>
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
                    <input class="stars" type="radio" id="technical-5-{{$technical->id}}" name="rating[{{$technical->id}}]" value="5" {{ (isset($ratings[$technical->id]) && $ratings[$technical->id] == 5)? 'checked':''}} disabled>
                    <label class="full" for="technical-5-{{$technical->id}}" title="Awesome - 5 stars"></label>
                    <input class="stars" type="radio" id="technical-4-{{$technical->id}}" name="rating[{{$technical->id}}]" value="4" {{ (isset($ratings[$technical->id]) && $ratings[$technical->id] == 4)? 'checked':''}} disabled>
                    <label class="full" for="technical-4-{{$technical->id}}" title="Pretty good - 4 stars"></label>
                    <input class="stars" type="radio" id="technical-3-{{$technical->id}}" name="rating[{{$technical->id}}]" value="3" {{ (isset($ratings[$technical->id]) && $ratings[$technical->id] == 3)? 'checked':''}} disabled>
                    <label class="full" for="technical-3-{{$technical->id}}" title="Meh - 3 stars"></label>
                    <input class="stars" type="radio" id="technical-2-{{$technical->id}}" name="rating[{{$technical->id}}]" value="2" {{ (isset($ratings[$technical->id]) && $ratings[$technical->id] == 2)? 'checked':''}} disabled>
                    <label class="full" for="technical-2-{{$technical->id}}" title="Kinda bad - 2 stars"></label>
                    <input class="stars" type="radio" id="technical-1-{{$technical->id}}" name="rating[{{$technical->id}}]" value="1" {{ (isset($ratings[$technical->id]) && $ratings[$technical->id] == 1)? 'checked':''}} disabled>
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
                    <input class="stars" type="radio" id="technical-5-{{$organizational->id}}" name="rating[{{$organizational->id}}]" value="5" {{ (isset($ratings[$organizational->id]) && $ratings[$organizational->id] == 5)? 'checked':''}} disabled>
                    <label class="full" for="technical-5-{{$organizational->id}}" title="Awesome - 5 stars"></label>
                    <input class="stars" type="radio" id="technical-4-{{$organizational->id}}" name="rating[{{$organizational->id}}]" value="4" {{ (isset($ratings[$organizational->id]) && $ratings[$organizational->id] == 4)? 'checked':''}} disabled>
                    <label class="full" for="technical-4-{{$organizational->id}}" title="Pretty good - 4 stars"></label>
                    <input class="stars" type="radio" id="technical-3-{{$organizational->id}}" name="rating[{{$organizational->id}}]" value="3" {{ (isset($ratings[$organizational->id]) && $ratings[$organizational->id] == 3)? 'checked':''}} disabled>
                    <label class="full" for="technical-3-{{$organizational->id}}" title="Meh - 3 stars"></label>
                    <input class="stars" type="radio" id="technical-2-{{$organizational->id}}" name="rating[{{$organizational->id}}]" value="2" {{ (isset($ratings[$organizational->id]) && $ratings[$organizational->id] == 2)? 'checked':''}} disabled>
                    <label class="full" for="technical-2-{{$organizational->id}}" title="Kinda bad - 2 stars"></label>
                    <input class="stars" type="radio" id="technical-1-{{$organizational->id}}" name="rating[{{$organizational->id}}]" value="1" {{ (isset($ratings[$organizational->id]) && $ratings[$organizational->id] == 1)? 'checked':''}} disabled>
                    <label class="full" for="technical-1-{{$organizational->id}}" title="Sucks big time - 1 star"></label>
                </fieldset>
            </div>
        @endforeach
    </div>

    <div class="row">
        <div class="col-md-12 mt-3">
            <h6>{{__('Behaviourals Competencies')}}</h6>
            <hr class="mt-0">
        </div>
        @foreach($behaviourals as $behavioural)
            <div class="col-6">
                {{$behavioural->name}}
            </div>
            <div class="col-6">
                <fieldset id='demo1' class="rating">
                    <input class="stars" type="radio" id="behavioural-5-{{$behavioural->id}}" name="rating[{{$behavioural->id}}]" value="5" {{ (isset($ratings[$behavioural->id]) && $ratings[$behavioural->id] == 5)? 'checked':''}} disabled>
                    <label class="full" for="behavioural-5-{{$behavioural->id}}" title="Awesome - 5 stars"></label>
                    <input class="stars" type="radio" id="behavioural-4-{{$behavioural->id}}" name="rating[{{$behavioural->id}}]" value="4" {{ (isset($ratings[$behavioural->id]) && $ratings[$behavioural->id] == 4)? 'checked':''}} disabled>
                    <label class="full" for="behavioural-4-{{$behavioural->id}}" title="Pretty good - 4 stars"></label>
                    <input class="stars" type="radio" id="behavioural-3-{{$behavioural->id}}" name="rating[{{$behavioural->id}}]" value="3" {{ (isset($ratings[$behavioural->id]) && $ratings[$behavioural->id] == 3)? 'checked':''}} disabled>
                    <label class="full" for="behavioural-3-{{$behavioural->id}}" title="Meh - 3 stars"></label>
                    <input class="stars" type="radio" id="behavioural-2-{{$behavioural->id}}" name="rating[{{$behavioural->id}}]" value="2" {{ (isset($ratings[$behavioural->id]) && $ratings[$behavioural->id] == 2)? 'checked':''}} disabled>
                    <label class="full" for="behavioural-2-{{$behavioural->id}}" title="Kinda bad - 2 stars"></label>
                    <input class="stars" type="radio" id="behavioural-1-{{$behavioural->id}}" name="rating[{{$behavioural->id}}]" value="1" {{ (isset($ratings[$behavioural->id]) && $ratings[$behavioural->id] == 1)? 'checked':''}} disabled>
                    <label class="full" for="behavioural-1-{{$behavioural->id}}" title="Sucks big time - 1 star"></label>
                </fieldset>
            </div>
        @endforeach
    </div>

</div>

