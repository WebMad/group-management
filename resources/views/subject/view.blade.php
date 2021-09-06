@extends('layouts.app')

@section('content')
    <div class="col-md-10 m-auto row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">{{ __('Предметы') }}</div>
                <div class="card-body">
                    <subject-list/>
                </div>
            </div>
        </div>
    </div>
@endsection
