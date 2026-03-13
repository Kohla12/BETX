import type { Server } from "socket.io";
import type Redis from "ioredis";

export function registerWsHandlers(io: Server, redis: Redis.Redis) {
  io.on("connection", (socket) => {
    console.log("[ws] client connected", socket.id);

    socket.on("join_match", (matchId: number) => {
      socket.join(`match:${matchId}`);
    });

    socket.on("join_user", (userId: number) => {
      socket.join(`user:${userId}`);
    });

    socket.on("disconnect", () => {
      console.log("[ws] client disconnected", socket.id);
    });
  });

  const sub = redis.duplicate();
  sub.subscribe("match_updates", "odds_updates", "bet_events", (err) => {
    if (err) {
      console.error("[redis] subscribe error", err);
    } else {
      console.log("[redis] subscribed to pubsub channels");
    }
  });

  sub.on("message", (channel, message) => {
    try {
      const payload = JSON.parse(message);
      switch (channel) {
        case "match_updates":
          io.to(`match:${payload.matchId}`).emit("match:update", payload);
          break;
        case "odds_updates":
          io.to(`match:${payload.matchId}`).emit("odds:update", payload);
          break;
        case "bet_events":
          io.to(`user:${payload.userId}`).emit("bet:event", payload);
          break;
        default:
          break;
      }
    } catch (e) {
      console.error("[ws] failed to parse pubsub message", e);
    }
  });
}

