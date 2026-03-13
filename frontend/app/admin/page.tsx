import { Shell } from "../../components/layout/Shell";

export default function AdminPage() {
  return (
    <Shell isAdmin>
      <h1 className="text-2xl font-semibold mb-4">Admin Dashboard</h1>
      <div className="grid gap-4 md:grid-cols-3">
        <div className="rounded-2xl border border-neon-cyan/40 bg-slate-900/70 p-4">
          <p className="text-xs text-slate-400">Turnover (24h)</p>
          <p className="mt-2 text-2xl font-semibold text-neon-cyan">0.00</p>
        </div>
        <div className="rounded-2xl border border-neon-magenta/40 bg-slate-900/70 p-4">
          <p className="text-xs text-slate-400">Active Users</p>
          <p className="mt-2 text-2xl font-semibold text-neon-magenta">0</p>
        </div>
        <div className="rounded-2xl border border-neon-lime/40 bg-slate-900/70 p-4">
          <p className="text-xs text-slate-400">Open Bets</p>
          <p className="mt-2 text-2xl font-semibold text-neon-lime">0</p>
        </div>
      </div>
    </Shell>
  );
}

