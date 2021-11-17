@extends('layouts.admin')

@section('page-title')
    {{__('Permissions')}}
@endsection
@section('content')
    <section class="section">
        <div class="section-header">
            <h1>{{__('Invoice')}}</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{route('dashboard')}}">{{__('Dashboard')}}</a></div>
                <div class="breadcrumb-item">{{__('Permissions')}}</div>
            </div>
        </div>
        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="d-flex justify-content-between w-100">
                                <h4>{{__('Manage Permissions')}}</h4>
                                <a href="#" data-url="{{ route('permissions.create') }}" data-size="lg" data-ajax-popup="true" data-title="{{__('Create New Permission')}}" class="btn btn-icon icon-left btn-primary">
                                    <i class="fa fa-plus"></i> {{__('Create')}}
                                </a>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="card-body p-0">
                                <div id="table-1_wrapper" class="dataTables_wrapper container-fluid dt-bootstrap4 no-footer">
                                    <div class="table-responsive">
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <table class="table table-flush" id="dataTable">
                                                    <thead>
                                                    <tr>
                                                        <th> {{__('Permissions')}}</th>
                                                        <th class="text-right" width="200px"> {{__('Action')}}</th>
                                                    </tr>
                                                    </thead>

                                                    <tbody>
                                                    @foreach ($permissions as $permission)
                                                        <tr>
                                                            <td>{{ $permission->name }}</td>

                                                            <td class="action">

                                                                <a href="#" class="edit-icon" data-url="{{ route('permissions.edit',$permission->id) }}" data-size="lg" data-ajax-popup="true" data-title="{{__('Update permission')}}" class="btn btn-outline btn-xs blue-madison" data-toggle="tooltip" data-original-title="{{__('Edit')}}">
                                                                    <i class="fas fa-pencil-alt"></i>
                                                                </a>

                                                                <a href="#" class="delete-icon " data-toggle="tooltip" data-original-title="{{__('Delete')}}" data-confirm="{{__('Are You Sure?').'|'.__('This action can not be undone. Do you want to continue?')}}" data-confirm-yes="document.getElementById('delete-form-{{$permission->id}}').submit();">
                                                                    <i class="fas fa-trash"></i>
                                                                </a>
                                                                {!! Form::open(['method' => 'DELETE', 'route' => ['permissions.destroy', $permission->id],'id'=>'delete-form-'.$permission->id]) !!}
                                                                {!! Form::close() !!}

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
                    </div>
                </div>
            </div>

        </div>
    </section>
@endsection
