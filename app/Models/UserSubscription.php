<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserSubscription extends Model
{
    use HasFactory;

    public $table = 'user_subscriptions';
    public $fillable = ['user_id','isPremium',];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
