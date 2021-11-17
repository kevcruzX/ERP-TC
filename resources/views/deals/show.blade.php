@extends('layouts.admin')

@section('page-title')
    {{$deal->name}}
@endsection

@push('css-page')
    <link rel="stylesheet" href="{{asset('assets/libs/summernote/summernote-bs4.css')}}">
    <link rel="stylesheet" href="{{asset('assets/libs/dropzonejs/dropzone.css')}}">
    <link rel="stylesheet" href="{{asset('assets/libs/bootstrap-timepicker/css/bootstrap-timepicker.css')}}">
    <style>
        .nav-tabs .nav-link-tabs.active {
            background: none;
        }
    </style>

    @if($calenderTasks)
        <link rel="stylesheet" href="{{asset('assets/libs/fullcalendar/dist/fullcalendar.min.css')}}">
    @endif
@endpush

@push('script-page')
    <script src="{{asset('assets/libs/summernote/summernote-bs4.js')}}"></script>
    <script src="{{asset('assets/libs/dropzonejs/min/dropzone.min.js')}}"></script>
    <script src="{{asset('assets/libs/bootstrap-timepicker/js/bootstrap-timepicker.js')}}"></script>
    <script>
        $(document).on("change", "#change-deal-status select[name=deal_status]", function () {
            $('#change-deal-status').submit();
        });

        @if(Auth::user()->type != 'client' || in_array('Client View Files',$permission))
            Dropzone.autoDiscover = false;
        myDropzone = new Dropzone("#dropzonewidget", {
            maxFiles: 20,
            maxFilesize: 20,
            parallelUploads: 1,
            acceptedFiles: ".jpeg,.jpg,.png,.pdf,.doc,.txt",
            url: "{{route('deals.file.upload',$deal->id)}}",
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
            formData.append("deal_id", {{$deal->id}});
        });

        myDropzone2 = new Dropzone("#dropzonewidget2", {
            maxFiles: 20,
            maxFilesize: 20,
            parallelUploads: 1,
            acceptedFiles: ".jpeg,.jpg,.png,.pdf,.doc,.txt",
            url: "{{route('deals.file.upload',$deal->id)}}",
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
            formData.append("deal_id", {{$deal->id}});
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
            @can('edit deal')
            html.appendChild(del);
            @endcan
            @endif

            file.previewTemplate.appendChild(html);

            if ($(".top-5-scroll").length) {
                $(".top-5-scroll").css({
                    height: 315
                }).niceScroll();
            }
        }

        @foreach($deal->files as $file)

        @if (file_exists(storage_path('deal_files/'.$file->file_path)))


        // Create the mock file:
        var mockFile = {name: "{{$file->file_name}}", size: {{\File::size(storage_path('deal_files/'.$file->file_path))}} };
        // Call the default addedfile event handler
        myDropzone.emit("addedfile", mockFile);
        // And optionally show the thumbnail of the file:
        myDropzone.emit("thumbnail", mockFile, "{{asset(Storage::url('deal_files/'.$file->file_path))}}");
        myDropzone.emit("complete", mockFile);

        dropzoneBtn(mockFile, {download: "{{route('deals.file.download',[$deal->id,$file->id])}}", delete: "{{route('deals.file.delete',[$deal->id,$file->id])}}"});

        // Create the mock file:
        var mockFile2 = {name: "{{$file->file_name}}", size: {{\File::size(storage_path('deal_files/'.$file->file_path))}} };
        // Call the default addedfile event handler
        myDropzone2.emit("addedfile", mockFile2);
        // And optionally show the thumbnail of the file:
        myDropzone2.emit("thumbnail", mockFile2, "{{asset(Storage::url('deal_files/'.$file->file_path))}}");
        myDropzone2.emit("complete", mockFile2);

        dropzoneBtn(mockFile2, {download: "{{route('deals.file.download',[$deal->id,$file->id])}}", delete: "{{route('deals.file.delete',[$deal->id,$file->id])}}"});
        @endif
        @endforeach
        @endif

        @can('edit deal')
        $('.summernote-simple').on('summernote.blur', function () {
            $.ajax({
                url: "{{route('deals.note.store',$deal->id)}}",
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

        @can('edit task')
        $(document).on("click", ".task-checkbox", function () {
            var chbox = $(this);
            var lbl = chbox.parent().parent().find('label');

            $.ajax({
                url: chbox.attr('data-url'),
                data: {_token: $('meta[name="csrf-token"]').attr('content'), status: chbox.val()},
                type: 'PUT',
                success: function (response) {
                    if (response.is_success) {
                        chbox.val(response.status);
                        if (response.status) {
                            lbl.addClass('strike');
                            lbl.find('.badge').removeClass('badge-warning').addClass('badge-success');
                        } else {
                            lbl.removeClass('strike');
                            lbl.find('.badge').removeClass('badge-success').addClass('badge-warning');
                        }
                        lbl.find('.badge').html(response.status_label);

                        show_toastr('Success', response.success, 'success');
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
        @endcan

        $(document).ready(function () {
            var tab = 'general';
                @if ($tab = Session::get('status'))
            var tab = '{{ $tab }}';
            @endif
            $("#myTab2 .nav-link-tabs[href='#" + tab + "']").trigger("click");
        });
    </script>

    @if($calenderTasks)
        <script src="{{ asset('assets/libs/fullcalendar/dist/fullcalendar.min.js') }}"></script>
        <script>
            $(document).ready(function () {
                var e, t, a = $('[data-toggle="event_calendar"]');
                a.length && (t = {
                    header: {right: "", center: "", left: "",},
                    buttonIcons: {prev: "calendar--prev", next: "calendar--next"},
                    theme: !1,
                    selectable: !0,
                    selectHelper: !0,
                    editable: false,
                    events: {!! json_encode($calenderTasks) !!},
                    eventStartEditable: !1,
                    locale: '{{basename(App::getLocale())}}',
                    viewRender: function (t) {
                        e.fullCalendar("getDate").month(), $(".fullcalendar-title").html(t.title)
                    },
                }, (e = a).fullCalendar(t),
                    $("body").on("click", "[data-calendar-view]", function (t) {
                        t.preventDefault(), $("[data-calendar-view]").removeClass("active"), $(this).addClass("active");
                        var a = $(this).attr("data-calendar-view");
                        e.fullCalendar("changeView", a)
                    }), $("body").on("click", ".fullcalendar-btn-next", function (t) {
                    t.preventDefault(), e.fullCalendar("next")
                }), $("body").on("click", ".fullcalendar-btn-prev", function (t) {
                    t.preventDefault(), e.fullCalendar("prev")
                }), $("body").on("click", ".fc-today-button", function (t) {
                    t.preventDefault(), e.fullCalendar("today")
                }));

                $(document).on('click', '.fc-day-grid-event', function (e) {
                    if (!$(this).hasClass('deal')) {
                        e.preventDefault();
                        var event = $(this);
                        var title = $(this).find('.fc-content .fc-title').html();
                        var size = 'md';
                        var url = $(this).attr('href');
                        $("#commonModal .modal-title").html(title);
                        $("#commonModal .modal-dialog").addClass('modal-' + size);

                        $.ajax({
                            url: url,
                            success: function (data) {
                                $('#commonModal .modal-body').html(data);
                                $("#commonModal").modal('show');
                            },
                            error: function (data) {
                                data = data.responseJSON;
                                show_toastr('Error', data.error, 'error')
                            }
                        });
                    }
                });
            });
        </script>
    @endif
@endpush

@section('action-button')
    <div class="all-button-box row d-flex justify-content-end">
        @can('edit deal')
            <div class="col-xl-2 col-lg-2 col-md-4 col-sm-6 col-6" data-toggle="tooltip" data-original-title="{{__('Change Deal Status')}}">
          <span class="py-0">
          {{ Form::open(array('route' => array('deals.change.status',$deal->id),'id'=>'change-deal-status','class'=>'mr-2')) }}
              {{ Form::select('deal_status', \App\Deal::$statues,$deal->status, array('class' => 'form-control select2','id'=>'deal_status')) }}
              {{ Form::close() }}
          </span>
            </div>

        @endcan
        @can('edit deal')
            <div class="col-xl-2 col-lg-2 col-md-4 col-sm-6 col-6">
                <a href="#" data-url="{{ URL::to('deals/'.$deal->id.'/labels') }}" data-ajax-popup="true" data-title="{{__('Labels')}}" class="btn btn-xs btn-white btn-icon-only width-auto"><i class="fas fa-tags"></i> {{__('Label')}}</a>
            </div>
            <div class="col-xl-2 col-lg-2 col-md-4 col-sm-6 col-6">
                <a href="#" data-url="{{ URL::to('deals/'.$deal->id.'/edit') }}" data-size="lg" data-ajax-popup="true" data-title="{{__('edit deal')}}" class="btn btn-xs btn-white btn-icon-only width-auto"><i class="fas fa-pencil-alt"></i> {{__('Edit')}}</a>
            </div>
        @endcan

        <div class="col-xl-2 col-lg-2 col-md-4 col-sm-6 col-6">
            <a href="{{ route('deals.index') }}" class="btn btn-xs btn-white btn-icon-only width-auto">
                <span class="btn-inner--icon"><i class="fas fa-arrow-left"></i>{{__('Back')}}</span>
            </a>
        </div>
    </div>
@endsection

@section('content')
    @php($labels = $deal->labels())
    @if($labels)
        <div class="row">
            <div class="col-12 mb-2">
                <div class="text-right">
                    @foreach($labels as $label)
                        <span class="badge badge-pill badge-{{$label->color}}">{{$label->name}}</span>
                    @endforeach
                </div>
            </div>
        </div>
    @endif

    <div class="row">
        <div class="col-12 mb-3">
            <ul class="nav nav-tabs" id="myTab2" role="tablist">
                <li>
                    <a class="nav-link-tabs" data-toggle="tab" href="#general" role="tab" aria-controls="home" aria-selected="true">{{__('General')}}</a>
                </li>
                <li>
                    <a class="nav-link-tabs" data-toggle="tab" href="#tasks" role="tab" aria-controls="profile" aria-selected="false">{{__('Tasks')}}</a>
                </li>
                <li>
                    <a class="nav-link-tabs" data-toggle="tab" href="#products" role="tab" aria-controls="contact" aria-selected="false">{{__('Products')}}</a>
                </li>
                <li>
                    <a class="nav-link-tabs" data-toggle="tab" href="#sources" role="tab" aria-controls="contact" aria-selected="false">{{__('Sources')}}</a>
                </li>
                <li>
                    <a class="nav-link-tabs" data-toggle="tab" href="#files" role="tab" aria-controls="contact" aria-selected="false">{{__('Files')}}</a>
                </li>
                <li>
                    <a class="nav-link-tabs" data-toggle="tab" href="#discussion" role="tab" aria-controls="contact" aria-selected="false">{{__('Discussion')}}</a>
                </li>
                @can('edit deal')
                    <li>
                        <a class="nav-link-tabs" data-toggle="tab" href="#notes" role="tab" aria-controls="contact" aria-selected="false">{{__('Notes')}}</a>
                    </li>
                @endcan
                <li>
                    <a class="nav-link-tabs" data-toggle="tab" href="#clients" role="tab" aria-controls="contact" aria-selected="false">{{__('Clients')}}</a>
                </li>
                <li>
                    <a class="nav-link-tabs" data-toggle="tab" href="#calls" role="tab" aria-controls="contact" aria-selected="false">{{__('Calls')}}</a>
                </li>

                <li>
                    <a class="nav-link-tabs" data-toggle="tab" href="#emails" role="tab" aria-controls="contact" aria-selected="false">{{__('Emails')}}</a>
                </li>

            </ul>
        </div>

        <div class="col-12">
            <div class="tab-content tab-bordered">
                <div class="tab-pane fade show active" id="general" role="tabpanel">
                    <div class="card py-2 text-sm">
                        <ul class="nav nav-pills p-1">
                            <li class="nav-item">
                                <a class="nav-link" href="#">{{__('Price')}} <span class="badge badge-pill badge-primary">{{\Auth::user()->priceFormat($deal->price)}}</span></a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link text-success" href="#">{{__('Pipeline')}} <span class="badge badge-pill badge-success">{{$deal->pipeline->name}}</span></a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link text-warning" href="#">{{__('Stage')}} <span class="badge badge-pill badge-warning">{{$deal->stage->name}}</span></a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#">{{__('Created')}} <span class="badge badge-pill badge-secondary">{{\Auth::user()->dateFormat($deal->created_at)}}</span></a>
                            </li>
                            <li class="nav-item deal_status pr-3" data-toggle="tooltip" data-original-title="{{__('Deal Status')}}">
                                @if($deal->status == 'Won')
                                    <a href="#" class="btn btn-xs btn-white bg-success btn-icon-only width-auto">{{__($deal->status)}}</a>
                                @elseif($deal->status == 'Loss')
                                    <a href="#" class="btn btn-xs btn-white bg-danger btn-icon-only width-auto">{{__($deal->status)}}</a>
                                @else
                                    <a href="#" class="btn btn-xs btn-white bg-info btn-icon-only width-auto">{{__($deal->status)}}</a>
                                @endif
                            </li>
                        </ul>
                    </div>

                    <?php
                    $tasks = $deal->tasks;
                    $products = $deal->products();
                    $sources = $deal->sources();
                    $calls = $deal->calls;
                    $emails = $deal->emails;
                    ?>

                    <div class="row">
                        <div class="col">
                            <div class="card card-box">
                                <div class="left-card">
                                    <div class="icon-box"><i class="fas fa-tasks"></i></div>
                                    <h4 class="pt-3">{{__('Task')}}</h4>
                                </div>
                                <div class="number-icon">{{count($tasks)}}</div>
                                <img src="{{ asset('assets/img/dot-icon.png') }}" class="dotted-icon"/>
                            </div>
                        </div>
                        <div class="col">
                            <div class="card card-box">
                                <div class="left-card">
                                    <div class="icon-box bg-info"><i class="fas fa-dolly"></i></div>
                                    <h4 class="pt-3">{{__('Product')}}</h4>
                                </div>
                                <div class="number-icon">{{count($products)}}</div>
                                <img src="{{ asset('assets/img/dot-icon.png') }}" class="dotted-icon"/>
                            </div>
                        </div>
                        <div class="col">
                            <div class="card card-box">
                                <div class="left-card">
                                    <div class="icon-box bg-warning"><i class="fas fa-eye"></i></div>
                                    <h4 class="pt-3">{{__('Source')}}</h4>
                                </div>
                                <div class="number-icon">{{count($sources)}}</div>
                                <img src="{{ asset('assets/img/dot-icon.png') }}" class="dotted-icon"/>
                            </div>
                        </div>
                        <div class="col">
                            <div class="card card-box">
                                <div class="left-card">
                                    <div class="icon-box bg-danger"><i class="fas fa-file-alt"></i></div>
                                    <h4 class="pt-3">{{__('Files')}}</h4>
                                </div>
                                <div class="number-icon">{{count($deal->files)}}</div>
                                <img src="{{ asset('assets/img/dot-icon.png') }}" class="dotted-icon"/>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xl-4 col-lg-4 col-sm-6 col-md-6">
                            <div class="justify-content-between align-items-center d-flex">
                                <h4 class="h4 font-weight-400 float-left">{{__('Users')}}</h4>
                                @can('edit deal')
                                    <a href="#" class="btn btn-sm btn-white float-right add-small" data-url="{{ route('deals.users.edit',$deal->id) }}" data-ajax-popup="true" data-title="{{__('Add User')}}">
                                        <i class="fas fa-plus"></i> {{__('Add')}}
                                    </a>
                                @endcan
                            </div>
                            <div class="card bg-none height-450 top-5-scroll">
                                <div class="table-responsive">
                                    <table class="table align-items-center mb-0">
                                        <tbody class="list">
                                        @foreach($deal->users as $user)
                                            <tr>
                                                <td>
                                                    <img data-original-title="{{(!empty($user)?$user->name:'')}}" @if($user->avatar) src="{{asset('/storage/uploads/avatar/'.$user->avatar)}}" @else src="{{asset('assets/img/avatar/avatar-1.png')}}" @endif width="30" class="avatar-sm rounded-circle">
                                                </td>
                                                <td>
                                                    <span class="number-id">{{$user->name}}</span>
                                                </td>
                                                @can('edit deal')
                                                    <td>
                                                        @if($deal->created_by == \Auth::user()->id)
                                                            <a href="#" class="delete-icon float-right" data-toggle="tooltip" data-original-title="{{__('Delete')}}" data-confirm="{{__('Are You Sure?').'|'.__('This action can not be undone. Do you want to continue?')}}" data-confirm-yes="document.getElementById('delete-form-{{$user->id}}').submit();">
                                                                <i class="fas fa-trash"></i>
                                                            </a>
                                                            {!! Form::open(['method' => 'DELETE', 'route' => ['deals.users.destroy',$deal->id,$user->id],'id'=>'delete-form-'.$user->id]) !!}
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

                        <div class="col-xl-4 col-lg-4 col-sm-6 col-md-6">
                            <div class="justify-content-between align-items-center d-flex">
                                <h4 class="h4 font-weight-400 float-left">{{__('Products')}}</h4>
                                @can('edit deal')
                                    <a href="#" class="btn btn-sm btn-white float-right add-small" data-url="{{ route('deals.products.edit',$deal->id) }}" data-ajax-popup="true" data-title="{{__('Add Products')}}">
                                        <i class="fas fa-plus"></i> {{__('Add')}}
                                    </a>
                                @endcan
                            </div>
                            <div class="card bg-none height-450 top-5-scroll">
                                <div class="table-responsive">
                                    <table class="table align-items-center mb-0">
                                        <tbody class="list">
                                        @php($products=$deal->products())
                                        @if($products)
                                            @foreach($products as $product)
                                                <tr>
                                                    <td>
                                                        <img width="30" @if($product->avatar) src="{{asset('/storage/product/'.$product->avatar)}}" @else src="{{asset('assets/img/news/img01.jpg')}}" @endif>
                                                    </td>
                                                    <td>
                                                        <span class="number-id">{{$product->name}} </span> (<span class="text-muted">{{\Auth::user()->priceFormat($product->price)}}</span>)
                                                    </td>
                                                    @can('edit deal')
                                                        <td>
                                                            <a href="#" class="delete-icon float-right" data-toggle="tooltip" data-original-title="{{__('Delete')}}" data-confirm="{{__('Are You Sure?').'|'.__('This action can not be undone. Do you want to continue?')}}" data-confirm-yes="document.getElementById('product-delete-form-{{$product->id}}').submit();">
                                                                <i class="fas fa-trash"></i>
                                                            </a>
                                                            {!! Form::open(['method' => 'DELETE', 'route' => ['deals.products.destroy',$deal->id,$product->id],'id'=>'product-delete-form-'.$product->id]) !!}
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

                        <div class="col-lg-4">
                            <div class="justify-content-between align-items-center d-flex">
                                <h4 class="h4 font-weight-400 float-left">{{__('Files')}}</h4>
                            </div>
                            <div class="card height-450">
                                <div class="card-body bg-none top-5-scroll">
                                    <div class="col-md-12 dropzone top-5-scroll browse-file" id="dropzonewidget"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        @can('edit deal')
                            <div class="col-6">
                                <div class="justify-content-between align-items-center d-flex">
                                    <h4 class="h4 font-weight-400 float-left">{{__('Notes')}}</h4>
                                </div>
                                <div class="card">
                                    <div class="card-body pb-0">
                                        <textarea class="summernote-simple">{!! $deal->notes !!}</textarea>
                                    </div>
                                </div>
                            </div>
                        @endcan
                        <div class="col-6">
                            <div class="justify-content-between align-items-center d-flex">
                                <h4 class="h4 font-weight-400 float-left">{{__('Activity')}}</h4>
                            </div>
                            <div class="card">
                                <div class="card-body">
                                    <div class="scrollbar-inner">
                                        <div class="mh-500 min-h-500">
                                            <div class="timeline timeline-one-side" data-timeline-content="axis" data-timeline-axis-style="dashed">
                                                @if(!$deal->activities->isEmpty())
                                                    @foreach($deal->activities as $activity)
                                                        <div class="timeline-block">
                                                            <span class="timeline-step timeline-step-sm bg-dark border-dark text-white">
                                                                <i class="fas {{ $activity->logIcon() }}"></i>
                                                            </span>
                                                            <div class="timeline-content">
                                                                <span class="text-dark text-sm">{{ __($activity->log_type) }}</span>
                                                                <a class="d-block h6 text-sm mb-0">{!! $activity->getRemark() !!}</a>
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
                    </div>
                    @if($calenderTasks)
                        <div class="row">
                            <div class="col-md-12">
                                <div class="card author-box card-primary">
                                    <div class="card-header">
                                        <div class="row justify-content-between align-items-center full-calender">
                                            <div class="col d-flex align-items-center">
                                                <div class="btn-group" role="group" aria-label="Basic example">
                                                    <a href="#" class="fullcalendar-btn-prev btn btn-sm btn-neutral">
                                                        <i class="fas fa-angle-left"></i>
                                                    </a>
                                                    <a href="#" class="fullcalendar-btn-next btn btn-sm btn-neutral">
                                                        <i class="fas fa-angle-right"></i>
                                                    </a>
                                                </div>
                                                <h5 class="fullcalendar-title h4 d-inline-block font-weight-400 mb-0"></h5>
                                            </div>
                                            <div class="col-lg-6 mt-3 mt-lg-0 text-lg-right">
                                                <div class="btn-group" role="group" aria-label="Basic example">
                                                    <button class="fc-today-button btn btn-sm btn-neutral" type="button">{{__('Today')}}</button>
                                                </div>
                                                <div class="btn-group" role="group" aria-label="Basic example">
                                                    <a href="#" class="btn btn-sm btn-neutral" data-calendar-view="month">{{__('Month')}}</a>
                                                    <a href="#" class="btn btn-sm btn-neutral" data-calendar-view="basicWeek">{{__('Week')}}</a>
                                                    <a href="#" class="btn btn-sm btn-neutral" data-calendar-view="basicDay">{{__('Day')}}</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div id='calendar-container'>
                                            <div id='calendar' data-toggle="event_calendar"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
                <div class="tab-pane fade show" id="tasks" role="tabpanel">
                    <div class="row pt-2">
                        <div class="col-12">
                            <div class="justify-content-between align-items-center d-flex">
                                <h4 class="h4 font-weight-400 float-left">{{__('Tasks')}}</h4>
                                @can('create task')
                                    <a href="#" class="btn btn-sm btn-white float-right add-small" data-url="{{ route('deals.tasks.create',$deal->id) }}" data-ajax-popup="true" data-title="{{__('Create Task')}}"><i class="fas fa-plus"></i> {{__('Create')}}</a>
                                @endcan
                            </div>
                            <div class="card">
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-striped">
                                            <tbody>
                                            @if(!$tasks->isEmpty())
                                                @foreach($tasks as $task)
                                                    <tr>
                                                        <td>
                                                            <div class="custom-control custom-switch">
                                                                @can('edit task')
                                                                    <input type="checkbox" class="custom-control-input task-checkbox" id="task_{{$task->id}}" @if($task->status) checked="checked" @endcan type="checkbox" value="{{$task->status}}" data-url="{{route('deals.tasks.update_status',[$deal->id,$task->id])}}"/>
                                                                @endcan
                                                                <label for="task_{{$task->id}}" class="custom-control-label ml-4 @if($task->status) strike @endif">
                                                                    <h6 class="media-title text-sm">
                                                                        {{$task->name}}
                                                                        @if($task->status)
                                                                            <div class="badge badge-pill badge-success mb-1">{{__(\App\DealTask::$status[$task->status])}}</div>
                                                                        @else
                                                                            <div class="badge badge-pill badge-warning mb-1">{{__(\App\DealTask::$status[$task->status])}}</div>
                                                                        @endif
                                                                    </h6>
                                                                    <div class="text-xs text-muted">{{__(\App\DealTask::$priorities[$task->priority])}} -
                                                                        <span class="text-primary">{{Auth::user()->dateFormat($task->date)}} {{Auth::user()->timeFormat($task->time)}}</span></div>
                                                                </label>
                                                            </div>
                                                        </td>
                                                        <td class="Action">
                                                            <span>
                                                                @can('edit task')
                                                                    <a href="#" class="edit-icon" data-title="{{__('Edit Task')}}" data-url="{{route('deals.tasks.edit',[$deal->id,$task->id])}}" data-ajax-popup="true" data-toggle="tooltip" data-original-title="{{__('Edit')}}"><i class="fas fa-pencil-alt"></i></a>
                                                                @endcan
                                                                @can('delete task')
                                                                    <a href="#" class="delete-icon" data-toggle="tooltip" data-original-title="{{__('Delete')}}" data-confirm="{{__('Are You Sure?').'|'.__('This action can not be undone. Do you want to continue?')}}" data-confirm-yes="document.getElementById('task-delete-form-{{$task->id}}').submit();"><i class="fas fa-trash"></i></a>
                                                                    {!! Form::open(['method' => 'DELETE', 'route' => ['deals.tasks.destroy',$deal->id,$task->id],'id'=>'task-delete-form-'.$task->id]) !!}
                                                                    {!! Form::close() !!}
                                                                @endcan
                                                            </span>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            @else
                                                <div class="text-center">
                                                    No Tasks Available.!
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
                <div class="tab-pane fade show" id="products" role="tabpanel">
                    <div class="row pt-2">
                        <div class="col-12">
                            <div class="justify-content-between align-items-center d-flex">
                                <h4 class="h4 font-weight-400 float-left">{{__('Products')}}</h4>
                                @can('edit deal')
                                    <a href="#" class="btn btn-sm btn-white float-right add-small" data-url="{{ route('deals.products.edit',$deal->id) }}" data-ajax-popup="true" data-title="{{__('Add Products')}}"><i class="fas fa-plus"></i> {{__('Add')}}</a>
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
                                                                {!! Form::open(['method' => 'DELETE', 'route' => ['deals.products.destroy',$deal->id,$product->id],'id'=>'product-delete-form-'.$product->id]) !!}
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
                <div class="tab-pane fade show" id="sources" role="tabpanel">
                    <div class="row pt-2">
                        <div class="col-12">
                            <div class="justify-content-between align-items-center d-flex">
                                <h4 class="h4 font-weight-400 float-left">{{__('Sources')}}</h4>
                                @can('edit deal')
                                    <a href="#" class="btn btn-sm btn-white float-right add-small" data-url="{{ route('deals.sources.edit',$deal->id) }}" data-ajax-popup="true" data-title="{{__('Edit Sources')}}"><i class="fas fa-pen"></i> {{__('Edit')}}</a>
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
                                                            @can('edit deal')
                                                                <a href="#" class="delete-icon float-right" data-toggle="tooltip" data-original-title="{{__('Delete')}}" data-confirm="{{__('Are You Sure?').'|'.__('This action can not be undone. Do you want to continue?')}}" data-confirm-yes="document.getElementById('source-delete-form-{{$source->id}}').submit();">
                                                                    <i class="fas fa-trash"></i>
                                                                </a>
                                                                {!! Form::open(['method' => 'DELETE', 'route' => ['deals.sources.destroy',$deal->id,$source->id],'id'=>'source-delete-form-'.$source->id]) !!}
                                                                {!! Form::close() !!}
                                                            @endcan
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            @else
                                                <div class="text-center">
                                                    No Source Added!
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
                <div class="tab-pane fade show" id="discussion" role="tabpanel">
                    <div class="row pt-2">
                        <div class="col-12">
                            <div class="justify-content-between align-items-center d-flex">
                                <h4 class="h4 font-weight-400 float-left">{{__('Discussion')}}</h4>
                                <a href="#" class="btn btn-sm btn-white float-right add-small" data-url="{{ route('deals.discussions.create',$deal->id) }}" data-ajax-popup="true" data-title="{{__('Add Message')}}"><i class="fas fa-plus"></i> {{__('Add Message')}}</a>
                            </div>
                            <div class="card">
                                <div class="card-body">
                                    <ul class="list-unstyled list-unstyled-border">
                                        @if(!$deal->discussions->isEmpty())
                                            @foreach($deal->discussions as $discussion)
                                                <li class="media mb-2">
                                                    <img alt="image" class="mr-3 rounded-circle" width="50" height="50" data-original-title="{{(!empty($discussion->user)?$discussion->user->name:'')}}" @if($discussion->user->avatar) src="{{asset('/storage/uploads/avatar/'.$discussion->user->avatar)}}" @else src="{{asset('assets/img/avatar/avatar-1.png')}}" @endif width="30">
                                                    <div class="media-body">
                                                        <div class="mt-0 mb-1 font-weight-bold text-sm">{{$discussion->user->name}}
                                                            <small>{{$discussion->user->type}}</small>
                                                            <small class="float-right">{{$discussion->created_at->diffForHumans()}}</small>
                                                        </div>
                                                        <div class="text-xs"> {{$discussion->comment}}</div>

                                                    </div>
                                                </li>
                                            @endforeach
                                        @else
                                            <div class="text-center">
                                                No Discussion Found!
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
                                    <textarea class="summernote-simple">{!! $deal->notes !!}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="tab-pane fade show" id="custom_fields" role="tabpanel">
                    <div class="row pt-2">
                        <div class="col-12">
                            <div class="justify-content-between align-items-center d-flex">
                                <h4 class="h4 font-weight-400 float-left">{{__('Custom Fields')}}</h4>
                            </div>
                            <div class="card">
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table align-items-center mb-0">
                                            <tbody class="list">
                                            @foreach($customFields as $field)
                                                <tr>
                                                    <td class="text-dark">{{$field->name}}</td>
                                                    @if(!empty($deal->customField))
                                                        <td>{{$deal->customField[$field->id]}}</td>
                                                    @endif
                                                </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                    {{--                                        <ul class="list-group">--}}
                                    {{--                                            @foreach($customFields as $field)--}}
                                    {{--                                                <li class="list-group-item text-sm">{{$field->name}} @if(!empty($deal->customField))<span class="float-right">{{$deal->customField[$field->id]}}</span>@endif</li>--}}
                                    {{--                                            @endforeach--}}
                                    {{--                                        </ul>--}}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="tab-pane fade show" id="clients" role="tabpanel">
                    <div class="row pt-2">
                        <div class="col-12">
                            <div class="justify-content-between align-items-center d-flex">
                                <h4 class="h4 font-weight-400 float-left">{{__('Clients')}}</h4>
                                @if(\Auth::user()->type == 'comapny')
                                    <a href="#" class="btn btn-sm btn-white float-right add-small" data-url="{{route('deals.clients.edit',$deal->id)}}" data-ajax-popup="true" data-title="{{__('Add Client')}}"><i class="fas fa-plus"></i> {{__('Add Client')}}</a>
                                @endif
                            </div>
                            <div class="card">
                                <div class="card-body">
                                    <table class="table table-striped dataTable">
                                        <thead>
                                        <tr>
                                            <th>{{__('Avatar')}}</th>
                                            <th>{{__('Name')}}</th>
                                            <th>{{__('Email')}}</th>
                                            @if(count($deal->clients) != 1)
                                                <th>{{__('Action')}}</th>
                                            @endif
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach ($deal->clients as $client)
                                            <tr>
                                                <td><img data-original-title="{{(!empty($client->avatar)?$client->name:'')}}" @if($client->avatar) src="{{asset('/storage/uploads/avatar/'.$client->avatar)}}" @else src="{{asset('assets/img/avatar/avatar-1.png')}}" @endif class="rounded-circle mr-1" width="40" height="40"></td>
                                                <td>{{ $client->name }}</td>
                                                <td>{{ $client->email }}</td>
                                                @if(count($deal->clients) != 1)
                                                    <td>
                                                        <a href="#" class="delete-icon" data-toggle="tooltip" data-original-title="{{__('Delete')}}" data-confirm="{{__('Are You Sure?').'|'.__('This action can not be undone. Do you want to continue?')}}" data-confirm-yes="document.getElementById('client-delete-form-{{$client->id}}').submit();"><i class="fas fa-trash"></i></a>
                                                        {!! Form::open(['method' => 'DELETE', 'route' => ['deals.clients.destroy',$deal->id,$client->id],'id'=>'client-delete-form-'.$client->id]) !!}
                                                        {!! Form::close() !!}
                                                    </td>
                                                @endif
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="tab-pane fade show" id="calls" role="tabpanel">
                    <div class="row pt-2">
                        <div class="col-12">
                            <div class="justify-content-between align-items-center d-flex">
                                <h4 class="h4 font-weight-400 float-left">{{__('Calls')}}</h4>
                                @can('create deal call')
                                    <a href="#" class="btn btn-sm btn-white float-right add-small" data-url="{{ route('deals.calls.create',$deal->id) }}" data-size="lg" data-ajax-popup="true" data-title="{{__('Add Call')}}"><i class="fas fa-plus"></i> {{__('Add Call')}}</a>
                                @endcan
                            </div>
                            <div class="card">
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-striped dataTable">
                                            <thead>
                                            <tr>
                                                <th>{{__('Subject')}}</th>
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
                                                    <td>{{ $call->getDealCallUser->name }}</td>
                                                    <td class="Action">
                                                            <span>
                                                            @can('edit deal call')
                                                                    <a href="#" data-url="{{ URL::to('deals/'.$deal->id.'/call/'.$call->id.'/edit') }}" data-size="lg" data-ajax-popup="true" data-title="{{__('Edit Deal Call')}}" class="edit-icon" data-toggle="tooltip" data-original-title="{{__('Edit')}}"><i class="fas fa-pencil-alt"></i></a>
                                                                @endcan
                                                                @can('delete deal call')
                                                                    <a href="#" class="delete-icon" data-toggle="tooltip" data-original-title="{{__('Delete')}}" data-confirm="{{__('Are You Sure?').'|'.__('This action can not be undone. Do you want to continue?')}}" data-confirm-yes="document.getElementById('delete-form-{{$call->id}}').submit();"><i class="fas fa-trash"></i></a>
                                                                    {!! Form::open(['method' => 'DELETE', 'route' => ['deals.calls.destroy',$deal->id ,$call->id],'id'=>'delete-form-'.$call->id]) !!}
                                                                    {!! Form::close() !!}
                                                                @endcan
                                                            </span>
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

                <div class="tab-pane fade show" id="emails" role="tabpanel">
                    <div class="row pt-2">
                        <div class="col-12">
                            <div class="justify-content-between align-items-center d-flex">
                                <h4 class="h4 font-weight-400 float-left">{{__('Emails')}}</h4>
                                @can('create deal email')
                                    <a href="#" class="btn btn-sm btn-white float-right add-small" data-url="{{ route('deals.emails.create',$deal->id) }}" data-size="lg" data-ajax-popup="true" data-title="{{__('Add Email')}}"><i class="fas fa-plus"></i> {{__('Add Email')}}</a>
                                @endcan
                            </div>
                            <div class="card">
                                <div class="card-body">
                                    <ul class="list-unstyled list-unstyled-border">
                                        @if(!$emails->isEmpty())
                                            @foreach($emails as $email)
                                                <li class="media">
                                                    <img alt="image" class="mr-3 mb-3 rounded-circle" width="50" height="50" src="{{asset('assets/img/avatar/avatar-1.png')}}">
                                                    <div class="media-body">
                                                        <div class="mt-0 mb-1 font-weight-bold text-sm">{{$email->subject}}
                                                            <small class="float-right">{{$email->created_at->diffForHumans()}}</small>
                                                        </div>
                                                        <div class="text-xs">{{$email->to}}</div>
                                                    </div>
                                                </li>
                                            @endforeach
                                        @else
                                            <div class="text-center">
                                                No Emails Found!
                                            </div>
                                        @endif
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
