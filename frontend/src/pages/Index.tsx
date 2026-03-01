import { useState } from "react";
import { Link } from "react-router-dom";
import HeroSection from "@/components/HeroSection";
import BookCatalog from "@/components/BookCatalog";
import WhyLexora from "@/components/WhyLexora";
import ReadingKingdomMap from "@/components/ReadingKingdomMap";
import GlobalHeader from "@/components/GlobalHeader";
import { type Genre } from "@/data/books";

const Index = () => {
  const [selectedGenre, setSelectedGenre] = useState<string | null>(null);
  const [showMap, setShowMap] = useState(false);

  return (
    <div className="min-h-screen bg-background">
      <GlobalHeader>
        <a href="#why" className="font-body text-sm text-muted-foreground hover:text-foreground transition-colors">
          About
        </a>
        <Link to="/store" className="font-body text-sm text-muted-foreground hover:text-foreground transition-colors">
          My Store
        </Link>
        <button
          onClick={() => setShowMap(true)}
          className="font-body text-sm text-muted-foreground hover:text-foreground transition-colors"
        >
          My Map
        </button>
      </GlobalHeader>

      <HeroSection onGenreSelect={(genre) => setSelectedGenre(genre)} />

      <div id="catalog">
        <BookCatalog externalGenre={selectedGenre} />
      </div>

      <div id="why">
        <WhyLexora />
      </div>

      <ReadingKingdomMap open={showMap} onOpenChange={setShowMap} onGenreSelect={(genre) => setSelectedGenre(genre)} />

      {/* Footer */}
      <footer className="border-t border-border py-8 text-center">
        <p className="font-pixel text-[8px] text-muted-foreground tracking-wider">
          ✦ LEXORA — A cozy corner for readers ✦
        </p>
      </footer>
    </div>
  );
};

export default Index;
