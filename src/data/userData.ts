import { type Book, books } from "./books";

export interface UserProfile {
  name: string;
  level: number;
  levelProgress: number; // 0-100
  dailyReadingHours: number;
  dailyReadingGoal: number;
  coins: number;
}

export interface UserBook {
  book: Book;
  status: "reading" | "completed" | "plan-to-read";
  progress?: number; // 0-100 for reading
}

export const mockUser: UserProfile = {
  name: "Eleanor Vance",
  level: 12,
  levelProgress: 68,
  dailyReadingHours: 2.4,
  dailyReadingGoal: 4,
  coins: 1350,
};

export const mockUserBooks: UserBook[] = [
  { book: books[0], status: "reading", progress: 72 },
  { book: books[1], status: "reading", progress: 35 },
  { book: books[5], status: "completed" },
  { book: books[6], status: "completed" },
  { book: books[11], status: "completed" },
  { book: books[3], status: "plan-to-read" },
  { book: books[7], status: "plan-to-read" },
  { book: books[9], status: "plan-to-read" },
  { book: books[13], status: "plan-to-read" },
];
