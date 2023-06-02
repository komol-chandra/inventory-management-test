<?php

namespace App\Models;

use App\Traits\CreatedUpdatedBy;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    use HasFactory, CreatedUpdatedBy;

    protected $table      = "products";
    protected $primaryKey = "id";
    protected $fillable   = [
        "is_active",
        "is_deleted",
        "name",
        "brand_name",
        "slug",
        "sku",
        "unit",
        "image",
        "short_description",
        "description",
        "created_by",
        "updated_by",
    ];

    public function scopeFullSearch($query, $search)
    {
        return $query->where(function ($query) use ($search) {
            $query->where('name', 'like', '%' . $search . '%')
                ->orWhere('sku', 'like', '%' . $search . '%');
        });
    }

    public function scopeThisDay($query)
    {
        return $query->whereBetween('created_at',
            [Carbon::now()->setTime(0, 0)->format('Y-m-d H:i:s'),
                Carbon::now()->setTime(23, 59, 59)->format('Y-m-d H:i:s')]);
    }

    public function scopeThisWeek($query)
    {
        return $query->whereBetween('created_at',
            [Carbon::now()->startOfWeek()->setTime(0, 0)->format('Y-m-d H:i:s'),
                Carbon::now()->endOfWeek()->setTime(23, 59, 59)->format('Y-m-d H:i:s')]);
    }

    public function scopeThisMonth($query)
    {
        return $query->whereBetween('created_at',
            [Carbon::now()->firstOfMonth()->setTime(0, 0)->format('Y-m-d H:i:s'),
                Carbon::now()->endOfMonth()->setTime(23, 59, 59)->format('Y-m-d H:i:s')]);
    }

    public function scopeThisYear($query)
    {
        return $query->whereBetween('created_at',
            [Carbon::now()->startOfYear()->setTime(0, 0)->format('Y-m-d H:i:s'),
                Carbon::now()->endOfYear()->setTime(23, 59, 59)->format('Y-m-d H:i:s')]);
    }

    /**
     * @return HasMany
     */

    public function stock()
    {
        return $this->hasMany(Stock::class)
            ->select('product_id')
            ->selectRaw('SUM(purchase_qty - sell_qty) as available_qty')
            ->groupBy('product_id');
    }
}
