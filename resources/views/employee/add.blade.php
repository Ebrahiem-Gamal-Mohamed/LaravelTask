@extends('layouts.app')

@section('content')


<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12 col-xs-12">
            <div class="card">
                <div class="card-body">
                    <h2 class="h2 text-center">Add New Employee </h2>
                    <form class="custom_form" action="{{ url('employee/store') }}" method="POST" enctype="multipart/form-data" >
                        @csrf
                        <div class="form-group row">
                            <label for="inputFirstName" class="col-sm-4 col-form-label">First Name</label>
                            <div class="col-sm-8">
                                <input name="first_name" type="text" class="form-control" id="inputFirstName" placeholder="First Name" required>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="inputLastName" class="col-sm-4 col-form-label">Last Name</label>
                            <div class="col-sm-8">
                                <input name="last_name" type="text" class="form-control" id="inputLastName" placeholder="Last Name" required>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="inputJob" class="col-sm-4 col-form-label">Employee Job</label>
                            <div class="col-sm-8">
                                <textarea class="form-control" name="emp_job" id="inputJob" cols="30" rows="2" placeholder="Employee Job" required></textarea>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="inputUserID" class="col-sm-4 col-form-label">User ID</label>
                            <div class="col-sm-8">
                                <select name="user_id" id="inputUserID" class="form-control">
                                    @foreach ($users as $user)
                                        <option value="{{ $user->id }}">{{ $user->id }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="inputLocation" class="col-sm-4 col-form-label">Employee Location</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id='locationSearch' placeholder="add location name ..">
                                <input type="hidden" class="form-control" name="lat" id="inputLocation" placeholder="Employee Location"/>
                                <input type="hidden" class="form-control" name="lng" id="inputLocation2" placeholder="Employee Location"/>                                
                                <div id="map"></div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label" for="exampleFormControlFile1">Upload your image</label>
                            <div class="col-sm-8">
                                <input name="user_image" type="file" class="form-control-file" id="exampleFormControlFile1" required> 
                            </div>
                        </div>
                        
                        <div class="form-group row">
                            <div class="col-sm-8 offset-sm-4">
                                <button type="submit" class="btn btn-primary">Save Data</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
        var map;
        
        function initMap() {                            
            var latitude = 31.0549151; // LATITUDE VALUE
            var longitude = 31.380243; // LONGITUDE VALUE
            
            var myLatLng = {lat: latitude, lng: longitude};
            var autocomplete = new google.maps.places.Autocomplete(document.getElementById('locationSearch'));

            map = new google.maps.Map(document.getElementById('map'), {
              center: myLatLng,
              zoom: 14,
              disableDoubleClickZoom: true, // disable the default map zoom on double click
            });
                        
            var marker = new google.maps.Marker({
              position: myLatLng,
              map: map,
              //title: 'Your Location',
              draggable: true,
            });    
            // Bind the map's bounds (viewport) property to the autocomplete object,
            // so that the autocomplete requests use the current map bounds for the
            // bounds option in the request.
            //autocomplete.bindTo('bounds', map);

            var searchBox = google.maps.places.SearchBox(document.getElementById('locationSearch'));
            google.maps.event.addListener(searchBox,'place_changed', function(){
                var places = searchBox.getPlaces();
                var bounds = new google.maps.LatLngBounds();
                var i, place;

                for(i=0; place=place[i]; i++){
                    bounds.extend(place.geometry.location);
                    marker.setPosition(place.geometry.location); //set marker to the new position ...
                }

                map.fitBounds(bounds);
                map.setZoom(14);

            });

            google.maps.event.addListener(searchBox,'places_changed', function(event){
                var lat = marker.getPosition().lat();
                var lng = marker.getPosition().lng();

                document.getElementById('inputLocation').value = lat;
                document.getElementById('inputLocation2').value =  lng;                                
            });

            // Update lat/long value of div when the marker is clicked
            /*marker.addListener('click', function(event) {              
                document.getElementById('inputLocation').value = event.latLng.lat();
                document.getElementById('inputLocation2').value =  event.latLng.lng();
            });*/
        }
        </script>

        <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyADDonI8QXXG340ZQW_wNhG2xZMG6O3BcY&libraries=places&callback=initMap"
async defer></script>
@endsection
