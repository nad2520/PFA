import { useState } from "react";
import heroVideo from "@/assets/hero-library.mp4";
import heroImage from "@/assets/hero-library.png";
import LumoWelcome from "./LumoWelcome";
import { Dialog, DialogContent } from "./ui/dialog";

interface HeroSectionProps {
  onGenreSelect?: (genre: string) => void;
}

const HeroSection = ({ onGenreSelect }: HeroSectionProps) => {
  const [showLumo, setShowLumo] = useState(false);

  const handleGetStarted = () => {
    setShowLumo(true);
  };

  const handleLumoDismiss = () => {
    setShowLumo(false);
  };

  return (
    <section className="relative w-full overflow-hidden">
      <div className="relative w-full h-[420px] md:h-[500px]">
        {/* Video layer */}
        <div className="absolute inset-0">
          <video
            autoPlay loop muted playsInline poster={heroImage}
            className="w-full h-full object-cover"
          >
            <source src={heroVideo} type="video/mp4" />
          </video>
        </div>

        {/* Lumo Welcome dialog */}
        <Dialog open={showLumo} onOpenChange={(open) => { if (!open) handleLumoDismiss(); }}>
          <DialogContent className="bg-card border-border max-w-lg p-0 overflow-hidden">
            <LumoWelcome onDismiss={handleLumoDismiss} />
          </DialogContent>
        </Dialog>

        {/* Vignette + bottom gradient */}
        <div className="absolute inset-0 vignette pointer-events-none" />
        <div className="absolute bottom-0 left-0 right-0 h-32 bg-gradient-to-t from-background to-transparent" />
      </div>

      {/* Hero content overlay */}
      <div className="absolute inset-0 flex flex-col items-center justify-center px-4">
        <h1 className="font-display text-4xl md:text-6xl lg:text-7xl font-bold text-golden text-center mb-8 animate-float-up">
          Lexora
        </h1>
        <div className="animate-float-up" style={{ animationDelay: "0.15s" }}>
          <button
            onClick={handleGetStarted}
            className="inline-block px-10 py-4 rounded-full bg-primary text-primary-foreground font-display text-lg font-semibold tracking-wide transition-all duration-300 hover:shadow-[0_0_30px_hsl(38_90%_60%/0.5)] hover:-translate-y-1 hover:scale-105 border border-primary/60"
          >
            Get Started ✦
          </button>
        </div>
      </div>
    </section>
  );
};

export default HeroSection;
