import { Shell } from "../../components/layout/Shell";

export default function ProfilePage() {
  return (
    <Shell>
      <h1 className="text-2xl font-semibold mb-4">Profile & Security</h1>
      <div className="space-y-4">
        <div className="rounded-2xl border border-slate-700 bg-slate-900/60 p-4">
          <h2 className="text-sm font-medium text-slate-300">Account</h2>
          <p className="mt-2 text-xs text-slate-400">
            Manage your personal details, verification, and preferences.
          </p>
        </div>
        <div className="rounded-2xl border border-slate-700 bg-slate-900/60 p-4">
          <h2 className="text-sm font-medium text-slate-300">Security</h2>
          <ul className="mt-2 text-xs text-slate-400 space-y-1">
            <li>• Two-factor authentication (2FA)</li>
            <li>• Withdrawal PIN</li>
            <li>• Trusted devices</li>
          </ul>
        </div>
      </div>
    </Shell>
  );
}

