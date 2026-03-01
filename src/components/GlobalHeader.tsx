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
  /** Extra nav items to render before the avatar */
  children?: React.ReactNode;
}

const GlobalHeader = ({ children }: GlobalHeaderProps) => {
  const navigate = useNavigate();

  // Mini lamp state — simulated hours (in real app, would come from context)
  const [hoursSinceRead] = useState(4); // default: user recently read

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

  const isWorried = hoursSinceRead >= 18;
  const lumoImage = isWorried ? lumoWorried : lumoHappy;

  return (
    <nav className="sticky top-0 z-50 bg-background/80 backdrop-blur-md border-b border-border">
      <div className="max-w-6xl mx-auto px-4 py-3 flex items-center justify-between">
        {/* Left: Logo */}
        <Link to="/" className="font-pixel text-[10px] md:text-xs text-primary tracking-wider">
          📖 LEXORA
        </Link>

        {/* Center: Mini Lamp of Knowledge */}
        <div className="flex items-center gap-2">
          <div className="relative">
            <div
              className={`w-7 h-7 rounded-full bg-primary flex items-center justify-center ${
                lampState === "flickering" ? "animate-lamp-flicker" : ""
              } ${lampState === "bright" ? "animate-lamp-glow" : ""}`}
              style={{
                opacity: lampOpacity,
                boxShadow: `0 0 ${lampOpacity * 14}px hsl(var(--amber-glow) / ${lampOpacity})`,
              }}
            >
              <span className="text-xs">{lampState === "dark" ? "💀" : "🔥"}</span>
            </div>
          </div>
          <span className="font-pixel text-[7px] text-muted-foreground tracking-wider hidden sm:inline">
            {lampState === "bright"
              ? "LAMP LIT"
              : lampState === "fading"
              ? "FADING…"
              : lampState === "flickering"
              ? "FLICKERING!"
              : "EXTINGUISHED"}
          </span>
        </div>

        {/* Right: Nav items */}
        <div className="flex gap-4 items-center">
          {children}

          {/* Disconnect button */}
          <button
            onClick={() => navigate("/")}
            className="font-pixel text-[8px] md:text-[9px] px-4 py-2 rounded-full bg-destructive/80 text-destructive-foreground tracking-wider hover:bg-destructive hover:shadow-lg hover:shadow-destructive/30 transition-all flex items-center gap-1.5"
          >
            <LogOut className="w-3 h-3" />
            DISCONNECT
          </button>

          {/* Avatar with hover card */}
          <HoverCard openDelay={200} closeDelay={100}>
            <HoverCardTrigger asChild>
              <button
                onClick={() => navigate("/profile")}
                className="rounded-full border-2 border-primary/60 hover:border-primary hover:shadow-md hover:shadow-primary/20 transition-all overflow-hidden w-9 h-9 shrink-0"
              >
                <img
                  src={lumoImage}
                  alt="User avatar"
                  className="w-full h-full object-cover"
                />
              </button>
            </HoverCardTrigger>
            <HoverCardContent className="w-56 bg-card border-border" side="bottom" align="end">
              <div className="flex flex-col items-center gap-3 py-1">
                <img
                  src={lumoHappy}
                  alt="Lumo"
                  className="w-14 h-14 rounded-full border-2 border-primary"
                />
                <div className="text-center space-y-1">
                  <p className="font-display text-base font-bold text-foreground">
                    {mockUser.name}
                  </p>
                  <p className="font-pixel text-[8px] text-primary tracking-wider">
                    LVL {mockUser.level}
                  </p>
                </div>
                <div className="flex items-center gap-1.5 bg-secondary rounded-full px-3 py-1">
                  <Coins className="w-3.5 h-3.5 text-primary" />
                  <span className="font-pixel text-[8px] text-foreground tracking-wider">
                    {mockUser.coins.toLocaleString()} COINS
                  </span>
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
