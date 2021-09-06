@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-md-10 m-auto row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">{{ __('Расписание') }}</div>
                <div class="card-body">
                    <schedule />
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
