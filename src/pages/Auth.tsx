import { useState } from "react";
import { Link, useNavigate } from "react-router-dom";
import { KeyRound, UserPlus, LogIn } from "lucide-react";
import { Button } from "@/components/ui/button";

const Auth = () => {
  const [isSignUp, setIsSignUp] = useState(false);
  const [username, setUsername] = useState("");
  const [password, setPassword] = useState("");
  const navigate = useNavigate();

  const handleSubmit = (e: React.FormEvent) => {
    e.preventDefault();
    // Mock auth — navigate to home
    navigate("/");
  };

  return (
    <div className="min-h-screen bg-background flex flex-col">
      {/* Nav */}
      <nav className="sticky top-0 z-50 bg-background/80 backdrop-blur-md border-b border-border">
        <div className="max-w-6xl mx-auto px-4 py-3 flex items-center justify-between">
          <Link to="/" className="font-pixel text-[10px] md:text-xs text-primary tracking-wider">
            📖 LEXORA
          </Link>
        </div>
      </nav>

      <div className="flex-1 flex items-center justify-center px-4 py-12">
        <div className="w-full max-w-md space-y-8">
          {/* Title scroll */}
          <div className="text-center space-y-2">
            <h1 className="font-display text-4xl md:text-5xl font-bold text-golden">
              Lexora
            </h1>
            <p className="font-pixel text-[9px] text-muted-foreground tracking-wider">
              ✦ YOUR COZY READING SANCTUARY ✦
            </p>
          </div>

          {/* Auth card */}
          <div className="rounded-xl border-2 border-primary/40 bg-card p-8 space-y-6 shadow-lg shadow-primary/5">
            <h2 className="font-display text-2xl font-bold text-foreground text-center">
              {isSignUp ? "Create Account" : "Sign In"}
            </h2>

            <form onSubmit={handleSubmit} className="space-y-5">
              {/* Username */}
              <div className="space-y-2">
                <label className="flex items-center gap-2 font-display text-sm font-semibold text-foreground">
                  <span className="text-lg">🐻</span> Username
                </label>
                <input
                  type="text"
                  value={username}
                  onChange={(e) => setUsername(e.target.value)}
                  placeholder="Username"
                  className="w-full px-4 py-3 rounded-lg bg-secondary border border-border text-foreground placeholder:text-muted-foreground focus:outline-none focus:ring-2 focus:ring-primary/50 focus:border-primary transition-all font-body"
                  required
                />
              </div>

              {/* Password */}
              <div className="space-y-2">
                <label className="flex items-center gap-2 font-display text-sm font-semibold text-foreground">
                  <KeyRound className="w-4 h-4 text-primary" /> Password
                </label>
                <input
                  type="password"
                  value={password}
                  onChange={(e) => setPassword(e.target.value)}
                  placeholder="••••••"
                  className="w-full px-4 py-3 rounded-lg bg-secondary border border-border text-foreground placeholder:text-muted-foreground focus:outline-none focus:ring-2 focus:ring-primary/50 focus:border-primary transition-all font-body"
                  required
                />
              </div>

              {isSignUp && (
                <div className="space-y-2">
                  <label className="flex items-center gap-2 font-display text-sm font-semibold text-foreground">
                    <KeyRound className="w-4 h-4 text-primary" /> Confirm Password
                  </label>
                  <input
                    type="password"
                    placeholder="••••••"
                    className="w-full px-4 py-3 rounded-lg bg-secondary border border-border text-foreground placeholder:text-muted-foreground focus:outline-none focus:ring-2 focus:ring-primary/50 focus:border-primary transition-all font-body"
                    required
                  />
                </div>
              )}

              <Button type="submit" className="w-full font-display text-lg font-semibold gap-2 py-6" size="lg">
                {isSignUp ? <UserPlus className="w-5 h-5" /> : <LogIn className="w-5 h-5" />}
                {isSignUp ? "Register" : "Sign In"}
              </Button>
            </form>

            {/* Toggle */}
            <div className="flex items-center justify-center gap-4 text-sm">
              <button
                onClick={() => setIsSignUp(!isSignUp)}
                className="font-body text-primary hover:underline transition-colors flex items-center gap-1"
              >
                {isSignUp ? (
                  <>✦ Already have an account? Sign In</>
                ) : (
                  <>✦ Register</>
                )}
              </button>
              {!isSignUp && (
                <span className="font-body text-muted-foreground">
                  🔒 Forgot Password?
                </span>
              )}
            </div>
          </div>
        </div>
      </div>
    </div>
  );
};

export default Auth;
