@extends('layouts.app')

@section('content')
    <div class="flex-center position-ref full-height content">
        <div class="laravel-style-bois">
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
                <script>
                    {{-- Adds the number parameter to the links --}}
                    $(".pagination li a").each(function (index, value){
                        value.setAttribute('href', value + '&n={{ $n  }}');
                    });
                </script>
                </tbody>
            </table>
        </div>
    </div>
@endsection
