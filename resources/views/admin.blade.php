@extends('layouts.app')

@section('content')
        <div class="title m-b-md">
            You're on an admin only page

            {{-- TODO admin can update status of items --}}
            <div class="col-md-6">
                    <table class="table table-striped" id="item_table">
                        <thead>
                        <tr>
                            <th scope="col">Item ID</th>
                            <th scope="col">Description</th>
                            <th scope="col">Time Entered</th>
                            <th scope="col">Notable Damage</th>
                            <th scope="col">Environment Found</th>
                            <th scope="col">Position X</th>
                            <th scope="col">Position Y</th>
                            <th scope="col">Position Comment</th>
                            <th scope="col">Finder Email</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($items as $item)
                            <tr>
                                <td scope="row">{{ $item->id }}</td>
                                <td>{{ $item->description }}</td>
                                <td>{{ $item->created_at }}</td>
                                <td>{{ $item->notable_damage }}</td>
                                <td>{{ $item->environment_found }}</td>
                                <td>{{ $item->pos_x }}</td>
                                <td>{{ $item->pos_y }}</td>
                                <td>{{ $item->position_comment }}</td>
                                <td>{{ $item->finder_email }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            
            
            {{-- TODO admin can remove items from view --}}

        </div>
@endsection
