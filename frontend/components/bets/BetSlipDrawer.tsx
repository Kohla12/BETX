"use client";

import { useState } from "react";
import { motion, AnimatePresence } from "framer-motion";
import { OddsButton } from "./OddsButton";

export function BetSlipDrawer() {
  const [open, setOpen] = useState(true);

  return (
    <div className="hidden lg:block w-80 shrink-0">
      <div className="sticky top-4">
        <AnimatePresence initial={false}>
          {open ? (
            <motion.div
              key="drawer"
              initial={{ x: 120, opacity: 0 }}
              animate={{ x: 0, opacity: 1 }}
              exit={{ x: 120, opacity: 0 }}
              transition={{ type: "spring", stiffness: 120, damping: 18 }}
              className="rounded-3xl border border-neon-cyan/40 bg-slate-950/90 backdrop-blur-xl p-4 shadow-[0_0_40px_rgba(34,211,238,0.35)]"
            >
              <div className="flex items-center justify-between mb-3">
                <p className="text-xs font-semibold tracking-[0.2em] uppercase text-slate-400">
                  Bet Slip
                </p>
                <button
                  onClick={() => setOpen(false)}
                  className="text-[10px] text-slate-500 hover:text-slate-300"
                >
                  Hide
                </button>
              </div>
              <div className="rounded-2xl border border-slate-800 bg-slate-900/70 p-3 mb-3">
                <p className="text-xs text-slate-400 mb-1">Selections</p>
                <p className="text-sm text-slate-500">No selections yet.</p>
              </div>
              <div className="space-y-3">
                <div>
                  <p className="text-xs text-slate-400 mb-1">Stake</p>
                  <input
                    type="number"
                    placeholder="0.00"
                    className="w-full rounded-xl bg-slate-900/80 border border-slate-700 px-3 py-2 text-sm text-slate-100 focus:outline-none focus:ring-1 focus:ring-neon-cyan"
                  />
                </div>
                <div className="flex items-center justify-between text-xs text-slate-400">
                  <span>Potential payout</span>
                  <span className="text-neon-cyan font-semibold">0.00</span>
                </div>
                <OddsButton label="Place Bet" odds={1.0} trend="none" />
              </div>
            </motion.div>
          ) : (
            <motion.button
              key="toggle"
              initial={{ x: 120, opacity: 0 }}
              animate={{ x: 0, opacity: 1 }}
              exit={{ x: 120, opacity: 0 }}
              onClick={() => setOpen(true)}
              className="rounded-full border border-neon-cyan/50 bg-slate-950/90 text-xs px-3 py-1.5 text-neon-cyan"
            >
              Show bet slip
            </motion.button>
          )}
        </AnimatePresence>
      </div>
    </div>
  );
}

