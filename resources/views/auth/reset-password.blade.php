@extends('layouts.app')

@section('title', 'Reset Password')

@section('content')
    <livewire:auth.reset-password :token="$token" :email="request()->query('email', '')" />
@endsection
