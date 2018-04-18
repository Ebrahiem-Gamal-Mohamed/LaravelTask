@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12 col-xs-12">
            
            <div class="card">
                <div class="card-body">
                    <h2 class="h2 text-center">Admin Home</h2>
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif

                    <div class="col-md-10 offset-md-2">
                        <div class="row">
                        <div class="col-md-8">
                            <form method="get" action="{{ url('employee/search') }}" class="navbar-form" role="search">
                                <div class="input-group add-on">
                                    <input required class="form-control" placeholder="Search for Employee by name .." name="search_item" id="srch-term" type="text">
                                    <div class="input-group-btn">
                                        <button class="btn btn-default" type="submit"><i class="fa fa-search"></i></button>
                                    </div>
                                </div>
                            </form>
                        </diV>
                        <div class="col-md-4">
                            <div class="nav">
                                <div class="navbar-nav ml-auto">
                                    <a class="btn btn-outline-primary" href="{{ url('employee/add') }}" role="button">Add Employee</a>
                                </div>
                            </div>
                        </div>
                        </div>
                    </div>

                    <table class="table table-bordered table-striped">
                        <thead class="thead-light">
                            <tr>
                                <th>Employee Name</th>
                                <th>Employee Job</th>
                                <th>Location</th>
                                <th>Photo</th>
                                <th>Control</th>
                            </tr>
                        </thead>
                        <tbody class="table-hover">
                            @foreach ($employees as $emp)
                            <tr>
                                <td><a href="employee/show/{{$emp->id}}">{{ $emp->first_name }} {{ $emp->last_name }}</a></td>
                                <td>{{ $emp->job }}</td>
                                <td>{{ $emp->location }}</td>
                                <td><img style="width:100%;height:120px;" src=" {{Storage::url($emp->user_image) }}"></td>
                                <td class="text-center">
                                    <a class="btn btn-info" href="employee/edit/{{ $emp->id }}">edit</a>
                                    <a class="btn btn-danger" href="employee/delete/{{ $emp->id }}">delete</a>                                    
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
@endsection
