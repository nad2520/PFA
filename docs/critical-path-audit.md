# Critical-Path Audit Report

## Context
- Scope: critical paths only (`auth`, `purchase/library`, `profile`, `API`, `DB migrations`).
- Goal: identify current risks/bugs and list concrete work needed.

## Executive Summary
- The purchase/library feature is close to working, but there are still reliability gaps between DB schema assumptions and frontend state rendering.
- The most important non-library risks are admin authorization exposure, logout UX mismatch, weak password hashing, and base-path routing edge cases.
- Database support for library/purchase is mostly in place through migrations, but production behavior depends on all key migrations being applied.

## Findings by Severity

### Critical
1. **Admin actions are not consistently protected**
   - Files: `app/controllers/AdminController.php`, `app/controllers/UsersController.php`, `app/controllers/BooksController.php`, `app/controllers/PostsController.php`, `config/routes.php`
   - Risk: unauthorized data mutation/deletion if routes are reachable without strict role checks.
   - Needed:
     - Enforce auth + admin role checks in all admin controllers.
     - Convert destructive GET routes to POST and keep CSRF validation.

### High
2. **Disconnect buttons do not perform real logout**
   - Files: `app/views/users/index.php`, `app/views/users/profile.php`, `app/views/users/store.php`, `app/views/users/book-detail.php`, `app/views/users/auth.php`
   - Current behavior: redirects to `index.php` instead of `/logout`.
   - Risk: user thinks they logged out while session remains active.
   - Needed: wire all disconnect buttons/links to `logout` route.

3. **Password hashing uses MD5**
   - Files: `app/controllers/AuthController.php`, `app/controllers/UsersController.php`
   - Risk: weak credential storage.
   - Needed: migrate to `password_hash()` / `password_verify()` with backward-compatible transition.

4. **Potential route base mismatch for `/PFA` root**
   - File: `public/index.php`
   - Risk: path normalization edge cases under base prefix may lead to inconsistent routing depending on server rewrite.
   - Needed: normalize both `/PFA` and `/PFA/` consistently.

### Medium
5. **Library rendering can fail when profile DOM assumptions differ**
   - Files: `public/assets/js/user_app.js`, `app/views/users/profile.php`
   - Risk: `My Library` stays empty due to missing DOM nodes or strict mapping assumptions.
   - Needed: null-safe rendering and resilient state mapping.

6. **Purchase/library behavior depends on schema completeness**
   - Files: `app/controllers/UserApiController.php`, `database/migrations/*.sql`
   - Risk: partial migrations cause silent feature regressions (e.g., purchase write errors).
   - Needed: schema verification + runtime fallback for optional columns.

## Purchase/Library Deep Analysis

### Expected flow
1. User clicks Add to library on `book-detail`.
2. Frontend posts to `/api/user/book/purchase`.
3. Backend deducts coins once (if needed), upserts `user_books`.
4. Frontend refreshes profile data and renders `libraryGrid`.

### Why it can still show empty
- DB row not written (schema mismatch in purchase transaction).
- Library rows returned but dropped by strict frontend mapping.
- Profile render path expecting containers that are absent.
- Book detail CTA/state not refreshed against latest profile payload.

## Database Readiness Matrix

Required migrations for purchase/library reliability:
- `001_book_completion_lexora.sql`
- `002_coin_system_rewards.sql`
- `003_reading_progress_personalization.sql`
- `004_lexora_catalog_books_seed.sql`
- `006_book_purchase_coin_costs.sql`
- `007_economy_logs_purchase_support.sql`
- `008_user_books_purchased_at.sql`
- `009_book_purchase_coin_deduction.sql`

Required schema elements:
- `user_books` table with unique `(user_id, book_id)`.
- `user_books.status`, `progress_page`, `started_at`.
- Optional but expected in current logic: `user_books.purchased_at`.
- `economy_logs.coins_spent` (for purchase logs).
- `reading_progress` and `user_quest_rewards`.

## Recommended Work Items
1. **Stabilize purchase transaction**
   - Keep fallback behavior when optional columns are missing.
   - Ensure deterministic errors if write cannot complete.
2. **Harden profile rendering**
   - Render library with null-safe containers.
   - Avoid dropping entries when book payload is partially missing.
3. **Fix book-detail CTA consistency**
   - Recompute `inLibrary` from refreshed profile state after purchase.
   - Replace Add to library button with in-library state/Read action.
4. **Security/ops follow-ups**
   - Admin role enforcement and POST-only destructive routes.
   - Real logout wiring.
   - Password hashing migration.

## Suggested Validation Cases
- Purchase a book first time: coins decrease by exact `coinCost`; book appears in profile.
- Click Add to library again for same book: no second charge; UI indicates already owned.
- Reload `book-detail` and `profile`: owned state persists.
- With optional schema fields missing: purchase still works with fallback or emits explicit actionable error.

