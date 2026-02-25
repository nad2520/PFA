import { useState } from "react";
import { Link } from "react-router-dom";
import HeroSection from "@/components/HeroSection";
import BookCatalog from "@/components/BookCatalog";
import WhyLexora from "@/components/WhyLexora";
import { type Genre } from "@/data/books";

const Index = () => {
  const [selectedGenre, setSelectedGenre] = useState<string | null>(null);

  return (
    <div className="min-h-screen bg-background">
      {/* Nav */}
      <nav className="sticky top-0 z-50 bg-background/80 backdrop-blur-md border-b border-border">
        <div className="max-w-6xl mx-auto px-4 py-3 flex items-center justify-between">
          <span className="font-pixel text-[10px] md:text-xs text-primary tracking-wider">
            📖 LEXORA
          </span>
          <div className="flex gap-4 items-center">
            <Link to="/profile" className="font-body text-sm text-muted-foreground hover:text-foreground transition-colors">
              Profile
            </Link>
            <a href="#why" className="font-body text-sm text-muted-foreground hover:text-foreground transition-colors">
              About
            </a>
            <Link to="/store" className="font-body text-sm text-muted-foreground hover:text-foreground transition-colors">
              My Store
            </Link>
            <Link
              to="/auth"
              className="font-pixel text-[8px] md:text-[9px] px-4 py-2 rounded-full bg-primary text-primary-foreground tracking-wider hover:shadow-lg hover:shadow-primary/30 transition-all"
            >
              SIGN IN
            </Link>
          </div>
        </div>
      </nav>

      <HeroSection onGenreSelect={(genre) => setSelectedGenre(genre)} />

      <div id="catalog">
        <BookCatalog externalGenre={selectedGenre} />
      </div>

      <div id="why">
        <WhyLexora />
      </div>

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
