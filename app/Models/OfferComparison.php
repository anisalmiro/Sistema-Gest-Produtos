<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OfferComparison extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'comparison_date',
        'selected_offer_id',
        'criteria_notes',
        'comparison_criteria'
    ];

    protected $casts = [
        'comparison_date' => 'datetime',
        'comparison_criteria' => 'array'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function selectedOffer()
    {
        return $this->belongsTo(Offer::class, 'selected_offer_id');
    }
}
