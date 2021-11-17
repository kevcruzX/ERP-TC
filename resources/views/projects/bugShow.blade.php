<div class="card bg-none card-box">
    <div class="row">
        <div class="col-12 col-md-6 col-lg-6">
            <div class="form-group">
                <b class="text-sm">{{ __('Title')}} :</b>
                <p class="m-0 p-0 text-sm">{{$bug->title}}</p>
            </div>
        </div>
        <div class="col-12 col-md-6 col-lg-6">
            <div class="form-group">
                <b class="text-sm">{{ __('Priority')}} :</b>
                <p class="m-0 p-0 text-sm">{{ucfirst($bug->priority)}}</p>
            </div>
        </div>
        <div class="col-12">
            <div class="form-group">
                <b class="text-sm">{{ __('Description')}} :</b>
                <p class="m-0 p-0 text-sm">{{$bug->description}}</p>
            </div>
        </div>
        <div class="col-12 col-md-4 col-lg-4">
            <div class="form-group">
                <b class="text-sm">{{ __('Created Date')}} :</b>
                <p class="m-0 p-0 text-sm">{{$bug->created_at}}</p>
            </div>
        </div>
        <div class="col-12 col-md-4 col-lg-4">
            <div class="form-group">
                <b class="text-sm">{{ __('Assign to')}} :</b>
                <p class="m-0 p-0 text-sm">{{(!empty($bug->assignTo)?$bug->assignTo->name:'')}}</p>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <ul class="nav nav-tabs" id="myTab" role="tablist">
                <li>
                    <a class="active show" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false">{{__('Comments')}}</a>
                </li>
                <li>
                    <a id="contact-tab" data-toggle="tab" href="#contact" role="tab" aria-controls="contact" aria-selected="false">{{__('Files')}}</a>
                </li>
            </ul>

            <div class="tab-content pt-4" id="myTabContent">
                <div class="tab-pane fade active show" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                    <div class="form-group m-0">
                        <form method="post" id="form-comment" data-action="{{route('bug.comment.store',[$bug->project_id,$bug->id])}}">
                            <textarea class="form-control" name="comment" placeholder="{{ __('Write message')}}" id="example-textarea" rows="3" required></textarea>
                            <div class="text-right mt-1">
                                <div class="btn-group mb-2 ml-2 d-none d-sm-inline-block">
                                    <button type="button" class="btn badge-blue btn-xs rounded-pill my-auto text-white">{{ __('Submit')}}</button>
                                </div>
                            </div>
                        </form>
                        <div class="comment-holder" id="comments">
                            @foreach($bug->comments as $comment)
                                <div class="media">
                                    <div class="media-body">
                                        <div class="d-flex justify-content-between align-items-end">
                                            <div>
                                                <h5 class="mt-0">{{(!empty($comment->user)?$comment->user->name:'')}}</h5>
                                                <p class="mb-0 text-xs">{{$comment->comment}}</p>
                                            </div>
                                            <a href="#" class="btn btn-outline btn-sm text-danger delete-comment" data-url="{{route('bug.comment.destroy',$comment->id)}}">
                                                <i class="fa fa-trash"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="contact" role="tabpanel" aria-labelledby="contact-tab">
                    <div class="form-group m-0">
                        <form method="post" id="form-file" enctype="multipart/form-data" data-url="{{ route('bug.comment.file.store',$bug->id) }}">
                            @csrf
                            <div class="choose-file form-group">
                                <label for="file" class="form-control-label">
                                    <div>{{__('Choose file here')}}</div>
                                    <input type="file" class="form-control" name="file" id="file" data-filename="file_update">
                                </label>
                                <p class="file_update"></p>
                            </div>
                            <br>
                            <span class="invalid-feedback" id="file-error" role="alert"></span>
                            <div class="text-right mt-1">
                                <div class="btn-group mb-2 ml-2 d-none d-sm-inline-block">
                                    <button type="submit" class="btn badge-blue btn-xs rounded-pill my-auto text-white">{{ __('Upload')}}</button>
                                </div>
                            </div>
                        </form>
                        <div class="row my-3" id="comments-file">
                            @foreach($bug->bugFiles as $file)
                                <div class="col-8 mb-2 file-{{$file->id}}">
                                    <h5 class="mt-0 mb-1 font-weight-bold text-sm"> {{$file->name}}</h5>
                                    <p class="m-0 text-xs">{{$file->file_size}}</p>
                                </div>
                                <div class="col-4 mb-2 file-{{$file->id}}">
                                    <div class="comment-trash" style="float: right">
                                        <a download href="{{asset(Storage::url('bugs/'.$file->file))}}" class="btn btn-outline btn-sm text-primary m-0 px-2">
                                            <i class="fa fa-download"></i>
                                        </a>
                                        <a href="#" class="btn btn-outline btn-sm red text-danger delete-comment-file m-0 px-2" data-id="{{$file->id}}" data-url="{{route('bug.comment.file.destroy',[$file->id])}}">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </div>
                                </div>

                                {{--<li class="media">
                                    <div class="media-body">
                                        <h6 class="mt-0 mb-1 font-weight-bold"> {{$file->name}}</h6>
                                        {{$file->file_size}}
                                        <div class="comment-trash" style="float: right">
                                            <a download href="{{asset(Storage::url('bugs/'.$file->file))}}" class="btn btn-outline btn-sm ">
                                                <i class="fa fa-download"></i>
                                            </a>
                                            <a href="#" class="btn btn-outline btn-sm red text-muted delete-comment-file" data-url="{{route('bug.comment.file.destroy',[$file->id])}}">
                                                <i class="fa fa-trash"></i>
                                            </a>
                                        </div>
                                    </div>
                                </li>--}}
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
