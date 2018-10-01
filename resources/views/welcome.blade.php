@extends('layouts.app')

@section('content')
    <div class="flex-center position-ref full-height content">
        <div class="laravel-style-bois">
            <h1>{{ config('app.name') }}</h1>
            <h3>Lost and Found Done Right</h3>
            <div class="row">
                {{--<div class="col-md-6">--}}
                    <table class="table table-striped">
                        <thead>
                        <tr>
                            <th scope="col">Item ID</th>
                            <th scope="col">Description</th>
                            <th scope="col">Time Entered</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($items as $item)
                            <tr>
                                <th scope="row"><a href="/item/{{ $item->id }}">{{ $item->id }}</a></th>
                                <td>{{ $item->description }}</td>
                                <td>{{ $item->created_at }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                {{--</div>--}}
                {{--<div class="col-md-6">2</div>--}}
            </div>
        </div>
    </div>
@endsection
