import { useState } from "react";
import lumoHappy from "@/assets/lumo-happy.png";
import { Scroll, BookOpen, Zap } from "lucide-react";

interface Bounty {
  icon: React.ReactNode;
  title: string;
  description: string;
  reward: string;
}

const bounties: Bounty[] = [
  {
    icon: <Scroll className="w-5 h-5 text-primary" />,
    title: "The Midnight Scholar",
    description: "Read 20 pages after 11 PM.",
    reward: "+200 XP, +50 Coins",
  },
  {
    icon: <BookOpen className="w-5 h-5 text-primary" />,
    title: "Genre Explorer",
    description: "Add a Historical Fiction book to your list.",
    reward: "+150 XP, +30 Coins",
  },
  {
    icon: <Zap className="w-5 h-5 text-primary" />,
    title: "Speed Reader",
    description: "Finish a book in under 3 days.",
    reward: "+500 XP, +100 Coins",
  },
];

interface LumoWelcomeProps {
  onDismiss: () => void;
}

const LumoWelcome = ({ onDismiss }: LumoWelcomeProps) => {
  const [entered, setEntered] = useState(false);

  // Trigger entrance after mount
  useState(() => {
    setTimeout(() => setEntered(true), 50);
  });

  return (
    <div className="flex flex-col items-center gap-4 w-full px-4 py-6">
      <img
        src={lumoHappy}
        alt="Lumo the bear"
        className="w-28 h-28 md:w-36 md:h-36 rounded-full border-4 border-primary shadow-lg shadow-primary/20 animate-breathe"
      />

      <div className="w-full space-y-4">
        <h2 className="font-pixel text-[10px] md:text-xs text-primary tracking-wider text-center">
          ✦ LUMO'S BOUNTY BOARD ✦
        </h2>
        <p className="font-body text-sm text-muted-foreground text-center">
          Complete these quests to level up and unlock new map regions!
        </p>

        <div className="space-y-3">
          {bounties.map((b) => (
            <div
              key={b.title}
              className="flex items-start gap-3 p-3 rounded-lg bg-secondary/50 border border-border hover:border-primary/30 transition-colors"
            >
              <div className="mt-0.5 shrink-0">{b.icon}</div>
              <div className="flex-1 min-w-0">
                <h3 className="font-display text-sm font-semibold text-foreground">
                  {b.title}
                </h3>
                <p className="font-body text-xs text-muted-foreground">{b.description}</p>
                <span className="font-pixel text-[7px] text-primary tracking-wider">
                  {b.reward}
                </span>
              </div>
            </div>
          ))}
        </div>

        <button
          onClick={onDismiss}
          className="w-full py-3 rounded-lg bg-primary text-primary-foreground font-pixel text-[9px] tracking-wider hover:shadow-lg hover:shadow-primary/30 transition-all"
        >
          ACCEPT BOUNTIES ✦
        </button>
      </div>
    </div>
  );
};

export default LumoWelcome;
