import { useState, useMemo } from "react";
import { books } from "@/data/books";
import BookCard from "./BookCard";
import GenreFilter, { type FilterTab } from "./GenreFilter";

interface BookCatalogProps {
  searchQuery: string;
}

const ITEMS_PER_PAGE = 8;

const BookCatalog = ({ searchQuery }: BookCatalogProps) => {
  const [activeFilter, setActiveFilter] = useState<FilterTab>("trending");
  const [visibleCount, setVisibleCount] = useState(ITEMS_PER_PAGE);

  const filteredBooks = useMemo(() => {
    let result = books;

    // Search filter
    if (searchQuery.trim()) {
      const q = searchQuery.toLowerCase();
      result = result.filter(
        (b) =>
          b.title.toLowerCase().includes(q) ||
          b.author.toLowerCase().includes(q)
      );
    }

    // Tab filter
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
      <h2 className="font-display text-2xl md:text-3xl font-bold text-foreground mb-6">
        📚 Book Catalog
      </h2>

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

      {/* See More */}
      {hasMore && (
        <div className="flex justify-center mt-10">
          <button
            onClick={() => setVisibleCount((c) => c + ITEMS_PER_PAGE)}
            className="px-8 py-3 rounded-lg bg-primary text-primary-foreground font-display text-lg font-semibold hover:shadow-lg hover:shadow-primary/30 transition-all duration-300 hover:-translate-y-0.5 border border-primary"
          >
            See More ✦
          </button>
        </div>
      )}
    </section>
  );
};

export default BookCatalog;
