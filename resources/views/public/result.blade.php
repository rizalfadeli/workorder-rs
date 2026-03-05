@extends('layout')

@section('content')

<div class="max-w-3xl mx-auto py-16">

    <div class="bg-white shadow rounded-xl p-8">

        <h2 class="text-xl font-bold mb-6">
            Detail Work Order
        </h2>

        <p><b>No WO:</b> {{ $workOrder->code }}</p>
        <p><b>Lokasi:</b> {{ $workOrder->location }}</p>
        <p><b>Item:</b> {{ $workOrder->item_name }}</p>
        <p><b>Status:</b> {{ strtoupper($workOrder->status) }}</p>

    </div>

</div>

@endsection