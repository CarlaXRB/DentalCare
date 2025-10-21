<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class Patient extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    use HasFactory;
    protected $guarded=[];

    public function setNamePatientAttribute($value){
        $this->attributes['name_patient'] = ucwords(strtolower($value));
    }
    public function user(){
        return $this->hasOne(User::class, 'email', 'email');
    }
    public function radiographies():HasMany{
        return $this->hasMany(Radiography::class, 'ci_patient', 'ci_patient');
    }
    public function tomographies():HasMany{
        return $this->hasMany(Tomography::class, 'ci_patient', 'ci_patient');
    }
    public function tools():HasMany{
        return $this->hasMany(Tool::class, 'ci_patient', 'ci_patient');
    }
    public function events():HasMany{
        return $this->hasMany(Event::class, 'patient_id', 'id');
    }
    public function reports():HasMany{
        return $this->hasMany(Report::class, 'ci_patient', 'ci_patient');
    }
    public function payments():HasMany{
        return $this->hasMany(Payment::class, 'ci_patient', 'ci_patient');
    }
    public function treatments():HasMany{
        return $this->hasMany(Treatment::class, 'ci_patient', 'ci_patient');
    }
}
