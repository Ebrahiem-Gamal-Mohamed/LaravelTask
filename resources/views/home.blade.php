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
                                <td id="loc_id"></td>
                                <td><img style="width:100%;height:120px;" src=" {{Storage::url($emp->user_image) }}"></td>
                                <td class="text-center">
                                    <a style="margin:7px;" class="btn btn-info" href="employee/edit/{{ $emp->id }}">edit</a>
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


<script type="text/javascript">

        lati = {{ $location[0] }};
        longi = {{ $location[1] }};

        function initMap() {                            
            //for get the address ...
            var geocoder = new google.maps.Geocoder;
            geocoder.geocode({'location':{lat: lati, lng: longi} }, function(results, status) {
                if (status === 'OK') {
                    if (results[0]) {
                        document.getElementById('loc_id').innerText = results[0].formatted_address;
                    } else {
                        window.alert('No results found');
                    }
                } else {
                    window.alert('Geocoder failed due to: ' + status);
                }
            });
        }
        </script>
        <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyADDonI8QXXG340ZQW_wNhG2xZMG6O3BcY&libraries=places&callback=initMap"
async defer></script>
@endsection
