@foreach($project->users as $user)
    <tr>
        <th scope="row">
            <div class="media align-items-center">
                <div>
                    <img data-original-title="{{(!empty($user)?$user->name:'')}}" @if($user->avatar) src="{{asset('/storage/uploads/avatar/'.$user->avatar)}}" @else src="{{asset('assets/img/avatar/avatar-1.png')}}" @endif  class="avatar rounded-circle avatar-sm">
                </div>
                <div class="media-body ml-3">
                    <a class="name mb-0 h6 text-sm">{{ $user->name }}</a>
                    <br>
                    <a class="text-sm text-muted">{{ $user->email }}</a>
                </div>
            </div>
        </th>
    </tr>
@endforeach
