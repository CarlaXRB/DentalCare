<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model;

class Tool extends Model
{
    use HasFactory;
    protected $guarded=[];

    public function radiography():BelongsTo{
        return $this->belongsTo(Radiography::class, 'tool_radiography_id', 'radiography_id');
    }
    public function tomography():BelongsTo{
        return $this->belongsTo(Tomography::class, 'tool_tomography_id', 'tomography_id');
    }
    public function patient():BelongsTo{
        return $this->belongsTo(Patient::class, 'ci_patient', 'ci_patient');
    }
    public function reports():HasMany{
        return $this->hasMany(Report::class, 'ci_patient', 'ci_patient');
    }
}
