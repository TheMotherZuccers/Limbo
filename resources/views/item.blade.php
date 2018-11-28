@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <?php $authenticated = ! Auth::guest() && ($item->finder_id == Auth::user()->id || Auth::user()->type == 'admin') ?>
                    @if ($authenticated)
                        <div class="card-header">Update an Item</div>

                        <div class="card-body">
                            {{ Form::model($item, array('url' => 'update_item', 'method' => 'PUT')) }}

                            {{ Form::label('id', 'ID') }}
                            {{ Form::number('id', null, ['readonly']) }}<br>

                            {{ Form::label('description', 'Description') }}
                            {{ Form::text('description') }}<br>

                            {{ Form::label('notable_damage', 'Notable Damage') }}
                            {{ Form::text('notable_damage') }}<br>

                            {{ Form::label('environment_found', 'Environment Item was Found In') }}
                            {{ Form::select('environment_found', ['inside' => 'Inside', 'sunny' => 'Sunny',
                            'rain' => 'Rain', 'snow' => 'Snow', 'humid' => 'Humid']) }}<br>

                            {{ Form::label('pos_x', 'Position Found') }}{{ Form::label('pos_y', ' ') }}
                            {{ Form::number('pos_x', null, ['class' => 'form-control','step' => '0.00001', 'readonly']) }}
                            {{ Form::number('pos_y', null, ['class' => 'form-control','step' => '0.00001', 'readonly']) }}
                            <br>

                            {{ Form::label('position_radius', 'Position Radius') }}
                            {{ Form::number('position_radius', null) }}<br>

                            {{ Form::label('position_comment', 'Position Comment') }}
                            {{ Form::text('position_comment') }}<br>

                            {{ Form::label('finder_email', 'Finder\'s Email') }}
                            {{ Form::text('finder_email') }}<br>

                            {{-- Allows admins to remove listings from Limbo --}}
                            @if (Auth::user()->type == 'admin')
                                {{ Form::label('hidden', 'Remove Listing') }}
                                {{ Form::checkbox('hidden') }}<br>
                            @endif

                            {{ Form::submit('Update Item') }}

                            {{ Form::close() }}
                        </div>
                    @else
                        {{-- This is what to display if the user does have editing permission--}}
                        <table class="table table-striped" id="item_table">
                            <tr>
                                <th>Description</th>
                                <td>{{ $item->description }}</td>
                            </tr>
                            <tr>
                                <th>Comment</th>
                                <td>{{ $item->position_comment }}</td>
                            </tr>
                            <tr>
                                <th>Notable Damage</th>
                                <td>{{ $item->notable_damage }}</td>
                            </tr>
                            <tr>
                                <th>Time Entered</th>
                                <td>{{ $item->created_at }}</td>
                            </tr>
                            <tr>
                                <th>Last Updated At</th>
                                <td>{{ $item->updated_at }}</td>
                            </tr>
                        </table>
                    @endif

                </div>
                @if (Auth::user())
                    <div class="card">
                        <div class="card-header">Claim Item</div>
                        <div class="card-body">
                            <?php
                            $user_claimed = False;
                            foreach ($item->claims as $claim) {
                                $user_claimed = $claim->user->id == Auth::user()->id;
                            }
                            ?>

                            @if (!$user_claimed)
                                {{ Form::model($item, array('url' => 'claim_item', 'method' => 'POST')) }}

                                {{ Form::hidden('item_id', $item->id) }}

                                {{ Form::label('claim_note', 'Claim Note') }}
                                {{ Form::text('claim_note') }}<br>

                                {{ Form::submit('Claim Item') }}

                                {{ Form::close() }}
                            @else
                                {{ 'You have already claimed this item' }}
                            @endif
                        </div>
                    </div>
                @endif
            </div>
            <div class="col-md-6">
                @if ($authenticated)
                    <h3 style="text-align: center">Move the marker to where the item was found</h3>
                @endif
                <div id="mapids" style="height: 360px; width: 100%;"></div>
                <script>
                    var map = L.map('mapids').setView([41.72212, -73.93417], 15);

                    L.tileLayer('https://api.tiles.mapbox.com/v4/{id}/{z}/{x}/{y}.png?access_token={accessToken}', {
                        attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors, <a href="https://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, Imagery © <a href="https://www.mapbox.com/">Mapbox</a>',
                        maxZoom: 18,
                        id: 'mapbox.streets',
                        accessToken: 'pk.eyJ1Ijoid2lsbGlhbWtsdWdlIiwiYSI6ImNqbW04eXB5dzBna2szcW83ajdlb2xpcmwifQ.RdkpVNHpUdMLV-2GJlTGTQ'
                    }).addTo(map);

                    marker = new L.marker([41.72212, -73.93417], {draggable: 'true'});
                    map.addLayer(marker);
                    document.getElementById("pos_x").value = marker.getLatLng().lat;
                    document.getElementById("pos_y").value = marker.getLatLng().lng;

                    marker.on('dragend', function (event) {
                        var marker = event.target;
                        var position = marker.getLatLng();
                        marker.setLatLng(new L.LatLng(position.lat, position.lng), {draggable: 'true'});
                        map.panTo(new L.LatLng(position.lat, position.lng))
                        document.getElementById("pos_x").value = marker.getLatLng().lat;
                        document.getElementById("pos_y").value = marker.getLatLng().lng;
                    });
                </script>
            </div>
        </div>
    </div>
@endsection
