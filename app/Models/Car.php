<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Car extends Model
{
    use HasFactory;

    protected $fillable = ['client_id', 'car_id', 'type', 'registered', 'ownbrand', 'accident'];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function serviceLogs()
    {
        return $this->hasMany(ServiceLog::class);
    }

}
