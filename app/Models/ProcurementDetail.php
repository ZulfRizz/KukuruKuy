<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProcurementDetail extends Model {
    use HasFactory;

    protected $fillable = [
        'procurement_id',
        'ingredient_id',
        'quantity',
    ];

    public $timestamps = false;

    protected $casts = [
        'quantity' => 'decimal:2',
    ];

    public function procurement(): BelongsTo {
        return $this->belongsTo(Procurement::class);
    }

    public function ingredient(): BelongsTo {
        return $this->belongsTo(Ingredient::class);
    }
}
