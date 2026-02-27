import { useState } from "react";
import { useNavigate } from "react-router-dom";
import readingKingdomMap from "@/assets/reading-kingdom-map.png";
import {
  Dialog,
  DialogContent,
  DialogTitle,
} from "@/components/ui/dialog";

interface MapRegion {
  id: string;
  label: string;
  genre: string;
  top: string;
  left: string;
  width: string;
  height: string;
  description: string;
}

const mapRegions: MapRegion[] = [
  {
    id: "romance",
    label: "💐 Romance Valley",
    genre: "Romance",
    top: "35%",
    left: "3%",
    width: "22%",
    height: "30%",
    description: "Lush floral valley with blossoming trees",
  },
  {
    id: "fantasy",
    label: "✨ Fantasy Peaks",
    genre: "Fantasy",
    top: "5%",
    left: "15%",
    width: "30%",
    height: "32%",
    description: "Enchanted glowing forests & snowy mountains",
  },
  {
    id: "drama",
    label: "🏰 Drama Kingdom",
    genre: "Drama",
    top: "30%",
    left: "30%",
    width: "22%",
    height: "30%",
    description: "The majestic central castle",
  },
  {
    id: "horror",
    label: "🌑 Horror Marsh",
    genre: "Horror",
    top: "10%",
    left: "52%",
    width: "25%",
    height: "35%",
    description: "Foggy marsh with twisted dark trees",
  },
  {
    id: "mystery",
    label: "🔍 Mystery Coast",
    genre: "Mystery",
    top: "45%",
    left: "55%",
    width: "22%",
    height: "30%",
    description: "Cliffside lighthouse in dramatic lighting",
  },
  {
    id: "historical",
    label: "🏛️ Ancient Ruins",
    genre: "Historical Fiction",
    top: "40%",
    left: "68%",
    width: "20%",
    height: "30%",
    description: "Stone columns and ancient ruins",
  },
  {
    id: "crime",
    label: "⚓ Adventure Seas",
    genre: "Crime",
    top: "65%",
    left: "60%",
    width: "30%",
    height: "28%",
    description: "Open seas with ships and dynamic waves",
  },
  {
    id: "contemporary",
    label: "🏡 Cozy Village",
    genre: "Drama",
    top: "62%",
    left: "15%",
    width: "30%",
    height: "28%",
    description: "Countryside cottages and stone bridges",
  },
];

interface ReadingKingdomMapProps {
  open: boolean;
  onOpenChange: (open: boolean) => void;
  onGenreSelect?: (genre: string) => void;
}

const ReadingKingdomMap = ({ open, onOpenChange, onGenreSelect }: ReadingKingdomMapProps) => {
  const navigate = useNavigate();
  const [hoveredRegion, setHoveredRegion] = useState<string | null>(null);

  const handleRegionClick = (genre: string) => {
    onOpenChange(false);
    onGenreSelect?.(genre);
    navigate("/");
    setTimeout(() => {
      const el = document.getElementById("catalog");
      if (el) el.scrollIntoView({ behavior: "smooth" });
    }, 150);
  };

  return (
    <Dialog open={open} onOpenChange={onOpenChange}>
      <DialogContent className="max-w-[95vw] w-[1200px] max-h-[90vh] p-0 border-2 border-primary/40 bg-transparent overflow-hidden rounded-xl">
        <DialogTitle className="sr-only">Reading Kingdom Map</DialogTitle>
        <div className="relative w-full">
          {/* Map header */}
          <div className="absolute top-3 left-0 right-0 z-20 flex justify-center pointer-events-none">
            <span className="font-pixel text-[9px] md:text-[11px] text-primary bg-card/90 backdrop-blur-sm px-5 py-2 rounded-full border border-primary/30 tracking-wider shadow-lg shadow-primary/10">
              ✦ YOUR READING KINGDOM ✦
            </span>
          </div>

          {/* Map image */}
          <img
            src={readingKingdomMap}
            alt="Reading Kingdom Map"
            className="w-full h-auto block"
            draggable={false}
          />

          {/* Clickable regions */}
          {mapRegions.map((region) => (
            <button
              key={region.id}
              onClick={() => handleRegionClick(region.genre)}
              onMouseEnter={() => setHoveredRegion(region.id)}
              onMouseLeave={() => setHoveredRegion(null)}
              className="absolute rounded-xl transition-all duration-300 ease-out border-2 border-transparent group"
              style={{
                top: region.top,
                left: region.left,
                width: region.width,
                height: region.height,
                background:
                  hoveredRegion === region.id
                    ? "radial-gradient(ellipse, hsl(38 90% 60% / 0.2), transparent 70%)"
                    : "transparent",
                borderColor:
                  hoveredRegion === region.id
                    ? "hsl(38 75% 55% / 0.5)"
                    : "transparent",
                boxShadow:
                  hoveredRegion === region.id
                    ? "0 0 30px hsl(38 90% 60% / 0.25), inset 0 0 20px hsl(38 90% 60% / 0.1)"
                    : "none",
              }}
              aria-label={`Explore ${region.label}`}
            >
              {/* Tooltip label */}
              <span
                className="absolute left-1/2 -translate-x-1/2 -bottom-1 font-pixel text-[7px] md:text-[8px] text-primary-foreground bg-primary/90 backdrop-blur-sm px-3 py-1 rounded-full whitespace-nowrap tracking-wider transition-all duration-300 border border-primary/50 shadow-md"
                style={{
                  opacity: hoveredRegion === region.id ? 1 : 0,
                  transform:
                    hoveredRegion === region.id
                      ? "translateX(-50%) translateY(0)"
                      : "translateX(-50%) translateY(4px)",
                }}
              >
                {region.label}
              </span>
            </button>
          ))}

          {/* Castle shimmer effect */}
          <div
            className="absolute pointer-events-none rounded-full"
            style={{
              top: "32%",
              left: "36%",
              width: "14%",
              height: "20%",
              background:
                "radial-gradient(ellipse, hsl(38 90% 60% / 0.08), transparent 70%)",
              animation: "castle-shimmer 3s ease-in-out infinite",
            }}
          />
        </div>
      </DialogContent>
    </Dialog>
  );
};

export default ReadingKingdomMap;
