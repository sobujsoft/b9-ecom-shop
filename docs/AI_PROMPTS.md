# ShopEase — AI Build Prompts

A sequenced set of copy-paste prompts to build **ShopEase** with an AI coding assistant. Follow the phases in order — each builds on the previous one.

| | |
|---|---|
| **Project** | ShopEase (E-commerce, Bangladesh market) |
| **Stack** | Laravel 13, Vue 3, Inertia.js, Tailwind CSS, Laravel Starter Kit |
| **Currency / Language** | BDT (৳) / English |
| **Reference Docs** | `REQUIREMENT_ANALYSIS.md`, `PRD.md`, `DESIGN_GUIDELINES.md`, *Database Design (TBD)* |

---

## How to Use This File

1. Run the prompts **in order** (Phase 0 → 5). Don't skip ahead — later phases assume earlier output exists.
2. Before each prompt, make sure the AI has access to the reference docs (paste or attach them).
3. After each phase, **review and test** before moving on. Use the "Review checkpoint" at the end of each phase.
4. Replace anything in `<angle brackets>` with your real values.
5. Keep changes scoped — ask the AI to touch only the files relevant to the current phase.

---

## Global Context Prompt (paste once at the start of every session)

```
You are an expert full-stack engineer building "ShopEase", an e-commerce web app for the
Bangladeshi market.

Tech stack (use exactly this):
- Laravel 13 (PHP) backend
- Vue 3 + Inertia.js frontend (Laravel Starter Kit with Inertia + Vue)
- Tailwind CSS for styling
- MySQL database

Hard rules:
- Currency is Bangladeshi Taka, shown as "৳" with thousands separators (e.g. ৳ 1,250).
- All UI text is in English.
- Mobile-first and fully responsive (test mentally at 360px, 768px, 1280px).
- Inventory is NOT quantity-tracked. Each product has a stock status: "In Stock" or "Stock Out".
  Stock Out products cannot be added to cart or purchased.
- Follow the project docs: REQUIREMENT_ANALYSIS.md, PRD.md, and DESIGN_GUIDELINES.md.
- Write clean, conventional, reusable code (Laravel + Vue best practices). Use reusable Vue components.

Before writing code, briefly state your plan and the files you will create/modify. Then implement.
Ask me only if a decision is genuinely blocking.
```

---

## Phase 0 — Project Setup

**Goal:** Get a running Laravel 13 + Vue 3 + Inertia + Tailwind project.

```
Set up a fresh ShopEase project using the Laravel Starter Kit with Inertia + Vue 3 and Tailwind CSS.

Do the following:
1. Confirm/scaffold the Laravel 13 app with the Inertia + Vue 3 starter kit and Tailwind CSS configured.
2. Configure the .env for MySQL (database name: shopease) and app name "ShopEase".
3. Add a currency helper (PHP and a Vue composable/util) that formats numbers as "৳ 1,250".
4. Set up a base layout: a StoreLayout (public storefront) and an AdminLayout (admin panel),
   each with placeholder header/footer and a content slot.
5. Apply the color palette, typography, and tokens from DESIGN_GUIDELINES.md to tailwind.config.js
   and a base CSS file. Load the Inter font.
6. Give me the exact commands to run, migrate, and start the dev server.

Output the file tree of what you created and the run instructions.
```

**Review checkpoint:** App boots, Tailwind works, both layouts render, currency helper returns `৳ 1,250`.

---

## Phase 1 — Professional Storefront (Static UI First)

**Goal:** Build a beautiful, responsive storefront using **dummy/placeholder data** (no DB yet). Focus purely on UI/UX.

```
Build the ShopEase storefront UI using STATIC placeholder data (hardcoded arrays / props) — no
database or backend logic yet. Strictly follow DESIGN_GUIDELINES.md for colors, typography,
spacing, components, and responsiveness.

Build these pages and reusable components inside the StoreLayout:

HOME PAGE (sections in this exact order):
1. Hero section — full-width image carousel/slider (auto-rotate, prev/next, dot indicators, swipe).
2. Category section — responsive grid of category tiles.
3. Best Selling Products — responsive product grid.
4. New Collection — responsive product grid.
5. Footer — links, contact, social, payment/COD note.

OTHER PAGES:
- Shop / Product Listing — product grid with filter (category, price), sort, search bar, pagination UI.
- Product Details — image gallery, name, description, price (৳), stock badge, quantity selector,
  "Add to Cart" button (disabled when Stock Out), and a reviews & ratings section.
- Cart — line items, quantity update/remove, order summary with totals in ৳.
- Checkout — shipping form + order summary + payment method selector (COD / SSLCommerz).
- Order Confirmation — success state with order number.

REUSABLE COMPONENTS (build these as standalone Vue components):
- Navbar (sticky, mobile hamburger drawer, cart icon with count badge, search)
- ProductCard (image, name, price ৳, stock badge, best-seller tag, add-to-cart)
- StockBadge (In Stock = green, Stock Out = red)
- HeroCarousel, CategoryCard, StarRating, PriceTag, Button variants, Footer

Requirements:
- Fully responsive (mobile-first). Use the grid patterns from DESIGN_GUIDELINES.md.
- Use realistic Bangladeshi placeholder data and ৳ prices.
- Make it look polished and professional: consistent spacing, hover states, subtle transitions,
  skeleton/placeholder for images.
- Currency everywhere via the ৳ helper.

Deliver page by page. Start with the Navbar + Footer + ProductCard, then the Home page, then the rest.
```

**Review checkpoint:** Every page looks professional and is responsive at 360/768/1280px. Carousel, filters UI, cart interactions work with dummy data. Stock Out cards correctly disable Add to Cart.

---

## Phase 2 — Database Design & Migrations

**Goal:** Design the data layer that will power the storefront and admin.

> First create a dedicated `DATABASE_DESIGN.md`, then implement it.

```
Design the database for ShopEase based on REQUIREMENT_ANALYSIS.md and PRD.md. First produce a
DATABASE_DESIGN.md document, then implement it in Laravel.

PART A — DATABASE_DESIGN.md (documentation):
- List every table with columns, types, nullability, defaults, and indexes.
- Define all relationships (one-to-many, etc.) and foreign keys with on-delete behavior.
- Include an entity list and a textual ER overview.

Tables to cover (at minimum):
- users (with role: customer/admin)
- hero_slides
- categories
- products (stock_status enum: in_stock | stock_out; flags: is_best_seller, is_featured, is_active)
- product_images
- orders (payment_method: cod | sslcommerz; payment_status: pending | paid | failed; status: pending→...→cancelled)
- order_items
- invoices
- reviews (rating 1–5, comment, is_approved)
- (any settings table needed for delivery charges)

PART B — Implementation (Laravel):
- Create migrations for all tables with proper foreign keys and indexes.
- Create Eloquent models with relationships, fillable/casts, and useful scopes
  (e.g., Product::inStock(), Product::bestSellers(), Product::newCollection()).
- Create database seeders + factories with realistic Bangladeshi sample data and ৳ prices
  (categories, products with images, hero slides, a few orders and reviews, one admin user).

Confirm migrations run cleanly and seeders populate the DB. Give me the commands.
```

**Review checkpoint:** `php artisan migrate:fresh --seed` runs cleanly; tables and relationships match `DATABASE_DESIGN.md`; sample data looks realistic.

---

## Phase 3 — Make the Storefront Dynamic

**Goal:** Replace placeholder data with real data from the database via Inertia.

```
Wire the existing static storefront (Phase 1) to the database (Phase 2) using Laravel controllers
and Inertia. Keep the exact same UI/design — only replace the data source and add real behavior.

Backend (controllers + Inertia responses):
- Home: load active hero slides, categories, best-selling products, and new-collection products.
- Shop: server-side filtering (category, price range), sorting (newest, price asc/desc, best selling),
  search by name, and pagination — passed via Inertia props.
- Product Details: load product with images, category, average rating, approved reviews.
- Cart: implement add/update/remove (session-based for guests; persist for logged-in customers).
- Checkout: validate shipping form, compute delivery charge (inside vs outside Dhaka, configurable),
  create the order + order_items + invoice. For now, support COD end-to-end (SSLCommerz comes in Phase 5).
- Reviews: allow logged-in customers to submit a rating + comment (saved as pending/unapproved).

Rules:
- Enforce Stock Out: such products cannot be added to cart or checked out (validate on the server too).
- All prices from DB, formatted in ৳.
- Use Form Requests for validation and keep controllers thin.
- Generate a unique order_number and an invoice on successful order placement.
- Show proper empty states, loading states, and validation errors.

Do it page by page: Home → Shop → Product Details → Cart → Checkout (COD) → Reviews submission.
Confirm each page works against the seeded data.
```

**Review checkpoint:** Real products/categories/hero load from DB; shop filters/sort/search/pagination work server-side; a COD order can be placed end-to-end and creates an order + invoice; review submission saves as pending.

---

## Phase 4 — Admin Panel

**Goal:** Build the full management backend inside AdminLayout, protected by auth + role.

```
Build the ShopEase admin panel inside AdminLayout, following PRD.md section 5 and DESIGN_GUIDELINES.md.
Protect all admin routes with authentication and an admin-role middleware.

Build these modules (CRUD + list views with search/filter where relevant):

1. Admin Auth & Dashboard
   - Admin login; role/middleware protection.
   - Dashboard widgets: total orders, total sales (৳), pending orders, total products; recent orders list.

2. Hero Management — CRUD slides (image upload, heading, subtext, CTA link, sort order, active toggle), reorderable.

3. Category Management — CRUD (name, slug, image, description, active toggle).

4. Product Management — CRUD with: name, category, description, price (৳), multiple image uploads,
   stock status toggle (In Stock / Stock Out), flags (Best Seller, Featured, Active).
   List view with search + filter by category and stock status.

5. Order Management — list/filter orders; order detail view (customer, items, totals ৳, payment method
   & status); update order status (Pending→Processing→Shipped→Delivered→Cancelled).

6. Invoice Management — view and download/print invoice as PDF.

7. Review Moderation — list reviews; approve / hide / delete; filter by product, rating, status.

8. Sales Reports — date-range filter (today/week/month/custom); metrics (total sales ৳, order count,
   average order value, COD vs SSLCommerz split); best-selling products; sales by category; trend charts;
   export to CSV/PDF.

Requirements:
- Reuse Vue components; consistent admin UI (tables, forms, modals) per DESIGN_GUIDELINES.md.
- Validated forms (Form Requests), image upload validation, thin controllers.
- Changes reflect on the storefront (e.g., toggling Stock Out, approving a review).

Build module by module, starting with Auth + Dashboard, then Hero, Category, Product, Order, Invoice,
Review, Reports.
```

**Review checkpoint:** Only admins can access `/admin/*`; full CRUD works for hero/category/product; orders' status updates persist; invoices export as PDF; reviews can be moderated; reports show accurate figures.

---

## Phase 5 — Payment Gateway (SSLCommerz) + Email Notifications

**Goal:** Add online payment and automated order emails.

```
Integrate the SSLCommerz payment gateway and email notifications into ShopEase checkout, following
PRD.md (sections 5.5, 5.16) and its risks/mitigations.

SSLCommerz:
- Add SSLCommerz config to .env and a config file (store id, store password, sandbox/live toggle).
- On checkout when "SSLCommerz" is selected:
  - Create the order with payment_status = pending, then initiate the SSLCommerz session and redirect.
- Implement callback routes: success, fail, cancel, and IPN.
  - On SUCCESS: VERIFY the transaction with SSLCommerz before marking the order paid & confirmed.
    Generate the invoice and send the confirmation email.
  - On FAIL/CANCEL: keep the order unpaid and return the customer to checkout with a clear message.
- Make callback handling IDEMPOTENT — repeated callbacks must not create duplicate orders or
  double-confirm. Protect callbacks (CSRF exemptions only where required, validate signatures/amounts).
- Keep COD working exactly as before.

Email notifications (Laravel Mail, QUEUED):
- Order confirmation email to the customer when an order is successfully placed (COD on placement;
  SSLCommerz after verified payment).
- Order status-update email to the customer when admin changes the order status.
- New-order alert email to the admin.
- Render order details and totals in ৳ in the email templates. Configure SMTP via .env
  (use Mailtrap for dev). Set up the queue worker and give me the command to run it.

Provide test steps for both sandbox payment success and failure, and confirm emails fire on the
correct events.
```

**Review checkpoint:** Sandbox SSLCommerz success confirms+pays the order and emails the customer; failure/cancel leaves it unpaid; callbacks are idempotent; all three email types fire correctly; COD still works.

---

## Phase 6 — Polish & QA (Optional but Recommended)

```
Do a final QA and polish pass on ShopEase:
- Verify full responsiveness on all pages at 360 / 768 / 1280px.
- Check accessibility: focus rings, alt text, color contrast (WCAG AA), aria-labels on carousel/cart.
- Confirm all currency is ৳-formatted and all UI text is English.
- Add empty states, loading skeletons, and friendly error pages (404/500).
- Optimize images (lazy-load, correct aspect ratios) and check page performance.
- Run through every acceptance criterion in PRD.md section 13 and report pass/fail for each.
List any issues found and fix them.
```

---

## Quick Reference — Phase Order

| Phase | Focus | Key Output |
|-------|-------|-----------|
| 0 | Setup | Running Laravel + Vue + Inertia + Tailwind |
| 1 | **Storefront UI** | Professional, responsive static pages |
| 2 | **Database** | `DATABASE_DESIGN.md` + migrations/models/seeders |
| 3 | **Dynamic storefront** | Real data + COD checkout |
| 4 | **Admin panel** | Full management modules |
| 5 | **Payment + Email** | SSLCommerz + notifications |
| 6 | QA & Polish | Production-ready |

---

## Tips for Better AI Results

- **One phase per session** keeps context focused and output high-quality.
- Always paste the **Global Context Prompt** first in a new session.
- Ask the AI to **state its plan and file list before coding**.
- After generation, ask: *"Show me how to test this"* and run it before moving on.
- If output drifts from the design, point it back to `DESIGN_GUIDELINES.md`.
- Commit your work after each successful phase.

---

*End of Document*
