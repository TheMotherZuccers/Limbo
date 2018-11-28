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

        function replace_table(_response) {
            // Remove current rows from the table and the table from formatting
            $.tableoverflow.removeTable($('#item_table'));

            $(".pagination li a").each(function (index, value) {
                value.setAttribute('href', value + '&n=' + n);
            }).click(function (event) {
                event.preventDefault();
                
                $.ajax({
                    url: $(this).attr('href'),
                    type: 'GET',
                    dataType: 'json',
                    success: function (_response) {
                        // convert string to JSON
                        $(function () {
                            // Remove current rows from the table and the table from formatting
                            $("#item-table-body tr").remove();
                            $.tableoverflow.removeTable($('#item_table'));

                            $.each(_response, function (i, item) {
                                $('<tr class="table-tr" data-url="/item/' + item.id + '">').append(
                                    $('<td>').text(item.description),
                                    $('<td>').text(item.created_at),
                                    $('<td>').text(item.updated_at),
                                    $('<td>').text(item.position_comment)
                                ).appendTo('#item-table-body');
                            });

                            $('#item_table').replaceWith(_response.links());

                            // redo the table overflow fix with the new items
                            $('table').tableoverflow();
                        });
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
                    // addRowHandlers();
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
                        <tbody id="item-table-body">
                        @foreach($items as $item)
                            <tr>
                                <td scope="row">{{$item->id}}</td>
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
    </script>
@endsection
