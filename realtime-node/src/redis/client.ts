import Redis from "ioredis";

export function createRedisClient() {
  const host = process.env.REDIS_HOST || "localhost";
  const port = Number(process.env.REDIS_PORT || 6379);

  const client = new Redis({ host, port });

  client.on("error", (err) => {
    console.error("[redis] error", err);
  });

  client.on("connect", () => {
    console.log(`[redis] connected to ${host}:${port}`);
  });

  return client;
}

