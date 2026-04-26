# Codebase Analysis Report

## Scope
- Repository audited at a standard depth across architecture, security, reliability, quality process, and authentication UX.
- Focus included critical runtime paths in `app/controllers`, routing in `config/routes.php`, models in `app/models`, frontend behavior in `public/assets/js`, and schema dependencies in `database/migrations`.
- This report prioritizes what should be done next, with risk and effort awareness.

## Executive Summary
- The project has a clear PHP MVC foundation and a feature-rich frontend, but there are high-risk access-control and route-method issues that should be addressed first.
- Authentication backend login contract is already minimal (`email` + `password`), but frontend/legacy wording creates confusion.
- Automated quality gates are mostly absent (tests, linting, CI), increasing regression risk for complex business flows.
- Migration-dependent logic is handled in code, but schema drift still threatens consistency in purchase/progress paths.

## Architecture Snapshot
- **Entry points**
  - `public/index.php` (main router entry)
  - `index.php` (root bridge)
- **Routing**
  - `config/routes.php`
  - `core/Router.php`
- **Controllers**
  - Auth/session: `app/controllers/AuthController.php`
  - User APIs: `app/controllers/UserApiController.php`
  - Admin/domain: `app/controllers/AdminController.php`, `UsersController.php`, `BooksController.php`, `PostsController.php`
- **Data layer**
  - DB bootstrap: `core/Database.php`, `config/database.php`
  - Domain models: `app/models/UserModel.php`, `BookModel.php`, `PostModel.php`
- **Frontend**
  - Landing/auth modal scripts: `public/assets/js/landing/**`
  - Main app runtime: `public/assets/js/user_app.js`
- **Schema**
  - Migration packs in `database/migrations/*.sql`

## High-Priority Risks (Needs Action)

### 1) Missing strict admin authorization checks
- **Risk**: mutation endpoints in admin-related controllers may execute without strong role enforcement.
- **Where**: `app/controllers/AdminController.php`, `UsersController.php`, `BooksController.php`, `PostsController.php`
- **Impact**: privilege escalation and unauthorized data changes.
- **Priority**: Critical.

### 2) Destructive operations exposed via GET
- **Risk**: delete/update actions registered as GET routes.
- **Where**: `config/routes.php` (e.g. `/admin/*/delete`, post update/delete patterns)
- **Impact**: CSRF and accidental destructive actions.
- **Priority**: Critical.

### 3) Password hashing inconsistency paths
- **Risk**: mixed modern hashes with legacy fallback/legacy update logic can reintroduce weak-hash behavior.
- **Where**: `app/controllers/AuthController.php`, admin user-update flows.
- **Impact**: weakened auth security posture.
- **Priority**: High.

### 4) Logout and auth UX inconsistency
- **Risk**: non-canonical logout routes and misleading auth labels increase session/UX bugs.
- **Where**: `app/views/users/auth.php`, related view scripts.
- **Impact**: user confusion, auth-state inconsistency.
- **Priority**: High.

### 5) Monolithic frontend state logic
- **Risk**: large single-file state-heavy logic causes high regression blast radius.
- **Where**: `public/assets/js/user_app.js`
- **Impact**: subtle bugs in library, purchases, profile, and progression views.
- **Priority**: Medium-High.

## Quality and Testing Gaps
- No clear automated test suite found for backend or frontend critical flows.
- No formal lint/static analysis baseline detected for PHP/JS.
- No repository CI workflow enforcing tests/checks before merge.
- Result: high manual QA burden and weak regression detection.

## Authentication-Specific Analysis
- Backend login already enforces `email` + `password` in `AuthController::login()`.
- Signup contract includes `username`, `email`, `password`, and optional `birthdate`.
- User confusion stems from shared modal/legacy labels that imply extra fields are part of sign-in.
- Required remediation: make login UI and payload unambiguously `email` + `password` only.

## Prioritized Remediation Backlog

### P0 (Immediate)
1. Enforce auth + admin-role middleware/guards on all admin mutation endpoints.
2. Convert destructive GET routes to POST/DELETE and add CSRF protections consistently.
3. Lock password update paths to `password_hash/password_verify` only (retain migration only where strictly necessary).

### P1 (Next Sprint)
1. Add baseline backend integration tests for auth, admin guards, and purchase/progress endpoints.
2. Add CI workflow with at least syntax checks + test execution.
3. Normalize logout/auth navigation to one canonical flow.

### P2 (Planned Hardening)
1. Refactor `user_app.js` into smaller modules by domain (library, profile, quests, store, state-sync).
2. Add migration compatibility checks for schema-dependent endpoints.
3. Align README/docs with actual setup, routes, and operational requirements.

## Recommended First Test Cases
- Login success with only `email` + `password`.
- Login failure for bad credentials (without leaking account existence details).
- Signup success and duplicate-email rejection.
- Admin mutation endpoint access denied for non-admin users.
- Purchase endpoint handles insufficient coins and duplicate purchase safely.
- Progress endpoint persists and validates expected payload boundaries.

## Delivery Notes
- This report is intended as an actionable engineering backlog seed.
- The auth sign-in simplification has been implemented separately in this same task batch.
