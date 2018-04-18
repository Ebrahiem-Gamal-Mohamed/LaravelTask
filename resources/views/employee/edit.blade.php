@extends('layouts.app')

@section('content')

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12 col-xs-12">
            <div class="card">
                <div class="card-body">
                    <h2 class="h2 text-center">Edit {{ $employee->first_name }} {{ $employee->last_name }} Data</h2>
                    <form class="custom_form" action="employee/update/{{$employee->id}}" method="POST" enctype="multipart/form-data" >
                        @csrf
                        <div class="form-group row">
                            <label for="inputFirstName" class="col-sm-4 col-form-label">First Name</label>
                            <div class="col-sm-8">
                                <input value="{{ $employee->first_name }}" name="first_name" type="text" class="form-control" id="inputFirstName" placeholder="First Name" required>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="inputLastName" class="col-sm-4 col-form-label">Last Name</label>
                            <div class="col-sm-8">
                                <input value="{{ $employee->last_name }}" name="last_name" type="text" class="form-control" id="inputLastName" placeholder="Last Name" required>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="inputJob" class="col-sm-4 col-form-label">Employee Job</label>
                            <div class="col-sm-8">
                                <textarea class="form-control" name="emp_job" id="inputJob" cols="30" rows="2" placeholder="Employee Job" required>{{ $employee->job }}</textarea>
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
                                <input required type="text" class="form-control" id='locationSearch' placeholder="add location name ..">
                                <input type="hidden" class="form-control" name="lat" id="inputLocation" placeholder="Employee Location"/>
                                <input type="hidden" class="form-control" name="lng" id="inputLocation2" placeholder="Employee Location"/>                                
                                <div id="map"></div>
                                <div id="infowindow-content">
                                    <img src="" width="16" height="16" id="place-icon">
                                    <span id="place-name"  class="title"></span><br>
                                    <span id="place-address"></span>
                                </div>
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
            latitude = document.getElementById('inputLocation');// LATITUDE VALUE 31.0549151; 
            longitude = document.getElementById('inputLocation2'); // LONGITUDE VALUE 31.380243;
            autocomplete = new google.maps.places.Autocomplete(document.getElementById('locationSearch'));

            map = new google.maps.Map(document.getElementById('map'), {
              center: {lat: 31.0549151, lng: 31.380243},
              zoom: 13,
              disableDoubleClickZoom: false, // disable the default map zoom on double click
            });
                        
            var marker = new google.maps.Marker({
              map: map,
              anchorPoint: new google.maps.Point(0, -29),
            });    

            // Bind the map's bounds (viewport) property to the autocomplete object,
            // so that the autocomplete requests use the current map bounds for the
            // bounds option in the request.
            autocomplete.bindTo('bounds', map);
            infowindow = new google.maps.InfoWindow();
            infowindowContent = document.getElementById('infowindow-content');
            infowindow.setContent(infowindowContent);

            autocomplete.addListener('place_changed', function(event) {
                infowindow.close();
                marker.setVisible(false);
                var place = autocomplete.getPlace();
                if (!place.geometry) {
                    // User entered the name of a Place that was not suggested and
                    // pressed the Enter key, or the Place Details request failed.
                    window.alert("No details available for input: '" + place.name + "'");
                    return;
                }

                // If the place has a geometry, then present it on a map.
                if (place.geometry.viewport) {
                    map.fitBounds(place.geometry.viewport);
                } else {
                    map.setCenter(place.geometry.location);
                    map.setZoom(17);  // Why 17? Because it looks good.
                }
                marker.setPosition(place.geometry.location);
                marker.setVisible(true);

                var address = '';
                if (place.address_components) {
                    address = [
                    (place.address_components[0] && place.address_components[0].short_name || ''),
                    (place.address_components[1] && place.address_components[1].short_name || ''),
                    (place.address_components[2] && place.address_components[2].short_name || '')
                    ].join(' ');
                }

                infowindowContent.children['place-icon'].src = place.icon;
                infowindowContent.children['place-name'].textContent = place.name;
                infowindowContent.children['place-address'].textContent = address;
                infowindow.open(map, marker);
                latitude.value = place.geometry.location.lat();
                longitude.value = place.geometry.location.lng();
            });
        }

        </script>

        <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyADDonI8QXXG340ZQW_wNhG2xZMG6O3BcY&libraries=places&callback=initMap"
async defer></script>

@endsection
