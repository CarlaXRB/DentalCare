<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class Event extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    use HasFactory;
    protected $guarded=[];
    public function setEventAttribute($value){
        $this->attributes['event'] = ucwords(strtolower($value));
    }
    public function setDetailsAttribute($value){
        $this->attributes['details'] = ucfirst(strtolower($value));
    }
    public function assignedDoctor(){
        return $this->belongsTo(User::class, 'assigned_doctor');
    }
    public function assignedRadiologist(){
        return $this->belongsTo(User::class, 'assigned_radiologist');
    }
    public function patient(){
        return $this->belongsTo(Patient::class, 'patient_id');
    }
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
    
}
