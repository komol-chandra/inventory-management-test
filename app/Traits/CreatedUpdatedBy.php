<?php

namespace App\Traits;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

trait CreatedUpdatedBy
{
    /*
    |--------------------------------------------------------------------------
    | Basic Model Scope Function
    |--------------------------------------------------------------------------
    |
    | Here is where you can register scope function for your model and reused them
    */
    public function scopeSearch($query, $search)
    {
        return $query->where('name', 'LIKE', $search . '%');
    }

    public function scopeIsActive($query)
    {
        return $query->where('is_active', 1);
    }

    public function scopeIsNotActive($query)
    {
        return $query->where('is_active', 0);
    }

    public function scopeIsNotDeleted($query)
    {
        return $query->where('is_deleted', 0);
    }

    public function scopeIsDeleted($query)
    {
        return $query->where('is_deleted', 1);
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


    public function createdBy()
    {
        return $this->belongsTo(User::class, 'id', 'created_by');
    }

    public function createdUser()
    {
        return $this->hasOne(User::class, 'id', 'created_by');
    }

    public function updatedUser()
    {
        return $this->hasOne(User::class, 'id', 'created_by');
    }
    public function updatedBy()
    {
        return $this->belongsTo(User::class);
    }
    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted(): void
    {
        static::creating(function (Model $model) {
            $model->setAttribute('created_by', auth()->id());
        });
        static::updating(function (Model $model) {
            $model->setAttribute('updated_by', auth()->id());
        });
    }
}
