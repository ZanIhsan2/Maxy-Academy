<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StorePurchaseOrderRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return optional($this->user())->can('manage-purchase') ?? false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'no_order'           => 'required',
            'tanggal_dibutuhkan' => 'required|date',
            'm_vendor_id1'       => 'required|exists:m_vendor,id',
            'items'              => 'required|array|min:1',
            'items.*.m_barang_sku' => 'required',
            'items.*.kuantitas'  => 'required|integer|min:1',
            'items.*.harga_unit' => 'required|numeric|min:0',
        ];
    }
}