import Link from "next/link";
import type { ReactNode } from "react";
import { BetSlipDrawer } from "../bets/BetSlipDrawer";

type Props = {
  children: ReactNode;
  isAdmin?: boolean;
};

const navItems = [
  { href: "/", label: "Home" },
  { href: "/live", label: "Live" },
  { href: "/wallet", label: "Wallet" },
  { href: "/bets", label: "History" },
  { href: "/promotions", label: "Promos" },
];

export function Shell({ children, isAdmin }: Props) {
  return (
    <div className="flex gap-4">
      <aside className="hidden md:flex flex-col w-52 shrink-0 border border-slate-800/80 rounded-3xl bg-slate-950/80 backdrop-blur-md p-4">
        <div className="mb-6">
          <div className="inline-flex items-center gap-1.5">
            <span className="h-2 w-2 rounded-full bg-neon-cyan animate-pulse" />
            <span className="text-xs font-semibold tracking-[0.2em] uppercase text-slate-400">
              BetX
            </span>
          </div>
          <p className="mt-2 text-xs text-slate-500">Neon betting interface</p>
        </div>
        <nav className="space-y-1 text-sm">
          {navItems.map((item) => (
            <Link
              key={item.href}
              href={item.href}
              className="block rounded-xl px-3 py-2 text-slate-300 hover:text-neon-cyan hover:bg-slate-900/80 transition-colors"
            >
              {item.label}
            </Link>
          ))}
          <div className="mt-4 pt-4 border-t border-slate-800 text-xs text-slate-500">
            <Link href="/profile" className="hover:text-neon-magenta">
              Profile & Security
            </Link>
          </div>
          <div className="mt-2 text-xs text-slate-500">
            <Link href="/admin" className="hover:text-neon-lime">
              Admin
            </Link>
          </div>
        </nav>
      </aside>
      <div className="flex-1 flex flex-col gap-4">
        <header className="md:hidden flex justify-between items-center mb-2">
          <div className="inline-flex items-center gap-1.5">
            <span className="h-2 w-2 rounded-full bg-neon-cyan animate-pulse" />
            <span className="text-xs font-semibold tracking-[0.2em] uppercase text-slate-400">
              BetX
            </span>
          </div>
          <Link
            href="/wallet"
            className="text-xs px-3 py-1.5 rounded-full border border-neon-cyan/50 text-neon-cyan"
          >
            Wallet
          </Link>
        </header>
        <section className="rounded-3xl border border-slate-800/80 bg-slate-950/80 backdrop-blur-xl px-4 sm:px-6 py-4 sm:py-5 shadow-[0_0_40px_rgba(15,23,42,0.9)] relative overflow-hidden">
          {isAdmin && (
            <div className="absolute inset-x-0 top-0 h-0.5 bg-gradient-to-r from-neon-cyan via-neon-magenta to-neon-lime" />
          )}
          {children}
        </section>
      </div>
      <BetSlipDrawer />
    </div>
  );
}

