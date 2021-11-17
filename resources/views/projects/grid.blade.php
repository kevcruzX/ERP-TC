@if(isset($projects) && !empty($projects) && count($projects) > 0)
    @foreach ($projects as $key => $project)
    
        <div class="col-xl-3 col-lg-3 col-sm-6">
            <div class="card hover-shadow-lg pb-0">
                <div class="card-header border-0 pb-0">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-0 {{ (strtotime($project->end_date) < time()) ? 'text-danger' : '' }}" data-toggle="tooltip" data-original-title="{{__('End Date')}}">{{ \App\Utility::getDateFormated($project->end_date) }}</h6>
                        </div>
                        {{-- <div class="text-right">
                            <span class="badge badge-xs badge-{{ (\Auth::user()->checkProject($project->id) == 'Owner') ? 'success' : 'warning'  }}" data-toggle="tooltip" data-original-title="{{__('You are ') .__(ucfirst($project->permission()))}}">{{ __(\Auth::user()->checkProject($project->id)) }}</span>
                        </div> --}}
                        <div class="text-right">
                          <span class="clearfix"></span>
                          <span class="badge badge-pill badge-{{\App\Project::$status_color[$project->status]}}">{{ __(\App\Project::$project_status[$project->status]) }}</span>
                        </div>
                    </div>
                </div>
                <div class="card-body text-center p-0">
                    <a href="{{ route('projects.show',$project) }}" class="avatar rounded-circle avatar-lg hover-translate-y-n3">
                        <img class="hweb" {{ $project->img_image }} >
                    </a>
                    <h5 class="h6 my-4">
                            <a href="{{ route('projects.show',$project) }}">{{ $project->project_name }}</a>
                        <br>
                    </h5>
                    <div class="avatar-group hover-avatar-ungroup mb-3" id="project_{{ $project->id }}">
                        @if(isset($project->users) && !empty($project->users) && count($project->users) > 0)
                            @foreach($project->users as $key => $user)
                                @if($key < 3)
                                    <a href="#" class="avatar rounded-circle">
                                        <img   @if($user->avatar) src="{{asset('/storage/uploads/avatar/'.$user->avatar)}}" @else src="{{asset('assets/img/avatar/avatar-1.png')}}" @endif title="{{ $user->name }}" style="height:36px;width:36px;">
                                    </a>
                                @else
                                    @break
                                @endif
                            @endforeach
                            @if(count($project->users) > 3)
                                <a href="#" class="avatar rounded-circle">
                                    <img  @if($user->avatar) src="{{asset('/storage/uploads/avatar/'.$user->avatar)}}" @else src="{{asset('assets/img/avatar/avatar-1.png')}}" @endif style="height:36px;width:36px;">
                                </a>
                            @endif
                        @endif
                    </div>

                </div>
                <div class="progress w-100 height-2">
                    <div class="progress-bar bg-{{ $project->project_progress()['color'] }}" role="progressbar" aria-valuenow="{{ $project->project_progress()['percentage'] }}" aria-valuemin="0" aria-valuemax="100" style="width: {{ $project->project_progress()['percentage'] }};"></div>
                </div>
                <div class="card-footer">
                    <div class="actions d-flex justify-content-between px-4">
                        @can('edit project')
                        <a href="#" data-url="{{ route('invite.project.member.view', $project->id) }}" data-ajax-popup="true" data-size="lg" data-title="{{__('Invite Member')}}" class="action-item" data-toggle="tooltip" data-original-title="{{__('Invite Member')}}">
                            <i class="fas fa-paper-plane"></i>
                        </a>
                        @endcan
                        @can('edit project')
                            <a href="#" class="action-item"
                                    data-url="{{ route('projects.edit', $project->id) }}" data-ajax-popup="true"
                                    data-title="{{ __('Edit Project') }}" data-toggle="tooltip"
                                    data-original-title="{{ __('Edit') }}" data-size='lg'>
                                    <span class="btn-inner--icon"><i class="fas fa-pencil-alt"></i></span>
                                </a>
                        @endcan
                        @can('delete project')
                            <a href="#" class="action-item text-danger" data-toggle="tooltip" data-original-title="{{__('Delete')}}" data-confirm="{{__('Are You Sure?')}}|{{__('This action can not be undone. Do you want to continue?')}}" data-confirm-yes="document.getElementById('delete-project-{{$project->id}}').submit();">
                                <i class="fas fa-trash-alt"></i>
                            </a>
                        @endcan
                    </div>
                    {!! Form::open(['method' => 'DELETE', 'route' => ['projects.destroy',$project->id],'id'=>'delete-project-'.$project->id]) !!}
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    @endforeach
@else
    <div class="col-xl-12 col-lg-12 col-sm-12">
        <div class="card">
            <div class="card-body">
                <h6 class="text-center mb-0">{{__('No Projects Found.')}}</h6>
            </div>
        </div>
    </div>
@endif
