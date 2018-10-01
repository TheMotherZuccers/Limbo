@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">Add an Item</div>

                    <div class="card-body">
                        {{ Form::open(array('url' => 'item_form')) }}

                        {{ Form::label('description', 'Description') }}
                        {{ Form::text('description') }}<br>

                        {{ Form::label('notable_damage', 'Notable Damage') }}
                        {{ Form::text('notable_damage') }}<br>

                        {{ Form::label('environment_found', 'Environment Item was Found In') }}
                        {{ Form::select('environment_found', ['inside' => 'Inside', 'sunny' => 'Sunny',
                        'rain' => 'Rain', 'snow' => 'Snow', 'humid' => 'Humid']) }}<br>

                        {{ Form::label('pos_x', 'Position Found') }}
                        {{ Form::number('pos_x', null, ['class' => 'form-control','step' => '0.00001']) }}
                        {{ Form::number('pos_y', null, ['class' => 'form-control','step' => '0.00001']) }}<br>

                        {{ Form::label('position_radius', 'Position Radius') }}
                        {{ Form::number('position_radius', null) }}<br>

                        {{ Form::label('position_comment', 'Position Comment') }}
                        {{ Form::text('position_comment') }}<br>

                        {{ Form::label('finder_email', 'Finder\'s Email') }}
                        {{ Form::text('finder_email') }}<br>

                        {{ Form::submit('Add Item') }}

                        {{ Form::close() }}
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div id="mapids" style="height: 360px; width: 720px;"></div>
                <script>
                    var mymap = L.map('mapids').setView([41.72212, -73.93417], 15);

                    L.tileLayer('https://api.tiles.mapbox.com/v4/{id}/{z}/{x}/{y}.png?access_token={accessToken}', {
                        attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors, <a href="https://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, Imagery © <a href="https://www.mapbox.com/">Mapbox</a>',
                        maxZoom: 18,
                        id: 'mapbox.streets',
                        accessToken: 'pk.eyJ1Ijoid2lsbGlhbWtsdWdlIiwiYSI6ImNqbW04eXB5dzBna2szcW83ajdlb2xpcmwifQ.RdkpVNHpUdMLV-2GJlTGTQ'
                    }).addTo(mymap);
                </script>
            </div>
        </div>
    </div>
@endsection
