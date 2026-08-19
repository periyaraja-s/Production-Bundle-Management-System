@extends('layouts.app')

@section('title', 'Production Bundles')

@section('content')
    @php
        $sortUrl = function (string $column) use ($sort, $direction): string {
            $nextDirection = $sort === $column && $direction === 'asc' ? 'desc' : 'asc';

            return request()->fullUrlWithQuery(['sort' => $column, 'direction' => $nextDirection, 'page' => null]);
        };
        $sortIndicator = function (string $column) use ($sort, $direction): string {
            return $sort === $column ? ($direction === 'asc' ? ' ▲' : ' ▼') : '';
        };
    @endphp

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h3 mb-0">Production Bundles</h1>
        <a href="{{ route('production-bundles.create') }}" class="btn btn-primary">Create Bundle</a>
    </div>

    <form method="GET" action="{{ route('production-bundles.index') }}" class="card shadow-sm mb-3">
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-4">
                    <label for="search" class="form-label">Search</label>
                    <input id="search" name="search" type="search" class="form-control" value="{{ request('search') }}" placeholder="Bundle, buyer, style, operator, or color">
                </div>
                <div class="col-md-4">
                    <label for="buyer_id" class="form-label">Buyer</label>
                    <select id="buyer_id" name="buyer_id" class="form-select">
                        <option value="">All buyers</option>
                        @foreach ($buyers as $buyer)
                            <option value="{{ $buyer->id }}" @selected((string) request('buyer_id') === (string) $buyer->id)>{{ $buyer->buyer_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="style_id" class="form-label">Style</label>
                    <select id="style_id" name="style_id" class="form-select">
                        <option value="">All styles</option>
                        @foreach ($styles as $style)
                            <option value="{{ $style->id }}" @selected((string) request('style_id') === (string) $style->id)>{{ $style->style_no }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="line_id" class="form-label">Sewing Line</label>
                    <select id="line_id" name="line_id" class="form-select">
                        <option value="">All lines</option>
                        @foreach ($sewingLines as $sewingLine)
                            <option value="{{ $sewingLine->id }}" @selected((string) request('line_id') === (string) $sewingLine->id)>{{ $sewingLine->line_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="date_from" class="form-label">Date From</label>
                    <input id="date_from" name="date_from" type="date" class="form-control" value="{{ request('date_from') }}">
                </div>
                <div class="col-md-3">
                    <label for="date_to" class="form-label">Date To</label>
                    <input id="date_to" name="date_to" type="date" class="form-control" value="{{ request('date_to') }}">
                </div>
                <div class="col-md-2">
                    <label for="per_page" class="form-label">Per Page</label>
                    <select id="per_page" name="per_page" class="form-select">
                        @foreach ([20, 50, 100] as $option)
                            <option value="{{ $option }}" @selected($perPage === $option)>{{ $option }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4 d-flex align-items-end gap-2">
                    <button type="submit" class="btn btn-primary">Apply</button>
                    <a href="{{ route('production-bundles.index') }}" class="btn btn-outline-secondary">Clear</a>
                </div>
            </div>
        </div>
    </form>

    <div class="card shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th><a class="link-dark text-decoration-none" href="{{ $sortUrl('bundle_no') }}">Bundle No.{{ $sortIndicator('bundle_no') }}</a></th>
                        <th><a class="link-dark text-decoration-none" href="{{ $sortUrl('buyer') }}">Buyer{{ $sortIndicator('buyer') }}</a></th>
                        <th><a class="link-dark text-decoration-none" href="{{ $sortUrl('style') }}">Style{{ $sortIndicator('style') }}</a></th>
                        <th>Color / Size</th>
                        <th>Line</th>
                        <th>Operator</th>
                        <th><a class="link-dark text-decoration-none" href="{{ $sortUrl('quantity') }}">Quantity{{ $sortIndicator('quantity') }}</a></th>
                        <th>Balance</th>
                        <th><a class="link-dark text-decoration-none" href="{{ $sortUrl('efficiency') }}">Efficiency{{ $sortIndicator('efficiency') }}</a></th>
                        <th>Rejection</th>
                        <th><a class="link-dark text-decoration-none" href="{{ $sortUrl('production_date') }}">Date{{ $sortIndicator('production_date') }}</a></th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($productionBundles as $productionBundle)
                        @php
                            $balance = $productionBundle->quantity - $productionBundle->completed_qty - $productionBundle->rejected_qty;
                            $efficiency = $productionBundle->quantity ? ($productionBundle->completed_qty / $productionBundle->quantity) * 100 : 0;
                            $rejection = $productionBundle->quantity ? ($productionBundle->rejected_qty / $productionBundle->quantity) * 100 : 0;
                        @endphp
                        <tr>
                            <td>{{ $productionBundle->bundle_no }}</td>
                            <td>{{ $productionBundle->buyer->buyer_name }}</td>
                            <td>{{ $productionBundle->style->style_no }}</td>
                            <td>{{ $productionBundle->color }} / {{ $productionBundle->size }}</td>
                            <td>{{ $productionBundle->sewingLine->line_name }}</td>
                            <td>{{ $productionBundle->operator_name ?: '—' }}</td>
                            <td>{{ $productionBundle->quantity }}</td>
                            <td>{{ $balance }}</td>
                            <td>{{ number_format($efficiency, 2) }}%</td>
                            <td>{{ number_format($rejection, 2) }}%</td>
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
                            <td colspan="13" class="text-center py-4 text-muted">No production bundles match your search or filters.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($productionBundles->hasPages())
            <div class="card-footer">
                {{ $productionBundles->links() }}
            </div>
        @endif
    </div>
@endsection
