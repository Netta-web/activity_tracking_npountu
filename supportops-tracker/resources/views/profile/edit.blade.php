@extends('layouts.app')
@section('title', 'Profile Settings')

@section('content')
<div class="max-w-3xl space-y-6">

    <div>
        <h2 class="text-lg font-bold text-gray-900">Profile Settings</h2>
        <p class="text-sm text-gray-400">Manage your account information and security</p>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8">
        <h3 class="text-sm font-bold text-gray-900 mb-5">Profile Information</h3>
        @include('profile.partials.update-profile-information-form')
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8">
        <h3 class="text-sm font-bold text-gray-900 mb-5">Update Password</h3>
        @include('profile.partials.update-password-form')
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-red-100 p-8">
        <h3 class="text-sm font-bold text-red-600 mb-5">Danger Zone</h3>
        @include('profile.partials.delete-user-form')
    </div>
</div>
@endsection
