@extends('layouts.app')

@section('title', 'Production Bundle Details')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h3 mb-0">Bundle {{ $productionBundle->bundle_no }}</h1>
        <div>
            <a href="{{ route('production-bundles.edit', $productionBundle) }}" class="btn btn-primary">Edit</a>
            <a href="{{ route('production-bundles.index') }}" class="btn btn-outline-secondary">Back to List</a>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <dl class="row mb-0">
                <dt class="col-sm-3">Buyer</dt><dd class="col-sm-9">{{ $productionBundle->buyer->buyer_name }}</dd>
                <dt class="col-sm-3">Style</dt><dd class="col-sm-9">{{ $productionBundle->style->style_no }}</dd>
                <dt class="col-sm-3">Color</dt><dd class="col-sm-9">{{ $productionBundle->color }}</dd>
                <dt class="col-sm-3">Size</dt><dd class="col-sm-9">{{ $productionBundle->size }}</dd>
                <dt class="col-sm-3">Sewing Line</dt><dd class="col-sm-9">{{ $productionBundle->sewingLine->line_name }}</dd>
                <dt class="col-sm-3">Quantity</dt><dd class="col-sm-9">{{ $productionBundle->quantity }}</dd>
                <dt class="col-sm-3">Completed Quantity</dt><dd class="col-sm-9">{{ $productionBundle->completed_qty }}</dd>
                <dt class="col-sm-3">Rejected Quantity</dt><dd class="col-sm-9">{{ $productionBundle->rejected_qty }}</dd>
                <dt class="col-sm-3">Operator</dt><dd class="col-sm-9">{{ $productionBundle->operator_name ?: '—' }}</dd>
                <dt class="col-sm-3">Production Date</dt><dd class="col-sm-9">{{ $productionBundle->production_date->format('d M Y') }}</dd>
                <dt class="col-sm-3">Remarks</dt><dd class="col-sm-9">{{ $productionBundle->remarks ?: '—' }}</dd>
            </dl>
        </div>
    </div>
@endsection
