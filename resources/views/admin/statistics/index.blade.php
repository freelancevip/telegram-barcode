@extends('layouts.admin')
@section('content')

    <div class="card">
        <div class="card-header">
            Statistics
        </div>

        <div class="card-body">
            <p>Total users: <b>{{ $total }}</b></p>
            <p>For week: <b>{{ $weekCount }}</b></p>
        </div>
    </div>

@endsection

