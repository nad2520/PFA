import { Search } from "lucide-react";
import heroImage from "@/assets/hero-library.png";

interface HeroSectionProps {
  searchQuery: string;
  onSearchChange: (query: string) => void;
}

const HeroSection = ({ searchQuery, onSearchChange }: HeroSectionProps) => {
  return (
    <section className="relative w-full overflow-hidden">
      {/* Background image */}
      <div className="relative w-full h-[420px] md:h-[500px] animate-breathe">
        <img
          src={heroImage}
          alt="Cozy pixel art library with a teddy bear reading"
          className="w-full h-full object-cover"
          loading="eager"
        />
        {/* Vignette overlay */}
        <div className="absolute inset-0 vignette pointer-events-none" />
        {/* Bottom gradient fade */}
        <div className="absolute bottom-0 left-0 right-0 h-32 bg-gradient-to-t from-background to-transparent" />
      </div>

      {/* Hero content overlay */}
      <div className="absolute inset-0 flex flex-col items-center justify-center px-4">
        <p className="font-pixel text-[10px] md:text-xs tracking-[0.3em] uppercase text-primary mb-4 animate-float-up">
          ✦ Digital E-book Library ✦
        </p>
        <h1 className="font-display text-3xl md:text-5xl lg:text-6xl font-bold text-golden text-center mb-3 animate-float-up" style={{ animationDelay: "0.1s" }}>
          Your Next Great Adventure Awaits
        </h1>
        <p className="font-body text-base md:text-lg text-muted-foreground text-center max-w-lg mb-8 animate-float-up" style={{ animationDelay: "0.2s" }}>
          Explore thousands of stories across every genre imaginable
        </p>

        {/* Search bar */}
        <div className="animate-float-up w-full max-w-md" style={{ animationDelay: "0.3s" }}>
          <div className="relative group">
            <Search className="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-muted-foreground group-focus-within:text-primary transition-colors" />
            <input
              type="text"
              value={searchQuery}
              onChange={(e) => onSearchChange(e.target.value)}
              placeholder="Search by title or author..."
              className="w-full pl-12 pr-4 py-3 rounded-lg bg-card/90 backdrop-blur-sm border border-border text-foreground placeholder:text-muted-foreground focus:outline-none focus:ring-2 focus:ring-primary/50 focus:border-primary transition-all font-body text-base"
            />
          </div>
        </div>
      </div>
    </section>
  );
};

export default HeroSection;
