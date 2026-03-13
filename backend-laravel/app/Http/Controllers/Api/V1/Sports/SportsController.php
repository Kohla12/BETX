<?php

namespace App\Http\Controllers\Api\V1\Sports;

use App\Http\Controllers\Controller;
use App\Models\MatchModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SportsController extends Controller
{
    public function sports()
    {
        // In a real setup, sports list may come from config or provider
        return response()->json([
            ['code' => 'football', 'name' => 'Football'],
            ['code' => 'basketball', 'name' => 'Basketball'],
            ['code' => 'tennis', 'name' => 'Tennis'],
        ]);
    }

    public function leagues(Request $request)
    {
        $sport = $request->query('sport');

        $query = DB::table('leagues');
        if ($sport) {
            $query->where('sport', $sport);
        }

        $leagues = $query->where('active', true)->get();

        return response()->json($leagues);
    }

    public function leagueMatches($leagueId)
    {
        $matches = MatchModel::where('league_id', $leagueId)
            ->where('start_time', '>=', now()->subDays(1))
            ->orderBy('start_time')
            ->get();

        return response()->json($matches);
    }

    public function liveMatches()
    {
        $matches = MatchModel::where('status', 'live')
            ->orderBy('start_time')
            ->get();

        return response()->json($matches);
    }

    public function showMatch(MatchModel $match)
    {
        return response()->json($match);
    }

    public function matchMarkets($matchId)
    {
        $markets = DB::table('markets')
            ->where('match_id', $matchId)
            ->where('status', 'open')
            ->get();

        $odds = DB::table('odds')
            ->whereIn('market_id', $markets->pluck('id'))
            ->get();

        return response()->json([
            'markets' => $markets,
            'odds' => $odds,
        ]);
    }
}

