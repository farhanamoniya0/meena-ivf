<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    protected $fillable = ['name','code','description','head_id','status'];

    public function head()         { return $this->belongsTo(User::class, 'head_id'); }
    public function appointments() { return $this->hasMany(Appointment::class); }
    public function tasks()        { return $this->hasMany(Task::class); }
}
