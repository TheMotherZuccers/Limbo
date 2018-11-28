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

        var n = 4;

        function replace_table(_response) {
            // Remove current rows from the table and the table from formatting
            $.tableoverflow.removeTable($('#item_table'));

            var parser = new DOMParser();
            var doc = parser.parseFromString(_response, 'text/html');

            var table = doc.getElementById('item_table');

            $('#item_table').replaceWith(table);

            $(".pagination li a").each(function (index, value) {
                value.setAttribute('href', value + '&n=' + n);
            }).click(function (event) {
                console.log('hi');
                event.preventDefault();

                console.log($(this).attr('href'));

                $.ajax({
                    url: $(this).attr('href'),
                    type: 'GET',
                    dataType: 'html',
                    success: function (_response) {
                        replace_table(_response);
                    },
                    error: function (_response) {
                        console.log(_response);
                    }
                });
            });

            // redo the table overflow fix with the new items
            $('table').tableoverflow();
            // band aid
            $(document).scrollTop(0);
        }

        // Runs when the document loads. Does initial responsiveness to page
        $(document).ready(function () {
            $.ajax({
                url: '{{ config('app.url', 'limbo.loc') }}/responsive_pagination',
                type: 'GET',
                data: {
                    'n': n
                },
                dataType: 'html',
                success: function (_response) {
                    replace_table(_response);
                },
                error: function (_response) {
                    console.log(_response);
                }
            });
        });

        // $('.pagination li a').click(function (event) {
        //     console.log('hi');
        //     event.preventDefault();
        //
        //     console.log($(this).attr('href'));
        //
        //     $.ajax({
        //         url: $(this).attr('href'),
        //         type: 'GET',
        //         dataType: 'html',
        //         success: function (_response) {
        //             replace_table(_response);
        //         },
        //         error: function (_response) {
        //             console.log(_response);
        //         }
        //     });
        // }

        // )
        // ;

    </script>

    <div class="flex-center position-ref full-height content">
        <div class="laravel-style-bois">
            <h2>{{ config('app.name') }}</h2>
            <h4>Lost and Found Done Right</h4>
            <div class="row justify-content-center" style="height: 70%">
                {{-- Height being 50% will only matter when the two columns stack --}}
                <div class="col-md-5 col-sm-12" style="height: 50%">
                    <h4>Click on any item to see its location on the map</h4>
                    <table class="table table-striped" id="item_table">
                        <thead>
                        <tr>
                            <th scope="col">Item ID</th>
                            <th scope="col">Description</th>
                            <th scope="col">Time Entered</th>
                        </tr>
                        </thead>
                        {{--<tbody id="item-table-body">--}}
                        {{--@foreach($items as $item)--}}
                            {{--<tr>--}}
                                {{--<td scope="row">{{$item->id}}</td>--}}
                                {{--<td>{{ $item->description }}</td>--}}
                                {{--<td>{{ $item->created_at }}</td>--}}
                            {{--</tr>--}}
                        {{--@endforeach--}}
                        {{--<caption align="bottom">--}}
                            {{--{{ $items->links() }}--}}
                        {{--</caption>--}}
                        {{--</tbody>--}}
                    </table>
                </div>
                <div class="col-md-5 col-sm-12">
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
