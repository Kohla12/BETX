import { Shell } from "../components/layout/Shell";
import { LiveRail } from "../components/sports/LiveRail";

export default function HomePage() {
  return (
    <Shell>
      <section className="mb-6">
        <h1 className="text-3xl sm:text-4xl font-semibold tracking-tight">
          BetX <span className="text-neon-cyan">Live</span>
        </h1>
        <p className="mt-2 text-sm text-slate-400">
          Futuristic, real-time betting with neon-illuminated odds and instant updates.
        </p>
      </section>
      <LiveRail />
    </Shell>
  );
}

