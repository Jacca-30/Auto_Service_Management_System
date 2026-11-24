<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property string $make
 * @property string $model
 * @property int|null $year
 * @property string|null $fuel_type
 * @property string|null $engine_code
 * @property string|null $engine_type
 * @property int|null $engine_displacement_cm3
 * @property int|null $engine_power_kw
 * @property string|null $transmission_type
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @property-read \Illuminate\Database\Eloquent\Collection<int, CustomerVehicle> $customerVehicles
 */

class Vehicle extends Model
{
    use HasFactory;

    protected $fillable = [
        'make',
        'model',
        'year',
        'fuel_type',
        'engine_code',
        'engine_type',
        'engine_displacement_cm3',
        'engine_power_kw',
        'transmission_type',
    ];

    public function customerVehicles(): HasMany
    {
        return $this->hasMany(CustomerVehicle::class);
    }
}
