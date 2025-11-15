<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentPlan extends Model
{
    use HasFactory;
    protected $guarded = [];
    public function budget()
    {
        return $this->belongsTo(Budget::class);
    }
    public function payments()
    {
        return $this->hasMany(Payment::class);
    }
}
