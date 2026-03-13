import { createServer } from "http";
import { Server } from "socket.io";
import { createRedisClient } from "./redis/client";
import { registerWsHandlers } from "./websocket/server";
import { startMockSportsFeed } from "./sports-providers/mock-provider";

async function bootstrap() {
  const httpServer = createServer();

  const allowedOrigins =
    process.env.ALLOWED_ORIGINS?.split(",").map((s) => s.trim()) || ["*"];

  const io = new Server(httpServer, {
    cors: {
      origin: allowedOrigins,
      methods: ["GET", "POST"],
    },
  });

  const redis = createRedisClient();

  registerWsHandlers(io, redis);
  startMockSportsFeed(redis);

  const port = Number(process.env.PORT || 4001);
  httpServer.listen(port, () => {
    console.log(`[realtime] listening on :${port}`);
  });
}

bootstrap().catch((err) => {
  console.error("[realtime] failed to start", err);
  process.exit(1);
});

