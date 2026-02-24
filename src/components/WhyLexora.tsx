import { BookOpen, RefreshCw, Users } from "lucide-react";

const features = [
  {
    icon: BookOpen,
    title: "Curated Collections",
    description:
      "Hand-picked reading lists organized by mood, theme, and genre — so you always find something worth your time.",
  },
  {
    icon: RefreshCw,
    title: "Cross-Platform Sync",
    description:
      "Start reading on your laptop, continue on your phone. Your bookmarks and progress travel with you everywhere.",
  },
  {
    icon: Users,
    title: "Community Reviews",
    description:
      "See what fellow readers think before you dive in. Rate, review, and discover hidden gems together.",
  },
];

const WhyLexora = () => {
  return (
    <section className="w-full max-w-6xl mx-auto px-4 py-16">
      <div className="text-center mb-12">
        <p className="font-pixel text-[10px] tracking-[0.3em] uppercase text-primary mb-3">
          ✦ Why Lexora? ✦
        </p>
        <h2 className="font-display text-2xl md:text-4xl font-bold text-foreground">
          Everything a reader needs
        </h2>
      </div>

      <div className="grid grid-cols-1 md:grid-cols-3 gap-8">
        {features.map((feature, i) => (
          <div
            key={feature.title}
            className="rounded-lg border border-border bg-card p-8 text-center transition-all duration-300 hover:-translate-y-1 hover:shadow-lg hover:shadow-primary/10 animate-float-up"
            style={{ animationDelay: `${i * 0.1}s` }}
          >
            <div className="mx-auto mb-5 flex h-14 w-14 items-center justify-center rounded-full bg-primary/15">
              <feature.icon className="h-7 w-7 text-primary" />
            </div>
            <h3 className="font-display text-xl font-semibold text-foreground mb-3">
              {feature.title}
            </h3>
            <p className="font-body text-base text-muted-foreground leading-relaxed">
              {feature.description}
            </p>
          </div>
        ))}
      </div>
    </section>
  );
};

export default WhyLexora;
