<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\GameFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Casts\Attribute as CastsAttribute;


class Game extends Model
{
    protected $table = 'types';    
    
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'zassession_id',
        'boardgame_id',
        'host_user_id',
        'max_players',
        'start_time',
        'status',
        'necesary_know_how',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'zassession_id' => 'integer',
            'boardgame_id' => 'integer',
            'host_user_id' => 'integer',
            'max_players' => 'integer',
            'start_time' => 'string',
            'status' => 'string',
            'necesary_know_how' => 'boolean',
        ];
    }
}
