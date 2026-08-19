@php($bundle = $productionBundle ?? null)

@if ($errors->any())
    <div class="alert alert-danger">Please correct the highlighted fields and try again.</div>
@endif

<div id="form-message" class="d-none" role="alert" aria-live="polite"></div>

<div class="row g-3">
    <div class="col-md-4">
        <label for="bundle_no" class="form-label">Bundle No.</label>
        <input id="bundle_no" name="bundle_no" type="text" class="form-control @error('bundle_no') is-invalid @enderror" value="{{ old('bundle_no', $bundle?->bundle_no) }}" required>
        @error('bundle_no')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-4">
        <label for="buyer_id" class="form-label">Buyer</label>
        <select id="buyer_id" name="buyer_id" class="form-select @error('buyer_id') is-invalid @enderror" required>
            <option value="">Select buyer</option>
            @foreach ($buyers as $buyer)
                <option value="{{ $buyer->id }}" @selected((string) old('buyer_id', $bundle?->buyer_id) === (string) $buyer->id)>{{ $buyer->buyer_name }}</option>
            @endforeach
        </select>
        @error('buyer_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-4">
        <label for="style_id" class="form-label">Style</label>
        <select id="style_id" name="style_id" class="form-select @error('style_id') is-invalid @enderror" required>
            <option value="">Select style</option>
            @foreach ($styles as $style)
                <option value="{{ $style->id }}" @selected((string) old('style_id', $bundle?->style_id) === (string) $style->id)>{{ $style->style_no }} ({{ $style->buyer->buyer_name }})</option>
            @endforeach
        </select>
        @error('style_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-4">
        <label for="color" class="form-label">Color</label>
        <input id="color" name="color" type="text" class="form-control @error('color') is-invalid @enderror" value="{{ old('color', $bundle?->color) }}" required>
        @error('color')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-4">
        <label for="size" class="form-label">Size</label>
        <input id="size" name="size" type="text" class="form-control @error('size') is-invalid @enderror" value="{{ old('size', $bundle?->size) }}" required>
        @error('size')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-4">
        <label for="line_id" class="form-label">Sewing Line</label>
        <select id="line_id" name="line_id" class="form-select @error('line_id') is-invalid @enderror" required>
            <option value="">Select sewing line</option>
            @foreach ($sewingLines as $sewingLine)
                <option value="{{ $sewingLine->id }}" @selected((string) old('line_id', $bundle?->line_id) === (string) $sewingLine->id)>{{ $sewingLine->line_name }}</option>
            @endforeach
        </select>
        @error('line_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-4">
        <label for="quantity" class="form-label">Quantity</label>
        <input id="quantity" name="quantity" type="number" min="0" class="form-control @error('quantity') is-invalid @enderror" value="{{ old('quantity', $bundle?->quantity ?? 0) }}" required>
        @error('quantity')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-4">
        <label for="completed_qty" class="form-label">Completed Quantity</label>
        <input id="completed_qty" name="completed_qty" type="number" min="0" class="form-control @error('completed_qty') is-invalid @enderror" value="{{ old('completed_qty', $bundle?->completed_qty ?? 0) }}" required>
        @error('completed_qty')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-4">
        <label for="rejected_qty" class="form-label">Rejected Quantity</label>
        <input id="rejected_qty" name="rejected_qty" type="number" min="0" class="form-control @error('rejected_qty') is-invalid @enderror" value="{{ old('rejected_qty', $bundle?->rejected_qty ?? 0) }}" required>
        @error('rejected_qty')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-12">
        <div class="row g-3 rounded bg-body-tertiary p-3">
            <div class="col-md-4">
                <div class="text-muted small text-uppercase">Balance</div>
                <div id="balance" class="fs-4 fw-semibold">0</div>
            </div>
            <div class="col-md-4">
                <div class="text-muted small text-uppercase">Efficiency</div>
                <div id="efficiency" class="fs-4 fw-semibold">0.00%</div>
            </div>
            <div class="col-md-4">
                <div class="text-muted small text-uppercase">Rejection</div>
                <div id="rejection" class="fs-4 fw-semibold">0.00%</div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <label for="operator_name" class="form-label">Operator Name</label>
        <input id="operator_name" name="operator_name" type="text" class="form-control @error('operator_name') is-invalid @enderror" value="{{ old('operator_name', $bundle?->operator_name) }}">
        @error('operator_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-6">
        <label for="production_date" class="form-label">Production Date</label>
        <input id="production_date" name="production_date" type="date" class="form-control @error('production_date') is-invalid @enderror" value="{{ old('production_date', $bundle?->production_date?->format('Y-m-d')) }}" required>
        @error('production_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-12">
        <label for="remarks" class="form-label">Remarks</label>
        <textarea id="remarks" name="remarks" rows="3" class="form-control @error('remarks') is-invalid @enderror">{{ old('remarks', $bundle?->remarks) }}</textarea>
        @error('remarks')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const form = document.getElementById('production-bundle-form');
        const quantity = document.getElementById('quantity');
        const completedQty = document.getElementById('completed_qty');
        const rejectedQty = document.getElementById('rejected_qty');
        const balance = document.getElementById('balance');
        const efficiency = document.getElementById('efficiency');
        const rejection = document.getElementById('rejection');
        const formMessage = document.getElementById('form-message');
        const submitButton = form.querySelector('[type="submit"]');
        const defaultSubmitText = submitButton.textContent;

        const updateCalculations = () => {
            const quantityValue = Number(quantity.value) || 0;
            const completedValue = Number(completedQty.value) || 0;
            const rejectedValue = Number(rejectedQty.value) || 0;

            balance.textContent = quantityValue - completedValue - rejectedValue;
            efficiency.textContent = quantityValue ? `${((completedValue / quantityValue) * 100).toFixed(2)}%` : '0.00%';
            rejection.textContent = quantityValue ? `${((rejectedValue / quantityValue) * 100).toFixed(2)}%` : '0.00%';
        };

        [quantity, completedQty, rejectedQty].forEach((input) => {
            input.addEventListener('input', updateCalculations);
            input.addEventListener('change', updateCalculations);
        });

        updateCalculations();

        const showMessage = (message, type) => {
            formMessage.textContent = message;
            formMessage.className = `alert alert-${type}`;
        };

        const clearValidationErrors = () => {
            form.querySelectorAll('.is-invalid').forEach((input) => input.classList.remove('is-invalid'));
            form.querySelectorAll('[data-validation-error]').forEach((message) => message.remove());
        };

        const showValidationErrors = (errors) => {
            Object.entries(errors).forEach(([field, messages]) => {
                const input = form.querySelector(`[name="${field}"]`);

                if (!input) {
                    return;
                }

                input.classList.add('is-invalid');
                const message = document.createElement('div');
                message.className = 'invalid-feedback';
                message.dataset.validationError = 'true';
                message.textContent = messages[0];
                input.insertAdjacentElement('afterend', message);
            });
        };

        form.addEventListener('submit', async (event) => {
            event.preventDefault();

            if (submitButton.disabled) {
                return;
            }

            clearValidationErrors();
            formMessage.className = 'd-none';
            submitButton.disabled = true;
            submitButton.textContent = 'Saving...';

            try {
                const response = await fetch(form.action, {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                    },
                    body: new FormData(form),
                });
                const data = await response.json();

                if (response.ok) {
                    showMessage(data.message, 'success');
                    window.location.assign(data.redirect_url);
                    return;
                }

                if (response.status === 422 && data.errors) {
                    showMessage('Please correct the highlighted fields and try again.', 'danger');
                    showValidationErrors(data.errors);
                } else {
                    showMessage(data.message || 'Unable to save the production bundle. Please try again.', 'danger');
                }
            } catch (error) {
                showMessage('Unable to save the production bundle. Please check your connection and try again.', 'danger');
            } finally {
                submitButton.disabled = false;
                submitButton.textContent = defaultSubmitText;
            }
        });
    });
</script>
