import { Link } from "react-router-dom";
import { CreditCard, ShieldCheck, Crown, Sparkles, Gem } from "lucide-react";
import { Button } from "@/components/ui/button";
import GlobalHeader from "@/components/GlobalHeader";

const tiers = [
  {
    name: "Scribe's Penny",
    coins: 100,
    price: "$0.99",
    icon: Sparkles,
    popular: false,
  },
  {
    name: "Scholar's Purse",
    coins: 600,
    price: "$4.99",
    icon: Crown,
    popular: true,
  },
  {
    name: "Imperial Vault",
    coins: 2500,
    price: "$19.99",
    icon: Gem,
    popular: false,
  },
];

const Store = () => {
  return (
    <div className="min-h-screen bg-background">
      <GlobalHeader>
        <Link to="/#why" className="font-body text-sm text-muted-foreground hover:text-foreground transition-colors">
          About
        </Link>
        <Link to="/store" className="font-body text-sm text-foreground transition-colors">
          My Store
        </Link>
      </GlobalHeader>

      {/* Header */}
      <section className="py-16 md:py-24 text-center space-y-4">
        <h1 className="font-display text-4xl md:text-6xl font-bold text-golden">
          Imperial Treasury
        </h1>
        <p className="font-body text-lg md:text-xl text-muted-foreground max-w-md mx-auto">
          Acquire premium book volumes or test your luck.
        </p>
      </section>

      {/* Tiers Grid */}
      <section className="max-w-5xl mx-auto px-4 pb-20">
        <div className="grid grid-cols-1 md:grid-cols-3 gap-6 md:gap-8">
          {tiers.map((tier) => {
            const Icon = tier.icon;
            return (
              <div
                key={tier.name}
                className={`relative rounded-xl border-2 bg-card p-8 flex flex-col items-center text-center gap-6 transition-all duration-300 hover:-translate-y-2 hover:shadow-lg hover:shadow-primary/10 ${
                  tier.popular
                    ? "border-primary shadow-md shadow-primary/20"
                    : "border-border"
                }`}
              >
                {tier.popular && (
                  <span className="absolute -top-3 left-1/2 -translate-x-1/2 font-pixel text-[8px] bg-primary text-primary-foreground px-4 py-1 rounded-full tracking-wider">
                    MOST POPULAR
                  </span>
                )}

                <div
                  className={`w-20 h-20 rounded-full flex items-center justify-center ${
                    tier.popular ? "bg-primary/20" : "bg-secondary"
                  }`}
                >
                  <Icon className={`w-10 h-10 ${tier.popular ? "text-primary" : "text-muted-foreground"}`} />
                </div>

                <div className="space-y-1">
                  <h2 className="font-display text-2xl font-bold text-foreground">{tier.name}</h2>
                  <p className="font-pixel text-xs text-primary tracking-wider">
                    {tier.coins.toLocaleString()} COINS
                  </p>
                </div>

                <p className="font-display text-4xl font-bold text-foreground">{tier.price}</p>

                <Button
                  className={`w-full font-display text-base font-semibold gap-2 ${
                    tier.popular ? "shadow-lg shadow-primary/30" : ""
                  }`}
                  size="lg"
                >
                  <CreditCard className="w-5 h-5" />
                  Buy Now
                </Button>
              </div>
            );
          })}
        </div>
      </section>

      {/* Secure Checkout Footer */}
      <footer className="border-t border-border py-8 text-center space-y-2">
        <div className="flex items-center justify-center gap-2 text-muted-foreground">
          <ShieldCheck className="w-5 h-5 text-accent-foreground" />
          <span className="font-body text-sm">Secure Checkout — Your payment is encrypted and safe</span>
        </div>
        <p className="font-pixel text-[8px] text-muted-foreground tracking-wider">
          ✦ LEXORA — A cozy corner for readers ✦
        </p>
      </footer>
    </div>
  );
};

export default Store;
