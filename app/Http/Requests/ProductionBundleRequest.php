<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class ProductionBundleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        $productionBundle = $this->route('production_bundle') ?? $this->route('bundle');

        return [
            'bundle_no' => [
                'required',
                'string',
                'max:255',
                Rule::unique('production_bundles', 'bundle_no')->ignore($productionBundle),
            ],
            'buyer_id' => ['required', 'exists:buyers,id'],
            'style_id' => ['required', 'exists:styles,id'],
            'color' => ['required', 'string', 'max:100'],
            'size' => ['required', 'string', 'max:50'],
            'line_id' => ['required', 'exists:sewing_lines,id'],
            'quantity' => ['required', 'integer', 'min:1'],
            'completed_qty' => ['required', 'integer', 'min:0', 'lte:quantity'],
            'rejected_qty' => ['required', 'integer', 'min:0', 'lte:quantity'],
            'operator_name' => ['nullable', 'string', 'max:150'],
            'production_date' => ['required', 'date', 'before_or_equal:today'],
            'remarks' => ['nullable', 'string'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'bundle_no.unique' => 'This bundle number is already in use.',
            'completed_qty.lte' => 'The completed quantity cannot be greater than the quantity.',
            'rejected_qty.lte' => 'The rejected quantity cannot be greater than the quantity.',
            'production_date.before_or_equal' => 'The production date cannot be in the future.',
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator): void {
            $completedQty = $this->integer('completed_qty');
            $rejectedQty = $this->integer('rejected_qty');
            $quantity = $this->integer('quantity');

            if ($completedQty + $rejectedQty > $quantity) {
                $validator->errors()->add(
                    'completed_qty',
                    'The completed and rejected quantities together cannot be greater than the quantity.'
                );
            }
        });
    }
}
