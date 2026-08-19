<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductionBundle extends Model
{
    use HasFactory, SoftDeletes;

    /** @var array<int, string> */
    protected $fillable = [
        'bundle_no',
        'buyer_id',
        'style_id',
        'color',
        'size',
        'line_id',
        'quantity',
        'completed_qty',
        'rejected_qty',
        'operator_name',
        'production_date',
        'remarks',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'production_date' => 'date',
            'quantity' => 'integer',
            'completed_qty' => 'integer',
            'rejected_qty' => 'integer',
        ];
    }

    public function buyer(): BelongsTo
    {
        return $this->belongsTo(Buyer::class);
    }

    public function style(): BelongsTo
    {
        return $this->belongsTo(Style::class);
    }

    public function sewingLine(): BelongsTo
    {
        return $this->belongsTo(SewingLine::class, 'line_id');
    }
}
