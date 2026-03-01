import { useState, useRef, useEffect } from "react";
import { X, Send, Minus } from "lucide-react";
import lumoHappy from "@/assets/lumo-happy.png";

interface ChatMessage {
  id: string;
  role: "user" | "lumo";
  text: string;
}

const LUMO_RESPONSES: Record<string, string> = {
  hello: "Hey there, fellow reader! 📚 I'm Lumo, your cozy reading companion. How can I help you today?",
  coins: "Coins are the currency of the Reading Kingdom! You earn them by finishing books and completing daily reading goals. You can spend them to unlock new books in the catalog. 🪙",
  books: "Looking for a great read? Check out the Book Catalog on the home page! You can filter by genre — Fantasy, Horror, Mystery, Romance, and more. Each book shows how many coins it costs and how much XP you'll earn! ✨",
  progress: "You can track your reading progress on your Profile page! I'll cheer you on every step of the way. Keep reading to level up! 📖",
  map: "The Reading Kingdom Map is your personalized fantasy world! Each region represents a genre you love. Click on any region to explore books from that genre. 🗺️",
  recommend: "Based on popular picks, I'd recommend checking out 'The Shadow's Edge' for epic fantasy, or 'Moonlit Promises' if you're in the mood for romance! Both are trending right now. 🌟",
  store: "The Store is where you can spend your hard-earned coins on exclusive items, badges, and book bundles. Keep reading to earn more! 🏪",
  help: "I can help you with:\n• 📚 Book recommendations\n• 🪙 How coins & XP work\n• 📖 Reading progress\n• 🗺️ The Reading Kingdom Map\n• 🏪 The Store\n\nJust ask me anything!",
  default: "That's a wonderful question! I'm still learning, but I'm always here to chat about books, your reading journey, and all things Lexora. Try asking me about coins, books, the map, or recommendations! 🐻",
};

function getLumoReply(input: string): string {
  const lower = input.toLowerCase();
  if (/hello|hi|hey|greet/.test(lower)) return LUMO_RESPONSES.hello;
  if (/coin|money|currency|cost|price/.test(lower)) return LUMO_RESPONSES.coins;
  if (/book|read|catalog|genre/.test(lower)) return LUMO_RESPONSES.books;
  if (/progress|level|xp|streak/.test(lower)) return LUMO_RESPONSES.progress;
  if (/map|kingdom|region|world/.test(lower)) return LUMO_RESPONSES.map;
  if (/recommend|suggest|pick/.test(lower)) return LUMO_RESPONSES.recommend;
  if (/store|shop|buy|purchase/.test(lower)) return LUMO_RESPONSES.store;
  if (/help|what can you|feature/.test(lower)) return LUMO_RESPONSES.help;
  return LUMO_RESPONSES.default;
}

const LumoChatbot = () => {
  const [isOpen, setIsOpen] = useState(false);
  const [messages, setMessages] = useState<ChatMessage[]>([
    { id: "welcome", role: "lumo", text: "Hi there! I'm Lumo 🐻 your cozy reading companion. Ask me anything about books, coins, or your reading journey!" },
  ]);
  const [input, setInput] = useState("");
  const scrollRef = useRef<HTMLDivElement>(null);

  useEffect(() => {
    if (scrollRef.current) {
      scrollRef.current.scrollTop = scrollRef.current.scrollHeight;
    }
  }, [messages]);

  const handleSend = () => {
    const trimmed = input.trim();
    if (!trimmed) return;

    const userMsg: ChatMessage = { id: `u-${Date.now()}`, role: "user", text: trimmed };
    setMessages((prev) => [...prev, userMsg]);
    setInput("");

    // Simulate typing delay
    setTimeout(() => {
      const reply: ChatMessage = { id: `l-${Date.now()}`, role: "lumo", text: getLumoReply(trimmed) };
      setMessages((prev) => [...prev, reply]);
    }, 600);
  };

  return (
    <>
      {/* Floating Lumo mascot */}
      {!isOpen && (
        <button
          onClick={() => setIsOpen(true)}
          className="fixed bottom-6 right-6 z-[100] group"
          aria-label="Open chat with Lumo"
        >
          <div className="relative animate-lumo-float">
            <img
              src={lumoHappy}
              alt="Lumo the reading companion"
              className="w-20 h-20 rounded-full object-cover border-2 border-primary shadow-lg shadow-primary/20 transition-transform group-hover:scale-110"
            />
            {/* Glow ring */}
            <div className="absolute inset-0 rounded-full border-2 border-primary/40 animate-ping pointer-events-none" style={{ animationDuration: "3s" }} />
            {/* Speech hint */}
            <div className="absolute -top-10 -left-8 bg-card border border-border rounded-lg px-3 py-1.5 shadow-md opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap">
              <span className="font-pixel text-[8px] tracking-wider text-primary">CHAT WITH ME!</span>
              <div className="absolute bottom-0 left-1/2 -translate-x-1/2 translate-y-1/2 rotate-45 w-2 h-2 bg-card border-r border-b border-border" />
            </div>
          </div>
        </button>
      )}

      {/* Chat window */}
      {isOpen && (
        <div className="fixed bottom-6 right-6 z-[100] w-[360px] max-w-[calc(100vw-2rem)] h-[520px] max-h-[calc(100vh-3rem)] flex flex-col rounded-2xl border border-border bg-card shadow-2xl shadow-warm-shadow/60 animate-scale-in overflow-hidden">
          {/* Header */}
          <div className="flex items-center gap-3 px-4 py-3 border-b border-border bg-secondary/30">
            <img src={lumoHappy} alt="Lumo" className="w-10 h-10 rounded-full object-cover border border-primary/50" />
            <div className="flex-1 min-w-0">
              <h3 className="font-display text-sm font-semibold text-foreground">Lumo</h3>
              <p className="font-pixel text-[7px] tracking-wider text-primary">YOUR READING COMPANION</p>
            </div>
            <div className="flex items-center gap-1">
              <button onClick={() => setIsOpen(false)} className="p-1.5 rounded-md text-muted-foreground hover:text-foreground hover:bg-muted transition-colors">
                <Minus className="w-4 h-4" />
              </button>
              <button onClick={() => setIsOpen(false)} className="p-1.5 rounded-md text-muted-foreground hover:text-destructive hover:bg-destructive/10 transition-colors">
                <X className="w-4 h-4" />
              </button>
            </div>
          </div>

          {/* Messages */}
          <div ref={scrollRef} className="flex-1 overflow-y-auto px-4 py-4 space-y-3">
            {messages.map((msg) => (
              <div key={msg.id} className={`flex gap-2 ${msg.role === "user" ? "justify-end" : "justify-start"}`}>
                {msg.role === "lumo" && (
                  <img src={lumoHappy} alt="" className="w-7 h-7 rounded-full object-cover border border-primary/30 flex-shrink-0 mt-1" />
                )}
                <div
                  className={`max-w-[75%] rounded-2xl px-3.5 py-2.5 font-body text-sm leading-relaxed whitespace-pre-line ${
                    msg.role === "user"
                      ? "bg-primary text-primary-foreground rounded-br-md"
                      : "bg-secondary text-secondary-foreground rounded-bl-md"
                  }`}
                >
                  {msg.text}
                </div>
              </div>
            ))}
          </div>

          {/* Input */}
          <div className="px-3 py-3 border-t border-border bg-secondary/20">
            <form
              onSubmit={(e) => { e.preventDefault(); handleSend(); }}
              className="flex items-center gap-2"
            >
              <input
                type="text"
                value={input}
                onChange={(e) => setInput(e.target.value)}
                placeholder="Ask Lumo anything..."
                className="flex-1 bg-muted border border-border rounded-xl px-4 py-2.5 text-sm font-body text-foreground placeholder:text-muted-foreground focus:outline-none focus:ring-1 focus:ring-primary"
              />
              <button
                type="submit"
                disabled={!input.trim()}
                className="p-2.5 rounded-xl bg-primary text-primary-foreground disabled:opacity-40 hover:shadow-md hover:shadow-primary/30 transition-all"
              >
                <Send className="w-4 h-4" />
              </button>
            </form>
          </div>
        </div>
      )}
    </>
  );
};

export default LumoChatbot;
