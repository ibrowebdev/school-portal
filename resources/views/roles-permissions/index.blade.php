@extends('layouts.master')
@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <x-page-header title="Roles & Permissions" :breadcrumbs="[
        ['label' => 'Dashboard', 'url' => route('home')],
        ['label' => 'Roles & Permissions', 'url' => '#']
    ]" />

    <div class="bg-white rounded-lg shadow">
        <div class="p-6">
            <livewire:role-permission-manager />
        </div>
    </div>
</div>
@endsection
