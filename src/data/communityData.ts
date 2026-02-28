export interface CommunityPost {
  id: string;
  bookId: number;
  author: string;
  avatarInitials: string;
  title: string;
  preview: string;
  upvotes: number;
  comments: number;
  timeAgo: string;
  tag?: "discussion" | "review" | "theory" | "spoiler";
}

export const communityPosts: CommunityPost[] = [
  // Book 1 - The Shadow's Edge
  { id: "p1", bookId: 1, author: "RuneMaster42", avatarInitials: "RM", title: "The magic system is incredibly well thought out", preview: "I love how shadow-bending has actual physical consequences. The way Kael's vision deteriorates each time he uses it adds such tension…", upvotes: 87, comments: 23, timeAgo: "2h ago", tag: "discussion" },
  { id: "p2", bookId: 1, author: "LoreKeeper", avatarInitials: "LK", title: "Theory: The Shadow King is Kael's father", preview: "Hear me out — there are at least three scenes that hint at a familial connection between them. In chapter 7 when…", upvotes: 134, comments: 56, timeAgo: "5h ago", tag: "theory" },
  { id: "p3", bookId: 1, author: "PageTurnerAva", avatarInitials: "PA", title: "Just finished — absolutely breathtaking ending", preview: "I stayed up until 3 AM to finish this and I have no regrets. The final battle scene gave me chills…", upvotes: 201, comments: 41, timeAgo: "1d ago", tag: "review" },

  // Book 2 - Whispers in the Dark
  { id: "p4", bookId: 2, author: "NightOwlReads", avatarInitials: "NO", title: "Don't read this alone at night — trust me", preview: "The scene in chapter 12 where the whispers start forming actual words had me sleeping with the lights on for a week…", upvotes: 156, comments: 38, timeAgo: "3h ago", tag: "review" },
  { id: "p5", bookId: 2, author: "HauntedPages", avatarInitials: "HP", title: "The Victorian manor is the real antagonist", preview: "Holloway's descriptions of the house make it feel alive. Every creaking floorboard, every shifting shadow — the manor itself is hunting Lena…", upvotes: 92, comments: 17, timeAgo: "8h ago", tag: "discussion" },

  // Book 3 - The Vanishing Hour
  { id: "p6", bookId: 3, author: "ClueSleuth", avatarInitials: "CS", title: "Timeline analysis of all disappearances", preview: "I mapped out every disappearance mentioned in the book and found a pattern that Iris missed. Look at the dates…", upvotes: 178, comments: 64, timeAgo: "1d ago", tag: "theory" },

  // Book 4 - Blood & Amber
  { id: "p7", bookId: 4, author: "NoirReader", avatarInitials: "NR", title: "The Prague setting steals the show", preview: "Vance's descriptions of the old city streets at night are so vivid I felt like I was walking alongside Marco…", upvotes: 67, comments: 12, timeAgo: "4h ago", tag: "review" },

  // Book 5 - Letters to Autumn
  { id: "p8", bookId: 5, author: "HopelessRomantic", avatarInitials: "HR", title: "The unsent letter in chapter 9 broke me", preview: "I had to put the book down and just sit with my feelings for an hour. Moreau writes longing like nobody else…", upvotes: 243, comments: 71, timeAgo: "6h ago", tag: "review" },
  { id: "p9", bookId: 5, author: "CoastalDreamer", avatarInitials: "CD", title: "The French coast descriptions are divine", preview: "I've actually been to the Brittany coast and Moreau captures the windswept melancholy so perfectly it hurts…", upvotes: 89, comments: 15, timeAgo: "2d ago", tag: "discussion" },

  // Book 6 - The Glass Curtain
  { id: "p10", bookId: 6, author: "StageLightFan", avatarInitials: "SF", title: "This book made me want to go to the theater", preview: "The way Cross captures the magic and chaos of backstage life is intoxicating. Maren is such a compelling character…", upvotes: 54, comments: 9, timeAgo: "12h ago", tag: "review" },

  // Book 7 - Empire of Dust
  { id: "p11", bookId: 7, author: "HistoryBuff99", avatarInitials: "HB", title: "How historically accurate is this?", preview: "I've been reading about the actual Ottoman cartography traditions and Wren clearly did her research. The detail about…", upvotes: 112, comments: 34, timeAgo: "1d ago", tag: "discussion" },

  // Book 8-16: generic posts so every book has at least one
  { id: "p12", bookId: 8, author: "FaeWatcher", avatarInitials: "FW", title: "The fae court politics are addictive", preview: "Elara navigating both courts while hiding her heritage is peak tension. Every chapter I'm holding my breath…", upvotes: 76, comments: 19, timeAgo: "7h ago", tag: "discussion" },
  { id: "p13", bookId: 9, author: "DarkLitFan", avatarInitials: "DF", title: "The linguistic horror angle is genius", preview: "Using the beauty of language as a gateway to cosmic horror? Isaac Thorne understood the assignment completely…", upvotes: 198, comments: 47, timeAgo: "3h ago", tag: "review" },
  { id: "p14", bookId: 10, author: "GearworkGal", avatarInitials: "GG", title: "Steampunk mystery perfection", preview: "The automaton's fragmented testimony is such a clever narrative device. Pemberton blends genres masterfully…", upvotes: 88, comments: 22, timeAgo: "1d ago", tag: "review" },
  { id: "p15", bookId: 11, author: "LegalEagle", avatarInitials: "LE", title: "The ethical dilemma is so well-crafted", preview: "Cassandra's internal conflict between duty and justice kept me turning pages. The courtroom scenes are riveting…", upvotes: 45, comments: 8, timeAgo: "2d ago", tag: "discussion" },
  { id: "p16", bookId: 12, author: "FlowerChild", avatarInitials: "FC", title: "Enemies-to-lovers done RIGHT", preview: "The midnight flower market scene is now my favorite scene in any romance novel ever. The chemistry is undeniable…", upvotes: 312, comments: 89, timeAgo: "5h ago", tag: "review" },
  { id: "p17", bookId: 13, author: "DramaQueen", avatarInitials: "DQ", title: "Nadia is the most complex protagonist I've read this year", preview: "Her ambition is both admirable and terrifying. Harlow writes morally gray characters so well…", upvotes: 63, comments: 14, timeAgo: "1d ago", tag: "discussion" },
  { id: "p18", bookId: 14, author: "MapLover", avatarInitials: "ML", title: "The conspiracy angle is mind-blowing", preview: "What if our actual historical maps contain similar lies? Cole has me questioning everything I learned in school…", upvotes: 145, comments: 52, timeAgo: "8h ago", tag: "theory" },
  { id: "p19", bookId: 15, author: "GothicReader", avatarInitials: "GR", title: "Perfect gothic atmosphere", preview: "Thornfield feels like a character unto itself. The curse mechanic is heartbreaking and beautiful simultaneously…", upvotes: 71, comments: 16, timeAgo: "2d ago", tag: "review" },
  { id: "p20", bookId: 16, author: "BoneCollector", avatarInitials: "BC", title: "The garden imagery is haunting", preview: "Crane's description of bones arranged like flowers growing from the earth is simultaneously beautiful and disturbing…", upvotes: 99, comments: 27, timeAgo: "1d ago", tag: "review" },
];
