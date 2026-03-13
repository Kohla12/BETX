import { Shell } from "../../components/layout/Shell";

export default function WalletPage() {
  return (
    <Shell>
      <h1 className="text-2xl font-semibold mb-4">Wallet</h1>
      <div className="grid gap-4 md:grid-cols-2">
        <div className="rounded-2xl border border-neon-cyan/30 bg-slate-900/60 p-4">
          <h2 className="text-sm font-medium text-slate-300">Betting Wallet</h2>
          <p className="mt-2 text-3xl font-semibold text-neon-cyan">0.00</p>
        </div>
        <div className="rounded-2xl border border-neon-magenta/30 bg-slate-900/60 p-4">
          <h2 className="text-sm font-medium text-slate-300">Bonus Wallet</h2>
          <p className="mt-2 text-3xl font-semibold text-neon-magenta">0.00</p>
        </div>
      </div>
    </Shell>
  );
}

