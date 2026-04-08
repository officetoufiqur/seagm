<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $fillable = [
        'user_id',
        'payment_id',
        'invoice_number',
        'amount',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function payment()
    {
        return $this->belongsTo(Payment::class);
    }

    public function items()
    {
        return $this->hasMany(InvoiceItem::class);
    }

    public function toPdf()
    {
        $this->load(['user', 'payment', 'items']);

        $items = $this->items->map(function ($item, $index) {
            return [
                'no' => $index + 1,
                'name' => $item->card->name ?? $item->name,
                'quantity' => $item->quantity,
                'price' => $item->price,
                'total' => $item->total,
            ];
        });

        return [
            'invoice' => $this,
            'user' => $this->user,
            'payment' => $this->payment,
            'items' => $items,
        ];
    }
}
