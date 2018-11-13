@extends('layouts.app')

@section('content')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
    <script type="text/javascript">
        $(function() {
            $('table.clickable-table').on("click", "tr.table-tr", function() {
                window.location = $(this).data("url");
            });
        });
    </script>

    <style>
        #item_table tr {
            border-top: 1px solid #fff;
        }
        #item-table-body tr:hover {
            background-color: #ccc;
        }
        #item-table-body th, #example td {
            padding: 3px 5px;
        }
        #item-table-body td:hover {
            cursor: pointer;
        }
    </style>

    <div>
        <h4>Click on any item edit it</h4>
        <table class="table table-striped clickable-table" id="item_table">
            <thead>
            <tr>
                <th scope="col">Description</th>
                <th scope="col">Date Entered</th>
                <th scope="col">Last Updated</th>
                <th scope="col">Comment</th>
                <th scope="col">Reporter's Email</th>
            </tr>
            </thead>
            <tbody id="item-table-body">
            @foreach($items as $item)
                <tr class='table-tr' data-url="/item/{{ $item->id }}">
                    <td>{{ $item->description }}</td>
                    <td>{{ $item->created_at }}</td>
                    <td>{{ $item->updated_at }} </td>
                    <td>{{ $item->position_comment }}</td>
                    <td>{{ $item->finder_email }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>

@endsection
