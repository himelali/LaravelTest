@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('messages.dashboard') }}</div>
                <div class="card-body">
                    {{ __('messages.logged_id') }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
