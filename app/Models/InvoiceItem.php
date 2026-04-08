<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InvoiceItem extends Model
{
    protected $table = 'invoice_items';

    protected $fillable = [
        'invoice_id',
        'card_api_id',
        'name',
        'quantity',
        'price',
        'total',
    ];

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    public function card()
    {
        return $this->belongsTo(Card::class, 'card_api_id', 'api_id');
    }
}
