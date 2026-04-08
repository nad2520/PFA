<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lexora Admin � Kingdom Management</title>
    <link rel="stylesheet" href="admin_scss/main.css">
    <link rel="stylesheet" href="admin_scss/admin.css">
    <script src="https://unpkg.com/lucide@latest"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,700;1,400&family=Crimson+Text:ital,wght@0,400;0,600;0,700;1,400&family=Press+Start+2P&display=swap');
    </style>
</head>

<body class="admin-page">

    <!-- --- SIDEBAR --- -->
    <aside class="admin-sidebar">
        <div class="sidebar-header">
            <div class="logo-icon">✦</div>
            <div class="logo-text">
                <div>LEXORA</div>
                <div style="font-size:0.6rem;color:#7A6040;font-weight:normal">Admin Portal</div>
            </div>
        </div>

        <nav class="sidebar-nav">
            <button class="nav-item active" data-section="dashboard">
                <i data-lucide="layout-dashboard"></i>
                <span class="nav-label">Dashboard</span>
            </button>
            <button class="nav-item" data-section="users">
                <i data-lucide="users"></i>
                <span class="nav-label">Users</span>
            </button>
            <button class="nav-item" data-section="books">
                <i data-lucide="book-open"></i>
                <span class="nav-label">Books</span>
            </button>
            <button class="nav-item" data-section="community">
                <i data-lucide="message-square"></i>
                <span class="nav-label">Community</span>
                <span class="badge">5</span>
            </button>
            <button class="nav-item" data-section="lumo">
                <i data-lucide="shield"></i>
                <span class="nav-label">Lumo AI</span>
                <span class="badge">23</span>
            </button>
            <button class="nav-item" data-section="age">
                <i data-lucide="lock"></i>
                <span class="nav-label">Age Access</span>
            </button>
            <button class="nav-item" data-section="rewards">
                <i data-lucide="coins"></i>
                <span class="nav-label">Rewards</span>
            </button>
            <button class="nav-item" data-section="settings">
                <i data-lucide="settings"></i>
                <span class="nav-label">Settings</span>
            </button>
            <div style="flex:1"></div>
            <button class="nav-item logout" data-section="logout" onclick="window.location.href='index.php'">
                <i data-lucide="log-out"></i>
                <span class="nav-label">Logout</span>
            </button>
        </nav>

        <div class="sidebar-footer">
            <div class="avatar">AL</div>
            <div class="admin-info">
                <h4>ArcaneLord</h4>
                <p>Super Admin</p>
            </div>
            <i data-lucide="crown" style="margin-left:auto;color:#D4AF37;width:14px"></i>
        </div>
    </aside>

    <!-- --- MAIN CONTENT --- -->
    <div class="admin-main">
        <header class="admin-header">
            <div class="header-left">
                <button class="toggle-sidebar" id="toggleSidebar">
                    <i data-lucide="more-horizontal"></i>
                </button>
                <h1 id="headerTitle">Dashboard</h1>
            </div>
            <div class="header-right">
                <div style="position:relative">
                    <button class="bell-btn" id="bellBtn">
                        <i data-lucide="bell"></i>
                        <span class="dot"></span>
                    </button>
                    <!-- Notification Dropdown -->
                    <div class="notification-dropdown hidden" id="notifDropdown">
                        <div class="notif-header">
                            <h6>Kingdom Alerts</h6>
                            <span class="notif-count">4 NEW</span>
                        </div>
                        <div class="notif-list" id="notifList">
                            <!-- Injected by JS -->
                        </div>
                        <div class="notif-footer">View All Chronicles</div>
                    </div>
                </div>
                <div class="user-avatar">AL</div>
            </div>
        </header>

        <!-- Decorative Glow -->
        <div style="height:1px;background:linear-gradient(90deg, transparent, #8B7322, transparent)"></div>

        <main class="admin-content">
            <div style="max-width:1200px;margin:0 auto">

                <!-- --- SECTION: DASHBOARD --- -->
                <div id="dashboard-section" class="section-content animate-in fade-in duration-500">
                    <div style="margin-bottom:1.5rem">
                        <div style="display:flex;align-items:center;gap:0.75rem;margin-bottom:0.25rem">
                            <i data-lucide="layout-dashboard" style="color:#D4AF37"></i>
                            <h2 style="font-size:1.25rem;font-weight:bold;color:#F5EDD6">Kingdom Overview</h2>
                        </div>
                        <p style="font-size:0.85rem;color:#A08060;margin-left:2.25rem">Real-time snapshot of your
                            reading realm</p>
                        <div
                            style="margin-top:0.75rem;height:1px;background:linear-gradient(90deg, #8B7322, transparent)">
                        </div>
                    </div>

                    <!-- Stat Cards -->
                    <div
                        style="display:grid;grid-template-columns:repeat(auto-fit, minmax(240px, 1fr));gap:1rem;margin-bottom:1.5rem">
                        <div class="admin-card stat-card">
                            <div class="stat-header">
                                <div class="icon-box"><i data-lucide="users"></i></div>
                                <div class="stat-labels">
                                    <h5>Total Users</h5>
                                    <div class="value">8,241</div>
                                    <div class="sub">+124 this week</div>
                                </div>
                            </div>
                        </div>
                        <div class="admin-card stat-card">
                            <div class="stat-header">
                                <div class="icon-box" style="color:#93C5FD"><i data-lucide="book-open"></i></div>
                                <div class="stat-labels">
                                    <h5>Total Books</h5>
                                    <div class="value">342</div>
                                    <div class="sub">24 genres</div>
                                </div>
                            </div>
                        </div>
                        <div class="admin-card stat-card">
                            <div class="stat-header">
                                <div class="icon-box" style="color:#86EFAC"><i data-lucide="message-square"></i></div>
                                <div class="stat-labels">
                                    <h5>Community Posts</h5>
                                    <div class="value">14,890</div>
                                    <div class="sub">Active discussions</div>
                                </div>
                            </div>
                        </div>
                        <div class="admin-card stat-card">
                            <div class="stat-header">
                                <div class="icon-box" style="color:#FCA5A5"><i data-lucide="alert-triangle"></i></div>
                                <div class="stat-labels">
                                    <h5>Flagged by Lumo</h5>
                                    <div class="value">23</div>
                                    <div class="sub">Pending review</div>
                                </div>
                            </div>
                        </div>
                        <div class="admin-card stat-card">
                            <div class="stat-header">
                                <div class="icon-box" style="color:#A855F7"><i data-lucide="lock"></i></div>
                                <div class="stat-labels">
                                    <h5>+18 Books</h5>
                                    <div class="value">89</div>
                                    <div class="sub">Age-restricted</div>
                                </div>
                            </div>
                        </div>
                        <div class="admin-card stat-card">
                            <div class="stat-header">
                                <div class="icon-box" style="color:#22C55E"><i data-lucide="eye"></i></div>
                                <div class="stat-labels">
                                    <h5>Visible to -18</h5>
                                    <div class="value">253</div>
                                    <div class="sub">Safe content</div>
                                </div>
                            </div>
                        </div>
                        <div class="admin-card stat-card">
                            <div class="stat-header">
                                <div class="icon-box"><i data-lucide="coins"></i></div>
                                <div class="stat-labels">
                                    <h5>Coins in Circulation</h5>
                                    <div class="value">2.4M</div>
                                    <div class="sub">Economy health: stable</div>
                                </div>
                            </div>
                        </div>
                        <div class="admin-card stat-card">
                            <div class="stat-header">
                                <div class="icon-box" style="color:#F97316"><i data-lucide="flame"></i></div>
                                <div class="stat-labels">
                                    <h5>Active Today</h5>
                                    <div class="value">1,832</div>
                                    <div class="sub">Reading right now</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Dashboard Charts -->
                    <div
                        style="display:grid;grid-template-columns:repeat(auto-fit, minmax(400px, 1fr));gap:1.5rem;margin-bottom:1.5rem">
                        <div class="admin-card">
                            <h3
                                style="font-size:0.85rem;font-weight:bold;margin-bottom:1rem;display:flex;align-items:center;gap:0.5rem">
                                <i data-lucide="trending-up" style="color:#D4AF37;width:16px"></i> Kingdom Growth
                            </h3>
                            <div style="height:200px"><canvas id="growthChart"></canvas></div>
                        </div>
                        <div class="admin-card">
                            <h3
                                style="font-size:0.85rem;font-weight:bold;margin-bottom:1rem;display:flex;align-items:center;gap:0.5rem">
                                <i data-lucide="bar-chart-2" style="color:#D4AF37;width:16px"></i> Engagement by Genre
                            </h3>
                            <div style="height:200px"><canvas id="genreChart"></canvas></div>
                        </div>
                    </div>

                    <div class="grid-2" style="grid-template-columns:repeat(auto-fit,minmax(240px,1fr));margin-bottom:1.5rem">
                        <div class="admin-card" style="padding:1rem">
                            <div style="display:flex;align-items:flex-start;gap:0.75rem">
                                <div class="icon-box" style="width:2.2rem;height:2.2rem"><i data-lucide="star"></i></div>
                                <div>
                                    <p style="font-size:0.65rem;letter-spacing:.05em;color:#A08060;text-transform:uppercase;margin:0 0 .2rem 0">Most Popular Genre</p>
                                    <p style="font-weight:700;color:#F5EDD6;margin:0">Fantasy</p>
                                    <p style="font-size:.72rem;color:#7A6040;margin:.2rem 0 0 0">38% of reads</p>
                                </div>
                            </div>
                        </div>
                        <div class="admin-card" style="padding:1rem">
                            <div style="display:flex;align-items:flex-start;gap:0.75rem">
                                <div class="icon-box" style="width:2.2rem;height:2.2rem"><i data-lucide="book-marked"></i></div>
                                <div>
                                    <p style="font-size:0.65rem;letter-spacing:.05em;color:#A08060;text-transform:uppercase;margin:0 0 .2rem 0">Most Read Book</p>
                                    <p style="font-weight:700;color:#F5EDD6;margin:0">Dragon's Lullaby</p>
                                    <p style="font-size:.72rem;color:#7A6040;margin:.2rem 0 0 0">4,210 reads</p>
                                </div>
                            </div>
                        </div>
                        <div class="admin-card" style="padding:1rem">
                            <div style="display:flex;align-items:flex-start;gap:0.75rem">
                                <div class="icon-box" style="width:2.2rem;height:2.2rem"><i data-lucide="crown"></i></div>
                                <div>
                                    <p style="font-size:0.65rem;letter-spacing:.05em;color:#A08060;text-transform:uppercase;margin:0 0 .2rem 0">Top Active User</p>
                                    <p style="font-weight:700;color:#F5EDD6;margin:0">CrimsonInk</p>
                                    <p style="font-size:.72rem;color:#7A6040;margin:.2rem 0 0 0">Level 61 � 9,800 coins</p>
                                </div>
                            </div>
                        </div>
                        <div class="admin-card" style="padding:1rem">
                            <div style="display:flex;align-items:flex-start;gap:0.75rem">
                                <div class="icon-box" style="width:2.2rem;height:2.2rem;color:#EF4444"><i data-lucide="alert-triangle"></i></div>
                                <div>
                                    <p style="font-size:0.65rem;letter-spacing:.05em;color:#A08060;text-transform:uppercase;margin:0 0 .2rem 0">Most Flagged Type</p>
                                    <p style="font-weight:700;color:#F5EDD6;margin:0">Spoilers</p>
                                    <p style="font-size:.72rem;color:#7A6040;margin:.2rem 0 0 0">42% of flags</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Alerts -->
                    <div class="admin-card" style="border-color:#6B1A2A">
                        <h3
                            style="font-size:0.85rem;font-weight:bold;margin-bottom:1rem;display:flex;align-items:center;gap:0.5rem">
                            <i data-lucide="bell" style="color:#EF4444;width:16px"></i> Quick Alerts
                        </h3>
                        <div style="display:flex;flex-direction:column;gap:0.5rem">
                            <div
                                style="display:flex;align-items:center;gap:0.75rem;padding:0.75rem;background:rgba(0,0,0,0.25);border-radius:0.75rem;font-size:0.85rem;color:#A08060">
                                <i data-lucide="alert-triangle" style="color:#FDBA74;width:14px"></i> 5 community posts
                                awaiting admin review
                            </div>
                            <div
                                style="display:flex;align-items:center;gap:0.75rem;padding:0.75rem;background:rgba(0,0,0,0.25);border-radius:0.75rem;font-size:0.85rem;color:#A08060">
                                <i data-lucide="book-open" style="color:#FDE047;width:14px"></i> 3 newly uploaded books
                                need age classification
                            </div>
                            <div
                                style="display:flex;align-items:center;gap:0.75rem;padding:0.75rem;background:rgba(0,0,0,0.25);border-radius:0.75rem;font-size:0.85rem;color:#A08060">
                                <i data-lucide="coins" style="color:#22C55E;width:14px"></i> Reward settings were
                                modified 2 hours ago
                            </div>
                            <div
                                style="display:flex;align-items:center;gap:0.75rem;padding:0.75rem;background:rgba(0,0,0,0.25);border-radius:0.75rem;font-size:0.85rem;color:#A08060">
                                <i data-lucide="shield" style="color:#60A5FA;width:14px"></i> Lumo AI flagged 7 new
                                posts since midnight
                            </div>
                        </div>
                    </div>
                </div>

                <!-- --- SECTION: USERS --- -->
                <div id="users-section" class="section-content hidden animate-in fade-in duration-500">
                    <div style="margin-bottom:1.5rem">
                        <div style="display:flex;align-items:center;gap:0.75rem;margin-bottom:0.25rem">
                            <i data-lucide="users" style="color:#D4AF37"></i>
                            <h2 style="font-size:1.25rem;font-weight:bold;color:#F5EDD6">User Management</h2>
                        </div>
                        <p style="font-size:0.85rem;color:#A08060;margin-left:2.25rem">Manage all Lexora kingdom members
                        </p>
                        <div
                            style="margin-top:0.75rem;height:1px;background:linear-gradient(90deg, #8B7322, transparent)">
                        </div>
                    </div>

                    <div style="display:flex;gap:1rem;margin-bottom:1rem">
                        <input type="text" placeholder="Search users..." class="admin-input" style="flex:1"
                            data-filter="users-search">
                        <select class="admin-input" data-filter="users-role">
                            <option value="All">All Roles</option>
                            <option value="Admin">Admin</option>
                            <option value="User +18">User +18</option>
                            <option value="User -18">User -18</option>
                        </select>
                    </div>

                    <div class="admin-card" style="padding:0">
                        <div class="admin-table-container">
                            <table>
                                <thead>
                                    <tr>
                                        <th>Avatar</th>
                                        <th>Username</th>
                                        <th>Email</th>
                                        <th>Role</th>
                                        <th>Level</th>
                                        <th>Coins</th>
                                        <th>Joined</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="userTbody">
                                    <!-- Injected by JS -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- --- SECTION: BOOKS --- -->
                <div id="books-section" class="section-content hidden animate-in fade-in duration-500">
                    <div style="margin-bottom:1.5rem">
                        <div style="display:flex;align-items:center;gap:0.75rem;margin-bottom:0.25rem">
                            <i data-lucide="book-open" style="color:#D4AF37"></i>
                            <h2 style="font-size:1.25rem;font-weight:bold;color:#F5EDD6">Books Management</h2>
                        </div>
                        <p style="font-size:0.85rem;color:#A08060;margin-left:2.25rem">Curate and manage your reading
                            library</p>
                        <div
                            style="margin-top:0.75rem;height:1px;background:linear-gradient(90deg, #8B7322, transparent)">
                        </div>
                    </div>

                    <div style="display:flex;gap:1rem;margin-bottom:1rem">
                        <input type="text" placeholder="Search books..." class="admin-input" style="flex:1"
                            data-filter="books-search">
                        <select class="admin-input" style="min-width:140px" data-filter="books-genre">
                            <option value="All">All Genres</option>
                        </select>
                        <select class="admin-input" style="min-width:160px" data-filter="books-audience">
                            <option value="All">All Audiences</option>
                            <option value="+18 Only">+18 Only</option>
                            <option value="All">All Ages</option>
                        </select>
                        <button class="admin-btn primary" data-action="book-add"><i data-lucide="plus"></i> Add
                            Book</button>
                    </div>

                    <div class="admin-card" style="padding:0">
                        <div class="admin-table-container">
                            <table>
                                <thead>
                                    <tr>
                                        <th>Cover</th>
                                        <th>Title</th>
                                        <th>Author</th>
                                        <th>Genre</th>
                                        <th>Cost</th>
                                        <th>Reward</th>
                                        <th>Audience</th>
                                        <th>Trending</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="bookTbody">
                                    <!-- Injected by JS -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Community Section -->
                <div id="community-section" class="section-content hidden animate-in fade-in duration-500">
                    <div style="margin-bottom:1.5rem">
                        <div style="display:flex;align-items:center;gap:0.75rem;margin-bottom:0.25rem">
                            <i data-lucide="message-square" style="color:#D4AF37"></i>
                            <h2 style="font-size:1.25rem;font-weight:bold;color:#F5EDD6">Community Management</h2>
                        </div>
                        <p style="font-size:0.85rem;color:#A08060;margin-left:2.25rem">Monitor and moderate reader
                            discussions</p>
                        <div
                            style="margin-top:0.75rem;height:1px;background:linear-gradient(90deg, #8B7322, transparent)">
                        </div>
                    </div>

                    <div style="display:flex;gap:1.5rem;margin-bottom:1.5rem">
                        <input type="text" placeholder="Search posts..." class="admin-input" style="flex:1"
                            data-filter="community-search">
                        <select class="admin-input" style="min-width:160px" data-filter="community-tag">
                            <option value="All">All Tags</option>
                            <option value="discussion">Discussion</option>
                            <option value="review">Review</option>
                            <option value="theory">Theory</option>
                            <option value="spoiler">Spoiler</option>
                        </select>
                        <select class="admin-input" style="min-width:170px" data-filter="community-status">
                            <option value="All">All Status</option>
                            <option value="Clean">Clean</option>
                            <option value="Flagged by Lumo">Flagged by Lumo</option>
                            <option value="Pending Admin Review">Pending Review</option>
                            <option value="Reviewed">Reviewed</option>
                        </select>
                    </div>

                    <div class="admin-card" style="padding:0">
                        <div class="admin-table-container">
                            <table>
                                <thead>
                                    <tr>
                                        <th>Post Title</th>
                                        <th>Author</th>
                                        <th>Book</th>
                                        <th>Tag</th>
                                        <th>Upvotes</th>
                                        <th>Comments</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="communityTbody">
                                    <!-- Injected by JS -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Lumo AI Section -->
                <div id="lumo-section" class="section-content hidden animate-in fade-in duration-500">
                    <div style="margin-bottom:1.5rem">
                        <div style="display:flex;align-items:center;gap:0.75rem;margin-bottom:0.25rem">
                            <i data-lucide="shield" style="color:#D4AF37"></i>
                            <h2 style="font-size:1.25rem;font-weight:bold;color:#F5EDD6">Lumo AI Moderation</h2>
                        </div>
                        <p style="font-size:0.85rem;color:#A08060;margin-left:2.25rem">Artificial Intelligence engine
                            monitoring the kingdom</p>
                        <div
                            style="margin-top:0.75rem;height:1px;background:linear-gradient(90deg, #8B7322, transparent)">
                        </div>
                    </div>

                    <div class="lumo-status-box active" style="margin-bottom:1.5rem" id="lumoStatusBox">
                        <div class="lumo-icon"><i data-lucide="zap" style="width:32px;height:32px"></i></div>
                        <div class="lumo-content">
                            <div style="display:flex;justify-content:space-between">
                                <div>
                                    <h3 id="lumoActiveText" style="font-weight:bold;color:#F5EDD6">System Status:
                                        Active</h3>
                                    <p style="font-size:0.7rem;color:#A08060">Processing signals at 14ms latency</p>
                                </div>
                                <div style="display:flex;align-items:center;gap:0.5rem">
                                    <label class="switch"><input id="lumoActiveToggle" type="checkbox"
                                            checked><span class="slider"></span></label>
                                    <span class="badge green">OPERATIONAL</span>
                                </div>
                            </div>
                            <div class="lumo-stats-grid">
                                <div class="lumo-stat-item"><span class="val">14.2K</span><span
                                        class="lab">Signals/Day</span></div>
                                <div class="lumo-stat-item"><span class="val" id="lumoPendingCount">0</span><span
                                        class="lab">Pending Review</span></div>
                                <div class="lumo-stat-item"><span class="val">0.2s</span><span class="lab">Avg
                                        Response</span></div>
                            </div>
                        </div>
                    </div>

                    <div class="grid-2">
                        <div class="admin-card">
                            <h3
                                style="font-size:0.85rem;font-weight:bold;margin-bottom:1rem;display:flex;align-items:center;gap:0.5rem">
                                <i data-lucide="pie-chart" style="color:#D4AF37;width:16px"></i> Flag Distribution
                            </h3>
                            <div class="chart-container-sm"><canvas id="lumoPieChart"></canvas></div>
                        </div>
                        <div class="admin-card">
                            <h3
                                style="font-size:0.85rem;font-weight:bold;margin-bottom:1rem;display:flex;align-items:center;gap:0.5rem">
                                <i data-lucide="activity" style="color:#D4AF37;width:16px"></i> Incident Volume
                            </h3>
                            <div class="chart-container-sm"><canvas id="lumoLineChart"></canvas></div>
                        </div>
                    </div>

                    <div style="display:flex;gap:1.5rem;margin:1.5rem 0">
                        <select class="admin-input" style="min-width:160px" data-filter="lumo-type">
                            <option value="All">All Types</option>
                            <option value="Spoiler">Spoiler</option>
                            <option value="Spam">Spam</option>
                            <option value="Offensive">Offensive</option>
                            <option value="Age-Sensitive">Age-Sensitive</option>
                        </select>
                        <select class="admin-input" style="min-width:140px" data-filter="lumo-severity">
                            <option value="All">All Severity</option>
                            <option value="Low">Low</option>
                            <option value="Medium">Medium</option>
                            <option value="High">High</option>
                        </select>
                    </div>

                    <div class="admin-card" style="padding:0">
                        <div class="admin-table-container">
                            <table>
                                <thead>
                                    <tr>
                                        <th>Preview</th>
                                        <th>Author</th>
                                        <th>Book</th>
                                        <th>Type</th>
                                        <th>Severity</th>
                                        <th>Suggested Action</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="lumoTbody"></tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Age Access Section -->
                <div id="age-section" class="section-content hidden animate-in fade-in duration-500">
                    <div style="margin-bottom:1.5rem">
                        <div style="display:flex;align-items:center;gap:0.75rem;margin-bottom:0.25rem">
                            <i data-lucide="lock" style="color:#D4AF37"></i>
                            <h2 style="font-size:1.25rem;font-weight:bold;color:#F5EDD6">Age Access Control</h2>
                        </div>
                        <p style="font-size:0.85rem;color:#A08060;margin-left:2.25rem">Define what each age group can
                            see and access</p>
                        <div
                            style="margin-top:0.75rem;height:1px;background:linear-gradient(90deg, #8B7322, transparent)">
                        </div>
                    </div>

                    <div class="info-banner" style="margin-bottom:1.5rem">
                        <i data-lucide="info"></i>
                        <p>These settings control platform section visibility per age group. Changes take effect
                            immediately. Content marked +18 is never shown to -18 users regardless of these settings.
                        </p>
                    </div>

                    <div class="grid-2">
                        <div class="age-card purple">
                            <div class="age-card-header">
                                <i data-lucide="user-check" style="color:#D8B4FE"></i>
                                <div>
                                    <h3>User +18 View</h3>
                                    <p>Adult readers — full access</p>
                                </div>
                                <span class="badge purple">+18</span>
                            </div>
                            <div class="age-card-body">
                                <div class="toggle-row"><span>About Section</span> <label class="switch"><input
                                            type="checkbox" checked data-age-group="plus18" data-age-key="about"><span
                                                class="slider"></span></label></div>
                                <div class="toggle-row"><span>Store</span> <label class="switch"><input type="checkbox"
                                            checked data-age-group="plus18" data-age-key="store"><span
                                                class="slider"></span></label></div>
                                <div class="toggle-row"><span>Lamp of Knowledge</span> <label class="switch"><input
                                            type="checkbox" checked data-age-group="plus18" data-age-key="lamp"><span
                                                class="slider"></span></label></div>
                                <div class="toggle-row"><span>Community</span> <label class="switch"><input
                                            type="checkbox" checked data-age-group="plus18" data-age-key="community"><span
                                                class="slider"></span></label></div>
                                <div class="toggle-row"><span>Lumo's Bounty Board</span> <label class="switch"><input
                                            type="checkbox" checked data-age-group="plus18" data-age-key="bountyBoard"><span
                                                class="slider"></span></label></div>
                                <div class="toggle-row"><span>Show +18 Books</span> <label class="switch"><input
                                            type="checkbox" checked data-age-group="plus18" data-age-key="plus18Books"><span
                                                class="slider"></span></label></div>
                            </div>
                        </div>
                        <div class="age-card green">
                            <div class="age-card-header">
                                <i data-lucide="user-check" style="color:#86EFAC"></i>
                                <div>
                                    <h3>User -18 View</h3>
                                    <p>Young readers — restricted access</p>
                                </div>
                                <span class="badge green">-18</span>
                            </div>
                            <div class="age-card-body">
                                <div class="toggle-row"><span>About Section</span> <label class="switch"><input
                                            type="checkbox" data-age-group="minus18" data-age-key="about"><span
                                                class="slider"></span></label></div>
                                <div class="toggle-row"><span>Store</span> <label class="switch"><input
                                            type="checkbox" data-age-group="minus18" data-age-key="store"><span
                                                class="slider"></span></label></div>
                                <div class="toggle-row"><span>Lamp of Knowledge</span> <label class="switch"><input
                                            type="checkbox" data-age-group="minus18" data-age-key="lamp"><span
                                                class="slider"></span></label></div>
                                <div class="toggle-row"><span>Community</span> <label class="switch"><input
                                            type="checkbox" checked data-age-group="minus18" data-age-key="community"><span
                                                class="slider"></span></label></div>
                                <div class="toggle-row"><span>Lumo's Bounty Board</span> <label class="switch"><input
                                            type="checkbox" checked data-age-group="minus18" data-age-key="bountyBoard"><span
                                                class="slider"></span></label></div>
                                <div class="toggle-row"><span>Show +18 Books</span> <label class="switch"><input
                                            type="checkbox" data-age-group="minus18" data-age-key="plus18Books"><span
                                                class="slider"></span></label></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Rewards Section -->
                <div id="rewards-section" class="section-content hidden animate-in fade-in duration-500">
                    <div style="margin-bottom:1.5rem">
                        <div style="display:flex;align-items:center;gap:0.75rem;margin-bottom:0.25rem">
                            <i data-lucide="coins" style="color:#D4AF37"></i>
                            <h2 style="font-size:1.25rem;font-weight:bold;color:#F5EDD6">Rewards & Economy</h2>
                        </div>
                        <p style="font-size:0.85rem;color:#A08060;margin-left:2.25rem">Configure the Lexora coin and XP
                            economy</p>
                        <div
                            style="margin-top:0.75rem;height:1px;background:linear-gradient(90deg, #8B7322, transparent)">
                        </div>
                    </div>

                    <div class="grid-3" style="margin-bottom:1.5rem">
                        <div class="mini-stat-card">
                            <div class="icon"><i data-lucide="arrow-up" class="text-green"></i></div>
                            <div>
                                <p class="label">Total Coins Earned</p>
                                <p class="val">3.2M</p>
                            </div>
                        </div>
                        <div class="mini-stat-card">
                            <div class="icon"><i data-lucide="arrow-down" class="text-red"></i></div>
                            <div>
                                <p class="label">Total Coins Spent</p>
                                <p class="val">780K</p>
                            </div>
                        </div>
                        <div class="mini-stat-card">
                            <div class="icon"><i data-lucide="bar-chart-2" class="text-gold"></i></div>
                            <div>
                                <p class="label">Avg Reward / Book</p>
                                <p class="val">45 🪙</p>
                            </div>
                        </div>
                    </div>

                    <div class="admin-card" style="margin-bottom:1.5rem">
                        <h3
                            style="font-size:0.85rem;font-weight:bold;margin-bottom:1rem;display:flex;align-items:center;gap:0.5rem">
                            <i data-lucide="trending-up" style="color:#D4AF37;width:16px"></i> 30-Day Economy Trend
                        </h3>
                        <div class="chart-container"><canvas id="economyLineChart"></canvas></div>
                    </div>

                    <div class="grid-2">
                        <div class="admin-card">
                            <h3 class="card-title"
                                style="font-size:0.85rem;font-weight:bold;margin-bottom:1rem;display:flex;align-items:center;gap:0.5rem">
                                <i data-lucide="award" style="color:#D4AF37;width:16px"></i> Default Reward Settings
                            </h3>
                            <div class="grid-2" style="gap:1rem">
                                <div><label class="label-xs">DEFAULT XP REWARD</label>
                                    <div class="input-with-label"><input type="text" class="admin-input"
                                            value="150" id="rewardDefaultXp"><span>XP</span></div>
                                </div>
                                <div><label class="label-xs">DEFAULT COIN REWARD</label>
                                    <div class="input-with-label"><input type="text" class="admin-input"
                                            value="40" id="rewardDefaultCoin"><span>🪙</span></div>
                                </div>
                            </div>
                        </div>
                        <div class="admin-card">
                            <h3 class="card-title"
                                style="font-size:0.85rem;font-weight:bold;margin-bottom:1rem;display:flex;align-items:center;gap:0.5rem">
                                🪔 Lamp Penalty Rules</h3>
                            <div class="penalty-list" id="rewardPenaltyWrap">
                                <div class="penalty-item"><span>Penalty Enabled</span>
                                    <label class="switch"><input id="rewardPenaltyEnabled" type="checkbox"
                                            checked><span class="slider"></span></label>
                                </div>
                                <div class="penalty-item"><span>🌱 Level 1–5 Penalty %</span> <input type="text"
                                        class="admin-input sm" value="10" id="penalty_l1_5"></div>
                                <div class="penalty-item"><span>📖 Level 6–15 Penalty %</span> <input type="text"
                                        class="admin-input sm" value="20" id="penalty_l6_15"></div>
                                <div class="penalty-item"><span>⚔️ Level 16–25 Penalty %</span> <input type="text"
                                        class="admin-input sm" value="30" id="penalty_l16_25"></div>
                                <div class="penalty-item"><span>👑 Level 26+ Penalty %</span> <input type="text"
                                        class="admin-input sm" value="40" id="penalty_l26"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Settings Section -->
                <div id="settings-section" class="section-content hidden animate-in fade-in duration-500">
                    <div style="margin-bottom:1.5rem">
                        <div style="display:flex;align-items:center;gap:0.75rem;margin-bottom:0.25rem">
                            <i data-lucide="settings" style="color:#D4AF37"></i>
                            <h2 style="font-size:1.25rem;font-weight:bold;color:#F5EDD6">Site Settings</h2>
                        </div>
                        <p style="font-size:0.85rem;color:#A08060;margin-left:2.25rem">Global configuration for the
                            Lexora platform</p>
                        <div
                            style="margin-top:0.75rem;height:1px;background:linear-gradient(90deg, #8B7322, transparent)">
                        </div>
                    </div>

                    <div class="admin-card" style="margin-bottom:1.5rem">
                        <h3 class="card-title"
                            style="font-size:0.85rem;font-weight:bold;margin-bottom:1rem;display:flex;align-items:center;gap:0.5rem">
                            <i data-lucide="book-marked" style="color:#D4AF37;width:16px"></i> Platform Identity</h3>
                        <div style="display:flex;flex-direction:column;gap:1.25rem">
                            <div><label class="label-xs">SITE NAME</label><input type="text" class="admin-input full"
                                    value="Lexora" id="settingSiteName"></div>
                            <div><label class="label-xs">TAGLINE</label><input type="text" class="admin-input full"
                                    value="Where Every Page Sparks a New Adventure" id="settingTagline"></div>
                            <div><label class="label-xs">LUMO WELCOME MESSAGE</label><textarea class="admin-input full"
                                    rows="3"
                                    id="settingLumoMessage">Greetings, young scholar! I am Lumo, your guide through the Reading Kingdom.</textarea>
                            </div>
                        </div>
                    </div>

                    <div class="admin-card" style="margin-bottom:1.5rem">
                        <h3 class="card-title"
                            style="font-size:0.85rem;font-weight:bold;margin-bottom:1rem;display:flex;align-items:center;gap:0.5rem">
                            <i data-lucide="sparkles" style="color:#D4AF37;width:16px"></i> Feature Toggles</h3>
                        <div class="toggle-list">
                            <div class="toggle-item">
                                <div class="info"><span>🔥 Enable Trending Badge</span>
                                    <p>Show hot/trending labels on popular books</p>
                                </div>
                                <label class="switch"><input type="checkbox" checked id="setting_trendingBadge"><span
                                        class="slider"></span></label>
                            </div>
                            <div class="toggle-item">
                                <div class="info"><span>🛡️ Enable Lumo AI Moderation</span>
                                    <p>Automatic content moderation engine</p>
                                </div>
                                <label class="switch"><input type="checkbox" checked id="setting_lumoModeration"><span
                                        class="slider"></span></label>
                            </div>
                            <div class="toggle-item">
                                <div class="info"><span>🤖 Enable Lumo Chatbot</span>
                                    <p>Show the AI chat assistant on all pages</p>
                                </div>
                                <label class="switch"><input type="checkbox" checked id="setting_lumoChatbot"><span
                                        class="slider"></span></label>
                            </div>
                        </div>
                    </div>

                    <div class="admin-card" style="margin-bottom:1.5rem">
                        <h3 class="card-title"
                            style="font-size:0.85rem;font-weight:bold;margin-bottom:1rem;display:flex;align-items:center;gap:0.5rem">
                            <i data-lucide="bar-chart-2" style="color:#D4AF37;width:16px"></i> Display Settings</h3>
                        <div><label class="label-xs">DEFAULT BOOKS PER PAGE</label>
                            <select class="admin-input" style="max-width:180px" id="settingBooksPerPage">
                                <option value="6">6 books</option>
                                <option value="8">8 books</option>
                                <option value="12" selected>12 books</option>
                                <option value="16">16 books</option>
                                <option value="24">24 books</option>
                            </select>
                        </div>
                    </div>

                    <div style="display:flex;gap:1rem">
                        <button class="admin-btn primary" id="settingsSaveBtn"><i data-lucide="save"></i> Save
                            Changes</button>
                        <button class="admin-btn secondary" id="settingsResetBtn"><i data-lucide="refresh-cw"></i>
                            Reset to Default</button>
                        <span class="badge green hidden" id="settingsSavedMsg">Settings saved successfully</span>
                    </div>
                </div>

            </div>
        </main>
    </div>

    <!-- --- SCRIPTS --- -->
    <div id="modalRoot"></div>
    <script src="../model/admin_data.js"></script>
    <script src="../controller/admin.js"></script>
</body>

</html>
