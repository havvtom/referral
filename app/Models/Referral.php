<?php

namespace App\Models;

use App\Models\Referral;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Referral extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $dates = ['completed_at'];

    protected $casts = [
    	'boolean' => 'completed_at'
    ];

    public static function boot()
    {
        parent::boot();

        static::creating(function (Referral $referral) {
            $referral->token = Str::random(50); 
        });
    }

    public function user()
    {
    	return $this->belongsTo(User::class);
    }

    public function scopeWhereNotCompleted(Builder $builder)
    {
        return $builder->where('completed', false);
    }

    public function scopeWhereNotFromUser(Builder $builder, ?User $user)
    {
        if( !$user ) {
            return $builder;
        }

        return $builder->where('user_id', '!=', $user->id);
    }
}
