export type Genre = "Fantasy" | "Horror" | "Mystery" | "Crime" | "Romance" | "Drama" | "Historical Fiction";

export interface Book {
  id: number;
  title: string;
  author: string;
  genre: Genre;
  cover: string;
  trending: boolean;
}

const covers = [
  "📕", "📗", "📘", "📙", "📓", "📔", "📒"
];

export const books: Book[] = [
  { id: 1, title: "The Shadow's Edge", author: "Elena Blackwood", genre: "Fantasy", cover: "📕", trending: true },
  { id: 2, title: "Whispers in the Dark", author: "Marcus Holloway", genre: "Horror", cover: "📗", trending: true },
  { id: 3, title: "The Vanishing Hour", author: "Claire Ashford", genre: "Mystery", cover: "📘", trending: false },
  { id: 4, title: "Blood & Amber", author: "Dominic Vance", genre: "Crime", cover: "📙", trending: true },
  { id: 5, title: "Letters to Autumn", author: "Sophia Moreau", genre: "Romance", cover: "📓", trending: false },
  { id: 6, title: "The Glass Curtain", author: "Julian Cross", genre: "Drama", cover: "📔", trending: true },
  { id: 7, title: "Empire of Dust", author: "Helena Wren", genre: "Historical Fiction", cover: "📒", trending: true },
  { id: 8, title: "The Fae Accord", author: "Rowan Ashby", genre: "Fantasy", cover: "📗", trending: false },
  { id: 9, title: "Cellar Door", author: "Isaac Thorne", genre: "Horror", cover: "📕", trending: true },
  { id: 10, title: "The Clockwork Witness", author: "Ada Pemberton", genre: "Mystery", cover: "📘", trending: false },
  { id: 11, title: "Scarlet Alibi", author: "Nora Briggs", genre: "Crime", cover: "📙", trending: false },
  { id: 12, title: "Moonlit Promises", author: "Camille Duval", genre: "Romance", cover: "📓", trending: true },
  { id: 13, title: "The Understudy", author: "Felix Harlow", genre: "Drama", cover: "📔", trending: false },
  { id: 14, title: "The Cartographer's Lie", author: "Sebastian Cole", genre: "Historical Fiction", cover: "📒", trending: true },
  { id: 15, title: "Thornfield Rising", author: "Ivy Blackthorn", genre: "Fantasy", cover: "📕", trending: false },
  { id: 16, title: "The Bone Garden", author: "Livia Crane", genre: "Horror", cover: "📗", trending: false },
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
