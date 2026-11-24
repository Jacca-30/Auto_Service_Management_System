<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int $repair_order_id
 * @property int|null $performed_by
 * @property string $findings
 * @property string|null $recommendations
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @property-read RepairOrder $repairOrder
 * @property-read User|null $performedBy
 */
class Diagnostic extends Model
{
    use HasFactory;

    protected $fillable = [
        'repair_order_id',
        'performed_by',
        'findings',
        'recommendations',
    ];

    public function repairOrder(): BelongsTo
    {
        return $this->belongsTo(RepairOrder::class);
    }

    public function performedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'performed_by');
    }
}
