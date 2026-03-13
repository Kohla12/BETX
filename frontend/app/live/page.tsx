import { Shell } from "../../components/layout/Shell";
import { LiveRail } from "../../components/sports/LiveRail";

export default function LivePage() {
  return (
    <Shell>
      <div className="flex items-center justify-between mb-4">
        <h1 className="text-2xl font-semibold">Live Betting</h1>
      </div>
      <LiveRail />
    </Shell>
  );
}

