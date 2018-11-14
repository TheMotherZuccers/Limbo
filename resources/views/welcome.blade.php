@extends('layouts.app')

@section('content')
    <style>
        #item-table-body tr:hover {
            background-color: #ccc;
        }

        #item-table-body td:hover {
            cursor: pointer;
        }
    </style>

    <script src="http://code.jquery.com/jquery-1.9.1.min.js"></script>
    <script src="js/jquery-tableoverflow.js"></script>
    <script>
        $(function () {
            $('table').tableoverflow();
        });
    </script>

    <div class="flex-center position-ref full-height content">
        <div class="laravel-style-bois">
            <h1>{{ config('app.name') }}</h1>
            <h3>Lost and Found Done Right</h3>
            <div class="row justify-content-center" style="height: 70%">
                <div class="col-5">
                    <h4>Click on any item to see its location on the map</h4>
                    <table class="table table-striped" id="item_table">
                        <thead>
                        <tr>
                            <th scope="col">Item ID</th>
                            <th scope="col">Description</th>
                            <th scope="col">Time Entered</th>
                        </tr>
                        </thead>
                        <tbody id="item-table-body">
                        @foreach($items as $item)
                            <tr>
                                <td scope="row">{{$item->id}}</td>
                                {{-- TODO limit the character's based on the size of the text area --}}
                                <td>{{ $item->description }}</td>
                                <td>{{ $item->created_at }}</td>
                            </tr>
                        @endforeach
                        <caption align="bottom">
                            {{ $items->links() }}
                        </caption>
                        </tbody>
                    </table>

                </div>
                <div class="col-5">
                    {{-- Starts as hidden so our magic JS can make it visible when it's loaded --}}
                    <div id="mapid" style="height: 100%; width: 100%; display: none;"></div>
                </div>
            </div>
        </div>
    </div>

    <script>
        {{-- So this works...basically it invalidates the size of the map after the page is loaded --}}
        setTimeout(function () {
            document.getElementById("mapid").style.display = "block";
            mymap.invalidateSize();
        }, 400);

        var mymap = L.map('mapid').setView([41.72212, -73.93417], 14);

        L.tileLayer('https://api.tiles.mapbox.com/v4/{id}/{z}/{x}/{y}.png?access_token={accessToken}', {
            attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors, <a href="https://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, Imagery © <a href="https://www.mapbox.com/">Mapbox</a>',
            maxZoom: 18,
            id: 'mapbox.streets',
            accessToken: 'pk.eyJ1Ijoid2lsbGlhbWtsdWdlIiwiYSI6ImNqbW04eXB5dzBna2szcW83ajdlb2xpcmwifQ.RdkpVNHpUdMLV-2GJlTGTQ'
        }).addTo(mymap);

        // Dictionary for storing the popups to be used with table clicks
        var popupDict = {};

                @foreach($items as $item)
        var marker = L.marker([{{  $item->position_found->getLat() }}, {{ $item->position_found->getLng() }}]).addTo(mymap);
        marker.bindPopup("<b>{{ $item->description }}</b><br>Time Found: {{ $item->created_at }}<br><a href=\'/item/{{ $item->id }}\'>Item Information</a><br>");
        popupDict["{{ $item->id }}"] = marker;

        @endforeach

        function addRowHandlers() {
            var table = document.getElementById("item-table-body");
            var rows = table.getElementsByTagName("tr");
            for (i = 0; i < rows.length; i++) {
                var currentRow = table.rows[i];
                var createClickHandler = function (row) {
                    return function () {
                        var cell = row.getElementsByTagName("td")[0];
                        console.log(cell);
                        var id = cell.innerText.trim();
                        console.log(id);
                        popupDict[id].openPopup();
                    };
                };
                currentRow.onclick = createClickHandler(currentRow);
            }
        }

        addRowHandlers();

    </script>
@endsection
