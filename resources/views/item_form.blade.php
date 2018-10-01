@extends('layouts.app')

@section('content')
    <?php
    use Illuminate\Support\Facades\Auth;
    ?>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Dashboard</div>

                <div class="card-body">

                    {{ Form::open(array('url' => 'item_form')) }}

                    {{ Form::label('description', 'Description') }}
                    {{ Form::text('description') }}<br>

                    {{ Form::label('notable_damage', 'Notable Damage') }}
                    {{ Form::text('notable_damage') }}<br>

                    {{ Form::label('environment_found', 'Environment Item was Found In') }}
                    {{ Form::select('environment_found', ['inside' => 'Inside', 'sunny' => 'Sunny',
                    'rain' => 'Rain', 'snow' => 'Snow', 'humid' => 'Humid']) }}<br>

                    {{ Form::label('pos_x', 'Position Found') }}
                    {{ Form::number('pos_x', null, ['class' => 'form-control','step' => '0.00001']) }}
                    {{ Form::number('pos_y', null, ['class' => 'form-control','step' => '0.00001']) }}<br>

                    {{ Form::label('position_radius', 'Position Radius') }}
                    {{ Form::number('position_radius', null) }}<br>

                    {{ Form::label('position_comment', 'Position Comment') }}
                    {{ Form::text('position_comment') }}<br>

                    {{ Form::label('finder_email', 'Finder\'s Email') }}
                    {{ Form::text('finder_email') }}<br>

                    {{ Form::submit('Add Item') }}

                    {{ Form::close() }}

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
