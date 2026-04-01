<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'num_partner',    
        'nickname',
        'name',
        'password',
        'type',
        'registration_date',
        'withdrawal_date',
        'email',
        'telephone',
        'age',
        'language'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    public function zassession()
    {
        return $this->belongsToMany(
            Zassession::class, 
            'user_zassession', 
            'user_id', 
            'zassession_id'
        );
    }

    public function games()
    {
        return $this->belongsToMany(
            Game::class,
            'game_user',
            'game_id',
            'user_id'            
        );
    }
}

