"use client";

import { useState } from "react";
import { OddsButton } from "../bets/OddsButton";

type LiveMatch = {
  id: number;
  home_team_id?: number;
  away_team_id?: number;
  home_score?: number;
  away_score?: number;
  status?: string;
  period?: string;
};

const MOCK_MATCHES: LiveMatch[] = [
  {
    id: 1,
    home_score: 2,
    away_score: 1,
    status: "live",
    period: "2H",
  },
  {
    id: 2,
    home_score: 0,
    away_score: 0,
    status: "scheduled",
    period: "-",
  },
  {
    id: 3,
    home_score: 3,
    away_score: 2,
    status: "finished",
    period: "FT",
  },
];

export function LiveRail() {
  const [matches] = useState<LiveMatch[]>(MOCK_MATCHES);

  return (
    <div className="grid gap-4 md:grid-cols-2 xl:grid-cols-3">
      {matches.map((match) => (
        <div
          key={match.id}
          className="rounded-2xl border border-slate-800 bg-slate-950/80 p-3 flex flex-col gap-3"
        >
          <div className="flex items-center justify-between text-xs text-slate-400">
            <span>Match #{match.id}</span>
            <span className="px-2 py-0.5 rounded-full border border-neon-cyan/40 text-[10px] uppercase tracking-wide">
              {match.status ?? "scheduled"}
            </span>
          </div>
          <div className="flex items-center justify-between">
            <div>
              <p className="text-sm text-slate-300">Home</p>
              <p className="mt-1 text-xl font-semibold">
                {match.home_score ?? 0}
              </p>
            </div>
            <div className="text-xs text-slate-500">{match.period}</div>
            <div className="text-right">
              <p className="text-sm text-slate-300">Away</p>
              <p className="mt-1 text-xl font-semibold">
                {match.away_score ?? 0}
              </p>
            </div>
          </div>
          <div className="flex gap-2 mt-2">
            <OddsButton label="Home" odds={1.8} />
            <OddsButton label="Draw" odds={3.4} />
            <OddsButton label="Away" odds={4.2} />
          </div>
        </div>
      ))}
    </div>
  );
}

