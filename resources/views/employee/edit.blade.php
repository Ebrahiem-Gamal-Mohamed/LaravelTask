@extends('layouts.app')

@section('content')

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12 col-xs-12">
            <div class="card">
                <div class="card-body">
                    <h2 class="h2 text-center">Edit <strong>{{ $employee->first_name }} {{ $employee->last_name }}</strong> Data</h2>
                    <form class="custom_form" method="POST" action="/employee/{{ $employee->id }}/update" enctype="multipart/form-data" >
                        @csrf
                        @if ($errors->has('first_name'))
                            <span class="invalid-feedback">
                                <strong>{{ $errors->first('first_name') }}</strong>
                            </span>
                        @endif
                        <div class="form-group row">
                            <label for="inputFirstName" class="col-sm-4 col-form-label">First Name</label>
                            <div class="col-sm-8">
                                <input value="{{ $employee->first_name }}" name="first_name" type="text" class="form-control" id="inputFirstName" placeholder="First Name" required>
                            </div>
                        </div>
                        @if ($errors->has('last_name'))
                            <span class="invalid-feedback">
                                <strong>{{ $errors->first('last_name') }}</strong>
                            </span>
                        @endif
                        <div class="form-group row">
                            <label for="inputLastName" class="col-sm-4 col-form-label">Last Name</label>
                            <div class="col-sm-8">
                                <input value="{{ $employee->last_name }}" name="last_name" type="text" class="form-control" id="inputLastName" placeholder="Last Name" required>
                            </div>
                        </div>
                        @if ($errors->has('emp_job'))
                            <span class="invalid-feedback">
                                <strong>{{ $errors->first('emp_job') }}</strong>
                            </span>
                        @endif
                        <div class="form-group row">
                            <label for="inputJob" class="col-sm-4 col-form-label">Employee Job</label>
                            <div class="col-sm-8">
                                <textarea class="form-control" name="emp_job" id="inputJob" cols="30" rows="2" placeholder="Employee Job" required>{{ $employee->job }}</textarea>
                            </div>
                        </diV>

                        <div class="form-group row">
                            <label for="inputLocation" class="col-sm-4 col-form-label">Employee Location</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id='locationSearch' placeholder="add new location name ..">
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
        lati = {{ $location[0] }};
        longi = {{ $location[1] }};

        function initMap() {                            
            latitude = document.getElementById('inputLocation');// LATITUDE VALUE 31.0549151; 
            longitude = document.getElementById('inputLocation2'); // LONGITUDE VALUE 31.380243;
            autocomplete = new google.maps.places.Autocomplete(document.getElementById('locationSearch'));
            infowindow = new google.maps.InfoWindow();
            infowindowContent = document.getElementById('infowindow-content');

            latitude.value = lati;
            longitude.value = longi;

            map = new google.maps.Map(document.getElementById('map'), {
              center: {lat: lati, lng: longi},
              zoom: 15,
              disableDoubleClickZoom: false, // disable the default map zoom on double click
            });

            //marker for the old location ...
            var marker2 = new google.maps.Marker({
              map: map,
              position: {
                  lat : lati,
                  lng: longi
              },
            });

            //for get the address ...
            var geocoder = new google.maps.Geocoder;
            
            google.maps.event.addListener(marker2, 'click', function() {
                geocoder.geocode({'location':{lat: lati, lng: longi} }, function(results, status) {
                    if (status === 'OK') {
                        if (results[0]) {
                        infowindow.setContent('<div><strong>' + results[0].formatted_address + '</strong><br></div>');
                        infowindow.open(map, marker2);
                        } else {
                        window.alert('No results found');
                        }
                    } else {
                        window.alert('Geocoder failed due to: ' + status);
                    }
                });
            });
                
            //marher for new place ....
            var marker = new google.maps.Marker({
              map: map,
              anchorPoint: new google.maps.Point(0, -29),
            });    

            // Bind the map's bounds (viewport) property to the autocomplete object,
            // so that the autocomplete requests use the current map bounds for the
            // bounds option in the request.
            autocomplete.bindTo('bounds', map);

            infowindow.setContent(infowindowContent);

            //handle place change from input ...
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
                marker2.setVisible(false);                
                
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

                latitude.value = place.geometry.location.lat();
                longitude.value = place.geometry.location.lng();
            });

            //display the new address ...
            google.maps.event.addListener(marker, 'click', function() {
                geocoder.geocode({'location': {lat: parseFloat(latitude.value), lng: parseFloat(longitude.value)} }, function(results, status) {
                    if (status === 'OK') {
                        if (results[0]) {
                        infowindow.setContent('<div><strong>' + results[0].formatted_address + '</strong><br></div>');
                        infowindow.open(map, marker);
                        } else {
                        window.alert('No results found');
                        }
                    } else {
                        window.alert('Geocoder failed due to: ' + status);
                    }
                });
            });

        }

        </script>

        <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyADDonI8QXXG340ZQW_wNhG2xZMG6O3BcY&libraries=places&callback=initMap"
async defer></script>

@endsection
