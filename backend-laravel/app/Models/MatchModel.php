<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MatchModel extends Model
{
    protected $table = 'matches';

    protected $fillable = [
        'league_id',
        'home_team_id',
        'away_team_id',
        'start_time',
        'status',
        'sport',
        'home_score',
        'away_score',
        'period',
    ];
}

