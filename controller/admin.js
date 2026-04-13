const ADMIN_DATA = {
    users: [
        { id: 1, username: "ArcaneLord", email: "arcane@lexora.io", role: "Admin", level: 99, coins: 12500, avatar: "AL", joinDate: "2023-01-15" },
        { id: 2, username: "ShadowReader", email: "shadow@mail.com", role: "User +18", level: 34, coins: 4320, avatar: "SR", joinDate: "2023-05-22" },
        { id: 3, username: "LittleScribe", email: "scribe@mail.com", role: "User -18", level: 7, coins: 880, avatar: "LS", joinDate: "2024-01-03" },
        { id: 4, username: "VelvetPages", email: "velvet@mail.com", role: "User +18", level: 52, coins: 7100, avatar: "VP", joinDate: "2023-03-11" },
        { id: 5, username: "MoonlitTales", email: "moonlit@mail.com", role: "User -18", level: 12, coins: 1200, avatar: "MT", joinDate: "2024-02-18" },
        { id: 6, username: "GoldenQuill", email: "quill@mail.com", role: "User +18", level: 28, coins: 3400, avatar: "GQ", joinDate: "2023-07-30" },
        { id: 7, username: "CrimsonInk", email: "crimson@mail.com", role: "User +18", level: 61, coins: 9800, avatar: "CI", joinDate: "2023-02-05" },
        { id: 8, username: "YoungSage", email: "sage@mail.com", role: "User -18", level: 5, coins: 450, avatar: "YS", joinDate: "2024-03-01" },
    ],
    books: [
        { id: 1, title: "Throne of Midnight", author: "E. Blackwood", genre: "Fantasy", cover: "🏰", coinCost: 150, xpReward: 200, coinReward: 50, audience: "All", trending: true, description: "An epic tale of power and darkness." },
        { id: 2, title: "Crimson Desires", author: "V. Morel", genre: "Romance", cover: "🌹", coinCost: 200, xpReward: 180, coinReward: 60, audience: "+18 Only", trending: false, description: "A passionate romance for mature readers." },
        { id: 3, title: "The Whispering Stars", author: "A. Celeste", genre: "Sci-Fi", cover: "⭐", coinCost: 120, xpReward: 160, coinReward: 40, audience: "All", trending: true, description: "Explore the cosmos through young eyes." },
        { id: 4, title: "Blood & Gold", author: "M. Thorne", genre: "Historical", cover: "⚔️", coinCost: 180, xpReward: 220, coinReward: 70, audience: "+18 Only", trending: false, description: "War, betrayal, and gold in ancient times." },
        { id: 5, title: "Dragon's Lullaby", author: "S. Ember", genre: "Fantasy", cover: "🐉", coinCost: 100, xpReward: 140, coinReward: 35, audience: "All", trending: true, description: "A young dragon finds her destiny." },
        { id: 6, title: "Neon Labyrinth", author: "K. Raven", genre: "Thriller", cover: "🔮", coinCost: 160, xpReward: 190, coinReward: 55, audience: "All", trending: false, description: "A cyberpunk mystery unfolds in neon rain." },
    ],
    posts: [
        { id: 1, title: "The ending of Throne of Midnight was...", author: "ShadowReader", book: "Throne of Midnight", tag: "spoiler", upvotes: 143, comments: 28, status: "Flagged by Lumo" },
        { id: 2, title: "Best fantasy reads of the decade?", author: "VelvetPages", book: "General", tag: "discussion", upvotes: 89, comments: 42, status: "Clean" },
        { id: 3, title: "Dragon's Lullaby - my full review", author: "MoonlitTales", book: "Dragon's Lullaby", tag: "review", upvotes: 56, comments: 14, status: "Clean" },
        { id: 4, title: "Is the Neon Labyrinth protagonist actually...", author: "CrimsonInk", book: "Neon Labyrinth", tag: "theory", upvotes: 210, comments: 67, status: "Pending Admin Review" },
        { id: 5, title: "BUY COINS CHEAP HERE!!! LIMITED TIME", author: "SpamUser99", book: "General", tag: "discussion", upvotes: 0, comments: 2, status: "Flagged by Lumo" },
        { id: 6, title: "Crimson Desires: Chapter 7 analysis", author: "GoldenQuill", book: "Crimson Desires", tag: "review", upvotes: 34, comments: 8, status: "Reviewed" },
    ],
    flags: [
        { id: 1, preview: "The ending when she dies was so...", author: "ShadowReader", book: "Throne of Midnight", type: "Spoiler", severity: "High", suggested: "Mark as Spoiler", status: "Pending" },
        { id: 2, preview: "BUY CHEAP COINS AT coinshop.xyz!!!!", author: "SpamUser99", book: "General", type: "Spam", severity: "High", suggested: "Hide Post", status: "Pending" },
        { id: 3, preview: "This content is clearly inappropriate and...", author: "DarkMage", book: "Crimson Desires", type: "Offensive", severity: "Medium", suggested: "Warn User", status: "Approved" },
        { id: 4, preview: "The scene in chapter 7 was extremely...", author: "GoldenQuill", book: "Crimson Desires", type: "Age-Sensitive", severity: "Medium", suggested: "Escalate to Admin Review", status: "Pending" },
        { id: 5, preview: "I know who the killer is in chapter...", author: "VelvetPages", book: "Neon Labyrinth", type: "Spoiler", severity: "Low", suggested: "Mark as Spoiler", status: "Rejected" },
        { id: 6, preview: "Stop posting the same thing over and over...", author: "CrimsonInk", book: "General", type: "Spam", severity: "Low", suggested: "Warn User", status: "Approved" },
    ],
    alerts: [
        { id: 1, icon: "message-square", title: "Community Review", text: "5 posts awaiting admin review", time: "2m ago", color: "text-blue-400" },
        { id: 2, icon: "book-open", title: "Unclassified Books", text: "3 uploads need age verification", time: "15m ago", color: "text-yellow-400" },
        { id: 3, icon: "coins", title: "Economy Update", text: "Reward settings modified", time: "2h ago", color: "text-green-400" },
        { id: 4, icon: "shield", title: "Lumo Detection", text: "7 new flags since midnight", time: "4h ago", color: "text-purple-400" },
    ],
    economy: [
        { day: "01", earned: 4500, spent: 3200 },
        { day: "05", earned: 5200, spent: 3800 },
        { day: "10", earned: 4800, spent: 4100 },
        { day: "15", earned: 6100, spent: 4500 },
        { day: "20", earned: 5500, spent: 4900 },
        { day: "25", earned: 7200, spent: 5200 },
        { day: "30", earned: 6800, spent: 5800 },
    ],
};

const AdminState = {
    activeSection: "dashboard",
    sidebarOpen: true,
    showNotifications: false,
    lumoActive: true,
    chartInstances: {},
    users: [...ADMIN_DATA.users],
    books: [...ADMIN_DATA.books],
    posts: [...ADMIN_DATA.posts],
    flags: [...ADMIN_DATA.flags],
    filters: {
        usersSearch: "",
        usersRole: "All",
        booksSearch: "",
        booksGenre: "All",
        booksAudience: "All",
        communitySearch: "",
        communityTag: "All",
        communityStatus: "All",
        lumoType: "All",
        lumoSeverity: "All",
    },
    ageAccess: {
        plus18: { about: true, store: true, lamp: true, community: true, bountyBoard: true, plus18Books: true },
        minus18: { about: false, store: false, lamp: false, community: true, bountyBoard: true, plus18Books: false },
    },
    rewards: {
        defaultXP: "150",
        defaultCoin: "40",
        penaltyEnabled: true,
        penalties: { l1_5: "10", l6_15: "20", l16_25: "30", l26: "40" },
    },
    settingsDefaults: {
        siteName: "Lexora",
        tagline: "Where Every Page Sparks a New Adventure",
        lumoMessage: "Greetings, young scholar! I am Lumo, your guide through the Reading Kingdom.",
        lumoChatbot: true,
        lumoModeration: true,
        trendingBadge: true,
        booksPerPage: "12",
    },
    settings: null,
    settingsSaved: false,
};
AdminState.settings = { ...AdminState.settingsDefaults };

const renderBadge = (label, color = "gold") => `<span class="badge ${color}">${label}</span>`;
const getIcon = (name, size = 18) => `<i data-lucide="${name}" style="width:${size}px;height:${size}px"></i>`;
const safeText = (value) => String(value).replace(/&/g, "&amp;").replace(/</g, "&lt;").replace(/>/g, "&gt;");

function destroyChart(id) {
    if (AdminState.chartInstances[id]) {
        AdminState.chartInstances[id].destroy();
        delete AdminState.chartInstances[id];
    }
}

function getValueFromModal(id) {
    const el = document.getElementById(id);
    return el ? el.value.trim() : "";
}

function openModal(title, fieldsHtml, onSave) {
    const container = document.getElementById("modalRoot");
    if (!container) return;
    container.innerHTML = `
      <div class="admin-modal-overlay">
        <div class="admin-modal">
          <div class="admin-modal-header">
            <h3>${safeText(title)}</h3>
            <button id="modalCloseBtn" class="admin-btn ghost">${getIcon("x", 15)}</button>
          </div>
          <div class="admin-modal-body">${fieldsHtml}</div>
          <div class="admin-modal-footer">
            <button id="modalCancelBtn" class="admin-btn secondary">Cancel</button>
            <button id="modalSaveBtn" class="admin-btn primary"><span>${getIcon("save", 14)}</span> Save</button>
          </div>
        </div>
      </div>`;
    lucide.createIcons();
    const close = () => { container.innerHTML = ""; };
    document.getElementById("modalCloseBtn").addEventListener("click", close);
    document.getElementById("modalCancelBtn").addEventListener("click", close);
    document.getElementById("modalSaveBtn").addEventListener("click", () => {
        const ok = onSave();
        if (ok !== false) close();
    });
}

function setupSidebarToggle() {
    document.getElementById("toggleSidebar").addEventListener("click", () => {
        AdminState.sidebarOpen = !AdminState.sidebarOpen;
        const sidebar = document.querySelector(".admin-sidebar");
        const main = document.querySelector(".admin-main");
        sidebar.classList.toggle("collapsed", !AdminState.sidebarOpen);
        main.classList.toggle("expanded", !AdminState.sidebarOpen);
    });
}

function setupNavigation() {
    document.querySelectorAll(".nav-item").forEach((item) => {
        item.addEventListener("click", () => {
            const section = item.dataset.section;
            if (section === "logout") {
                window.location.href = "/lexora_mlk/logout.php";
                return;
            }
            AdminState.activeSection = section;
            document.querySelectorAll(".nav-item").forEach((btn) => btn.classList.remove("active"));
            item.classList.add("active");
            document.querySelectorAll(".section-content").forEach((s) => s.classList.add("hidden"));
            document.getElementById(`${section}-section`).classList.remove("hidden");
            document.getElementById("headerTitle").textContent = item.querySelector(".nav-label").textContent;
            if (section === "dashboard") initDashboardCharts();
            if (section === "lumo") initLumoCharts();
            if (section === "rewards") initRewardsCharts();
        });
    });
}

function setupNotifications() {
    const bellBtn = document.getElementById("bellBtn");
    const notifDropdown = document.getElementById("notifDropdown");
    bellBtn.addEventListener("click", (e) => {
        e.stopPropagation();
        AdminState.showNotifications = !AdminState.showNotifications;
        notifDropdown.classList.toggle("hidden", !AdminState.showNotifications);
        bellBtn.classList.toggle("active", AdminState.showNotifications);
    });
    document.addEventListener("click", (e) => {
        if (!notifDropdown.contains(e.target) && !bellBtn.contains(e.target)) {
            AdminState.showNotifications = false;
            notifDropdown.classList.add("hidden");
            bellBtn.classList.remove("active");
        }
    });
}

function renderNotifications() {
    const list = document.getElementById("notifList");
    list.innerHTML = ADMIN_DATA.alerts.map((a) => `
      <div class="notif-item">
        <div class="notif-icon ${a.color}">${getIcon(a.icon, 14)}</div>
        <div class="notif-body">
          <h6>${safeText(a.title)}</h6>
          <p>${safeText(a.text)}</p>
          <span class="time">${safeText(a.time)}</span>
        </div>
      </div>`).join("");
    lucide.createIcons();
}

function filteredUsers() {
    const q = AdminState.filters.usersSearch.toLowerCase();
    return AdminState.users.filter((u) => {
        const roleOk = AdminState.filters.usersRole === "All" || u.role === AdminState.filters.usersRole;
        const searchOk = !q || u.username.toLowerCase().includes(q) || u.email.toLowerCase().includes(q);
        return roleOk && searchOk;
    });
}

function setupUserFilters() {
    const search = document.querySelector('[data-filter="users-search"]');
    const role = document.querySelector('[data-filter="users-role"]');
    if (search) search.addEventListener("input", (e) => {
        AdminState.filters.usersSearch = e.target.value;
        renderUserTable();
    });
    if (role) role.addEventListener("change", (e) => {
        AdminState.filters.usersRole = e.target.value;
        renderUserTable();
    });
}

function editUserById(id) {
    const user = AdminState.users.find((u) => u.id === id);
    if (!user) return;
    openModal("Edit User", `
      <label class="label-xs">Username</label>
      <input id="editUserUsername" class="admin-input full" value="${safeText(user.username)}">
      <label class="label-xs mt-3">Role</label>
      <select id="editUserRole" class="admin-input full">
        <option ${user.role === "Admin" ? "selected" : ""}>Admin</option>
        <option ${user.role === "User +18" ? "selected" : ""}>User +18</option>
        <option ${user.role === "User -18" ? "selected" : ""}>User -18</option>
      </select>
      <div class="grid-2 mt-3" style="gap:1rem">
        <div><label class="label-xs">Level</label><input id="editUserLevel" class="admin-input full" value="${user.level}"></div>
        <div><label class="label-xs">Coins</label><input id="editUserCoins" class="admin-input full" value="${user.coins}"></div>
      </div>`,
        () => {
            const level = parseInt(getValueFromModal("editUserLevel"), 10);
            const coins = parseInt(getValueFromModal("editUserCoins"), 10);
            user.username = getValueFromModal("editUserUsername") || user.username;
            user.role = getValueFromModal("editUserRole") || user.role;
            user.level = Number.isNaN(level) ? user.level : level;
            user.coins = Number.isNaN(coins) ? user.coins : coins;
            renderUserTable();
            return true;
        });
}

function renderUserTable() {
    // Disabled in favor of PHP rendering
    return;
    const tbody = document.getElementById("userTbody");
    if (!tbody) return;
    const users = filteredUsers();
    tbody.innerHTML = users.map((u) => `
      <tr>
        <td><div class="avatar">${safeText(u.avatar)}</div></td>
        <td style="font-weight:bold">${safeText(u.username)}</td>
        <td style="color:#A08060">${safeText(u.email)}</td>
        <td>${renderBadge(u.role, u.role === "Admin" ? "gold" : (u.role === "User -18" ? "green" : "purple"))}</td>
        <td><span style="color:#D4AF37">${getIcon("zap", 12)} ${u.level}</span></td>
        <td><span style="color:#D4AF37">${getIcon("coins", 12)} ${u.coins.toLocaleString()}</span></td>
        <td style="font-size:0.7rem;color:#7A6040">${safeText(u.joinDate)}</td>
        <td>
          <div style="display:flex;gap:0.25rem">
            <button class="admin-btn ghost" data-action="user-view" data-id="${u.id}">${getIcon("eye", 13)}</button>
            <button class="admin-btn ghost" data-action="user-edit" data-id="${u.id}">${getIcon("edit-2", 13)}</button>
            <button class="admin-btn ghost" style="color:#EF4444" data-action="user-delete" data-id="${u.id}">${getIcon("trash-2", 13)}</button>
          </div>
        </td>
      </tr>`).join("");
    if (!users.length) {
        tbody.innerHTML = `<tr><td colspan="8" style="text-align:center;color:#A08060;padding:2rem">No users found matching your filters.</td></tr>`;
    }
    tbody.querySelectorAll('[data-action="user-view"]').forEach((btn) => {
        btn.addEventListener("click", () => alert("View action is visual-only in parity mode."));
    });
    tbody.querySelectorAll('[data-action="user-edit"]').forEach((btn) => {
        btn.addEventListener("click", () => editUserById(Number(btn.dataset.id)));
    });
    tbody.querySelectorAll('[data-action="user-delete"]').forEach((btn) => {
        btn.addEventListener("click", () => {
            const id = Number(btn.dataset.id);
            AdminState.users = AdminState.users.filter((u) => u.id !== id);
            renderUserTable();
        });
    });
    lucide.createIcons();
}

function setupBookFilters() {
    const search = document.querySelector('[data-filter="books-search"]');
    const genre = document.querySelector('[data-filter="books-genre"]');
    const audience = document.querySelector('[data-filter="books-audience"]');
    if (search) search.addEventListener("input", (e) => {
        AdminState.filters.booksSearch = e.target.value;
        renderBookTable();
    });
    if (genre) genre.addEventListener("change", (e) => {
        AdminState.filters.booksGenre = e.target.value;
        renderBookTable();
    });
    if (audience) audience.addEventListener("change", (e) => {
        AdminState.filters.booksAudience = e.target.value;
        renderBookTable();
    });
}

function filteredBooks() {
    const q = AdminState.filters.booksSearch.toLowerCase();
    return AdminState.books.filter((b) => {
        const genreOk = AdminState.filters.booksGenre === "All" || b.genre === AdminState.filters.booksGenre;
        const audienceOk = AdminState.filters.booksAudience === "All" || b.audience === AdminState.filters.booksAudience;
        const qOk = !q || b.title.toLowerCase().includes(q) || b.author.toLowerCase().includes(q);
        return genreOk && audienceOk && qOk;
    });
}

function openBookModal(mode, existing) {
    const book = existing || {
        id: Date.now(),
        title: "",
        author: "",
        genre: "Fantasy",
        cover: "📖",
        coinCost: 100,
        xpReward: 150,
        coinReward: 40,
        audience: "All",
        trending: false,
        description: "",
    };
    openModal(mode === "add" ? "Add New Book" : "Edit Book", `
      <label class="label-xs">Title</label><input id="bookTitle" class="admin-input full" value="${safeText(book.title)}">
      <label class="label-xs mt-3">Author</label><input id="bookAuthor" class="admin-input full" value="${safeText(book.author)}">
      <div class="grid-2 mt-3" style="gap:1rem">
        <div><label class="label-xs">Genre</label><input id="bookGenre" class="admin-input full" value="${safeText(book.genre)}"></div>
        <div><label class="label-xs">Cover</label><input id="bookCover" class="admin-input full" value="${safeText(book.cover)}"></div>
      </div>
      <div class="grid-3 mt-3" style="gap:1rem">
        <div><label class="label-xs">Coin Cost</label><input id="bookCost" class="admin-input full" value="${book.coinCost}"></div>
        <div><label class="label-xs">XP Reward</label><input id="bookXp" class="admin-input full" value="${book.xpReward}"></div>
        <div><label class="label-xs">Coin Reward</label><input id="bookCoinReward" class="admin-input full" value="${book.coinReward}"></div>
      </div>
      <div class="grid-2 mt-3" style="gap:1rem">
        <div>
          <label class="label-xs">Audience</label>
          <select id="bookAudience" class="admin-input full">
            <option value="All" ${book.audience === "All" ? "selected" : ""}>All Ages</option>
            <option value="+18 Only" ${book.audience === "+18 Only" ? "selected" : ""}>+18 Only</option>
          </select>
        </div>
        <div style="display:flex;align-items:end;gap:.5rem">
          <label class="switch"><input id="bookTrending" type="checkbox" ${book.trending ? "checked" : ""}><span class="slider"></span></label>
          <span style="font-size:.8rem;color:#A08060">Trending badge</span>
        </div>
      </div>`,
        () => {
            const next = {
                ...book,
                title: getValueFromModal("bookTitle") || book.title,
                author: getValueFromModal("bookAuthor") || book.author,
                genre: getValueFromModal("bookGenre") || book.genre,
                cover: getValueFromModal("bookCover") || book.cover,
                coinCost: parseInt(getValueFromModal("bookCost"), 10) || 0,
                xpReward: parseInt(getValueFromModal("bookXp"), 10) || 0,
                coinReward: parseInt(getValueFromModal("bookCoinReward"), 10) || 0,
                audience: getValueFromModal("bookAudience") || "All",
                trending: document.getElementById("bookTrending").checked,
            };
            if (mode === "add") {
                AdminState.books = [...AdminState.books, next];
            } else {
                AdminState.books = AdminState.books.map((b) => b.id === next.id ? next : b);
            }
            renderBookTable();
            refreshBookGenreFilterOptions();
            return true;
        });
}

function refreshBookGenreFilterOptions() {
    const el = document.querySelector('[data-filter="books-genre"]');
    if (!el) return;
    const current = AdminState.filters.booksGenre;
    const genres = ["All", ...new Set(AdminState.books.map((b) => b.genre))];
    el.innerHTML = genres.map((g) => `<option value="${safeText(g)}">${safeText(g)}</option>`).join("");
    el.value = genres.includes(current) ? current : "All";
    AdminState.filters.booksGenre = el.value;
}

function renderBookTable() {
    // Disabled in favor of PHP rendering
    return;
    const tbody = document.getElementById("bookTbody");
    if (!tbody) return;
    const books = filteredBooks();
    tbody.innerHTML = books.map((b) => `
      <tr>
        <td style="font-size:1.5rem">${safeText(b.cover)}</td>
        <td style="font-weight:bold">${safeText(b.title)}</td>
        <td style="color:#A08060">${safeText(b.author)}</td>
        <td>${renderBadge(b.genre, "blue")}</td>
        <td><span style="color:#D4AF37">${getIcon("coins", 12)} ${b.coinCost}</span></td>
        <td style="font-size:0.7rem;color:#A08060">+${b.xpReward}xp / +${b.coinReward} coins</td>
        <td>${renderBadge(b.audience === "All" ? "All Ages" : "+18 Only", b.audience === "All" ? "green" : "red")}</td>
        <td>${b.trending ? renderBadge("HOT", "orange") : "—"}</td>
        <td>
          <div style="display:flex;gap:0.25rem">
            <button class="admin-btn ghost" data-action="book-view" data-id="${b.id}">${getIcon("eye", 13)}</button>
            <button class="admin-btn ghost" data-action="book-edit" data-id="${b.id}">${getIcon("edit-2", 13)}</button>
            <button class="admin-btn ghost" style="color:#EF4444" data-action="book-delete" data-id="${b.id}">${getIcon("trash-2", 13)}</button>
          </div>
        </td>
      </tr>`).join("");
    if (!books.length) {
        tbody.innerHTML = `<tr><td colspan="9" style="text-align:center;color:#A08060;padding:2rem">No books found matching your filters.</td></tr>`;
    }
    tbody.querySelectorAll('[data-action="book-view"]').forEach((btn) => {
        btn.addEventListener("click", () => alert("View action is visual-only in parity mode."));
    });
    tbody.querySelectorAll('[data-action="book-edit"]').forEach((btn) => {
        btn.addEventListener("click", () => {
            const id = Number(btn.dataset.id);
            const book = AdminState.books.find((b) => b.id === id);
            if (book) openBookModal("edit", book);
        });
    });
    tbody.querySelectorAll('[data-action="book-delete"]').forEach((btn) => {
        btn.addEventListener("click", () => {
            const id = Number(btn.dataset.id);
            AdminState.books = AdminState.books.filter((b) => b.id !== id);
            renderBookTable();
            refreshBookGenreFilterOptions();
        });
    });
    lucide.createIcons();
}

function setupBookActions() {
    const addBtn = document.querySelector('[data-action="book-add"]');
    if (addBtn) {
        addBtn.addEventListener("click", () => openBookModal("add"));
    }
}

function setupCommunityFilters() {
    const search = document.querySelector('[data-filter="community-search"]');
    const tag = document.querySelector('[data-filter="community-tag"]');
    const status = document.querySelector('[data-filter="community-status"]');
    if (search) search.addEventListener("input", (e) => {
        AdminState.filters.communitySearch = e.target.value;
        renderCommunityTable();
    });
    if (tag) tag.addEventListener("change", (e) => {
        AdminState.filters.communityTag = e.target.value;
        renderCommunityTable();
    });
    if (status) status.addEventListener("change", (e) => {
        AdminState.filters.communityStatus = e.target.value;
        renderCommunityTable();
    });
}

function renderPostStatusBadge(status) {
    if (status === "Clean") return renderBadge("Clean", "green");
    if (status === "Flagged by Lumo") return renderBadge("Flagged", "red");
    if (status === "Pending Admin Review") return renderBadge("Pending", "orange");
    return renderBadge("Reviewed", "blue");
}

function renderTagBadge(tag) {
    const colorMap = { discussion: "blue", review: "green", theory: "purple", spoiler: "red" };
    return renderBadge(tag, colorMap[tag] || "blue");
}

function filteredPosts() {
    const q = AdminState.filters.communitySearch.toLowerCase();
    return AdminState.posts.filter((p) => {
        const qOk = !q || p.title.toLowerCase().includes(q);
        const tagOk = AdminState.filters.communityTag === "All" || p.tag === AdminState.filters.communityTag;
        const statusOk = AdminState.filters.communityStatus === "All" || p.status === AdminState.filters.communityStatus;
        return qOk && tagOk && statusOk;
    });
}

function renderCommunityTable() {
    // Disabled in favor of PHP rendering
    return;
    const tbody = document.getElementById("communityTbody");
    if (!tbody) return;
    const posts = filteredPosts();
    tbody.innerHTML = posts.map((p) => `
      <tr>
        <td style="max-width:220px"><p style="color:#F5EDD6;font-weight:600;white-space:nowrap;overflow:hidden;text-overflow:ellipsis">${safeText(p.title)}</p></td>
        <td>${safeText(p.author)}</td>
        <td style="font-size:0.75rem;color:var(--admin-text-dim)">${safeText(p.book)}</td>
        <td>${renderTagBadge(p.tag)}</td>
        <td style="color:#D4AF37">${p.upvotes}</td>
        <td style="color:#A08060">${p.comments}</td>
        <td>${renderPostStatusBadge(p.status)}</td>
        <td>
          <div style="display:flex;gap:0.25rem">
            <button class="admin-btn ghost" data-action="post-view" data-id="${p.id}">${getIcon("eye", 13)}</button>
            <button class="admin-btn ghost" data-action="post-review" data-id="${p.id}">${getIcon("eye-off", 13)}</button>
            <button class="admin-btn ghost" data-action="post-tag" data-id="${p.id}">${getIcon("tag", 13)}</button>
            <button class="admin-btn ghost" style="color:#EF4444" data-action="post-delete" data-id="${p.id}">${getIcon("trash-2", 13)}</button>
          </div>
        </td>
      </tr>`).join("");
    if (!posts.length) {
        tbody.innerHTML = `<tr><td colspan="8" style="text-align:center;color:#A08060;padding:2rem">No posts found.</td></tr>`;
    }
    tbody.querySelectorAll('[data-action="post-view"]').forEach((btn) => {
        btn.addEventListener("click", () => alert("Post preview is visual-only in parity mode."));
    });
    tbody.querySelectorAll('[data-action="post-review"]').forEach((btn) => {
        btn.addEventListener("click", () => {
            const id = Number(btn.dataset.id);
            AdminState.posts = AdminState.posts.map((p) => p.id === id ? { ...p, status: "Reviewed" } : p);
            renderCommunityTable();
        });
    });
    tbody.querySelectorAll('[data-action="post-tag"]').forEach((btn) => {
        btn.addEventListener("click", () => {
            const id = Number(btn.dataset.id);
            AdminState.posts = AdminState.posts.map((p) => p.id === id ? { ...p, status: "Flagged by Lumo", tag: "spoiler" } : p);
            renderCommunityTable();
        });
    });
    tbody.querySelectorAll('[data-action="post-delete"]').forEach((btn) => {
        btn.addEventListener("click", () => {
            const id = Number(btn.dataset.id);
            AdminState.posts = AdminState.posts.filter((p) => p.id !== id);
            renderCommunityTable();
        });
    });
    lucide.createIcons();
}

function setupLumoFiltersAndToggle() {
    const type = document.querySelector('[data-filter="lumo-type"]');
    const severity = document.querySelector('[data-filter="lumo-severity"]');
    const active = document.getElementById("lumoActiveToggle");
    if (type) type.addEventListener("change", (e) => {
        AdminState.filters.lumoType = e.target.value;
        renderLumoTable();
    });
    if (severity) severity.addEventListener("change", (e) => {
        AdminState.filters.lumoSeverity = e.target.value;
        renderLumoTable();
    });
    if (active) active.addEventListener("change", (e) => {
        AdminState.lumoActive = e.target.checked;
        const status = document.getElementById("lumoActiveText");
        const statusBox = document.getElementById("lumoStatusBox");
        if (status) status.textContent = AdminState.lumoActive ? "System Status: Active" : "System Status: Inactive";
        if (statusBox) statusBox.classList.toggle("active", AdminState.lumoActive);
    });
}

function filteredFlags() {
    return AdminState.flags.filter((f) => {
        const typeOk = AdminState.filters.lumoType === "All" || f.type === AdminState.filters.lumoType;
        const severityOk = AdminState.filters.lumoSeverity === "All" || f.severity === AdminState.filters.lumoSeverity;
        return typeOk && severityOk;
    });
}

function renderLumoTable() {
    const tbody = document.getElementById("lumoTbody");
    if (!tbody) return;
    const flags = filteredFlags();
    const pending = AdminState.flags.filter((f) => f.status === "Pending").length;
    const pendingLabel = document.getElementById("lumoPendingCount");
    if (pendingLabel) pendingLabel.textContent = String(pending);
    tbody.innerHTML = flags.map((f) => `
      <tr>
        <td style="max-width:160px"><p class="text-xs truncate italic" style="color:var(--admin-text-muted)">"${safeText(f.preview)}"</p></td>
        <td>${safeText(f.author)}</td>
        <td style="font-size:0.75rem;color:var(--admin-text-dim)">${safeText(f.book)}</td>
        <td>${renderBadge(f.type, getSectionBadgeColor(f.type))}</td>
        <td>${renderBadge(f.severity, f.severity === "High" ? "red" : f.severity === "Medium" ? "orange" : "blue")}</td>
        <td style="font-size:0.75rem;color:var(--admin-text-muted)">${safeText(f.suggested)}</td>
        <td>${renderBadge(f.status, f.status === "Approved" ? "green" : f.status === "Pending" ? "gold" : "red")}</td>
        <td>
          <div style="display:flex;gap:0.25rem">
            <button class="admin-btn ghost" style="color:#22C55E" data-action="flag-approve" data-id="${f.id}">${getIcon("check", 13)}</button>
            <button class="admin-btn ghost" style="color:#EF4444" data-action="flag-reject" data-id="${f.id}">${getIcon("x", 13)}</button>
            <button class="admin-btn ghost" data-action="flag-view" data-id="${f.id}">${getIcon("eye", 13)}</button>
          </div>
        </td>
      </tr>`).join("");
    if (!flags.length) {
        tbody.innerHTML = `<tr><td colspan="8" style="text-align:center;color:#A08060;padding:2rem">No flags match your filters.</td></tr>`;
    }
    tbody.querySelectorAll('[data-action="flag-approve"]').forEach((btn) => {
        btn.addEventListener("click", () => {
            const id = Number(btn.dataset.id);
            AdminState.flags = AdminState.flags.map((f) => f.id === id ? { ...f, status: "Approved" } : f);
            renderLumoTable();
        });
    });
    tbody.querySelectorAll('[data-action="flag-reject"]').forEach((btn) => {
        btn.addEventListener("click", () => {
            const id = Number(btn.dataset.id);
            AdminState.flags = AdminState.flags.map((f) => f.id === id ? { ...f, status: "Rejected" } : f);
            renderLumoTable();
        });
    });
    tbody.querySelectorAll('[data-action="flag-view"]').forEach((btn) => {
        btn.addEventListener("click", () => alert("Flag preview is visual-only in parity mode."));
    });
    lucide.createIcons();
}

function setupAgeAccess() {
    document.querySelectorAll('[data-age-group][data-age-key]').forEach((el) => {
        el.addEventListener("change", (e) => {
            const group = el.dataset.ageGroup;
            const key = el.dataset.ageKey;
            AdminState.ageAccess[group][key] = e.target.checked;
        });
    });
}

function setupRewardsSettings() {
    const map = [
        ["rewardDefaultXp", "defaultXP"],
        ["rewardDefaultCoin", "defaultCoin"],
    ];
    map.forEach(([id, key]) => {
        const el = document.getElementById(id);
        if (el) {
            el.addEventListener("input", (e) => {
                AdminState.rewards[key] = e.target.value;
            });
        }
    });

    const penaltyToggle = document.getElementById("rewardPenaltyEnabled");
    if (penaltyToggle) {
        penaltyToggle.addEventListener("change", (e) => {
            AdminState.rewards.penaltyEnabled = e.target.checked;
            const wrap = document.getElementById("rewardPenaltyWrap");
            if (wrap) wrap.style.opacity = AdminState.rewards.penaltyEnabled ? "1" : "0.35";
        });
    }

    ["l1_5", "l6_15", "l16_25", "l26"].forEach((key) => {
        const el = document.getElementById(`penalty_${key}`);
        if (el) {
            el.addEventListener("input", (e) => {
                AdminState.rewards.penalties[key] = e.target.value;
            });
        }
    });
}

function setupSiteSettings() {
    const bind = [
        ["settingSiteName", "siteName"],
        ["settingTagline", "tagline"],
        ["settingLumoMessage", "lumoMessage"],
        ["settingBooksPerPage", "booksPerPage"],
    ];
    bind.forEach(([id, key]) => {
        const el = document.getElementById(id);
        if (el) {
            el.addEventListener("input", (e) => { AdminState.settings[key] = e.target.value; });
            el.addEventListener("change", (e) => { AdminState.settings[key] = e.target.value; });
        }
    });
    ["lumoChatbot", "lumoModeration", "trendingBadge"].forEach((key) => {
        const el = document.getElementById(`setting_${key}`);
        if (el) {
            el.addEventListener("change", (e) => { AdminState.settings[key] = e.target.checked; });
        }
    });
    const save = document.getElementById("settingsSaveBtn");
    const reset = document.getElementById("settingsResetBtn");
    const stateMsg = document.getElementById("settingsSavedMsg");
    if (save) {
        save.addEventListener("click", () => {
            AdminState.settingsSaved = true;
            if (stateMsg) stateMsg.classList.remove("hidden");
            setTimeout(() => {
                AdminState.settingsSaved = false;
                if (stateMsg) stateMsg.classList.add("hidden");
            }, 2500);
        });
    }
    if (reset) {
        reset.addEventListener("click", () => {
            AdminState.settings = { ...AdminState.settingsDefaults };
            syncSettingsUi();
        });
    }
}

function syncSettingsUi() {
    const set = AdminState.settings;
    const byId = (id) => document.getElementById(id);
    if (byId("settingSiteName")) byId("settingSiteName").value = set.siteName;
    if (byId("settingTagline")) byId("settingTagline").value = set.tagline;
    if (byId("settingLumoMessage")) byId("settingLumoMessage").value = set.lumoMessage;
    if (byId("settingBooksPerPage")) byId("settingBooksPerPage").value = set.booksPerPage;
    if (byId("setting_lumoChatbot")) byId("setting_lumoChatbot").checked = set.lumoChatbot;
    if (byId("setting_lumoModeration")) byId("setting_lumoModeration").checked = set.lumoModeration;
    if (byId("setting_trendingBadge")) byId("setting_trendingBadge").checked = set.trendingBadge;
}

function getSectionBadgeColor(type) {
    switch (type) {
        case "Spoiler": return "gold";
        case "Spam": return "red";
        case "Offensive": return "purple";
        case "Age-Sensitive": return "orange";
        default: return "blue";
    }
}

function initDashboardCharts() {
    destroyChart("growthChart");
    destroyChart("genreChart");
    const growthCanvas = document.getElementById("growthChart");
    const genreCanvas = document.getElementById("genreChart");
    if (!growthCanvas || !genreCanvas) return;
    AdminState.chartInstances.growthChart = new Chart(growthCanvas.getContext("2d"), {
        type: "line",
        data: { labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun"], datasets: [{ data: [4000, 4500, 5200, 5800, 6500, 8241], borderColor: "#D4AF37", backgroundColor: "rgba(212, 175, 55, 0.1)", borderWidth: 3, fill: true, tension: 0.4 }] },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: { y: { grid: { color: "rgba(74, 42, 16, 0.3)" }, ticks: { color: "#7A6040", font: { size: 10 } } }, x: { grid: { display: false }, ticks: { color: "#7A6040", font: { size: 10 } } } },
        },
    });
    AdminState.chartInstances.genreChart = new Chart(genreCanvas.getContext("2d"), {
        type: "bar",
        data: { labels: ["Fantasy", "Sci-Fi", "Romance", "Thriller", "Historical"], datasets: [{ data: [38, 24, 18, 12, 8], backgroundColor: "#8B7322", borderRadius: 4 }] },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: { y: { grid: { color: "rgba(74, 42, 16, 0.3)" }, ticks: { color: "#7A6040", font: { size: 10 } } }, x: { grid: { display: false }, ticks: { color: "#7A6040", font: { size: 10 } } } },
        },
    });
}

function initLumoCharts() {
    destroyChart("lumoPieChart");
    destroyChart("lumoLineChart");
    const pie = document.getElementById("lumoPieChart");
    const line = document.getElementById("lumoLineChart");
    if (!pie || !line) return;
    AdminState.chartInstances.lumoPieChart = new Chart(pie.getContext("2d"), {
        type: "doughnut",
        data: { labels: ["Spam", "Spoiler", "Offensive", "Other"], datasets: [{ data: [45, 30, 15, 10], backgroundColor: ["#EF4444", "#D4AF37", "#6B1A2A", "#A855F7"], borderColor: "transparent" }] },
        options: { responsive: true, maintainAspectRatio: false, cutout: "60%", plugins: { legend: { display: false } } },
    });
    AdminState.chartInstances.lumoLineChart = new Chart(line.getContext("2d"), {
        type: "line",
        data: { labels: ["Mon", "Tue", "Wed", "Thu", "Fri", "Sat", "Sun"], datasets: [{ data: [12, 19, 15, 8, 22, 30, 23], borderColor: "#A78BFA", backgroundColor: "rgba(167, 139, 250, 0.1)", fill: true, tension: 0.4 }] },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: { y: { grid: { color: "rgba(255,255,255,0.05)" }, ticks: { color: "#7A6040", font: { size: 10 } } }, x: { grid: { display: false }, ticks: { color: "#7A6040", font: { size: 10 } } } },
        },
    });
}

function initRewardsCharts() {
    destroyChart("economyLineChart");
    const canvas = document.getElementById("economyLineChart");
    if (!canvas) return;
    AdminState.chartInstances.economyLineChart = new Chart(canvas.getContext("2d"), {
        type: "line",
        data: {
            labels: ["01", "05", "10", "15", "20", "25", "30"],
            datasets: [
                { label: "Earned", data: [4500, 5200, 4800, 6100, 5500, 7200, 6800], borderColor: "#D4AF37", tension: 0.3 },
                { label: "Spent", data: [3200, 3800, 4100, 4500, 4900, 5200, 5800], borderColor: "#EF4444", tension: 0.3 },
            ],
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: { y: { grid: { color: "rgba(74, 42, 16, 0.3)" }, ticks: { color: "#7A6040", font: { size: 10 } } }, x: { grid: { display: false }, ticks: { color: "#7A6040", font: { size: 10 } } } },
        },
    });
}

function syncInitialInputs() {
    const bindValue = (selector, value) => {
        const el = document.querySelector(selector);
        if (el) el.value = value;
    };
    bindValue('[data-filter="users-search"]', AdminState.filters.usersSearch);
    bindValue('[data-filter="users-role"]', AdminState.filters.usersRole);
    bindValue('[data-filter="books-search"]', AdminState.filters.booksSearch);
    bindValue('[data-filter="books-audience"]', AdminState.filters.booksAudience);
    bindValue('[data-filter="community-search"]', AdminState.filters.communitySearch);
    bindValue('[data-filter="community-tag"]', AdminState.filters.communityTag);
    bindValue('[data-filter="community-status"]', AdminState.filters.communityStatus);
    bindValue('[data-filter="lumo-type"]', AdminState.filters.lumoType);
    bindValue('[data-filter="lumo-severity"]', AdminState.filters.lumoSeverity);
    refreshBookGenreFilterOptions();

    const byId = (id) => document.getElementById(id);
    if (byId("rewardDefaultXp")) byId("rewardDefaultXp").value = AdminState.rewards.defaultXP;
    if (byId("rewardDefaultCoin")) byId("rewardDefaultCoin").value = AdminState.rewards.defaultCoin;
    if (byId("rewardPenaltyEnabled")) byId("rewardPenaltyEnabled").checked = AdminState.rewards.penaltyEnabled;
    if (byId("penalty_l1_5")) byId("penalty_l1_5").value = AdminState.rewards.penalties.l1_5;
    if (byId("penalty_l6_15")) byId("penalty_l6_15").value = AdminState.rewards.penalties.l6_15;
    if (byId("penalty_l16_25")) byId("penalty_l16_25").value = AdminState.rewards.penalties.l16_25;
    if (byId("penalty_l26")) byId("penalty_l26").value = AdminState.rewards.penalties.l26;
    const penaltyWrap = byId("rewardPenaltyWrap");
    if (penaltyWrap) penaltyWrap.style.opacity = AdminState.rewards.penaltyEnabled ? "1" : "0.35";

    if (byId("lumoActiveToggle")) byId("lumoActiveToggle").checked = AdminState.lumoActive;
    syncSettingsUi();
}

function initAdmin() {
    setupSidebarToggle();
    setupNavigation();
    setupNotifications();
    setupUserFilters();
    setupBookFilters();
    setupBookActions();
    setupCommunityFilters();
    setupLumoFiltersAndToggle();
    setupAgeAccess();
    setupRewardsSettings();
    setupSiteSettings();
    syncInitialInputs();
    renderNotifications();
    renderUserTable();
    renderBookTable();
    renderCommunityTable();
    renderLumoTable();
    initDashboardCharts();
    initLumoCharts();
    initRewardsCharts();
    lucide.createIcons();

    // Handle redirection / reloading on specific tabs based on URL parameters
    const params = new URLSearchParams(window.location.search);
    let targetSection = "dashboard";
    if (params.has("section")) {
        targetSection = params.get("section");
    } else if (window.location.search.includes("book")) {
        targetSection = "books";
    } else if (window.location.search.includes("post") || params.has("tab") && params.get("tab") === "community") {
        targetSection = "community";
    } else if (window.location.search.includes("user") || window.location.search.includes("modif") || window.location.search.includes("delete=")) {
        targetSection = "users";
    }

    if (targetSection !== "dashboard") {
        const navBtn = document.querySelector(`[data-section="${targetSection}"]`);
        if (navBtn) navBtn.click();
    }
}

document.addEventListener("DOMContentLoaded", initAdmin);
