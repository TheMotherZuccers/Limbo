@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <?php $authenticated = !Auth::guest() && (Auth::user()->type == 'admin') ?>
                    @if ($authenticated)
                        <div class="card-header">Update a User</div>

                        <div class="card-body">
                            {{ Form::model($user, array('url' => 'update_user', 'method' => 'PUT')) }}

                            {{ Form::label('id', 'ID') }}
                            {{ Form::number('id', null, ['readonly']) }}<br>

                            {{ Form::label('name', 'Name') }}
                            {{ Form::text('name') }}<br>

                            {{-- Allows admins to remove listings from Limbo --}}
                            @if (Auth::user()->type == 'admin')
                                {{ Form::label('type', 'User Type') }}
                                {{ Form::text('type') }}<br>
                            @endif

                            {{ Form::submit('Update User') }}

                            {{ Form::close() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
