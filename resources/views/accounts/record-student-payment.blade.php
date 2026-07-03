@extends('layouts.master')
@section('content')
<div class="space-y-6">
    <x-page-header title="Record Student Payment" :breadcrumbs="[
        ['label' => 'Dashboard', 'url' => route('home')],
        ['label' => 'Record Payment', 'url' => '#']
    ]" />

    <div class="bg-white rounded-lg shadow-sm border border-gray-100">
        <div class="p-6">
            <livewire:record-student-payment />
        </div>
    </div>
</div>
@endsection
