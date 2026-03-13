import { Shell } from "../../components/layout/Shell";

export default function BetsPage() {
  return (
    <Shell>
      <h1 className="text-2xl font-semibold mb-4">Bet History</h1>
      <p className="text-sm text-slate-400">
        Your recent slips will appear here once you start betting.
      </p>
    </Shell>
  );
}

