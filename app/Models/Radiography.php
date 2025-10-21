<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class Radiography extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    use HasFactory;
    protected $guarded=[];

    public function setRadiographyTypeAttribute($value){
        $this->attributes['radiography_type'] = ucwords(strtolower($value));
    }
    public function setRadiographyDoctorAttribute($value){
        $this->attributes['radiography_doctor'] = ucwords(strtolower($value));
    }
    public function setRadiographyChargeAttribute($value){
        $this->attributes['radiography_charge'] = ucwords(strtolower($value));
    }
    public function patient():BelongsTo{
        return $this->belongsTo(Patient::class, 'ci_patient', 'ci_patient');
    }
    public function tools():HasMany{
        return $this->hasMany(Tool::class, 'tool_radiography_id', 'radiography_id');
    }
    public function reports():HasMany{
        return $this->hasMany(Report::class, 'report_id', 'radiography_id');
    }
}
