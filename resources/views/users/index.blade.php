@extends('layouts.master')
@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <x-page-header title="List Users" :breadcrumbs="[
        ['label' => 'Dashboard', 'url' => route('home')],
        ['label' => 'List Users', 'url' => '#']
    ]" />

    <livewire:users-list />
</div>

@section('script')
    
@endsection
@endsection
