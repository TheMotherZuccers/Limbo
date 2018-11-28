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
            <form action="{{ url('search') }}" method="get">
                <div class="form-group">
                    <input type="text" name="q" class="form-control" placeholder="Search..." autocomplete="off" id="input_box" />
                </div>
                <script>
                    {{-- Submits the form on input and on page loads sets value and focus --}}
                    $('.form-control').on('input', function() {
                        var input_val = document.getElementById('input_box').value;
                        console.log('input value ' + input_val);
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
                Click on any item view it -
                <small>({{ $items->count() }} items)</small>
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

@endsection
