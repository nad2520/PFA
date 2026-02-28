import { type Book, genreColors, genreCovers } from "@/data/books";
import { bookPrices } from "@/data/bookPrices";
import { Coins, Star } from "lucide-react";
import { useNavigate } from "react-router-dom";

interface BookCardProps {
  book: Book;
  index: number;
}

const BookCard = ({ book, index }: BookCardProps) => {
  const colors = genreColors[book.genre];
  const coverImage = genreCovers[book.genre];
  const price = bookPrices[book.id];
  const navigate = useNavigate();

  return (
    <div
      className="book-card-container cursor-pointer animate-float-up"
      style={{ animationDelay: `${index * 0.05}s` }}
      onClick={() => navigate(`/book/${book.id}`)}
    >
      <div className="book-card-inner rounded-lg border border-border overflow-hidden" style={{ minHeight: "320px" }}>
        {/* Front */}
        <div className="book-card-front bg-card">
          <div className="relative h-48 md:h-56 overflow-hidden">
            <img
              src={coverImage}
              alt={`${book.title} cover`}
              className="w-full h-full object-cover"
              loading="lazy"
            />
            <div className="absolute inset-0 bg-gradient-to-t from-card/60 to-transparent pointer-events-none" />
            {book.trending && (
              <span className="absolute top-2 right-2 font-pixel text-[8px] px-2 py-1 rounded bg-primary text-primary-foreground tracking-wider">
                ★ HOT
              </span>
            )}
          </div>
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

        {/* Back */}
        <div className="book-card-back bg-card rounded-lg border border-primary/30 flex flex-col items-center justify-center p-6 text-center gap-4">
          <h3 className="font-display text-lg font-bold text-foreground line-clamp-2">
            {book.title}
          </h3>
          <p className="font-body text-sm text-muted-foreground italic">{book.author}</p>

          <div className="w-full border-t border-border my-1" />

          <div className="flex items-center gap-2 text-primary">
            <Coins className="w-5 h-5" />
            <span className="font-pixel text-[10px] tracking-wider">
              {price?.cost.toLocaleString() ?? "FREE"} COINS
            </span>
          </div>

          <div className="flex items-center gap-2 text-accent-foreground">
            <Star className="w-5 h-5 text-primary" />
            <span className="font-pixel text-[10px] tracking-wider">
              +{price?.xpReward ?? 50} XP &amp; +{price?.coinReward ?? 100} COINS
            </span>
          </div>

          <button className="mt-2 px-6 py-2 rounded-lg bg-primary text-primary-foreground font-pixel text-[9px] tracking-wider hover:shadow-lg hover:shadow-primary/30 transition-all">
            ADD TO LIST
          </button>
        </div>
      </div>
    </div>
  );
};

export default BookCard;
