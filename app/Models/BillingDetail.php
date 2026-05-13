<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BillingDetail extends Model
{
    protected $fillable = [
        'order_id',
        'rfc',
        'business_name',
        'tax_regime',
        'postal_code',
        'fiscal_address',
        'cfdi_usage',
        'invoice_pdf_path',
        'invoice_status',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
