// lexora-state.js — mirrors story-shelf-retreat localStorage + bookContent
(function () {
  const STORAGE_KEY = "lexora-user-books";
  const LAST_READ_KEY = "lexora-user-books-last";
  const PROGRESS_KEY = "lexora-reading-progress";

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

  function getAllProgress() {
    try {
      return JSON.parse(localStorage.getItem(PROGRESS_KEY) || "{}");
    } catch {
      return {};
    }
  }

  function getBookProgress(bookId) {
    return getAllProgress()[bookId] ?? 0;
  }

  function saveReadingPage(bookId, page) {
    const all = getAllProgress();
    all[bookId] = page;
    localStorage.setItem(PROGRESS_KEY, JSON.stringify(all));
  }

  function getUserBooks() {
    const L = window.__lexora;
    if (!L) return [];
    const saved = localStorage.getItem(STORAGE_KEY);
    if (!saved) {
      return L.mockUserBooks.map(function (ub) {
        return { book: ub.book, status: ub.status, progress: ub.progress };
      });
    }
    try {
      const parsed = JSON.parse(saved);
      return parsed
        .map(function (entry) {
          const book = L.books.find(function (b) {
            return b.id === entry.bookId;
          });
          if (!book) return null;
          return {
            book: book,
            status: entry.status,
            progress: entry.progress,
          };
        })
        .filter(Boolean);
    } catch {
      return L.mockUserBooks.map(function (ub) {
        return { book: ub.book, status: ub.status, progress: ub.progress };
      });
    }
  }

  function saveUserBooks(userBooks) {
    const serializable = userBooks.map(function (ub) {
      return {
        bookId: ub.book.id,
        status: ub.status,
        progress: ub.progress,
      };
    });
    localStorage.setItem(STORAGE_KEY, JSON.stringify(serializable));
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
    const L = window.__lexora;
    if (!L) return;
    let userBooks = getUserBooks();
    if (
      userBooks.some(function (ub) {
        return ub.book.id === bookId && (ub.status === "reading" || ub.status === "completed");
      })
    ) {
      return;
    }
    const book = L.books.find(function (b) {
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
    const L = window.__lexora;
    if (!L) return;
    let userBooks = getUserBooks();
    if (
      userBooks.some(function (ub) {
        return ub.book.id === bookId && ub.status === "plan-to-read";
      })
    ) {
      return;
    }
    const book = L.books.find(function (b) {
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
   * Merge /api/user/profile `library` into local shelf so badges (READING → DONE) match the DB.
   */
  function applyLibraryFromServer(rows) {
    if (!rows || !Array.isArray(rows) || !window.__lexora) return;
    const L = window.__lexora;
    if (!L.books || !L.books.length) return;

    let userBooks = getUserBooks();
    rows.forEach(function (row) {
      const bid = Number(row.book_id);
      const book =
        row.book && Number(row.book.id) === bid
          ? row.book
          : L.books.find(function (b) {
              return b.id === bid;
            });
      if (!book) return;

      const st = row.status === "plan-to-read" ? "plan-to-read" : row.status;
      let progress = row.progress;
      if (progress == null) {
        if (st === "completed") progress = 100;
        else {
          const total = getBookPages(bid).length || 24;
          const pp =
            typeof row.progress_page === "number" ? row.progress_page : 0;
          progress =
            total > 0 ? Math.round(((pp + 1) / total) * 100) : 0;
        }
      }

      const idx = userBooks.findIndex(function (ub) {
        return ub.book.id === bid;
      });
      const entry = { book: book, status: st, progress: progress };
      if (idx >= 0) userBooks[idx] = entry;
      else userBooks.push(entry);
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
