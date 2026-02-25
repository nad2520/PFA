import { Link } from "react-router-dom";
import { ArrowLeft, Flame, BookOpen, Trophy, Coins } from "lucide-react";
import { mockUser, mockUserBooks } from "@/data/userData";
import { genreCovers, genreColors } from "@/data/books";
import { Progress } from "@/components/ui/progress";

const CircularProgress = ({ value, max, label }: { value: number; max: number; label: string }) => {
  const pct = Math.min((value / max) * 100, 100);
  const r = 40;
  const circ = 2 * Math.PI * r;
  const offset = circ - (pct / 100) * circ;

  return (
    <div className="flex flex-col items-center gap-2">
      <div className="relative w-24 h-24">
        <svg className="w-full h-full -rotate-90" viewBox="0 0 100 100">
          <circle cx="50" cy="50" r={r} fill="none" stroke="hsl(var(--border))" strokeWidth="6" />
          <circle
            cx="50" cy="50" r={r} fill="none"
            stroke="hsl(var(--primary))"
            strokeWidth="6" strokeLinecap="round"
            strokeDasharray={circ} strokeDashoffset={offset}
            className="transition-all duration-700"
          />
        </svg>
        <div className="absolute inset-0 flex items-center justify-center">
          <span className="font-display text-lg font-bold text-foreground">{value}h</span>
        </div>
      </div>
      <span className="font-body text-sm text-muted-foreground">{label}</span>
    </div>
  );
};

const Profile = () => {
  const library = mockUserBooks.filter((b) => b.status === "reading" || b.status === "completed");
  const planToRead = mockUserBooks.filter((b) => b.status === "plan-to-read");

  return (
    <div className="min-h-screen bg-background">
      {/* Nav */}
      <nav className="sticky top-0 z-50 bg-background/80 backdrop-blur-md border-b border-border">
        <div className="max-w-6xl mx-auto px-4 py-3 flex items-center justify-between">
          <Link to="/" className="font-pixel text-[10px] md:text-xs text-primary tracking-wider">
            📖 LEXORA
          </Link>
          <div className="flex gap-4 items-center">
            <Link to="/profile" className="font-body text-sm text-foreground transition-colors">
              Profile
            </Link>
            <a href="/#why" className="font-body text-sm text-muted-foreground hover:text-foreground transition-colors">
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

      <div className="max-w-6xl mx-auto px-4 py-10 space-y-12">
        {/* Header Section */}
        <section className="rounded-xl border border-border bg-card p-6 md:p-8">
          <div className="flex flex-col md:flex-row items-center gap-8">
            {/* Avatar & Name */}
            <div className="flex flex-col items-center gap-3">
              <div className="w-20 h-20 rounded-full bg-primary/20 border-2 border-primary flex items-center justify-center">
                <span className="font-pixel text-2xl">📚</span>
              </div>
              <h1 className="font-display text-2xl md:text-3xl font-bold text-foreground">
                {mockUser.name}
              </h1>
              {/* Level bar */}
              <div className="w-full max-w-[200px] space-y-1">
                <div className="flex items-center justify-between">
                  <span className="font-pixel text-[8px] text-primary tracking-wider flex items-center gap-1">
                    <Trophy className="w-3 h-3" /> LVL {mockUser.level}
                  </span>
                  <span className="font-pixel text-[8px] text-muted-foreground">
                    {mockUser.levelProgress}%
                  </span>
                </div>
                <Progress value={mockUser.levelProgress} className="h-2" />
              </div>
            </div>

            {/* Stats */}
            <div className="flex-1 flex flex-wrap items-center justify-center md:justify-evenly gap-8">
              <CircularProgress
                value={mockUser.dailyReadingHours}
                max={mockUser.dailyReadingGoal}
                label={`of ${mockUser.dailyReadingGoal}h goal`}
              />

              <div className="flex flex-col items-center gap-2">
                <div className="w-24 h-24 rounded-full bg-secondary flex items-center justify-center">
                  <Coins className="w-8 h-8 text-primary" />
                </div>
                <span className="font-display text-lg font-bold text-foreground">{mockUser.coins.toLocaleString()}</span>
                <span className="font-body text-sm text-muted-foreground">Coins</span>
              </div>

              <div className="flex flex-col items-center gap-2">
                <div className="w-24 h-24 rounded-full bg-secondary flex items-center justify-center">
                  <Flame className="w-8 h-8 text-destructive" />
                </div>
                <span className="font-display text-lg font-bold text-foreground">{library.length}</span>
                <span className="font-body text-sm text-muted-foreground">Books Read</span>
              </div>
            </div>
          </div>
        </section>

        {/* My Library */}
        <section className="space-y-4">
          <h2 className="font-display text-2xl font-bold text-foreground flex items-center gap-2">
            <BookOpen className="w-6 h-6 text-primary" /> My Library
          </h2>
          <div className="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4 md:gap-6">
            {library.map((ub) => {
              const colors = genreColors[ub.book.genre];
              const cover = genreCovers[ub.book.genre];
              return (
                <div key={ub.book.id} className="book-card rounded-lg border border-border bg-card overflow-hidden">
                  <div className="relative h-48 md:h-56 overflow-hidden">
                    <img src={cover} alt={ub.book.title} className="w-full h-full object-cover" loading="lazy" />
                    <div className="absolute inset-0 bg-gradient-to-t from-card/60 to-transparent pointer-events-none" />
                    <span className={`absolute top-2 right-2 font-pixel text-[8px] px-2 py-1 rounded ${
                      ub.status === "completed" ? "bg-accent text-accent-foreground" : "bg-primary text-primary-foreground"
                    } tracking-wider`}>
                      {ub.status === "completed" ? "✓ DONE" : "READING"}
                    </span>
                  </div>
                  <div className="p-4 space-y-2">
                    <h3 className="font-display text-base font-semibold leading-tight line-clamp-1 text-foreground">
                      {ub.book.title}
                    </h3>
                    <p className="font-body text-sm text-muted-foreground line-clamp-1">{ub.book.author}</p>
                    {ub.status === "reading" && ub.progress != null && (
                      <div className="space-y-1">
                        <Progress value={ub.progress} className="h-1.5" />
                        <p className="font-pixel text-[7px] text-muted-foreground text-right">{ub.progress}%</p>
                      </div>
                    )}
                    <span className={`genre-tag inline-block ${colors.bg} ${colors.text}`}>
                      {ub.book.genre}
                    </span>
                  </div>
                </div>
              );
            })}
          </div>
        </section>

        {/* My List */}
        <section className="space-y-4">
          <h2 className="font-display text-2xl font-bold text-foreground flex items-center gap-2">
            📋 My List
          </h2>
          {planToRead.length === 0 ? (
            <div className="rounded-xl border border-dashed border-border bg-card/50 p-12 text-center space-y-4">
              <p className="font-pixel text-xs text-muted-foreground">Your list is empty!</p>
              <Link
                to="/#catalog"
                className="inline-block px-6 py-3 rounded-lg bg-primary text-primary-foreground font-display text-base font-semibold hover:shadow-lg hover:shadow-primary/30 transition-all"
              >
                Find your first book! ✦
              </Link>
            </div>
          ) : (
            <div className="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4 md:gap-6">
              {planToRead.map((ub) => {
                const colors = genreColors[ub.book.genre];
                const cover = genreCovers[ub.book.genre];
                return (
                  <div key={ub.book.id} className="book-card rounded-lg border border-border bg-card overflow-hidden">
                    <div className="relative h-48 md:h-56 overflow-hidden">
                      <img src={cover} alt={ub.book.title} className="w-full h-full object-cover" loading="lazy" />
                      <div className="absolute inset-0 bg-gradient-to-t from-card/60 to-transparent pointer-events-none" />
                      <span className="absolute top-2 right-2 font-pixel text-[8px] px-2 py-1 rounded bg-secondary text-secondary-foreground tracking-wider">
                        PLAN
                      </span>
                    </div>
                    <div className="p-4 space-y-2">
                      <h3 className="font-display text-base font-semibold leading-tight line-clamp-1 text-foreground">
                        {ub.book.title}
                      </h3>
                      <p className="font-body text-sm text-muted-foreground line-clamp-1">{ub.book.author}</p>
                      <span className={`genre-tag inline-block ${colors.bg} ${colors.text}`}>
                        {ub.book.genre}
                      </span>
                    </div>
                  </div>
                );
              })}
            </div>
          )}
        </section>
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

export default Profile;
