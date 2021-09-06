@extends('layouts.app')

@section('content')
    <div class="col-md-10 m-auto row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">{{ __('Преподаватели') }}</div>
                <div class="card-body">
                    <teacher-list/>
                </div>
            </div>
        </div>
    </div>
@endsection
