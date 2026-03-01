import { Link } from "react-router-dom";
import { BookOpen, Coins, Map, UserPlus, Compass, Unlock, TrendingUp } from "lucide-react";
import { useEffect, useRef } from "react";
import lumoHappy from "@/assets/lumo-happy.png";

/* ── floating dust canvas ── */
const DustCanvas = () => {
  const canvasRef = useRef<HTMLCanvasElement>(null);

  useEffect(() => {
    const canvas = canvasRef.current;
    if (!canvas) return;
    const ctx = canvas.getContext("2d");
    if (!ctx) return;

    let animId: number;
    const particles: { x: number; y: number; r: number; vy: number; vx: number; o: number }[] = [];

    const resize = () => {
      canvas.width = window.innerWidth;
      canvas.height = window.innerHeight;
    };
    resize();
    window.addEventListener("resize", resize);

    for (let i = 0; i < 60; i++) {
      particles.push({
        x: Math.random() * canvas.width,
        y: Math.random() * canvas.height,
        r: Math.random() * 2 + 0.5,
        vy: -(Math.random() * 0.3 + 0.1),
        vx: (Math.random() - 0.5) * 0.2,
        o: Math.random() * 0.5 + 0.2,
      });
    }

    const draw = () => {
      ctx.clearRect(0, 0, canvas.width, canvas.height);
      particles.forEach((p) => {
        ctx.beginPath();
        ctx.arc(p.x, p.y, p.r, 0, Math.PI * 2);
        ctx.fillStyle = `hsla(38,80%,65%,${p.o})`;
        ctx.fill();
        p.y += p.vy;
        p.x += p.vx;
        if (p.y < -10) {
          p.y = canvas.height + 10;
          p.x = Math.random() * canvas.width;
        }
      });
      animId = requestAnimationFrame(draw);
    };
    draw();

    return () => {
      cancelAnimationFrame(animId);
      window.removeEventListener("resize", resize);
    };
  }, []);

  return <canvas ref={canvasRef} className="absolute inset-0 pointer-events-none z-10" />;
};

/* ── fade-in on scroll observer ── */
const useFadeIn = () => {
  const ref = useRef<HTMLDivElement>(null);
  useEffect(() => {
    const el = ref.current;
    if (!el) return;
    const obs = new IntersectionObserver(
      ([e]) => {
        if (e.isIntersecting) {
          el.classList.add("opacity-100", "translate-y-0");
          el.classList.remove("opacity-0", "translate-y-8");
        }
      },
      { threshold: 0.15 }
    );
    obs.observe(el);
    return () => obs.disconnect();
  }, []);
  return ref;
};

const FadeSection = ({ children, className = "" }: { children: React.ReactNode; className?: string }) => {
  const ref = useFadeIn();
  return (
    <div ref={ref} className={`transition-all duration-700 ease-out opacity-0 translate-y-8 ${className}`}>
      {children}
    </div>
  );
};

/* ── feature cards data ── */
const features = [
  { icon: Coins, title: "Unlock Books with Coins", desc: "Spend coins earned through reading to unlock new adventures." },
  { icon: TrendingUp, title: "Earn XP & Rewards", desc: "Complete books, earn experience, and level up your reader profile." },
  { icon: Map, title: "Progress Through a Fantasy Map", desc: "Watch your journey unfold across a magical Scholar's Map." },
];

const steps = [
  { icon: UserPlus, title: "Create Your Account", desc: "Meet Lumo and enter the world." },
  { icon: Compass, title: "Choose a Genre World", desc: "Explore Romance, Fantasy, Mystery, Horror, and more." },
  { icon: Unlock, title: "Unlock & Read", desc: "Spend coins to unlock books and start reading." },
  { icon: TrendingUp, title: "Earn & Level Up", desc: "Finish books, earn rewards, climb the Scholar's Map." },
];

/* ── page ── */
const Landing = () => {
  return (
    <div className="min-h-screen bg-background text-foreground overflow-x-hidden">
      {/* ═══════ HERO ═══════ */}
      <section className="relative w-full min-h-screen flex items-center justify-center overflow-hidden">
        {/* bg gradient */}
        <div className="absolute inset-0 bg-gradient-to-b from-[hsl(24,20%,8%)] via-background to-background" />
        {/* lantern glow blobs */}
        <div className="absolute top-[15%] left-[20%] w-64 h-64 rounded-full bg-primary/10 blur-[100px] animate-breathe" />
        <div className="absolute top-[30%] right-[15%] w-48 h-48 rounded-full bg-primary/8 blur-[80px] animate-breathe" style={{ animationDelay: "2s" }} />
        <DustCanvas />

        <div className="relative z-20 max-w-3xl mx-auto px-4 text-center space-y-8">
          <h1 className="font-display text-6xl md:text-8xl font-bold text-golden animate-float-up">
            Lexora
          </h1>
          <p className="font-display text-lg md:text-xl text-foreground/80 italic animate-float-up" style={{ animationDelay: "0.1s" }}>
            "A living world of stories, guided by Lumo."
          </p>

          <div className="animate-float-up max-w-lg mx-auto space-y-4" style={{ animationDelay: "0.25s" }}>
            <p className="font-body text-base md:text-lg text-muted-foreground leading-relaxed">
              One quiet night in an ancient digital library,<br />
              a forgotten book began to glow…<br />
              From its pages, a tiny guardian was born.<br />
              His name was <span className="text-primary font-semibold">Lumo</span>.<br />
              And his mission?<br />
              <span className="text-foreground">To guide readers through worlds made of words.</span>
            </p>
          </div>

          <div className="flex flex-col sm:flex-row gap-4 justify-center animate-float-up" style={{ animationDelay: "0.4s" }}>
            <Link
              to="/auth"
              className="inline-block px-10 py-4 rounded-full bg-primary text-primary-foreground font-display text-lg font-semibold tracking-wide transition-all duration-300 hover:shadow-[0_0_40px_hsl(38_90%_60%/0.5)] hover:-translate-y-1 hover:scale-105 border border-primary/60"
            >
              ✦ Begin Your Journey ✦
            </Link>
            <Link
              to="/auth"
              className="inline-block px-8 py-4 rounded-full border-2 border-primary/40 text-primary font-display text-base font-semibold tracking-wide transition-all duration-300 hover:bg-primary/10 hover:-translate-y-0.5"
            >
              Sign In / Sign Up
            </Link>
          </div>
        </div>

        {/* bottom fade */}
        <div className="absolute bottom-0 left-0 right-0 h-32 bg-gradient-to-t from-background to-transparent z-20" />
      </section>

      {/* ═══════ WHAT IS LEXORA ═══════ */}
      <section className="py-24 px-4">
        <div className="max-w-5xl mx-auto">
          <FadeSection className="text-center mb-16">
            <h2 className="font-display text-3xl md:text-5xl font-bold text-foreground mb-4">What Is Lexora?</h2>
            <p className="font-body text-base md:text-lg text-muted-foreground max-w-2xl mx-auto leading-relaxed">
              Lexora is not just an ebook website. It is a <span className="text-primary font-semibold">gamified reading universe</span> where readers unlock books, earn coins, level up through a fantasy map, and build their own digital library.
            </p>
          </FadeSection>

          <div className="grid md:grid-cols-3 gap-6">
            {features.map((f, i) => (
              <FadeSection key={f.title}>
                <div
                  className="rounded-xl border-2 border-primary/30 bg-card p-8 text-center space-y-4 transition-all duration-300 hover:-translate-y-2 hover:shadow-lg hover:shadow-primary/10 hover:border-primary/60"
                  style={{ transitionDelay: `${i * 80}ms` }}
                >
                  <div className="w-16 h-16 mx-auto rounded-xl bg-primary/10 flex items-center justify-center">
                    <f.icon className="w-8 h-8 text-primary" />
                  </div>
                  <h3 className="font-display text-xl font-bold text-foreground">{f.title}</h3>
                  <p className="font-body text-sm text-muted-foreground">{f.desc}</p>
                </div>
              </FadeSection>
            ))}
          </div>
        </div>
      </section>

      {/* ═══════ HOW IT WORKS ═══════ */}
      <section className="py-24 px-4 bg-card/50">
        <div className="max-w-5xl mx-auto">
          <FadeSection className="text-center mb-16">
            <h2 className="font-display text-3xl md:text-5xl font-bold text-foreground mb-4">How Your Journey Begins</h2>
          </FadeSection>

          <div className="grid sm:grid-cols-2 lg:grid-cols-4 gap-8">
            {steps.map((s, i) => (
              <FadeSection key={s.title}>
                <div className="relative text-center space-y-4">
                  {/* step number */}
                  <div className="font-pixel text-[10px] text-primary tracking-widest mb-2">STEP {i + 1}</div>
                  <div className="w-14 h-14 mx-auto rounded-full border-2 border-primary/40 bg-secondary flex items-center justify-center">
                    <s.icon className="w-6 h-6 text-primary" />
                  </div>
                  <h3 className="font-display text-lg font-bold text-foreground">{s.title}</h3>
                  <p className="font-body text-sm text-muted-foreground">{s.desc}</p>
                  {/* golden connector line */}
                  {i < steps.length - 1 && (
                    <div className="hidden lg:block absolute top-10 left-[calc(50%+40px)] w-[calc(100%-80px)] h-px bg-gradient-to-r from-primary/40 to-primary/10" />
                  )}
                </div>
              </FadeSection>
            ))}
          </div>
        </div>
      </section>

      {/* ═══════ MEET LUMO ═══════ */}
      <section className="py-24 px-4">
        <div className="max-w-5xl mx-auto">
          <FadeSection>
            <div className="flex flex-col md:flex-row items-center gap-12 md:gap-16">
              {/* Lumo image */}
              <div className="flex-shrink-0 relative">
                <div className="absolute inset-0 rounded-full bg-primary/15 blur-[60px] scale-125" />
                <img
                  src={lumoHappy}
                  alt="Lumo the teddy bear mascot"
                  className="relative w-48 h-48 md:w-64 md:h-64 object-contain animate-lumo-float drop-shadow-[0_0_30px_hsl(38,80%,55%,0.3)]"
                />
              </div>

              {/* text */}
              <div className="text-center md:text-left space-y-4">
                <h2 className="font-display text-3xl md:text-5xl font-bold text-foreground">
                  Meet <span className="text-golden">Lumo</span>
                </h2>
                <p className="font-display text-lg italic text-muted-foreground">Your Reading Companion</p>
                <p className="font-body text-base md:text-lg text-muted-foreground leading-relaxed max-w-lg">
                  Lumo tracks your progress, gives recommendations, celebrates your achievements, and keeps your reading journey alive. He's always by your side — a warm, fuzzy guide through every chapter.
                </p>
              </div>
            </div>
          </FadeSection>
        </div>
      </section>

      {/* ═══════ FINAL CTA ═══════ */}
      <section className="relative py-32 px-4 overflow-hidden">
        <div className="absolute inset-0 bg-gradient-to-b from-background via-[hsl(24,20%,8%)] to-[hsl(24,20%,6%)]" />
        <div className="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-96 h-96 rounded-full bg-primary/8 blur-[120px]" />

        <FadeSection className="relative z-10 text-center max-w-2xl mx-auto space-y-8">
          <p className="font-display text-2xl md:text-4xl font-bold text-foreground leading-snug">
            "Every story is a door.<br />
            <span className="text-golden">Are you ready to open yours?</span>"
          </p>
          <Link
            to="/auth"
            className="inline-block px-12 py-5 rounded-full bg-primary text-primary-foreground font-display text-xl font-semibold tracking-wide transition-all duration-300 hover:shadow-[0_0_50px_hsl(38_90%_60%/0.5)] hover:-translate-y-1 hover:scale-105 border border-primary/60"
          >
            ✦ Enter Lexora ✦
          </Link>
        </FadeSection>
      </section>

      {/* footer */}
      <footer className="border-t border-border py-8 text-center">
        <p className="font-pixel text-[8px] text-muted-foreground tracking-wider">
          ✦ LEXORA — A cozy corner for readers ✦
        </p>
      </footer>
    </div>
  );
};

export default Landing;
