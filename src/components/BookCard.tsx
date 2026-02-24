import { type Book, genreColors, genreCovers } from "@/data/books";

interface BookCardProps {
  book: Book;
  index: number;
}

const BookCard = ({ book, index }: BookCardProps) => {
  const colors = genreColors[book.genre];
  const coverImage = genreCovers[book.genre];

  return (
    <div
      className="book-card rounded-lg border border-border bg-card overflow-hidden cursor-pointer animate-float-up"
      style={{ animationDelay: `${index * 0.05}s` }}
    >
      {/* Cover area */}
      <div className="relative h-48 md:h-56 overflow-hidden">
        <img
          src={coverImage}
          alt={`${book.title} cover`}
          className="w-full h-full object-cover"
          loading="lazy"
        />
        {/* Warm overlay */}
        <div className="absolute inset-0 bg-gradient-to-t from-card/60 to-transparent pointer-events-none" />
        {book.trending && (
          <span className="absolute top-2 right-2 font-pixel text-[8px] px-2 py-1 rounded bg-primary text-primary-foreground tracking-wider">
            ★ HOT
          </span>
        )}
      </div>

      {/* Info */}
      <div className="p-4 space-y-2">
        <h3 className="font-display text-base font-semibold leading-tight line-clamp-1 text-foreground">
          {book.title}
        </h3>
        <p className="font-body text-sm text-muted-foreground line-clamp-1">{book.author}</p>
        <span className={`genre-tag inline-block ${colors.bg} ${colors.text}`}>
          {book.genre}
        </span>
      </div>
    </div>
  );
};

export default BookCard;
