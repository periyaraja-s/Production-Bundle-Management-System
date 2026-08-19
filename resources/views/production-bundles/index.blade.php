@extends('layouts.app')

@section('title', 'Production Bundles')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h3 mb-0">Production Bundles</h1>
        <a href="{{ route('production-bundles.create') }}" class="btn btn-primary">Create Bundle</a>
    </div>

    <div class="card shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>Bundle No.</th>
                        <th>Buyer</th>
                        <th>Style</th>
                        <th>Color / Size</th>
                        <th>Line</th>
                        <th>Quantity</th>
                        <th>Date</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($productionBundles as $productionBundle)
                        <tr>
                            <td>{{ $productionBundle->bundle_no }}</td>
                            <td>{{ $productionBundle->buyer->buyer_name }}</td>
                            <td>{{ $productionBundle->style->style_no }}</td>
                            <td>{{ $productionBundle->color }} / {{ $productionBundle->size }}</td>
                            <td>{{ $productionBundle->sewingLine->line_name }}</td>
                            <td>{{ $productionBundle->quantity }}</td>
                            <td>{{ $productionBundle->production_date->format('d M Y') }}</td>
                            <td class="text-end text-nowrap">
                                <a href="{{ route('production-bundles.show', $productionBundle) }}" class="btn btn-sm btn-outline-secondary">View</a>
                                <a href="{{ route('production-bundles.edit', $productionBundle) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                                <form action="{{ route('production-bundles.destroy', $productionBundle) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-4 text-muted">No production bundles have been created yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
