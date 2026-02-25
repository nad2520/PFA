import { useState } from "react";
import { useNavigate } from "react-router-dom";
import heroVideo from "@/assets/hero-library.mp4";
import heroImage from "@/assets/hero-library.png";
import genreMap from "@/assets/genre-map.png";

const mapGenres = [
  { label: "Romance", genre: "Romance", top: "5%", left: "5%", width: "25%", height: "20%" },
  { label: "Fantasy", genre: "Fantasy", top: "2%", left: "30%", width: "30%", height: "25%" },
  { label: "Mystery", genre: "Mystery", top: "30%", left: "2%", width: "28%", height: "25%" },
  { label: "Horror", genre: "Horror", top: "25%", left: "65%", width: "30%", height: "25%" },
  { label: "Historical", genre: "Historical Fiction", top: "35%", left: "32%", width: "30%", height: "20%" },
  { label: "Adventure", genre: "Crime", top: "60%", left: "5%", width: "30%", height: "25%" },
];

interface HeroSectionProps {
  onGenreSelect?: (genre: string) => void;
}

const HeroSection = ({ onGenreSelect }: HeroSectionProps) => {
  const [showMap, setShowMap] = useState(false);
  const navigate = useNavigate();

  const handleGetStarted = () => {
    setShowMap(true);
  };

  const handleGenreClick = (genre: string) => {
    onGenreSelect?.(genre);
    // Scroll to catalog
    const el = document.getElementById("catalog");
    if (el) el.scrollIntoView({ behavior: "smooth" });
  };

  return (
    <section className="relative w-full overflow-hidden">
      <div className="relative w-full h-[420px] md:h-[500px]">
        {/* Video layer */}
        <div
          className="absolute inset-0 transition-opacity duration-500 ease-in-out"
          style={{ opacity: showMap ? 0 : 1 }}
        >
          <video
            autoPlay
            loop
            muted
            playsInline
            poster={heroImage}
            className="w-full h-full object-cover"
          >
            <source src={heroVideo} type="video/mp4" />
          </video>
        </div>

        {/* Map layer */}
        <div
          className="absolute inset-0 transition-opacity duration-500 ease-in-out"
          style={{ opacity: showMap ? 1 : 0, pointerEvents: showMap ? "auto" : "none" }}
        >
          <img src={genreMap} alt="Genre Map" className="w-full h-full object-cover" />
          {/* Clickable genre hotspots */}
          {mapGenres.map((g) => (
            <button
              key={g.genre}
              onClick={() => handleGenreClick(g.genre)}
              className="absolute rounded-lg hover:bg-primary/20 transition-colors duration-200 border-2 border-transparent hover:border-primary/50"
              style={{ top: g.top, left: g.left, width: g.width, height: g.height }}
              aria-label={`Browse ${g.label} books`}
            />
          ))}
        </div>

        {/* Vignette overlay */}
        <div className="absolute inset-0 vignette pointer-events-none" />
        {/* Bottom gradient fade */}
        <div className="absolute bottom-0 left-0 right-0 h-32 bg-gradient-to-t from-background to-transparent" />
      </div>

      {/* Hero content overlay */}
      <div
        className="absolute inset-0 flex flex-col items-center justify-center px-4 transition-opacity duration-500"
        style={{ opacity: showMap ? 0 : 1, pointerEvents: showMap ? "none" : "auto" }}
      >
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

      {/* Map instruction overlay */}
      {showMap && (
        <div className="absolute bottom-12 left-0 right-0 flex justify-center pointer-events-none">
          <span className="font-pixel text-[9px] text-foreground bg-card/80 backdrop-blur-sm px-4 py-2 rounded-full border border-border tracking-wider animate-float-up">
            ✦ TAP A REGION TO EXPLORE ✦
          </span>
        </div>
      )}
    </section>
  );
};

export default HeroSection;
