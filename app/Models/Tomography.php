<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class Tomography extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    use HasFactory;
    protected $guarded=[];

    public function setTomographyTypeAttribute($value){
        $this->attributes['tomography_type'] = ucwords(strtolower($value));
    }
    public function setTomographyDoctorAttribute($value){
        $this->attributes['tomography_doctor'] = ucwords(strtolower($value));
    }
    public function setTomographyChargeAttribute($value){
        $this->attributes['tomography_charge'] = ucwords(strtolower($value));
    }
    public function patient():BelongsTo{
        return $this->belongsTo(Patient::class, 'ci_patient', 'ci_patient');
    }
    public function tools():HasMany{
        return $this->hasMany(Tool::class, 'tomography_id', 'tool_tomography_id');
    }
    public function reports():HasMany{
        return $this->hasMany(Report::class, 'report_id', 'tomography_id');
    }
}
