import heroImage from "@/assets/hero-library.png";

const HeroSection = () => {
  return (
    <section className="relative w-full overflow-hidden">
      {/* Background image */}
      <div className="relative w-full h-[420px] md:h-[500px] animate-breathe">
        <img
          src={heroImage}
          alt="Cozy pixel art library with a teddy bear reading"
          className="w-full h-full object-cover"
          loading="eager"
        />
        {/* Vignette overlay */}
        <div className="absolute inset-0 vignette pointer-events-none" />
        {/* Bottom gradient fade */}
        <div className="absolute bottom-0 left-0 right-0 h-32 bg-gradient-to-t from-background to-transparent" />
      </div>

      {/* Hero content overlay */}
      <div className="absolute inset-0 flex flex-col items-center justify-center px-4">
        <h1 className="font-display text-4xl md:text-6xl lg:text-7xl font-bold text-golden text-center mb-8 animate-float-up">
          Lexora
        </h1>

        {/* Get Started CTA */}
        <div className="animate-float-up" style={{ animationDelay: "0.3s" }}>
          <a
            href="#catalog"
            className="inline-block px-10 py-4 rounded-full bg-primary text-primary-foreground font-display text-lg font-semibold tracking-wide transition-all duration-300 hover:shadow-[0_0_30px_hsl(38_90%_60%/0.5)] hover:-translate-y-1 hover:scale-105 border border-primary/60"
          >
            Get Started ✦
          </a>
        </div>
      </div>
    </section>
  );
};

export default HeroSection;
