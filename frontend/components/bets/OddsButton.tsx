"use client";

import { motion } from "framer-motion";

type Props = {
  label: string;
  odds: number;
  trend?: "up" | "down" | "none";
  selected?: boolean;
  onClick?: () => void;
};

export function OddsButton({ label, odds, trend = "none", selected, onClick }: Props) {
  const baseClasses = "rounded-xl px-3 py-2 text-xs md:text-sm font-semibold transition-colors border border-cyan-500/30 bg-slate-900/60 backdrop-blur-sm hover:border-cyan-400 hover:bg-slate-900";
  const selectedClasses = selected ? "bg-neon-cyan text-slate-950 border-neon-cyan" : "";
  const className = `${baseClasses} ${selectedClasses}`.trim();

  return (
    <motion.button
      onClick={onClick}
      whileTap={{ scale: 0.95 }}
      animate={
        trend === "up"
          ? { boxShadow: "0 0 20px rgba(34,197,94,0.8)" }
          : trend === "down"
          ? { boxShadow: "0 0 20px rgba(239,68,68,0.8)" }
          : { boxShadow: "0 0 0 rgba(0,0,0,0)" }
      }
      className={className}
    >
      <span className="mr-2 text-slate-300">{label}</span>
      <span className="text-neon-cyan">{odds.toFixed(2)}</span>
    </motion.button>
  );
}
