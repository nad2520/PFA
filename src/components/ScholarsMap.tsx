import scholarsMapImg from "@/assets/scholars-map.png";
import lumoHappy from "@/assets/lumo-happy.png";

interface MapNode {
  level: number;
  label: string;
  x: string;
  y: string;
}

const mapNodes: MapNode[] = [
  { level: 1, label: "Starting Meadow", x: "12%", y: "82%" },
  { level: 5, label: "Whispering Woods", x: "18%", y: "55%" },
  { level: 10, label: "Crystal Ruins", x: "40%", y: "68%" },
  { level: 15, label: "Crystal Lake", x: "52%", y: "85%" },
  { level: 20, label: "Stone Pass", x: "62%", y: "60%" },
  { level: 25, label: "Dragon's Rest", x: "75%", y: "42%" },
  { level: 30, label: "Imperial Library", x: "90%", y: "25%" },
];

interface ScholarsMapProps {
  currentLevel: number;
}

const ScholarsMap = ({ currentLevel }: ScholarsMapProps) => {
  // Find the active node (highest unlocked)
  const activeNodeIndex = mapNodes.findIndex((n) => n.level > currentLevel) - 1;
  const heroIndex = activeNodeIndex >= 0 ? activeNodeIndex : mapNodes.length - 1;

  return (
    <div className="rounded-xl border border-border bg-card overflow-hidden">
      <div className="p-4 border-b border-border">
        <h2 className="font-display text-xl font-bold text-foreground flex items-center gap-2">
          🗺️ Scholar's Map
        </h2>
        <p className="font-body text-sm text-muted-foreground mt-1">
          Level up to unlock new regions and biomes
        </p>
      </div>

      <div className="relative w-full" style={{ paddingBottom: "50%" }}>
        <img
          src={scholarsMapImg}
          alt="Scholar's Map"
          className="absolute inset-0 w-full h-full object-cover"
        />

        {/* Path nodes */}
        {mapNodes.map((node, i) => {
          const unlocked = currentLevel >= node.level;
          const isHero = i === heroIndex;

          return (
            <div
              key={node.level}
              className="absolute flex flex-col items-center"
              style={{ left: node.x, top: node.y, transform: "translate(-50%, -50%)" }}
            >
              {/* Hero sprite */}
              {isHero && (
                <img
                  src={lumoHappy}
                  alt="You are here"
                  className="w-8 h-8 md:w-10 md:h-10 rounded-full border-2 border-primary shadow-lg shadow-primary/30 animate-breathe -mb-1 z-10"
                />
              )}

              {/* Node marker */}
              <div
                className={`w-7 h-7 md:w-8 md:h-8 rounded-full flex items-center justify-center border-2 text-[8px] font-pixel ${
                  unlocked
                    ? "bg-primary border-primary text-primary-foreground"
                    : "bg-secondary/80 border-border text-muted-foreground"
                } ${!isHero ? "shadow-md" : ""}`}
              >
                {unlocked ? "⚔️" : "🔒"}
              </div>

              {/* Label */}
              <span
                className={`font-pixel text-[6px] md:text-[7px] mt-1 tracking-wider whitespace-nowrap ${
                  unlocked ? "text-foreground" : "text-muted-foreground"
                }`}
              >
                {!unlocked && `LV.${node.level} `}
                {node.label}
              </span>
            </div>
          );
        })}
      </div>
    </div>
  );
};

export default ScholarsMap;
