@extends('layouts.app')

@section('content')
    <div class="flex-center position-ref full-height content">
        <div class="laravel-style-bois">
            <h1>{{ config('app.name') }}</h1>
            <h3>Lost and Found Done Right</h3>
            <div class="row">
                <div class="col-md-6">
                    <table class="table table-striped" id="item_table">
                        <thead>
                        <tr>
                            <th scope="col">Item ID</th>
                            <th scope="col">Description</th>
                            <th scope="col">Time Entered</th>
                            <th scope="col">Claimed?</th>
                        </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td scope="row">{{ $item->id }}</td>
                                <td>{{ $item->description }}</td>
                                <td>{{ $item->created_at }}</td>
                                <td>{{ $item->claimed</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="col-md-6">
                    <div id="mapid" style="height: 360px; width: 500px;"></div>
                    <script>
                        var mymap = L.map('mapid').setView([41.72212, -73.93417], 15);

                        L.tileLayer('https://api.tiles.mapbox.com/v4/{id}/{z}/{x}/{y}.png?access_token={accessToken}', {
                            attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors, <a href="https://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, Imagery © <a href="https://www.mapbox.com/">Mapbox</a>',
                            maxZoom: 18,
                            id: 'mapbox.streets',
                            accessToken: 'pk.eyJ1Ijoid2lsbGlhbWtsdWdlIiwiYSI6ImNqbW04eXB5dzBna2szcW83ajdlb2xpcmwifQ.RdkpVNHpUdMLV-2GJlTGTQ'
                        }).addTo(mymap);

                        var marker = L.marker([{{  $item->position_found->getLat() }}, {{ $item->position_found->getLng() }}]).addTo(mymap);
                        marker.bindPopup("<b>{{ $item->description }}</b><br>Time Found: {{ $item->created_at }}<br><a href=\'/item/{{ $item->id }}\'>Item Information</a><br>");
                        popupDict["{{ $item->id }}"] = marker;

                    </script>
                </div>
            </div>
        </div>
    </div>
@endsection
