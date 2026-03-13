import type Redis from "ioredis";

export function startMockSportsFeed(redis: Redis.Redis) {
  // Simple mock ticker for development without a real sports API
  setInterval(() => {
    const matchId = 1;
    const payload = {
      matchId,
      status: "live",
      homeScore: Math.floor(Math.random() * 5),
      awayScore: Math.floor(Math.random() * 5),
      period: "2H",
      updatedAt: new Date().toISOString(),
    };

    redis.publish("match_updates", JSON.stringify(payload));
  }, 7000);

  setInterval(() => {
    const matchId = 1;
    const payload = {
      matchId,
      marketId: 1,
      odds: [
        { selection: "HOME", odds: 1.8 + Math.random() * 0.2 },
        { selection: "DRAW", odds: 3.2 + Math.random() * 0.2 },
        { selection: "AWAY", odds: 4.5 + Math.random() * 0.3 },
      ],
      updatedAt: new Date().toISOString(),
    };

    redis.publish("odds_updates", JSON.stringify(payload));
  }, 9000);
}

