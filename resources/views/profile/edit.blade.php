@extends('layouts.panel')

@section('title', 'Mi Perfil')
@section('subtitle', 'Administra la información de tu cuenta y seguridad')

@section('content')
<div class="max-w-5xl mx-auto space-y-6">
    <div class="glass-card rounded-[2rem] p-6 sm:p-8 border border-white/80 shadow-sm">
        <div class="max-w-xl">
            @include('profile.partials.update-profile-information-form')
        </div>
    </div>

    <div class="glass-card rounded-[2rem] p-6 sm:p-8 border border-white/80 shadow-sm">
        <div class="max-w-xl">
            @include('profile.partials.update-password-form')
        </div>
    </div>

    <div class="glass-card rounded-[2rem] p-6 sm:p-8 border border-white/80 shadow-sm">
        <div class="max-w-xl">
            @include('profile.partials.delete-user-form')
        </div>
    </div>
</div>
@endsection
