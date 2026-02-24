import { useState, useMemo } from "react";
import { Search } from "lucide-react";
import { books } from "@/data/books";
import BookCard from "./BookCard";
import GenreFilter, { type FilterTab } from "./GenreFilter";

const ITEMS_PER_PAGE = 8;

const BookCatalog = () => {
  const [searchQuery, setSearchQuery] = useState("");
  const [activeFilter, setActiveFilter] = useState<FilterTab>("trending");
  const [visibleCount, setVisibleCount] = useState(ITEMS_PER_PAGE);

  const filteredBooks = useMemo(() => {
    let result = books;

    if (searchQuery.trim()) {
      const q = searchQuery.toLowerCase();
      result = result.filter(
        (b) =>
          b.title.toLowerCase().includes(q) ||
          b.author.toLowerCase().includes(q)
      );
    }

    if (activeFilter === "trending") {
      result = result.filter((b) => b.trending);
    } else {
      result = result.filter((b) => b.genre === activeFilter);
    }

    return result;
  }, [searchQuery, activeFilter]);

  const visibleBooks = filteredBooks.slice(0, visibleCount);
  const hasMore = visibleCount < filteredBooks.length;

  const handleFilterChange = (filter: FilterTab) => {
    setActiveFilter(filter);
    setVisibleCount(ITEMS_PER_PAGE);
  };

  return (
    <section className="w-full max-w-6xl mx-auto px-4 py-10">
      {/* Catalog header */}
      <div className="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
        <h2 className="font-display text-2xl md:text-3xl font-bold text-foreground">
          📚 Book Catalog
        </h2>

        {/* Search bar */}
        <div className="relative group w-full md:w-72">
          <Search className="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-muted-foreground group-focus-within:text-primary transition-colors" />
          <input
            type="text"
            value={searchQuery}
            onChange={(e) => setSearchQuery(e.target.value)}
            placeholder="Search by title or author..."
            className="w-full pl-10 pr-4 py-2.5 rounded-lg bg-card/90 backdrop-blur-sm border border-border text-foreground placeholder:text-muted-foreground focus:outline-none focus:ring-2 focus:ring-primary/50 focus:border-primary transition-all font-body text-sm"
          />
        </div>
      </div>

      <GenreFilter activeFilter={activeFilter} onFilterChange={handleFilterChange} />

      {/* Grid */}
      <div className="mt-6 grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4 md:gap-6">
        {visibleBooks.map((book, i) => (
          <BookCard key={book.id} book={book} index={i} />
        ))}
      </div>

      {visibleBooks.length === 0 && (
        <div className="text-center py-16">
          <p className="font-pixel text-xs text-muted-foreground">No books found</p>
        </div>
      )}

      {/* Explore More */}
      {hasMore && (
        <div className="flex justify-center mt-10">
          <button
            onClick={() => setVisibleCount((c) => c + ITEMS_PER_PAGE)}
            className="px-8 py-3 rounded-lg bg-primary text-primary-foreground font-display text-lg font-semibold hover:shadow-lg hover:shadow-primary/30 transition-all duration-300 hover:-translate-y-0.5 border border-primary"
          >
            Explore More ✦
          </button>
        </div>
      )}
    </section>
  );
};

export default BookCatalog;
