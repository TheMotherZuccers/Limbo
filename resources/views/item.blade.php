@extends('layouts.app')

@section('content')
    <style>
        #item-claim-body tr:hover {
            background-color: #ccc;
        }

        #item-claim-body td:hover {
            cursor: pointer;
        }
    </style>

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
                        {{-- This is what to display if the user does not have editing permission--}}
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
                @if (!$item->claimed && Auth::user())
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
                <div class="row">
                    @if ($authenticated)
                        <h3 style="text-align: center">Move the marker to where the item was found</h3>
                    @endif
                    <div id="mapids" style="height: 360px; width: 100%;"></div>
                </div>

                @if (!$item->claimed && !Auth::guest() && Auth::user()->type == 'admin')
                    <div class="row">
                        <table class="table table-striped" id="item_table">
                            <thead>
                            <tr>
                                <th scope="col">User</th>
                                <th scope="col">Comment</th>
                                <th scope="col">Last Updated At</th>
                            </tr>
                            </thead>
                            <tbody id="item-claim-body">
                            @foreach ($item->claims as $claim)
                                <tr>
                                    <td style="display:none;">{{ $claim->id }}</td>
                                    <td scope="row">{{ $claim->user->name }}</td>
                                    <td>{{ $claim->comment }}</td>
                                    <td>{{ $item->updated_at }}</td>
                                </tr>
                            @endforeach
                            {{--<caption align="bottom">--}}
                            {{--{{ $items->links() }}--}}
                            {{--</caption>--}}
                            </tbody>
                        </table>
                        {{ Form::model($item, array('url' => 'approve_claim', 'method' => 'POST', 'name' => 'claim')) }}

                        {{ Form::hidden('item_id', $item->id) }}

                        {{ Form::hidden('claim_id') }}

                        {{ Form::submit('Approve Claim') }}

                        {{ Form::close() }}

                        <script>
                            function reset_row_colors() {
                                var table = document.getElementById("item-claim-body");
                                var rows = table.getElementsByTagName("tr");
                                for (i = 0; i < rows.length; i++) {
                                    rows[i].style.backgroundColor = '';
                                }
                            }

                            function addRowHandlers() {
                                var table = document.getElementById("item-claim-body");
                                var rows = table.getElementsByTagName("tr");
                                for (i = 0; i < rows.length; i++) {
                                    var currentRow = table.rows[i];
                                    var createClickHandler = function (row) {
                                        return function () {
                                            reset_row_colors();
                                            row.style.backgroundColor = '#ccc';
                                            var cell = row.getElementsByTagName("td")[0];
                                            console.log(document.claim.elements['claim_id'].value);
                                            document.claim.elements['claim_id'].value = cell.innerText.trim();
                                        };
                                    };
                                    currentRow.onclick = createClickHandler(currentRow);
                                }
                            }
                            addRowHandlers();
                        </script>
                    </div>
                @endif

                <script>
                    var map = L.map('mapids').setView([41.72212, -73.93417], 15);

                    L.tileLayer('https://api.tiles.mapbox.com/v4/{id}/{z}/{x}/{y}.png?access_token={accessToken}', {
                        attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors, <a href="https://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, Imagery © <a href="https://www.mapbox.com/">Mapbox</a>',
                        maxZoom: 18,
                        id: 'mapbox.streets',
                        accessToken: 'pk.eyJ1Ijoid2lsbGlhbWtsdWdlIiwiYSI6ImNqbW04eXB5dzBna2szcW83ajdlb2xpcmwifQ.RdkpVNHpUdMLV-2GJlTGTQ'
                    }).addTo(map);

                    marker = new L.marker([{{  $item->position_found->getLat() }}, {{ $item->position_found->getLng() }}], {draggable: 'true'});
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
