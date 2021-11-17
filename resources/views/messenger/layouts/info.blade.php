{{-- user info and avatar --}}
@if(!empty(\Auth::user()->avatar))
    <div class="avatar av-l" style="background-image: url('{{ asset('/storage/'.config('chatify.user_avatar.folder').\Auth::user()->avatar) }}');">
    </div>
@else
    <img class="avatar av-m" avatar="{{\Auth::user()->name}}">
@endif

<p class="info-name">{{ config('chatify.name') }}</p>
<div class="messenger-infoView-btns">

    <a href="#" class="danger delete-conversation"><i class="fas fa-trash-alt"></i> {{__('Delete Conversation')}}</a>
</div>
{{-- shared photos --}}
<div class="messenger-infoView-shared">
    <p class="messenger-title">{{__('shared photos')}}</p>
    <div class="shared-photos-list"></div>
</div>
