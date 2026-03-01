import { useState, useEffect, useMemo } from "react";
import lumoHappy from "@/assets/lumo-happy.png";
import lumoWorried from "@/assets/lumo-worried.png";
import { Coins } from "lucide-react";

interface LampOfKnowledgeProps {
  userLevel: number;
  coins: number;
  onCoinPenalty?: (amount: number) => void;
}

const LampOfKnowledge = ({ userLevel, coins, onCoinPenalty }: LampOfKnowledgeProps) => {
  // Simulate hours since last read (for demo, use a slider-controlled value)
  const [hoursSinceRead, setHoursSinceRead] = useState(0);
  const [penaltyApplied, setPenaltyApplied] = useState(false);

  const lampState = useMemo(() => {
    if (hoursSinceRead < 18) return "bright";
    if (hoursSinceRead < 22) return "fading";
    if (hoursSinceRead < 24) return "flickering";
    return "dark";
  }, [hoursSinceRead]);

  const lampOpacity = useMemo(() => {
    if (hoursSinceRead < 18) return 1;
    if (hoursSinceRead < 22) return 1 - ((hoursSinceRead - 18) / 4) * 0.6;
    if (hoursSinceRead < 24) return 0.3;
    return 0.05;
  }, [hoursSinceRead]);

  const penaltyPercent = useMemo(() => {
    if (userLevel <= 5) return 10;
    if (userLevel <= 15) return 30;
    if (userLevel <= 25) return 50;
    return 70;
  }, [userLevel]);

  useEffect(() => {
    if (lampState === "dark" && !penaltyApplied) {
      const amount = Math.floor(coins * (penaltyPercent / 100));
      onCoinPenalty?.(amount);
      setPenaltyApplied(true);
    }
    if (lampState !== "dark") {
      setPenaltyApplied(false);
    }
  }, [lampState, penaltyApplied, coins, penaltyPercent, onCoinPenalty]);

  const lumoImage = hoursSinceRead >= 18 ? lumoWorried : lumoHappy;
  const isWorried = hoursSinceRead >= 18;

  return (
    <div className="rounded-xl border border-border bg-card p-6 space-y-4">
      <div className="flex items-center justify-between">
        <h3 className="font-display text-lg font-bold text-foreground flex items-center gap-2">
          🪔 Lamp of Knowledge
        </h3>
        <span className="font-pixel text-[8px] text-muted-foreground tracking-wider">
          DEMO CONTROL
        </span>
      </div>

      <div className="flex items-center gap-6">
        {/* Lumo + Lamp */}
        <div className="relative shrink-0">
          <img
            src={lumoImage}
            alt="Lumo"
            className="w-20 h-20 rounded-full border-2 border-primary"
          />
          {/* Lamp glow */}
          <div
            className={`absolute -top-2 -right-2 w-8 h-8 rounded-full bg-primary ${
              lampState === "flickering" ? "animate-lamp-flicker" : ""
            } ${lampState === "bright" ? "animate-lamp-glow" : ""}`}
            style={{ opacity: lampOpacity, boxShadow: `0 0 ${lampOpacity * 20}px hsl(var(--amber-glow) / ${lampOpacity})` }}
          >
            <span className="flex items-center justify-center h-full text-sm">
              {lampState === "dark" ? "💀" : "🔥"}
            </span>
          </div>
        </div>

        <div className="flex-1 space-y-3">
          {/* Status message */}
          {isWorried && lampState !== "dark" && (
            <div className="pixel-dialogue rounded-lg bg-destructive/10 p-3">
              <p className="font-pixel text-[7px] text-destructive tracking-wider leading-relaxed">
                "You have to read at least a short novel before I sleep, otherwise you will lose your coins!"
              </p>
            </div>
          )}

          {lampState === "dark" && (
            <div className="pixel-dialogue rounded-lg bg-destructive/20 p-3 flex items-center gap-2">
              <Coins className="w-4 h-4 text-destructive" />
              <p className="font-pixel text-[7px] text-destructive tracking-wider">
                -{penaltyPercent}% COINS LOST! ({Math.floor(coins * (penaltyPercent / 100)).toLocaleString()} coins)
              </p>
            </div>
          )}

          {!isWorried && (
            <p className="font-body text-sm text-muted-foreground">
              Lumo is happy! Keep reading to maintain your streak.
            </p>
          )}

          {/* Time slider (demo) */}
          <div className="space-y-1">
            <div className="flex justify-between">
              <span className="font-pixel text-[7px] text-muted-foreground">Hours since last read</span>
              <span className="font-pixel text-[7px] text-primary">{hoursSinceRead}h</span>
            </div>
            <input
              type="range"
              min={0}
              max={24}
              step={1}
              value={hoursSinceRead}
              onChange={(e) => setHoursSinceRead(Number(e.target.value))}
              className="w-full accent-primary h-2"
            />
            <div className="flex justify-between font-pixel text-[6px] text-muted-foreground">
              <span>0h ☀️</span>
              <span>18h ⚠️</span>
              <span>24h 💀</span>
            </div>
          </div>
        </div>
      </div>
    </div>
  );
};

export default LampOfKnowledge;
