@extends('layouts.app')

@section('content')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
    <script src="js/jquery-tableoverflow.js"></script>
    <script type="text/javascript">
        $(function () {
            $('table').tableoverflow();
        });

        $(function () {
            $('table.clickable-table').on("click", "tr.table-tr", function () {
                window.location = $(this).data("url");
            });
        });
    </script>

    <style>
        #item-table-body tr:hover {
            background-color: #ccc;
        }

        #item-table-body td:hover {
            cursor: pointer;
        }
    </style>

    <div class="row">
        <div class="container">
            <form action="{{ url('search') }}" method="get" id="input-form">
                <div class="form-group">
                    <input type="text" name="q" class="form-control" placeholder="Search..." autocomplete="off"
                           id="input_box"/>
                </div>
                <script>
                    // jQuery, bind an event handler or use some other way to trigger ajax call.
                    $('#input-form').submit(function (event) {
                        event.preventDefault();
                        $.ajax({
                            url: '{{ config('app.url', 'limbo.loc') }}/searchastype',
                            type: 'GET',
                            data: {
                                'q': $('#input_box').val()
                            },
                            // Remember that you need to have your csrf token included
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
                                        ).appendTo('#item_table');
                                    });
                                    // redo the table overflow fix with the new items
                                    $('table').tableoverflow();
                                });
                            },
                            error: function (_response) {
                                console.log(_response);
                            }
                        });
                    });

                    {{-- Submits the form on input and on page loads sets value and focus --}}
                    $('#input_box').on('input', function () {
                        var input_val = document.getElementById('input_box').value;
                        if (!/\s+$/.test(input_val)) {
                            $(this).closest('form').submit();
                        }
                    }).focus().val("{{ request('q') }}");
                </script>
            </form>
        </div>
    </div>
    <div class="container justify-content-center" style="height: 70%">
        <div class="panel panel-primary">
            <div class="panel-heading">
                Click on any item view it
                {{--<small>({{ $items->count() }} items)</small>--}}
            </div>
            <div class="panel-body">
                <table class="table table-striped clickable-table" id="item_table">
                    <thead>
                    <tr>
                        <th scope="col">Description</th>
                        <th scope="col">Date Entered</th>
                        <th scope="col">Last Updated</th>
                        <th scope="col">Comment</th>
                    </tr>
                    </thead>
                    <tbody id="item-table-body">

                    @forelse($items as $item)
                        <tr class='table-tr' data-url="/item/{{ $item->id }}">
                            <td>{{ $item->description }}</td>
                            <td>{{ $item->created_at }}</td>
                            <td>{{ $item->updated_at }} </td>
                            <td>{{ $item->position_comment }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td>No items found</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
