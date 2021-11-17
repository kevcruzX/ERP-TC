@extends('layouts.admin')

@section('page-title')
    {{$lead->name}}
@endsection

@push('css-page')
    <link rel="stylesheet" href="{{asset('assets/libs/summernote/summernote-bs4.css')}}">
    <link rel="stylesheet" href="{{asset('assets/libs/dropzonejs/dropzone.css')}}">
    <style>
        .nav-tabs .nav-link-tabs.active {
            background: none;
        }
    </style>
@endpush

@push('script-page')
    <script src="{{asset('assets/libs/summernote/summernote-bs4.js')}}"></script>
    <script src="{{asset('assets/libs/dropzonejs/min/dropzone.min.js')}}"></script>
    <script>
        @if(Auth::user()->type != 'client')
            Dropzone.autoDiscover = false;
        myDropzone = new Dropzone("#dropzonewidget", {
            maxFiles: 20,
            maxFilesize: 20,
            parallelUploads: 1,
            filename: false,
            acceptedFiles: ".jpeg,.jpg,.png,.pdf,.doc,.txt",
            url: "{{route('leads.file.upload',$lead->id)}}",
            success: function (file, response) {
                if (response.is_success) {
                    dropzoneBtn(file, response);
                } else {
                    myDropzone.removeFile(file);
                    show_toastr('Error', response.error, 'error');
                }
            },
            error: function (file, response) {
                myDropzone.removeFile(file);
                if (response.error) {
                    show_toastr('Error', response.error, 'error');
                } else {
                    show_toastr('Error', response, 'error');
                }
            }
        });
        myDropzone.on("sending", function (file, xhr, formData) {
            formData.append("_token", $('meta[name="csrf-token"]').attr('content'));
            formData.append("lead_id", {{$lead->id}});
        });

        myDropzone2 = new Dropzone("#dropzonewidget2", {
            maxFiles: 20,
            maxFilesize: 20,
            parallelUploads: 1,
            acceptedFiles: ".jpeg,.jpg,.png,.pdf,.doc,.txt",
            url: "{{route('leads.file.upload',$lead->id)}}",
            success: function (file, response) {
                if (response.is_success) {
                    dropzoneBtn(file, response);
                } else {
                    myDropzone2.removeFile(file);
                    show_toastr('Error', response.error, 'error');
                }
            },
            error: function (file, response) {
                myDropzone2.removeFile(file);
                if (response.error) {
                    show_toastr('Error', response.error, 'error');
                } else {
                    show_toastr('Error', response, 'error');
                }
            }
        });
        myDropzone2.on("sending", function (file, xhr, formData) {
            formData.append("_token", $('meta[name="csrf-token"]').attr('content'));
            formData.append("lead_id", {{$lead->id}});
        });

        function dropzoneBtn(file, response) {
            var download = document.createElement('a');
            download.setAttribute('href', response.download);
            download.setAttribute('class', "badge badge-pill badge-blue mx-1");
            download.setAttribute('data-toggle', "tooltip");
            download.setAttribute('data-original-title', "{{__('Download')}}");
            download.innerHTML = "<i class='fas fa-download'></i>";

            var del = document.createElement('a');
            del.setAttribute('href', response.delete);
            del.setAttribute('class', "badge badge-pill badge-danger mx-1");
            del.setAttribute('data-toggle', "tooltip");
            del.setAttribute('data-original-title', "{{__('Delete')}}");
            del.innerHTML = "<i class='fas fa-trash'></i>";

            del.addEventListener("click", function (e) {
                e.preventDefault();
                e.stopPropagation();
                if (confirm("Are you sure ?")) {
                    var btn = $(this);
                    $.ajax({
                        url: btn.attr('href'),
                        data: {_token: $('meta[name="csrf-token"]').attr('content')},
                        type: 'DELETE',
                        success: function (response) {
                            if (response.is_success) {
                                btn.closest('.dz-image-preview').remove();
                            } else {
                                show_toastr('Error', response.error, 'error');
                            }
                        },
                        error: function (response) {
                            response = response.responseJSON;
                            if (response.is_success) {
                                show_toastr('Error', response.error, 'error');
                            } else {
                                show_toastr('Error', response, 'error');
                            }
                        }
                    })
                }
            });

            var html = document.createElement('div');
            html.appendChild(download);
            @if(Auth::user()->type != 'client')
            @can('edit lead')
            html.appendChild(del);
            @endcan
            @endif

            file.previewTemplate.appendChild(html);
        }

        @foreach($lead->files as $file)
        @if (file_exists(storage_path('lead_files/'.$file->file_path)))
        // Create the mock file:
        var mockFile = {name: "{{$file->file_name}}", size: {{\File::size(storage_path('lead_files/'.$file->file_path))}} };
        // Call the default addedfile event handler
        myDropzone.emit("addedfile", mockFile);
        // And optionally show the thumbnail of the file:
        myDropzone.emit("thumbnail", mockFile, "{{asset(Storage::url('lead_files/'.$file->file_path))}}");
        myDropzone.emit("complete", mockFile);

        dropzoneBtn(mockFile, {download: "{{route('leads.file.download',[$lead->id,$file->id])}}", delete: "{{route('leads.file.delete',[$lead->id,$file->id])}}"});

        // Create the mock file:
        var mockFile2 = {name: "{{$file->file_name}}", size: {{\File::size(storage_path('lead_files/'.$file->file_path))}} };
        // Call the default addedfile event handler
        myDropzone2.emit("addedfile", mockFile2);
        // And optionally show the thumbnail of the file:
        myDropzone2.emit("thumbnail", mockFile2, "{{asset(Storage::url('lead_files/'.$file->file_path))}}");
        myDropzone2.emit("complete", mockFile2);

        dropzoneBtn(mockFile2, {download: "{{route('leads.file.download',[$lead->id,$file->id])}}", delete: "{{route('leads.file.delete',[$lead->id,$file->id])}}"});
        @endif
        @endforeach
        @endif
        @can('edit lead')
        $('.summernote-simple').on('summernote.blur', function () {
            $.ajax({
                url: "{{route('leads.note.store',$lead->id)}}",
                data: {_token: $('meta[name="csrf-token"]').attr('content'), notes: $(this).val()},
                type: 'POST',
                success: function (response) {
                    if (response.is_success) {
                        // show_toastr('Success', response.success,'success');
                    } else {
                        show_toastr('Error', response.error, 'error');
                    }
                },
                error: function (response) {
                    response = response.responseJSON;
                    if (response.is_success) {
                        show_toastr('Error', response.error, 'error');
                    } else {
                        show_toastr('Error', response, 'error');
                    }
                }
            })
        });
        @else
        $('.summernote-simple').summernote('disable');
        @endcan

        $(document).ready(function () {
            var tab = 'general';
                @if ($tab = Session::get('status'))
            var tab = '{{ $tab }}';
            @endif
            $("#myTab2 .nav-link-text[href='#" + tab + "']").trigger("click");
        });
    </script>
@endpush
@section('action-button')
    <div class="all-button-box row d-flex justify-content-end">
        @can('edit lead')
            <div class="col-xl-2 col-lg-2 col-md-4 col-sm-6 col-6">
                <a href="#" data-url="{{ URL::to('leads/'.$lead->id.'/labels') }}" data-ajax-popup="true" data-title="{{__('Labels')}}" class="btn btn-xs btn-white btn-icon-only width-auto"><i class="fas fa-tags"></i> {{__('Label')}}</a>
            </div>
            <div class="col-xl-2 col-lg-2 col-md-4 col-sm-6 col-6">
                <a href="#" data-url="{{ URL::to('leads/'.$lead->id.'/edit') }}" data-size="lg" data-ajax-popup="true" data-title="{{__('Edit Lead')}}" class="btn btn-xs btn-white btn-icon-only width-auto"><i class="fas fa-pencil-alt"></i> {{__('Edit')}}</a>
            </div>
        @endcan
        @can('convert lead to deal')
            @if(!empty($deal))
                <div class="col-xl-3 col-lg-3 col-md-4 col-sm-6 col-6">
                    <a href="@can('View Deal') @if($deal->is_active) {{route('deals.show',$deal->id)}} @else # @endif @else # @endcan" class="btn btn-xs btn-white btn-icon-only width-auto"><i class="fas fa-exchange-alt"></i> {{__('Already Converted To Deal')}}</a>
                </div>
            @else
                <div class="col-xl-2 col-lg-2 col-md-4 col-sm-6 col-6">
                    <a href="#" data-url="{{ URL::to('leads/'.$lead->id.'/show_convert') }}" data-size="lg" data-ajax-popup="true" data-title="{{__('Convert ['.$lead->subject.'] To Deal')}}" class="btn btn-xs btn-white btn-icon-only width-auto"><i class="fas fa-exchange-alt"></i> {{__('Convert To Deal')}}</a>
                </div>
            @endif
        @endcan
    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-12 mb-3">
            <ul class="nav nav-tabs" id="myTab2" role="tablist">
                <li>
                    <a class="nav-link-text" data-toggle="tab" href="#general" role="tab" aria-controls="home" aria-selected="true">{{__('General')}}</a>
                </li>
                @if(Auth::user()->type != 'client')
                    <li>
                        <a class="nav-link-text" data-toggle="tab" href="#products" role="tab" aria-controls="contact" aria-selected="false">{{__('Products')}}</a>
                    </li>
                @endif
                @if(Auth::user()->type != 'client')
                    <li>
                        <a class="nav-link-text" data-toggle="tab" href="#sources" role="tab" aria-controls="contact" aria-selected="false">{{__('Sources')}}</a>
                    </li>
                @endif
                @if(Auth::user()->type != 'client')
                    <li>
                        <a class="nav-link-text" data-toggle="tab" href="#files" role="tab" aria-controls="contact" aria-selected="false">{{__('Files')}}</a>
                    </li>
                @endif
                <li>
                    <a class="nav-link-text" data-toggle="tab" href="#discussion" role="tab" aria-controls="contact" aria-selected="false">{{__('Discussion')}}</a>
                </li>
                @can('edit lead')
                    <li>
                        <a class="nav-link-text" data-toggle="tab" href="#notes" role="tab" aria-controls="contact" aria-selected="false">{{__('Notes')}}</a>
                    </li>
                @endcan
                @if(Auth::user()->type != 'client')
                    <li>
                        <a class="nav-link-text" data-toggle="tab" href="#calls" role="tab" aria-controls="contact" aria-selected="false">{{__('Calls')}}</a>
                    </li>
                @endif
                @if(Auth::user()->type != 'client')
                    <li>
                        <a class="nav-link-text" data-toggle="tab" href="#emails" role="tab" aria-controls="contact" aria-selected="false">{{__('Emails')}}</a>
                    </li>
                @endif
            </ul>
        </div>

        <div class="col-12">
            <div class="tab-content tab-bordered">
                <div class="tab-pane fade show active" id="general" role="tabpanel">
                    <div class="card py-2 text-sm">
                      <div class="row">


                          <div class="col-8">
                            <ul class="nav nav-pills p-1">
                            <li class="nav-item">
                                <a class="nav-link text-success" href="#">{{__('Pipeline')}} <span class="badge badge-pill badge-success">{{$lead->pipeline->name}}</span></a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link text-warning" href="#">{{__('Stage')}} <span class="badge badge-pill badge-warning">{{$lead->stage->name}}</span></a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link " href="#">{{__('Created')}} <span class="badge badge-pill badge-secondary">{{\Auth::user()->dateFormat($lead->created_at)}}</span></a>
                            </li>
                            <li class="nav-item w-10">
                                <div class="progress-wrapper pt-1">
                                    <span class="progress-tooltip" style="left: {{$precentage}}%;">{{$precentage}}%</span>
                                    <div class="progress" style="height: 3px;">
                                        <div class="progress-bar" role="progressbar" style="width: {{$precentage}}%;" aria-valuenow="{{$precentage}}" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </div>
                            </li>
                          </ul>
                          </div>
                          @php($labels = $lead->labels())
                          @if($labels)
                          <div class="col-4">
                            <ul class="nav nav-pills p-1 float-right m-2">
                              <li class="nav-item">
                                @foreach($labels as $label)
                                    <span class="badge badge-pill badge-{{$label->color}}">{{$label->name}}</span>
                                @endforeach
                              </li>
                            </ul>
                          </div>
                          @endif
                          </div>
                    </div>

                    <?php
                    $products = $lead->products();
                    $sources = $lead->sources();
                    $calls = $lead->calls;
                    $emails = $lead->emails;
                    ?>
                    <div class="row">
                        <div class="col-4">
                            <div class="card card-box">
                                <div class="left-card">
                                    <div class="icon-box"><i class="fas fa-dolly"></i></div>
                                    <h4 class="pt-3">{{__('Product')}}</h4>
                                </div>
                                <div class="number-icon">{{count($products)}}</div>
                                <img src="{{ asset('assets/img/dot-icon.png') }}" class="dotted-icon">
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="card card-box">
                                <div class="left-card">
                                    <div class="icon-box yellow-bg"><i class="fas fa-eye"></i></div>
                                    <h4 class="pt-3">{{__('Source')}}</h4>
                                </div>
                                <div class="number-icon">{{count($sources)}}</div>
                                <img src="{{ asset('assets/img/dot-icon.png') }}" class="dotted-icon">
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="card card-box">
                                <div class="left-card">
                                    <div class="icon-box red-bg"><i class="fas fa-file-alt"></i></div>
                                    <h4 class="pt-3">{{__('Files')}}</h4>
                                </div>
                                <div class="number-icon">{{count($lead->files)}}</div>
                                <img src="{{ asset('assets/img/dot-icon.png') }}" class="dotted-icon">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        @if(Auth::user()->type != 'client')
                            <div class="col-xl-4 col-lg-4 col-sm-6 col-md-6">
                                <div class="justify-content-between align-items-center d-flex">
                                    <h4 class="h4 font-weight-400 float-left">{{__('Users')}}</h4>
                                    @can('edit lead')
                                        <a href="#" class="btn btn-sm btn-white float-right add-small" data-url="{{ route('leads.users.edit',$lead->id) }}" data-ajax-popup="true" data-title="{{__('Add User')}}">
                                            <i class="fas fa-plus"></i> {{__('Add')}}
                                        </a>
                                    @endcan
                                </div>
                                <div class="card bg-none height-450 top-5-scroll">
                                    <div class="table-responsive">
                                        <table class="table align-items-center mb-0">
                                            <tbody class="list">
                                            @foreach($lead->users as $user)
                                                <tr>
                                                    <td>
                                                        <img @if($user->avatar) src="{{asset('/storage/uploads/avatar/'.$user->avatar)}}" @else src="{{asset('assets/img/avatar/avatar-1.png')}}" @endif width="30" class="avatar-sm rounded-circle">
                                                    </td>
                                                    <td>
                                                        <span class="number-id">{{$user->name}}</span>
                                                    </td>
                                                    @can('edit lead')
                                                        <td>
                                                            @if($lead->created_by == \Auth::user()->id)
                                                                <a href="#" class="delete-icon float-right" data-toggle="tooltip" data-original-title="{{__('Delete')}}" data-confirm="{{__('Are You Sure?').'|'.__('This action can not be undone. Do you want to continue?')}}" data-confirm-yes="document.getElementById('delete-form-{{$user->id}}').submit();">
                                                                    <i class="fas fa-trash"></i>
                                                                </a>
                                                                {!! Form::open(['method' => 'DELETE', 'route' => ['leads.users.destroy',$lead->id,$user->id],'id'=>'delete-form-'.$user->id]) !!}
                                                                {!! Form::close() !!}
                                                            @endif
                                                        </td>
                                                    @endcan
                                                </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        @endif
                        @if(Auth::user()->type != 'client')
                            <div class="col-xl-4 col-lg-4 col-sm-6 col-md-6">
                                <div class="justify-content-between align-items-center d-flex">
                                    <h4 class="h4 font-weight-400 float-left">{{__('Products')}}</h4>
                                    @can('edit lead')
                                        <a href="#" class="btn btn-sm btn-white float-right add-small" data-url="{{ route('leads.products.edit',$lead->id) }}" data-ajax-popup="true" data-title="{{__('Add Products')}}">
                                            <i class="fas fa-plus"></i> {{__('Add')}}
                                        </a>
                                    @endcan
                                </div>
                                <div class="card bg-none height-450 top-5-scroll">
                                    <div class="table-responsive">
                                        <table class="table align-items-center mb-0">
                                            <tbody class="list">
                                            @php($products = $lead->products())
                                            @if($products)
                                                @foreach($products as $product)
                                                    <tr>
                                                        <td>
                                                            <img width="30" @if($product->avatar) src="{{asset('/storage/product/'.$product->avatar)}}" @else src="{{asset('assets/img/news/img01.jpg')}}" @endif>
                                                        </td>
                                                        <td>
                                                            <span class="number-id">{{$product->name}} </span> (<span class="text-muted">{{\Auth::user()->priceFormat($product->price)}}</span>)
                                                        </td>
                                                        @can('edit lead')
                                                            <td>
                                                                <a href="#" class="delete-icon float-right" data-toggle="tooltip" data-original-title="{{__('Delete')}}" data-confirm="{{__('Are You Sure?').'|'.__('This action can not be undone. Do you want to continue?')}}" data-confirm-yes="document.getElementById('product-delete-form-{{$product->id}}').submit();">
                                                                    <i class="fas fa-trash"></i>
                                                                </a>
                                                                {!! Form::open(['method' => 'DELETE', 'route' => ['leads.products.destroy',$lead->id,$product->id],'id'=>'product-delete-form-'.$product->id]) !!}
                                                                {!! Form::close() !!}
                                                            </td>
                                                        @endcan
                                                    </tr>
                                                @endforeach
                                            @else
                                                <tr>
                                                    <td>{{__('No Product Found.!')}}</td>
                                                </tr>
                                            @endif
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        @endif
                        @if(Auth::user()->type != 'client')
                            <div class="col-lg-4">
                                <div class="justify-content-between align-items-center d-flex">
                                    <h4 class="h4 font-weight-400 float-left">{{__('Files')}}</h4>
                                </div>
                                <div class="card height-450">
                                    <div class="card-body bg-none top-5-scroll">
                                        <div class="col-md-12 dropzone browse-file" id="dropzonewidget"></div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>

                    <div class="row">
                        @can('edit lead')
                            <div class="col-6">
                                <div class="justify-content-between align-items-center d-flex">
                                    <h4 class="h4 font-weight-400 float-left">{{__('Notes')}}</h4>
                                </div>
                                <div class="card">
                                    <div class="card-body pb-0">
                                        <textarea class="summernote-simple">{!! $lead->notes !!}</textarea>
                                    </div>
                                </div>
                            </div>
                        @endcan
                        @if(Auth::user()->type != 'client')
                            <div class="col-6">
                              <div class="justify-content-between align-items-center d-flex">
                                  <h4 class="h4 font-weight-400 float-left">{{__('Activity')}}</h4>
                              </div>
                              <div class="card">
                                  <div class="card-body">
                                      <div class="scrollbar-inner">
                                          <div class="mh-500 min-h-500">
                                              <div class="timeline timeline-one-side" data-timeline-content="axis" data-timeline-axis-style="dashed">
                                                @if(!$lead->activities->isEmpty())
                                                  @foreach($lead->activities as $activity)
                                                      <div class="timeline-block">
                                                          <span class="timeline-step timeline-step-sm bg-dark border-dark text-white">
                                                              <i class="fas fas {{ $activity->logIcon() }}"></i>
                                                          </span>
                                                          <div class="timeline-content">
                                                              <span class="text-dark text-sm">{{ __($activity->log_type) }}</span>
                                                              <a class="d-block h6 text-sm mb-0">{!! $activity->getLeadRemark() !!}</a>
                                                              <small><i class="fas fa-clock mr-1"></i>{{$activity->created_at->diffForHumans()}}</small>
                                                          </div>
                                                      </div>
                                                  @endforeach
                                                @else
                                                  No activity found yet.
                                                @endif
                                              </div>
                                          </div>
                                      </div>
                                  </div>
                              </div>
                            </div>
                        @endif
                    </div>
                </div>

                @if(Auth::user()->type != 'client')
                    <div class="tab-pane fade show" id="products" role="tabpanel">
                        <div class="row pt-2">
                            <div class="col-12">
                                <div class="justify-content-between align-items-center d-flex">
                                    <h4 class="h4 font-weight-400 float-left">{{__('Products')}}</h4>
                                    @can('edit lead')
                                        <a href="#" class="btn btn-sm btn-white float-right add-small" data-url="{{ route('leads.products.edit',$lead->id) }}" data-ajax-popup="true" data-title="{{__('Add Products')}}"><i class="fas fa-plus"></i> {{__('Add')}}</a>
                                    @endcan
                                </div>
                                <div class="card">
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table class="table align-items-center mb-0">
                                                <tbody class="list">
                                                @if($products)
                                                    @foreach($products as $product)
                                                        <tr>
                                                            <td>
                                                                <img width="50" @if($product->avatar) src="{{asset('/storage/product/'.$product->avatar)}}" @else src="{{asset('assets/img/news/img01.jpg')}}" @endif>
                                                            </td>
                                                            <td>
                                                                <span class="number-id">{{$product->name}} </span> (<span class="text-muted">{{\Auth::user()->priceFormat($product->price)}}</span>)
                                                            </td>
                                                            <td>
                                                                @can('edit lead')
                                                                    <a href="#" class="delete-icon float-right" data-toggle="tooltip" data-original-title="{{__('Delete')}}" data-confirm="{{__('Are You Sure?').'|'.__('This action can not be undone. Do you want to continue?')}}" data-confirm-yes="document.getElementById('product-delete-form-{{$product->id}}').submit();">
                                                                        <i class="fas fa-trash"></i>
                                                                    </a>
                                                                    {!! Form::open(['method' => 'DELETE', 'route' => ['leads.products.destroy',$lead->id,$product->id],'id'=>'product-delete-form-'.$product->id]) !!}
                                                                    {!! Form::close() !!}
                                                                @endcan
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                @else
                                                <div class="text-center">
                                                  No Product Found.!
                                                </div>
                                                @endif
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
                @if(Auth::user()->type != 'client')
                    <div class="tab-pane fade show" id="sources" role="tabpanel">
                        <div class="row pt-2">
                            <div class="col-12">
                                <div class="justify-content-between align-items-center d-flex">
                                    <h4 class="h4 font-weight-400 float-left">{{__('Sources')}}</h4>
                                    @can('edit lead')
                                        <a href="#" class="btn btn-sm btn-white float-right add-small" data-url="{{ route('leads.sources.edit',$lead->id) }}" data-ajax-popup="true" data-title="{{__('Edit Sources')}}"><i class="fas fa-pen"></i> {{__('Edit')}}</a>
                                    @endcan
                                </div>
                                <div class="card">
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table class="table align-items-center mb-0">
                                                <tbody class="list">
                                                @if($sources)
                                                    @foreach($sources as $source)
                                                        <tr>
                                                            <td>
                                                                <span class="text-dark">{{$source->name}}</span>
                                                            </td>
                                                            <td>
                                                                @can('edit lead')
                                                                    <a href="#" class="delete-icon float-right" data-toggle="tooltip" data-original-title="{{__('Delete')}}" data-confirm="{{__('Are You Sure?').'|'.__('This action can not be undone. Do you want to continue?')}}" data-confirm-yes="document.getElementById('source-delete-form-{{$source->id}}').submit();">
                                                                        <i class="fas fa-trash"></i>
                                                                    </a>
                                                                    {!! Form::open(['method' => 'DELETE', 'route' => ['leads.sources.destroy',$lead->id,$source->id],'id'=>'source-delete-form-'.$source->id]) !!}
                                                                    {!! Form::close() !!}
                                                                @endcan
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                @else
                                                <div class="text-center">
                                                  No Source Added.!
                                                </div>
                                                @endif
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
                @if(Auth::user()->type != 'client')
                    <div class="tab-pane fade show" id="files" role="tabpanel">
                        <div class="row pt-2">
                            <div class="col-12">
                                <div class="justify-content-between align-items-center d-flex">
                                    <h4 class="h4 font-weight-400 float-left">{{__('Files')}}</h4>
                                </div>
                                <div class="card mb-0">
                                    <div class="card-body">
                                        <div class="col-md-12 dropzone top-5-scroll browse-file" id="dropzonewidget2"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
                <div class="tab-pane fade show" id="discussion" role="tabpanel">
                    <div class="row pt-2">
                        <div class="col-12">
                            <div class="justify-content-between align-items-center d-flex">
                                <h4 class="h4 font-weight-400 float-left">{{__('Discussion')}}</h4>
                                <a href="#" class="btn btn-sm btn-white float-right add-small" data-url="{{ route('leads.discussions.create',$lead->id) }}" data-ajax-popup="true" data-title="{{__('Add Message')}}"><i class="fas fa-plus"></i> {{__('Add Message')}}</a>
                            </div>
                            <div class="card">
                                <div class="card-body">
                                    <ul class="list-unstyled list-unstyled-border">
                                      @if(!$lead->discussions->isEmpty())
                                        @foreach($lead->discussions as $discussion)
                                            <li class="media mb-3">
                                                <img alt="image" class="mr-3 rounded-circle" width="50" height="50" src="@if($discussion->user->avatar) {{asset('/storage/uploads/avatar/'.$discussion->user->avatar)}} @else {{asset('assets/img/avatar/avatar-1.png')}} @endif">
                                                <div class="media-body">
                                                    <div class="mt-0 mb-1 font-weight-bold text-sm">{{$discussion->user->name}} <small>{{$discussion->user->type}}</small> <small class="float-right">{{$discussion->created_at->diffForHumans()}}</small></div>
                                                    <div class="text-xs"> {{$discussion->comment}}</div>
                                                </div>
                                            </li>
                                        @endforeach
                                      @else
                                      <div class="text-center">
                                        No Discussion Available.!
                                      </div>
                                      @endif
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="tab-pane fade show" id="notes" role="tabpanel">
                    <div class="row pt-2">
                        <div class="col-12">
                            <div class="justify-content-between align-items-center d-flex">
                                <h4 class="h4 font-weight-400 float-left">{{__('Notes')}}</h4>
                            </div>
                            <div class="card">
                                <div class="card-body">
                                    <textarea class="summernote-simple">{!! $lead->notes !!}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                @if(Auth::user()->type != 'client')
                    <div class="tab-pane fade show" id="calls" role="tabpanel">
                        <div class="row pt-2">
                            <div class="col-12">
                                <div class="justify-content-between align-items-center d-flex">
                                    <h4 class="h4 font-weight-400 float-left">{{__('Calls')}}</h4>
                                    @can('create lead call')
                                        <a href="#" class="btn btn-sm btn-white float-right add-small" data-url="{{ route('leads.calls.create',$lead->id) }}" data-size="lg" data-ajax-popup="true" data-title="{{__('Add Call')}}"><i class="fas fa-plus"></i> {{__('Add Call')}}</a>
                                    @endcan
                                </div>
                                <div class="card">
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table class="table table-striped mb-0 dataTable">
                                                <thead>
                                                <tr>
                                                    <th width="">{{__('Subject')}}</th>
                                                    <th>{{__('Call Type')}}</th>
                                                    <th>{{__('Duration')}}</th>
                                                    <th>{{__('User')}}</th>
                                                    <th width="14%"></th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                @foreach($calls as $call)
                                                    <tr>
                                                        <td>{{ $call->subject }}</td>
                                                        <td>{{ ucfirst($call->call_type) }}</td>
                                                        <td>{{ $call->duration }}</td>
                                                        <td>{{ isset($call->getLeadCallUser) ? $call->getLeadCallUser->name : '-' }}</td>
                                                        <td>
                                                            @can('edit lead call')
                                                                <a href="#" data-url="{{ URL::to('leads/'.$lead->id.'/call/'.$call->id.'/edit') }}" data-size="lg" data-ajax-popup="true" data-title="{{__('Edit Lead Call')}}" class="edit-icon" data-toggle="tooltip" data-original-title="{{__('Edit')}}"><i class="fas fa-pencil-alt"></i></a>
                                                            @endcan
                                                            @can('delete lead call')
                                                                <a href="#" class="delete-icon" data-toggle="tooltip" data-original-title="{{__('Delete')}}" data-confirm="{{__('Are You Sure?').'|'.__('This action can not be undone. Do you want to continue?')}}" data-confirm-yes="document.getElementById('delete-form-{{$call->id}}').submit();"><i class="fas fa-trash"></i></a>
                                                                {!! Form::open(['method' => 'DELETE', 'route' => ['leads.calls.destroy',$lead->id ,$call->id],'id'=>'delete-form-'.$call->id]) !!}
                                                                {!! Form::close() !!}
                                                            @endcan
                                                        </td>
                                                    </tr>
                                                @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
                @if(Auth::user()->type != 'client')
                    <div class="tab-pane fade show" id="emails" role="tabpanel">
                        <div class="row pt-2">
                            <div class="col-12">
                                <div class="justify-content-between align-items-center d-flex">
                                    <h4 class="h4 font-weight-400 float-left">{{__('Emails')}}</h4>
                                    @can('create lead email')
                                        <a href="#" class="btn btn-sm btn-white float-right add-small" data-url="{{ route('leads.emails.create',$lead->id) }}" data-size="lg" data-ajax-popup="true" data-title="{{__('Add Email')}}"><i class="fas fa-plus"></i> {{__('Add Email')}}</a>
                                    @endcan
                                </div>
                                <div class="card">
                                    <div class="card-body">
                                        <ul class="list-unstyled list-unstyled-border">
                                          @if(!$emails->isEmpty())
                                            @foreach($emails as $email)
                                                <li class="media mb-3">
                                                    <img alt="image" class="mr-3 rounded-circle" width="50" height="50" src="{{asset('assets/img/avatar/avatar-1.png')}}">
                                                    <div class="media-body">
                                                        <div class="mt-0 mb-1 font-weight-bold text-sm">{{$email->subject}} <small class="float-right">{{$email->created_at->diffForHumans()}}</small></div>
                                                        <div class="text-xs"> {{$email->to}}</div>
                                                    </div>
                                                </li>
                                            @endforeach
                                          @else
                                          <div class="text-center">
                                            No Emails Available.!
                                          </div>
                                          @endif
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
