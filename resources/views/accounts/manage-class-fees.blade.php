@extends('layouts.master')
@section('content')
<div class="space-y-6">
    <x-page-header title="Manage Class Fees" :breadcrumbs="[
        ['label' => 'Dashboard', 'url' => route('home')],
        ['label' => 'Manage Class Fees', 'url' => '#']
    ]" />

    <div class="bg-white rounded-lg shadow-sm border border-gray-100">
        <div class="p-6">
            <livewire:manage-class-fees />
        </div>
    </div>
</div>
@endsection
