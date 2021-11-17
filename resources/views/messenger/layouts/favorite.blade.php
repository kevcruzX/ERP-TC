
<div class="favorite-list-item">
    @if(!empty($user->avatar))
        <div data-id="{{ $user->id }}" data-action="0" class="avatar av-m" style="background-image: url('{{ asset('/storage/'.config('chatify.user_avatar.folder').'/'.$user->avatar) }}');">
        </div>
    @else
        <img class="avatar av-m" avatar="{{$user->name}}">
    @endif
    <p>{{ strlen($user->name) > 5 ? substr($user->name,0,6).'..' : $user->name }}</p>
</div>
