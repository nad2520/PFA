import { useState, useMemo } from "react";
import { Link, useNavigate } from "react-router-dom";
import { Coins, LogOut } from "lucide-react";
import { mockUser } from "@/data/userData";
import lumoHappy from "@/assets/lumo-happy.png";
import lumoWorried from "@/assets/lumo-worried.png";
import {
  HoverCard,
  HoverCardContent,
  HoverCardTrigger,
} from "@/components/ui/hover-card";

interface GlobalHeaderProps {
  children?: React.ReactNode;
}

const GlobalHeader = ({ children }: GlobalHeaderProps) => {
  const navigate = useNavigate();
  const [coins, setCoins] = useState(mockUser.coins);
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
    if (mockUser.level <= 5) return 10;
    if (mockUser.level <= 15) return 30;
    if (mockUser.level <= 25) return 50;
    return 70;
  }, []);

  const isWorried = hoursSinceRead >= 18;
  const lumoImage = isWorried ? lumoWorried : lumoHappy;

  // Penalty logic
  if (lampState === "dark" && !penaltyApplied) {
    const amount = Math.floor(coins * (penaltyPercent / 100));
    setCoins((c) => Math.max(0, c - amount));
    setPenaltyApplied(true);
  }
  if (lampState !== "dark" && penaltyApplied) {
    setPenaltyApplied(false);
  }

  return (
    <nav className="sticky top-0 z-50 bg-background/80 backdrop-blur-md border-b border-border">
      <div className="max-w-6xl mx-auto px-4 py-3 flex items-center gap-4">
        {/* Left: Logo */}
        <Link to="/" className="font-pixel text-[10px] md:text-xs text-primary tracking-wider shrink-0">
          📖 LEXORA
        </Link>

        {/* Center: Full Lamp of Knowledge bar */}
        <div className="flex-1 rounded-xl border border-border bg-card/60 backdrop-blur-sm px-4 py-2">
          <div className="flex items-center gap-4">
            {/* Lumo + Lamp */}
            <div className="relative shrink-0">
              <img
                src={lumoImage}
                alt="Lumo"
                className="w-10 h-10 rounded-full border-2 border-primary"
              />
              <div
                className={`absolute -top-1 -right-1 w-5 h-5 rounded-full bg-primary ${
                  lampState === "flickering" ? "animate-lamp-flicker" : ""
                } ${lampState === "bright" ? "animate-lamp-glow" : ""}`}
                style={{
                  opacity: lampOpacity,
                  boxShadow: `0 0 ${lampOpacity * 14}px hsl(var(--amber-glow) / ${lampOpacity})`,
                }}
              >
                <span className="flex items-center justify-center h-full text-[10px]">
                  {lampState === "dark" ? "💀" : "🔥"}
                </span>
              </div>
            </div>

            <div className="flex-1 min-w-0 space-y-1">
              {/* Status messages */}
              {isWorried && lampState !== "dark" && (
                <p className="font-pixel text-[7px] text-destructive tracking-wider leading-relaxed truncate">
                  "Read before I sleep or lose your coins!"
                </p>
              )}
              {lampState === "dark" && (
                <div className="flex items-center gap-1">
                  <Coins className="w-3 h-3 text-destructive shrink-0" />
                  <p className="font-pixel text-[7px] text-destructive tracking-wider truncate">
                    -{penaltyPercent}% COINS LOST!
                  </p>
                </div>
              )}
              {!isWorried && (
                <p className="font-pixel text-[7px] text-muted-foreground tracking-wider hidden sm:block">
                  🪔 LAMP LIT — Keep reading!
                </p>
              )}

              {/* Slider */}
              <div className="flex items-center gap-2">
                <input
                  type="range"
                  min={0}
                  max={24}
                  step={1}
                  value={hoursSinceRead}
                  onChange={(e) => setHoursSinceRead(Number(e.target.value))}
                  className="w-full accent-primary h-1.5 max-w-[180px]"
                />
                <span className="font-pixel text-[7px] text-primary shrink-0">{hoursSinceRead}h</span>
              </div>
            </div>
          </div>
        </div>

        {/* Right: Nav items */}
        <div className="flex gap-4 items-center shrink-0">
          {children}

          <button
            onClick={() => navigate("/")}
            className="font-pixel text-[8px] md:text-[9px] px-4 py-2 rounded-full bg-destructive/80 text-destructive-foreground tracking-wider hover:bg-destructive hover:shadow-lg hover:shadow-destructive/30 transition-all flex items-center gap-1.5"
          >
            <LogOut className="w-3 h-3" />
            DISCONNECT
          </button>

          <HoverCard openDelay={200} closeDelay={100}>
            <HoverCardTrigger asChild>
              <button
                onClick={() => navigate("/profile")}
                className="rounded-full border-2 border-primary/60 hover:border-primary hover:shadow-md hover:shadow-primary/20 transition-all overflow-hidden w-9 h-9 shrink-0"
              >
                <img src={lumoImage} alt="User avatar" className="w-full h-full object-cover" />
              </button>
            </HoverCardTrigger>
            <HoverCardContent className="w-56 bg-card border-border" side="bottom" align="end">
              <div className="flex flex-col items-center gap-3 py-1">
                <img src={lumoHappy} alt="Lumo" className="w-14 h-14 rounded-full border-2 border-primary" />
                <div className="text-center space-y-1">
                  <p className="font-display text-base font-bold text-foreground">{mockUser.name}</p>
                  <p className="font-pixel text-[8px] text-primary tracking-wider">LVL {mockUser.level}</p>
                </div>
                <div className="flex items-center gap-1.5 bg-secondary rounded-full px-3 py-1">
                  <Coins className="w-3.5 h-3.5 text-primary" />
                  <span className="font-pixel text-[8px] text-foreground tracking-wider">{coins.toLocaleString()} COINS</span>
                </div>
              </div>
            </HoverCardContent>
          </HoverCard>
        </div>
      </div>
    </nav>
  );
};

export default GlobalHeader;
