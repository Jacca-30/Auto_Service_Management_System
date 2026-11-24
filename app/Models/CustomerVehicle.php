<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property int $customer_id
 * @property int $vehicle_id
 * @property string|null $vin
 * @property string|null $license_plate
 * @property string|null $reg_cert_number
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Customer $customer
 * @property-read Vehicle $vehicle
 */

class CustomerVehicle extends Model
{
    use HasFactory;
    protected $fillable = [
        'customer_id',
        'vehicle_id',
        'vin',
        'license_plate',
        'reg_cert_number',
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function repairOrders(): HasMany
    {
        return $this->hasMany(RepairOrder::class);
    }

    public function customerVehicles(): HasMany
    {
        return $this->hasMany(CustomerVehicle::class);
    }
}
