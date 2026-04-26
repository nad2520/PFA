// lexora-state.js — mirrors story-shelf-retreat localStorage + bookContent
(function () {
  const LAST_READ_KEY = "lexora-user-books-last";
  let memoryUserBooks = [];
  let memoryProgress = {};

  const loremParagraphs = [
    "The morning light crept through the curtains like a timid guest, casting golden ribbons across the wooden floor. Outside, the village was already stirring — the baker's chimney puffed white smoke into the pale sky, and somewhere a rooster announced the dawn with relentless optimism.",
    "She pulled the leather-bound journal from its hiding place beneath the loose floorboard. Its pages were yellowed and brittle, filled with a handwriting she almost recognized. Almost. The ink had faded in places, but the urgency behind every word remained perfectly preserved.",
    "The forest path narrowed until the canopy above formed a cathedral of green. Shafts of light pierced through gaps in the leaves, illuminating dust motes that danced like tiny spirits. Every step deeper felt like stepping further from the world she knew.",
    "He sat at the edge of the dock, feet dangling above the dark water. The lake was impossibly still, a mirror reflecting a sky full of stars. Somewhere across the water, a lantern flickered — proof that he wasn't entirely alone in this forgotten corner of the world.",
    "The letter arrived without a return address, sealed with crimson wax stamped with an unfamiliar crest. Inside, three words were written in elegant script: 'Remember the garden.' She hadn't thought about the garden in twenty years. Someone else had.",
    "Rain hammered the cobblestones as she ducked beneath the awning of the old bookshop. Through the fogged glass she could see shelves stretching to the ceiling, crammed with volumes that seemed to lean toward each other like old friends sharing secrets.",
    "The map was wrong. He was certain of it now. The coastline curved where it should have been straight, and an entire island appeared where charts showed only open sea. Someone had drawn this map not to guide travelers, but to mislead them.",
    "Candlelight threw long shadows across the stone walls of the chamber. The council sat in silence, each member weighing the gravity of what had just been proposed. War was easy to start and impossible to take back. They all knew this. And yet.",
    "She found the key in a box of her grandmother's things — small, brass, intricately carved with symbols she couldn't decipher. It fit no lock in the house. But it hummed when she held it, a faint vibration that traveled up her arm and settled in her chest like a second heartbeat.",
    "The train pulled away from the station with a reluctant groan, as if it too was sorry to leave. She pressed her forehead against the cold window and watched the platform shrink — along with everything familiar. Ahead lay only questions and the thin promise of answers.",
    "Dust motes swirled in the beam of his flashlight as he descended the spiral staircase. The air grew colder with each step, thick with the smell of earth and something older. At the bottom, a door waited — heavy oak, banded with iron, and slightly ajar.",
    "The marketplace was a riot of color and noise. Silk merchants called out prices in singsong voices while spice vendors arranged pyramids of saffron and turmeric that glowed like captured sunlight. In the midst of it all, a child sat cross-legged, reading a book as if the world had gone quiet just for her.",
    "He recognized the melody before he recognized the musician. It was the lullaby his mother used to hum — the one she claimed she'd invented. But here it was, played on a weathered violin by a stranger in a city thousands of miles from home.",
    "The greenhouse stood at the edge of the estate, its glass panels clouded with age and neglect. Inside, impossibly, things still grew. Roses climbed the iron framework in defiant spirals, and ferns unfurled from cracks in the tile floor. Life, it seemed, did not require permission.",
    "Dawn broke over the battlefield like an unwelcome truth. The fog retreated slowly, revealing what the night had tried to hide. She stood among the aftermath, sword still in hand, and wondered whether victory was supposed to feel this much like loss.",
    "The library occupied an entire wing of the castle, its shelves carved from dark wood that smelled of cedar and centuries. A rolling ladder waited at the far end, and high above, a stained-glass window cast colored light across the spines of ten thousand stories waiting to be opened.",
  ];

  const pageCache = {};

  function generateBookPages(bookId, totalPages) {
    totalPages = totalPages || 24;
    const pages = [];
    const seed = bookId * 7;
    for (let i = 0; i < totalPages; i++) {
      const parasPerPage = 3;
      const pageContent = [];
      for (let j = 0; j < parasPerPage; j++) {
        const idx = (seed + i * parasPerPage + j) % loremParagraphs.length;
        pageContent.push(loremParagraphs[idx]);
      }
      pages.push(pageContent.join("\n\n"));
    }
    return pages;
  }

  function pageCacheKey(bookId, totalPages) {
    return bookId + ':' + (totalPages || 24);
  }

  function getBookPages(bookId, totalPages) {
    const t = totalPages || 24;
    const key = pageCacheKey(bookId, t);
    if (!pageCache[key]) {
      pageCache[key] = generateBookPages(bookId, t);
    }
    return pageCache[key];
  }

  function getBookProgress(bookId) {
    return memoryProgress[bookId] ?? 0;
  }

  function saveReadingPage(bookId, page) {
    memoryProgress[bookId] = page;
  }

  function getUserBooks() {
    return Array.isArray(memoryUserBooks) ? [...memoryUserBooks] : [];
  }

  function saveUserBooks(userBooks) {
    memoryUserBooks = Array.isArray(userBooks) ? [...userBooks] : [];
  }

  function getLastBookReadId() {
    const s = localStorage.getItem(LAST_READ_KEY);
    return s ? Number(s) : null;
  }

  function setLastBookReadId(id) {
    if (id != null) {
      localStorage.setItem(LAST_READ_KEY, String(id));
    } else {
      localStorage.removeItem(LAST_READ_KEY);
    }
  }

  function isInLibrary(bookId) {
    return getUserBooks().some(function (ub) {
      return ub.book.id === bookId && (ub.status === "reading" || ub.status === "completed");
    });
  }

  function isInList(bookId) {
    return getUserBooks().some(function (ub) {
      return ub.book.id === bookId && ub.status === "plan-to-read";
    });
  }

  function getBookStatus(bookId) {
    return getUserBooks().find(function (ub) {
      return ub.book.id === bookId;
    });
  }

  function addToLibrary(bookId) {
    let userBooks = getUserBooks();
    if (
      userBooks.some(function (ub) {
        return ub.book.id === bookId && (ub.status === "reading" || ub.status === "completed");
      })
    ) {
      return;
    }
    const book = (window.__lexora?.books || []).find(function (b) {
      return b.id === bookId;
    });
    if (!book) return;
    userBooks = userBooks.filter(function (ub) {
      return !(ub.book.id === bookId && ub.status === "plan-to-read");
    });
    userBooks.push({ book: book, status: "reading", progress: 0 });
    saveUserBooks(userBooks);
  }

  function addToList(bookId) {
    let userBooks = getUserBooks();
    if (
      userBooks.some(function (ub) {
        return ub.book.id === bookId && ub.status === "plan-to-read";
      })
    ) {
      return;
    }
    const book = (window.__lexora?.books || []).find(function (b) {
      return b.id === bookId;
    });
    if (!book) return;
    userBooks.push({ book: book, status: "plan-to-read" });
    saveUserBooks(userBooks);
  }

  function removeFromLibrary(bookId) {
    let userBooks = getUserBooks();
    userBooks = userBooks.filter(function (ub) {
      return !(ub.book.id === bookId && (ub.status === "reading" || ub.status === "completed"));
    });
    saveUserBooks(userBooks);
  }

  function removeFromList(bookId) {
    let userBooks = getUserBooks();
    userBooks = userBooks.filter(function (ub) {
      return !(ub.book.id === bookId && ub.status === "plan-to-read");
    });
    saveUserBooks(userBooks);
  }

  /**
   * Replace local shelf from /api/user/profile `library` (DB: `user_books` + `books`).
   * Empty array always clears — no placeholder books. Uses each row's `book` payload when present
   * so the catalog script does not need to have run first.
   */
  function applyLibraryFromServer(rows) {
    if (!Array.isArray(rows)) return;

    if (rows.length === 0) {
      saveUserBooks([]);
      return;
    }

    const L = window.__lexora;
    let userBooks = [];
    rows.forEach(function (row) {
      const bid = Number(row.book_id);
      let book = null;
      if (row.book && Number(row.book.id) === bid) {
        const raw = row.book;
        const rawGenres = Array.isArray(raw.genres) ? raw.genres : [];
        const cleanedGenres = rawGenres
          .map(function (g) { return String(g || "").trim(); })
          .filter(function (g) { return g !== ""; });
        const fallbackGenre = String(raw.genre || "").trim();
        book = {
          id: bid,
          title: String(raw.title || ""),
          author: String(raw.author || ""),
          publicationYear: Number(raw.publicationYear || raw.publication_year || 0),
          genre: String(raw.genre || ""),
          genres: cleanedGenres.length > 0
            ? cleanedGenres
            : (fallbackGenre ? [fallbackGenre] : []),
          trending: !!raw.trending,
          description: String(raw.description || ""),
          audience: String(raw.audience || "All"),
          cover: String(raw.cover || "📖"),
        };
      } else if (L && L.books && L.books.length) {
        book = L.books.find(function (b) {
          return b.id === bid;
        });
      }
      if (!book) return;

      const st = row.status === "plan-to-read" || row.status === "plan_to_read"
        ? "plan-to-read"
        : row.status;
      const total = getBookPages(bid).length || 24;
      const maxIdx = Math.max(0, total - 1);
      let pp = Number(row.progress_page);
      if (!Number.isFinite(pp) || pp < 0) {
        pp = 0;
      } else {
        pp = Math.min(Math.floor(pp), maxIdx);
      }

      let progress = row.progress;
      if (progress == null) {
        if (st === "completed") progress = 100;
        else {
          progress = total > 0 ? Math.round(((pp + 1) / total) * 100) : 0;
        }
      }

      const entry = { book: book, status: st, progress: progress };
      userBooks.push(entry);
      if (st === "reading" || st === "completed") {
        memoryProgress[bid] = pp;
      }
    });
    saveUserBooks(userBooks);
  }

  /** Sync local shelf after server-side completion (1-based last page for detail UI). */
  function markBookCompleted(bookId) {
    const L = window.__lexora;
    const pagesForBook = getBookPages(bookId);
    const lastPage = pagesForBook.length;
    saveReadingPage(bookId, lastPage);
    let userBooks = getUserBooks();
    const idx = userBooks.findIndex(function (ub) {
      return ub.book.id === bookId;
    });
    if (idx >= 0) {
      userBooks[idx] = {
        book: userBooks[idx].book,
        status: "completed",
        progress: 100,
      };
    } else if (L) {
      const book = L.books.find(function (b) {
        return b.id === bookId;
      });
      if (book) {
        userBooks.push({ book: book, status: "completed", progress: 100 });
      }
    }
    saveUserBooks(userBooks);
  }

  window.LexoraState = {
    getBookPages: getBookPages,
    getBookProgress: getBookProgress,
    saveReadingPage: saveReadingPage,
    getUserBooks: getUserBooks,
    saveUserBooks: saveUserBooks,
    getLastBookReadId: getLastBookReadId,
    setLastBookReadId: setLastBookReadId,
    isInLibrary: isInLibrary,
    isInList: isInList,
    getBookStatus: getBookStatus,
    addToLibrary: addToLibrary,
    addToList: addToList,
    removeFromLibrary: removeFromLibrary,
    removeFromList: removeFromList,
    markBookCompleted: markBookCompleted,
    applyLibraryFromServer: applyLibraryFromServer,
  };
})();
