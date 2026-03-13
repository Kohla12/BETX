import "./globals.css";
import type { ReactNode } from "react";

export const metadata = {
  title: "BetX – Futuristic Sports Betting",
  description: "Real-time neon betting experience.",
};

export default function RootLayout({ children }: { children: ReactNode }) {
  return (
    <html lang="en">
      <body className="min-h-screen bg-slate-950 text-slate-100 bg-grid-neon bg-[size:32px_32px]">
        <div className="fixed inset-0 pointer-events-none opacity-60">
          <div className="absolute -inset-40 bg-gradient-radial from-neon-cyan/10 via-transparent to-transparent blur-3xl" />
          <div className="absolute inset-y-0 right-0 w-1/2 bg-gradient-to-b from-neon-magenta/10 via-transparent to-neon-lime/10 blur-3xl" />
        </div>
        <main className="relative z-10 max-w-7xl mx-auto px-3 sm:px-6 py-4">
          {children}
        </main>
      </body>
    </html>
  );
}

