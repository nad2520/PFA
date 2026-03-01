import { useParams, useNavigate, Link } from "react-router-dom";
import { books, genreCovers, genreColors } from "@/data/books";
import { bookPrices } from "@/data/bookPrices";
import { mockUserBooks } from "@/data/userData";
import { communityPosts, type CommunityPost } from "@/data/communityData";
import { ArrowLeft, Coins, Star, BookOpen, MessageSquare, ArrowUp, Clock, TrendingUp, Filter } from "lucide-react";
import { Avatar, AvatarFallback } from "@/components/ui/avatar";
import { Button } from "@/components/ui/button";
import { Progress } from "@/components/ui/progress";
import { Badge } from "@/components/ui/badge";
import { useState } from "react";
import GlobalHeader from "@/components/GlobalHeader";

type SortMode = "top" | "recent" | "trending";

const tagColors: Record<string, string> = {
  discussion: "bg-accent text-accent-foreground",
  review: "bg-primary/20 text-primary",
  theory: "bg-burgundy/30 text-foreground",
  spoiler: "bg-destructive/20 text-destructive",
};

const PostCard = ({ post }: { post: CommunityPost }) => (
  <div className="group rounded-lg border border-border bg-card p-5 transition-all hover:border-primary/30 hover:shadow-md hover:shadow-primary/5">
    <div className="flex gap-4">
      {/* Upvote column */}
      <div className="flex flex-col items-center gap-1 pt-1">
        <button className="text-muted-foreground hover:text-primary transition-colors">
          <ArrowUp className="w-5 h-5" />
        </button>
        <span className="font-pixel text-[9px] text-primary">{post.upvotes}</span>
      </div>

      {/* Content */}
      <div className="flex-1 min-w-0 space-y-2">
        <div className="flex items-center gap-2 flex-wrap">
          <Avatar className="h-6 w-6">
            <AvatarFallback className="text-[10px] bg-secondary text-secondary-foreground font-pixel">
              {post.avatarInitials}
            </AvatarFallback>
          </Avatar>
          <span className="font-body text-sm text-muted-foreground">{post.author}</span>
          <span className="text-muted-foreground/50 text-xs">·</span>
          <span className="text-xs text-muted-foreground/60">{post.timeAgo}</span>
          {post.tag && (
            <span className={`px-2 py-0.5 rounded-full text-[10px] font-pixel tracking-wider uppercase ${tagColors[post.tag]}`}>
              {post.tag}
            </span>
          )}
        </div>
        <h4 className="font-display text-base font-semibold text-foreground leading-tight group-hover:text-primary transition-colors">
          {post.title}
        </h4>
        <p className="font-body text-sm text-muted-foreground line-clamp-2 leading-relaxed">{post.preview}</p>
        <div className="flex items-center gap-4 pt-1">
          <button className="flex items-center gap-1.5 text-muted-foreground hover:text-primary transition-colors text-xs">
            <MessageSquare className="w-4 h-4" />
            <span className="font-body">{post.comments} comments</span>
          </button>
        </div>
      </div>
    </div>
  </div>
);

const BookDetail = () => {
  const { id } = useParams<{ id: string }>();
  const navigate = useNavigate();
  const [sortMode, setSortMode] = useState<SortMode>("top");

  const book = books.find((b) => b.id === Number(id));
  if (!book) {
    return (
      <div className="min-h-screen bg-background flex items-center justify-center">
        <div className="text-center space-y-4">
          <h1 className="font-display text-3xl text-foreground">Book not found</h1>
          <Button variant="outline" onClick={() => navigate("/")}>Return Home</Button>
        </div>
      </div>
    );
  }

  const coverImage = genreCovers[book.genre];
  const colors = genreColors[book.genre];
  const price = bookPrices[book.id];
  const userBook = mockUserBooks.find((ub) => ub.book.id === book.id);
  const isReading = userBook?.status === "reading";
  const isCompleted = userBook?.status === "completed";

  const posts = communityPosts
    .filter((p) => p.bookId === book.id)
    .sort((a, b) => {
      if (sortMode === "top") return b.upvotes - a.upvotes;
      if (sortMode === "trending") return b.comments - a.comments;
      return 0; // recent — already in order
    });

  return (
    <div className="min-h-screen bg-background">
      <GlobalHeader>
        <button onClick={() => navigate(-1)} className="text-muted-foreground hover:text-foreground transition-colors flex items-center gap-1.5">
          <ArrowLeft className="w-4 h-4" />
          <span className="font-body text-sm">Back</span>
        </button>
        <Link to="/store" className="font-body text-sm text-muted-foreground hover:text-foreground transition-colors">
          My Store
        </Link>
      </GlobalHeader>

      <main className="max-w-5xl mx-auto px-4 py-8 space-y-12">
        {/* ─── Book Info Section ─── */}
        <section className="flex flex-col md:flex-row gap-8 md:gap-12">
          {/* Cover */}
          <div className="flex-shrink-0 mx-auto md:mx-0">
            <div className="relative w-64 md:w-72 rounded-xl overflow-hidden shadow-2xl shadow-warm-shadow/60 border border-border">
              <img src={coverImage} alt={book.title} className="w-full aspect-[2/3] object-cover" />
              <div className="absolute inset-0 bg-gradient-to-t from-card/40 to-transparent pointer-events-none" />
              {book.trending && (
                <span className="absolute top-3 right-3 font-pixel text-[8px] px-2 py-1 rounded bg-primary text-primary-foreground tracking-wider">★ HOT</span>
              )}
            </div>
          </div>

          {/* Details */}
          <div className="flex-1 space-y-6">
            <div className="space-y-2">
              <span className={`genre-tag inline-block ${colors.bg} ${colors.text}`}>{book.genre}</span>
              <h1 className="font-display text-3xl md:text-4xl font-bold text-foreground leading-tight">{book.title}</h1>
              <p className="font-body text-lg text-muted-foreground italic">by {book.author}</p>
            </div>

            <p className="font-body text-base text-foreground/80 leading-relaxed max-w-xl">{book.description}</p>

            {/* Coins & Rewards */}
            <div className="flex flex-wrap gap-4">
              <div className="flex items-center gap-2 px-4 py-2.5 rounded-lg bg-card border border-border">
                <Coins className="w-5 h-5 text-primary" />
                <div>
                  <p className="font-pixel text-[9px] tracking-wider text-muted-foreground">COST</p>
                  <p className="font-pixel text-[11px] tracking-wider text-primary">{price?.cost.toLocaleString() ?? "FREE"}</p>
                </div>
              </div>
              <div className="flex items-center gap-2 px-4 py-2.5 rounded-lg bg-card border border-border">
                <Star className="w-5 h-5 text-primary" />
                <div>
                  <p className="font-pixel text-[9px] tracking-wider text-muted-foreground">EARN</p>
                  <p className="font-pixel text-[11px] tracking-wider text-accent-foreground">+{price?.xpReward ?? 50} XP · +{price?.coinReward ?? 100} Coins</p>
                </div>
              </div>
            </div>

            {/* Reading progress */}
            {isReading && userBook?.progress != null && (
              <div className="space-y-2 max-w-sm">
                <div className="flex justify-between text-sm font-body">
                  <span className="text-muted-foreground">Reading Progress</span>
                  <span className="text-primary font-semibold">{userBook.progress}%</span>
                </div>
                <Progress value={userBook.progress} className="h-2.5" />
              </div>
            )}

            {isCompleted && (
              <Badge className="bg-accent text-accent-foreground font-pixel text-[9px] tracking-wider">✓ COMPLETED</Badge>
            )}

            {/* CTA */}
            <Button
              size="lg"
              className="font-pixel text-[10px] tracking-wider px-8 shadow-lg shadow-primary/20 hover:shadow-primary/40 transition-all"
            >
              <BookOpen className="w-4 h-4 mr-2" />
              {isReading ? "CONTINUE READING" : isCompleted ? "READ AGAIN" : "START READING"}
            </Button>
          </div>
        </section>

        {/* ─── Divider ─── */}
        <div className="border-t border-border" />

        {/* ─── Community Section ─── */}
        <section className="space-y-6">
          <div className="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
              <h2 className="font-display text-2xl font-bold text-foreground">Book Community</h2>
              <p className="font-body text-sm text-muted-foreground mt-1">Join the discussion about <span className="text-primary italic">{book.title}</span></p>
            </div>

            {/* Sort tabs */}
            <div className="flex items-center gap-1 p-1 rounded-lg bg-card border border-border">
              {([
                { key: "top" as SortMode, icon: ArrowUp, label: "Top" },
                { key: "recent" as SortMode, icon: Clock, label: "Recent" },
                { key: "trending" as SortMode, icon: TrendingUp, label: "Trending" },
              ]).map(({ key, icon: Icon, label }) => (
                <button
                  key={key}
                  onClick={() => setSortMode(key)}
                  className={`flex items-center gap-1.5 px-3 py-1.5 rounded-md text-xs font-body transition-all ${
                    sortMode === key
                      ? "bg-primary text-primary-foreground shadow-sm"
                      : "text-muted-foreground hover:text-foreground"
                  }`}
                >
                  <Icon className="w-3.5 h-3.5" />
                  {label}
                </button>
              ))}
            </div>
          </div>

          {/* Posts */}
          <div className="space-y-3">
            {posts.length > 0 ? (
              posts.map((post) => <PostCard key={post.id} post={post} />)
            ) : (
              <div className="text-center py-16 space-y-3">
                <MessageSquare className="w-10 h-10 text-muted-foreground/40 mx-auto" />
                <p className="font-display text-lg text-muted-foreground">No discussions yet</p>
                <p className="font-body text-sm text-muted-foreground/60">Be the first to start a conversation about this book!</p>
              </div>
            )}
          </div>
        </section>
      </main>

      {/* Footer */}
      <footer className="border-t border-border py-8 text-center">
        <p className="font-pixel text-[8px] tracking-widest text-muted-foreground">© LEXORA · THE READING KINGDOM</p>
      </footer>
    </div>
  );
};

export default BookDetail;
