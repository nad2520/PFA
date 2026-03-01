import coverFantasy from "@/assets/cover-fantasy.png";
import coverHorror from "@/assets/cover-horror.png";
import coverMystery from "@/assets/cover-mystery.png";
import coverCrime from "@/assets/cover-crime.png";
import coverRomance from "@/assets/cover-romance.png";
import coverDrama from "@/assets/cover-drama.png";
import coverHistorical from "@/assets/cover-historical.png";

export type Genre = "Fantasy" | "Horror" | "Mystery" | "Crime" | "Romance" | "Drama" | "Historical Fiction";

export interface Book {
  id: number;
  title: string;
  author: string;
  genre: Genre;
  cover: string;
  trending: boolean;
  description: string;
}

export const genreCovers: Record<Genre, string> = {
  Fantasy: coverFantasy,
  Horror: coverHorror,
  Mystery: coverMystery,
  Crime: coverCrime,
  Romance: coverRomance,
  Drama: coverDrama,
  "Historical Fiction": coverHistorical,
};

export const books: Book[] = [
  { id: 1, title: "The Shadow's Edge", author: "Elena Blackwood", genre: "Fantasy", cover: "📕", trending: true, description: "In a world where shadows hold ancient power, young mage Kael discovers he can bend darkness itself. When the Shadow King rises to consume the realm, Kael must master forbidden arts and forge unlikely alliances to protect everything he loves — even if it means becoming the very thing he fears." },
  { id: 2, title: "Whispers in the Dark", author: "Marcus Holloway", genre: "Horror", cover: "📗", trending: true, description: "After inheriting a decaying Victorian manor, journalist Lena Cole begins hearing voices that shouldn't exist. Each whisper reveals a fragment of a terrible truth buried beneath the floorboards — a truth that the house will kill to keep hidden." },
  { id: 3, title: "The Vanishing Hour", author: "Claire Ashford", genre: "Mystery", cover: "📘", trending: false, description: "Every night at exactly 3:17 AM, someone in the coastal town of Mirren Bay disappears without a trace. Detective Iris Thorne has forty-eight hours to unravel a pattern that spans decades before she becomes the next name on the list." },
  { id: 4, title: "Blood & Amber", author: "Dominic Vance", genre: "Crime", cover: "📙", trending: true, description: "When a priceless amber artifact surfaces in the criminal underworld of Prague, retired thief Marco Sorel is drawn back into the dangerous game he swore to leave behind. Betrayal, revenge, and a web of lies await at every corner." },
  { id: 5, title: "Letters to Autumn", author: "Sophia Moreau", genre: "Romance", cover: "📓", trending: false, description: "A collection of unsent letters leads bookshop owner Autumn Leclair to the doorstep of a reclusive poet living on the French coast. As autumn turns to winter, their guarded hearts begin to thaw in ways neither expected." },
  { id: 6, title: "The Glass Curtain", author: "Julian Cross", genre: "Drama", cover: "📔", trending: true, description: "Behind the glittering facade of a prestigious theater company, director Maren Hale fights to stage one final production. As opening night approaches, buried secrets among the cast threaten to bring the curtain down — permanently." },
  { id: 7, title: "Empire of Dust", author: "Helena Wren", genre: "Historical Fiction", cover: "📒", trending: true, description: "In the crumbling twilight of the Ottoman Empire, a young cartographer is tasked with mapping lands that powerful men would prefer stay hidden. Her journey through deserts and courts will rewrite history — if she survives." },
  { id: 8, title: "The Fae Accord", author: "Rowan Ashby", genre: "Fantasy", cover: "📗", trending: false, description: "When the ancient treaty between humans and fae shatters, half-fae diplomat Elara is the only one who can broker a new peace. But both courts have secrets, and Elara's own bloodline holds the most dangerous one of all." },
  { id: 9, title: "Cellar Door", author: "Isaac Thorne", genre: "Horror", cover: "📕", trending: true, description: "Linguist David Harker always believed 'cellar door' was the most beautiful phrase in English. Then he found the actual door — hidden in the basement of his childhood home — and what waits behind it is anything but beautiful." },
  { id: 10, title: "The Clockwork Witness", author: "Ada Pemberton", genre: "Mystery", cover: "📘", trending: false, description: "In an alternate Victorian London powered by clockwork, a mechanical automaton is the sole witness to a murder in high society. Inspector Wren must decode the machine's fragmented memories before the killer strikes again." },
  { id: 11, title: "Scarlet Alibi", author: "Nora Briggs", genre: "Crime", cover: "📙", trending: false, description: "Defense attorney Cassandra Hale knows her client is guilty. But when she discovers the real crime is far worse than anyone imagined, she must decide: protect the system, or burn it down to find the truth." },
  { id: 12, title: "Moonlit Promises", author: "Camille Duval", genre: "Romance", cover: "📓", trending: true, description: "Two rival florists in a small Provençal village are forced to collaborate on a grand wedding. Between midnight flower markets and rain-soaked deliveries, old grudges blossom into something unexpected and tender." },
  { id: 13, title: "The Understudy", author: "Felix Harlow", genre: "Drama", cover: "📔", trending: false, description: "Always in someone else's shadow, understudy Nadia finally gets her chance when the leading actress vanishes on opening night. But the spotlight reveals more than talent — it exposes the lies Nadia told to get there." },
  { id: 14, title: "The Cartographer's Lie", author: "Sebastian Cole", genre: "Historical Fiction", cover: "📒", trending: true, description: "A 16th-century mapmaker discovers that the world's most trusted atlas contains deliberate falsehoods — placed there by someone willing to kill to control what humanity believes about the shape of the world." },
  { id: 15, title: "Thornfield Rising", author: "Ivy Blackthorn", genre: "Fantasy", cover: "📕", trending: false, description: "The ancient fortress of Thornfield has stood empty for centuries. When a young herbalist takes shelter within its walls, the castle awakens — and with it, a curse that binds her fate to a ghostly lord trapped between worlds." },
  { id: 16, title: "The Bone Garden", author: "Livia Crane", genre: "Horror", cover: "📗", trending: false, description: "Archaeologist Dr. Sable Voss unearths a garden of human bones arranged in impossible patterns beneath a quiet English village. The bones are growing. And the village remembers nothing — or claims to." },
];

export const genres: Genre[] = ["Fantasy", "Horror", "Mystery", "Crime", "Romance", "Drama", "Historical Fiction"];

export const genreColors: Record<Genre, { bg: string; text: string }> = {
  Fantasy: { bg: "bg-accent", text: "text-accent-foreground" },
  Horror: { bg: "bg-destructive/20", text: "text-destructive" },
  Mystery: { bg: "bg-secondary", text: "text-secondary-foreground" },
  Crime: { bg: "bg-muted", text: "text-muted-foreground" },
  Romance: { bg: "bg-burgundy/30", text: "text-foreground" },
  Drama: { bg: "bg-primary/20", text: "text-primary" },
  "Historical Fiction": { bg: "bg-mahogany/40", text: "text-foreground" },
};
