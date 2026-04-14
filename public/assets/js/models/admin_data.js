// ─── Genre Colors ─────────────────────────────────────────────────────────────
const genreColors = {
  "Fantasy":          { bg: "bg-accent",         text: "text-accent-foreground",     css: "background:hsl(150,30%,25%);color:hsl(38,50%,90%)" },
  "Horror":           { bg: "bg-destructive/20",  text: "text-destructive",           css: "background:hsl(0,62%,50%,.2);color:hsl(0,62%,50%)" },
  "Mystery":          { bg: "bg-secondary",       text: "text-secondary-foreground",  css: "background:hsl(24,25%,20%);color:hsl(38,50%,90%)" },
  "Crime":            { bg: "bg-muted",           text: "text-muted-foreground",      css: "background:hsl(24,15%,18%);color:hsl(30,20%,55%)" },
  "Romance":          { bg: "bg-burgundy/30",     text: "text-foreground",            css: "background:hsl(345,45%,30%,.3);color:hsl(38,50%,90%)" },
  "Drama":            { bg: "bg-primary/20",      text: "text-primary",               css: "background:hsl(38,75%,55%,.2);color:hsl(38,75%,55%)" },
  "Historical Fiction":{ bg:"bg-mahogany/40",     text: "text-foreground",            css: "background:hsl(12,40%,18%,.4);color:hsl(38,50%,90%)" },
};

// ─── Genre Covers (relative paths from HTML files) ────────────────────────────
const genreCovers = {
  "Fantasy":           "assets/cover-fantasy.png",
  "Horror":            "assets/cover-horror.png",
  "Mystery":           "assets/cover-mystery.png",
  "Crime":             "assets/cover-crime.png",
  "Romance":           "assets/cover-romance.png",
  "Drama":             "assets/cover-drama.png",
  "Historical Fiction":"assets/cover-historical.png",
};

// ─── Genres List ──────────────────────────────────────────────────────────────
const genres = ["Fantasy","Horror","Mystery","Crime","Romance","Drama","Historical Fiction"];

// ─── Books ────────────────────────────────────────────────────────────────────
const books = [
  { id:1,  title:"The Shadow's Edge",        author:"Elena Blackwood",   genre:"Fantasy",          trending:true,  description:"In a world where shadows hold ancient power, young mage Kael discovers he can bend darkness itself. When the Shadow King rises to consume the realm, Kael must master forbidden arts and forge unlikely alliances to protect everything he loves — even if it means becoming the very thing he fears." },
  { id:2,  title:"Whispers in the Dark",     author:"Marcus Holloway",   genre:"Horror",           trending:true,  description:"After inheriting a decaying Victorian manor, journalist Lena Cole begins hearing voices that shouldn't exist. Each whisper reveals a fragment of a terrible truth buried beneath the floorboards — a truth that the house will kill to keep hidden." },
  { id:3,  title:"The Vanishing Hour",       author:"Claire Ashford",    genre:"Mystery",          trending:false, description:"Every night at exactly 3:17 AM, someone in the coastal town of Mirren Bay disappears without a trace. Detective Iris Thorne has forty-eight hours to unravel a pattern that spans decades before she becomes the next name on the list." },
  { id:4,  title:"Blood & Amber",            author:"Dominic Vance",     genre:"Crime",            trending:true,  description:"When a priceless amber artifact surfaces in the criminal underworld of Prague, retired thief Marco Sorel is drawn back into the dangerous game he swore to leave behind. Betrayal, revenge, and a web of lies await at every corner." },
  { id:5,  title:"Letters to Autumn",        author:"Sophia Moreau",     genre:"Romance",          trending:false, description:"A collection of unsent letters leads bookshop owner Autumn Leclair to the doorstep of a reclusive poet living on the French coast. As autumn turns to winter, their guarded hearts begin to thaw in ways neither expected." },
  { id:6,  title:"The Glass Curtain",        author:"Julian Cross",      genre:"Drama",            trending:true,  description:"Behind the glittering facade of a prestigious theater company, director Maren Hale fights to stage one final production. As opening night approaches, buried secrets among the cast threaten to bring the curtain down — permanently." },
  { id:7,  title:"Empire of Dust",           author:"Helena Wren",       genre:"Historical Fiction",trending:true, description:"In the crumbling twilight of the Ottoman Empire, a young cartographer is tasked with mapping lands that powerful men would prefer stay hidden. Her journey through deserts and courts will rewrite history — if she survives." },
  { id:8,  title:"The Fae Accord",           author:"Rowan Ashby",       genre:"Fantasy",          trending:false, description:"When the ancient treaty between humans and fae shatters, half-fae diplomat Elara is the only one who can broker a new peace. But both courts have secrets, and Elara's own bloodline holds the most dangerous one of all." },
  { id:9,  title:"Cellar Door",              author:"Isaac Thorne",      genre:"Horror",           trending:true,  description:"Linguist David Harker always believed 'cellar door' was the most beautiful phrase in English. Then he found the actual door — hidden in the basement of his childhood home — and what waits behind it is anything but beautiful." },
  { id:10, title:"The Clockwork Witness",    author:"Ada Pemberton",     genre:"Mystery",          trending:false, description:"In an alternate Victorian London powered by clockwork, a mechanical automaton is the sole witness to a murder in high society. Inspector Wren must decode the machine's fragmented memories before the killer strikes again." },
  { id:11, title:"Scarlet Alibi",            author:"Nora Briggs",       genre:"Crime",            trending:false, description:"Defense attorney Cassandra Hale knows her client is guilty. But when she discovers the real crime is far worse than anyone imagined, she must decide: protect the system, or burn it down to find the truth." },
  { id:12, title:"Moonlit Promises",         author:"Camille Duval",     genre:"Romance",          trending:true,  description:"Two rival florists in a small Provençal village are forced to collaborate on a grand wedding. Between midnight flower markets and rain-soaked deliveries, old grudges blossom into something unexpected and tender." },
  { id:13, title:"The Understudy",           author:"Felix Harlow",      genre:"Drama",            trending:false, description:"Always in someone else's shadow, understudy Nadia finally gets her chance when the leading actress vanishes on opening night. But the spotlight reveals more than talent — it exposes the lies Nadia told to get there." },
  { id:14, title:"The Cartographer's Lie",   author:"Sebastian Cole",    genre:"Historical Fiction",trending:true, description:"A 16th-century mapmaker discovers that the world's most trusted atlas contains deliberate falsehoods — placed there by someone willing to kill to control what humanity believes about the shape of the world." },
  { id:15, title:"Thornfield Rising",        author:"Ivy Blackthorn",    genre:"Fantasy",          trending:false, description:"The ancient fortress of Thornfield has stood empty for centuries. When a young herbalist takes shelter within its walls, the castle awakens — and with it, a curse that binds her fate to a ghostly lord trapped between worlds." },
  { id:16, title:"The Bone Garden",          author:"Livia Crane",       genre:"Horror",           trending:false, description:"Archaeologist Dr. Sable Voss unearths a garden of human bones arranged in impossible patterns beneath a quiet English village. The bones are growing. And the village remembers nothing — or claims to." },
];

// ─── Book Prices ─────────────────────────────────────────────────────────────
const bookPrices = {
  1: { cost:2300, xpReward:150, coinReward:300 },
  2: { cost:1800, xpReward:120, coinReward:250 },
  3: { cost:1500, xpReward:100, coinReward:200 },
  4: { cost:2100, xpReward:140, coinReward:280 },
  5: { cost:900,  xpReward:80,  coinReward:150 },
  6: { cost:1200, xpReward:90,  coinReward:180 },
  7: { cost:2500, xpReward:160, coinReward:320 },
  8: { cost:1600, xpReward:110, coinReward:220 },
  9: { cost:2000, xpReward:130, coinReward:260 },
  10:{ cost:1400, xpReward:95,  coinReward:190 },
  11:{ cost:1700, xpReward:115, coinReward:230 },
  12:{ cost:800,  xpReward:70,  coinReward:140 },
  13:{ cost:1100, xpReward:85,  coinReward:170 },
  14:{ cost:2400, xpReward:155, coinReward:310 },
  15:{ cost:1900, xpReward:125, coinReward:240 },
  16:{ cost:2200, xpReward:145, coinReward:290 },
};

// ─── Mock User ────────────────────────────────────────────────────────────────
const mockUser = {
  name: "Eleanor Vance",
  level: 12,
  levelProgress: 68,
  dailyReadingHours: 2.4,
  dailyReadingGoal: 4,
  coins: 1350,
};

// ─── Mock User Books ──────────────────────────────────────────────────────────
const mockUserBooks = [
  { book: books[0],  status:"reading",      progress:72 },
  { book: books[1],  status:"reading",      progress:35 },
  { book: books[5],  status:"completed" },
  { book: books[6],  status:"completed" },
  { book: books[11], status:"completed" },
  { book: books[3],  status:"plan-to-read" },
  { book: books[7],  status:"plan-to-read" },
  { book: books[9],  status:"plan-to-read" },
  { book: books[13], status:"plan-to-read" },
];

// ─── Community Posts ──────────────────────────────────────────────────────────
const communityPosts = [
  { id:"p1",  bookId:1,  author:"RuneMaster42",     avatarInitials:"RM", title:"The magic system is incredibly well thought out",       preview:"I love how shadow-bending has actual physical consequences. The way Kael's vision deteriorates each time he uses it adds such tension…",    upvotes:87,  comments:23, timeAgo:"2h ago",  tag:"discussion" },
  { id:"p2",  bookId:1,  author:"LoreKeeper",        avatarInitials:"LK", title:"Theory: The Shadow King is Kael's father",              preview:"Hear me out — there are at least three scenes that hint at a familial connection between them. In chapter 7 when…",                       upvotes:134, comments:56, timeAgo:"5h ago",  tag:"theory" },
  { id:"p3",  bookId:1,  author:"PageTurnerAva",     avatarInitials:"PA", title:"Just finished — absolutely breathtaking ending",         preview:"I stayed up until 3 AM to finish this and I have no regrets. The final battle scene gave me chills…",                                    upvotes:201, comments:41, timeAgo:"1d ago",  tag:"review" },
  { id:"p4",  bookId:2,  author:"NightOwlReads",     avatarInitials:"NO", title:"Don't read this alone at night — trust me",             preview:"The scene in chapter 12 where the whispers start forming actual words had me sleeping with the lights on for a week…",                  upvotes:156, comments:38, timeAgo:"3h ago",  tag:"review" },
  { id:"p5",  bookId:2,  author:"HauntedPages",      avatarInitials:"HP", title:"The Victorian manor is the real antagonist",            preview:"Holloway's descriptions of the house make it feel alive. Every creaking floorboard, every shifting shadow — the manor itself is hunting Lena…", upvotes:92, comments:17, timeAgo:"8h ago", tag:"discussion" },
  { id:"p6",  bookId:3,  author:"ClueSleuth",        avatarInitials:"CS", title:"Timeline analysis of all disappearances",               preview:"I mapped out every disappearance mentioned in the book and found a pattern that Iris missed. Look at the dates…",                         upvotes:178, comments:64, timeAgo:"1d ago",  tag:"theory" },
  { id:"p7",  bookId:4,  author:"NoirReader",         avatarInitials:"NR", title:"The Prague setting steals the show",                    preview:"Vance's descriptions of the old city streets at night are so vivid I felt like I was walking alongside Marco…",                           upvotes:67,  comments:12, timeAgo:"4h ago",  tag:"review" },
  { id:"p8",  bookId:5,  author:"HopelessRomantic",  avatarInitials:"HR", title:"The unsent letter in chapter 9 broke me",               preview:"I had to put the book down and just sit with my feelings for an hour. Moreau writes longing like nobody else…",                          upvotes:243, comments:71, timeAgo:"6h ago",  tag:"review" },
  { id:"p9",  bookId:5,  author:"CoastalDreamer",    avatarInitials:"CD", title:"The French coast descriptions are divine",               preview:"I've actually been to the Brittany coast and Moreau captures the windswept melancholy so perfectly it hurts…",                           upvotes:89,  comments:15, timeAgo:"2d ago",  tag:"discussion" },
  { id:"p10", bookId:6,  author:"StageLightFan",     avatarInitials:"SF", title:"This book made me want to go to the theater",           preview:"The way Cross captures the magic and chaos of backstage life is intoxicating. Maren is such a compelling character…",                   upvotes:54,  comments:9,  timeAgo:"12h ago", tag:"review" },
  { id:"p11", bookId:7,  author:"HistoryBuff99",     avatarInitials:"HB", title:"How historically accurate is this?",                    preview:"I've been reading about the actual Ottoman cartography traditions and Wren clearly did her research. The detail about…",                  upvotes:112, comments:34, timeAgo:"1d ago",  tag:"discussion" },
  { id:"p12", bookId:8,  author:"FaeWatcher",        avatarInitials:"FW", title:"The fae court politics are addictive",                   preview:"Elara navigating both courts while hiding her heritage is peak tension. Every chapter I'm holding my breath…",                           upvotes:76,  comments:19, timeAgo:"7h ago",  tag:"discussion" },
  { id:"p13", bookId:9,  author:"DarkLitFan",        avatarInitials:"DF", title:"The linguistic horror angle is genius",                  preview:"Using the beauty of language as a gateway to cosmic horror? Isaac Thorne understood the assignment completely…",                          upvotes:198, comments:47, timeAgo:"3h ago",  tag:"review" },
  { id:"p14", bookId:10, author:"GearworkGal",       avatarInitials:"GG", title:"Steampunk mystery perfection",                          preview:"The automaton's fragmented testimony is such a clever narrative device. Pemberton blends genres masterfully…",                            upvotes:88,  comments:22, timeAgo:"1d ago",  tag:"review" },
  { id:"p15", bookId:11, author:"LegalEagle",        avatarInitials:"LE", title:"The ethical dilemma is so well-crafted",                preview:"Cassandra's internal conflict between duty and justice kept me turning pages. The courtroom scenes are riveting…",                       upvotes:45,  comments:8,  timeAgo:"2d ago",  tag:"discussion" },
  { id:"p16", bookId:12, author:"FlowerChild",       avatarInitials:"FC", title:"Enemies-to-lovers done RIGHT",                          preview:"The midnight flower market scene is now my favorite scene in any romance novel ever. The chemistry is undeniable…",                      upvotes:312, comments:89, timeAgo:"5h ago",  tag:"review" },
  { id:"p17", bookId:13, author:"DramaQueen",        avatarInitials:"DQ", title:"Nadia is the most complex protagonist I've read this year", preview:"Her ambition is both admirable and terrifying. Harlow writes morally gray characters so well…",                                    upvotes:63,  comments:14, timeAgo:"1d ago",  tag:"discussion" },
  { id:"p18", bookId:14, author:"MapLover",          avatarInitials:"ML", title:"The conspiracy angle is mind-blowing",                  preview:"What if our actual historical maps contain similar lies? Cole has me questioning everything I learned in school…",                       upvotes:145, comments:52, timeAgo:"8h ago",  tag:"theory" },
  { id:"p19", bookId:15, author:"GothicReader",      avatarInitials:"GR", title:"Perfect gothic atmosphere",                             preview:"Thornfield feels like a character unto itself. The curse mechanic is heartbreaking and beautiful simultaneously…",                        upvotes:71,  comments:16, timeAgo:"2d ago",  tag:"review" },
  { id:"p20", bookId:16, author:"BoneCollector",     avatarInitials:"BC", title:"The garden imagery is haunting",                        preview:"Crane's description of bones arranged like flowers growing from the earth is simultaneously beautiful and disturbing…",                  upvotes:99,  comments:27, timeAgo:"1d ago",  tag:"review" },
];
