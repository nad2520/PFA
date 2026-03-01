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
    <section className="w-full px-4 py-20 md:py-28">
      <div className="max-w-6xl mx-auto space-y-24">
        {features.map((feature, i) => (
          <div
            key={feature.title}
            className={`flex flex-col md:flex-row items-center gap-10 md:gap-16 ${
              i % 2 === 1 ? "md:flex-row-reverse" : ""
            }`}
          >
            {/* Visual / Icon block */}
            <div className="flex-shrink-0 flex items-center justify-center w-full md:w-1/2">
              <div className="w-48 h-48 md:w-64 md:h-64 rounded-2xl bg-card border border-border flex items-center justify-center transition-all duration-300 hover:shadow-lg hover:shadow-primary/10">
                <feature.icon className="w-20 h-20 md:w-28 md:h-28 text-primary" />
              </div>
            </div>

            {/* Text block */}
            <div className="w-full md:w-1/2 text-center md:text-left">
              <h3 className="font-display text-2xl md:text-4xl font-bold text-foreground mb-4 italic">
                {feature.title}
              </h3>
              <p className="font-body text-base md:text-lg text-muted-foreground leading-relaxed max-w-md mx-auto md:mx-0">
                {feature.description}
              </p>
            </div>
          </div>
        ))}
      </div>
    </section>
  );
};

export default WhyLexora;
