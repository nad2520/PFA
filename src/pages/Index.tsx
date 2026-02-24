import { useState } from "react";
import HeroSection from "@/components/HeroSection";
import BookCatalog from "@/components/BookCatalog";

const Index = () => {
  const [searchQuery, setSearchQuery] = useState("");

  return (
    <div className="min-h-screen bg-background">
      {/* Nav */}
      <nav className="sticky top-0 z-50 bg-background/80 backdrop-blur-md border-b border-border">
        <div className="max-w-6xl mx-auto px-4 py-3 flex items-center justify-between">
          <span className="font-pixel text-[10px] md:text-xs text-primary tracking-wider">
            📖 BOOKDEN
          </span>
          <div className="flex gap-4 items-center">
            <a href="#catalog" className="font-body text-sm text-muted-foreground hover:text-foreground transition-colors">
              Browse
            </a>
            <a href="#" className="font-body text-sm text-muted-foreground hover:text-foreground transition-colors">
              About
            </a>
          </div>
        </div>
      </nav>

      <HeroSection searchQuery={searchQuery} onSearchChange={setSearchQuery} />

      <div id="catalog">
        <BookCatalog searchQuery={searchQuery} />
      </div>

      {/* Footer */}
      <footer className="border-t border-border py-8 text-center">
        <p className="font-pixel text-[8px] text-muted-foreground tracking-wider">
          ✦ BOOKDEN — A cozy corner for readers ✦
        </p>
      </footer>
    </div>
  );
};

export default Index;
