@extends('layouts.app')

@section('content')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
    <script type="text/javascript">
        $(function () {
            $('table.clickable-table').on("click", "tr.table-tr", function () {
                window.location = $(this).data("url");
            });
        });

        // $('#inputbox').keyup(function() {
        // $(this).closest('form').submit();
        // });
    </script>

    <div class="row">
        <div class="container">
            <form action="{{ url('search') }}" method="get">
                <div class="form-group">
                    <input
                            type="text"
                            name="q"
                            class="form-control"
                            placeholder="Search..."
                            value="{{ request('q') }}"
                    />
                </div>
                <script>
                    $('.form-control').on('input', function() {
                        $(this).closest('form').submit();
                    });
                </script>
            </form>
        </div>
    </div>
    <div class="container row">
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
