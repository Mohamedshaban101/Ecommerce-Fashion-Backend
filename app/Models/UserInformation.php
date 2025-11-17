<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserInformation extends Model
{
    protected $table = 'user_information';
    protected $fillable = ['name' , 'email' , 'address' , 'phone' , 'zip' , 'city' , 'state' , 'user_id'];

    public function user(){
        return $this->belongsTo(User::class);
    }
}
