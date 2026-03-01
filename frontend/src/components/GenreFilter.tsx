import { type Genre, genres } from "@/data/books";

type FilterTab = "trending" | Genre;

interface GenreFilterProps {
  activeFilter: FilterTab;
  onFilterChange: (filter: FilterTab) => void;
}

const GenreFilter = ({ activeFilter, onFilterChange }: GenreFilterProps) => {
  const allFilters: FilterTab[] = ["trending", ...genres];

  return (
    <div className="w-full overflow-x-auto scrollbar-hide">
      <div className="flex gap-2 pb-2 min-w-max">
        {allFilters.map((filter) => (
          <button
            key={filter}
            onClick={() => onFilterChange(filter)}
            className={`px-4 py-2 rounded-lg font-pixel text-[9px] md:text-[10px] tracking-wider uppercase transition-all duration-200 whitespace-nowrap border ${
              activeFilter === filter
                ? "bg-primary text-primary-foreground border-primary shadow-lg shadow-primary/20"
                : "bg-card text-muted-foreground border-border hover:border-primary/50 hover:text-foreground"
            }`}
          >
            {filter === "trending" ? "🔥 Trending" : filter}
          </button>
        ))}
      </div>
    </div>
  );
};

export default GenreFilter;
export type { FilterTab };
