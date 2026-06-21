# ShopEase — Product Requirements Document (PRD)

| | |
|---|---|
| **Product** | ShopEase |
| **Type** | E-commerce Web Application |
| **Target Market** | Bangladesh |
| **Language / Currency** | English / Bangladeshi Taka (৳, BDT) |
| **Tech Stack** | Laravel 13, Vue 3, Inertia.js, Tailwind CSS, Laravel Starter Kit |
| **Document Version** | 1.0 |
| **Date** | 2026-06-14 |
| **Owner** | Product Team |
| **Status** | Draft |
| **Related Docs** | `REQUIREMENT_ANALYSIS.md`, `DESIGN_GUIDELINES.md`, *Database Design (separate, TBD)* |

> **Note:** Database schema and route/URL structure are intentionally excluded from this PRD and will be covered in a dedicated Database Design document.

---

## 1. Overview

### 1.1 Summary
ShopEase is a single-store e-commerce platform built for the Bangladeshi market. Customers browse a curated storefront, add products to a cart, and check out using **Cash on Delivery (COD)** or the **SSLCommerz** payment gateway. An admin panel powers the entire catalog, order lifecycle, invoicing, reviews moderation, sales reporting, and automated email notifications.

### 1.2 Problem Statement
Small and mid-size Bangladeshi merchants need an affordable, professional online store that:
- Works flawlessly on mobile (the dominant access channel).
- Accepts both COD and local online payments.
- Is simple to operate — no complex inventory counting, just clear stock status.
- Builds buyer trust through reviews, clean design, and reliable order communication.

### 1.3 Goals
- Deliver a fast, attractive, fully responsive storefront in BDT and English.
- Support dual payment (COD + SSLCommerz) with reliable order/payment handling.
- Give admins full control over catalog, orders, invoices, reviews, and reports.
- Keep operations lightweight (stock badge instead of quantity tracking).

### 1.4 Non-Goals
- Multi-vendor / marketplace functionality.
- Multi-language (Bangla) UI.
- Quantity-based inventory tracking.
- Coupons, wishlist, and SMS notifications (future scope).

---

## 2. Success Metrics (KPIs)

| Metric | Target |
|--------|--------|
| Mobile page load (LCP) | < 2.5s on 4G |
| Checkout completion rate | ≥ 60% of initiated checkouts |
| SSLCommerz payment success rate | ≥ 95% of attempted online payments |
| Order confirmation email delivery | ≥ 99% sent within 1 min |
| Cart-to-order conversion | Tracked and improving month over month |
| Admin order processing time | Status updates reflected instantly |

---

## 3. Target Users & Personas

### 3.1 Personas
- **Rina (Shopper, 26, Dhaka)** — Browses on her phone, prefers COD but open to bKash via SSLCommerz, reads reviews before buying.
- **Karim (Shopper, 34, Chattogram)** — Pays online with card, wants fast delivery and order tracking emails.
- **Store Admin (Owner/Manager)** — Manages products and banners, processes daily orders, watches sales reports.

### 3.2 Roles
| Role | Capabilities |
|------|--------------|
| Guest | Browse, search, add to cart, guest checkout |
| Customer | Guest features + account, order history, submit reviews |
| Admin | Full management of catalog, orders, invoices, reviews, reports |

---

## 4. Scope

### 4.1 In Scope
- Storefront: home, shop, product details, cart, checkout, order confirmation.
- Payments: **COD + SSLCommerz**.
- Product **reviews & ratings** (with moderation).
- Admin: hero, category, product, order, invoice, **review moderation**, **sales reports**.
- **Email order notifications** (confirmation, status updates, admin alert).
- Stock status badge (In Stock / Stock Out) — no quantity tracking.
- Fully responsive, BDT currency, English UI.

### 4.2 Out of Scope (This Version)
- Additional gateways (bKash/Nagad direct), coupons, wishlist, SMS, multi-language, advanced inventory.

---

## 5. Features & Requirements

Each feature lists priority using **MoSCoW** (Must / Should / Could).

### 5.1 Storefront — Home Page · *Must*
Sections must render in this order: **Hero carousel → Categories → Best Selling → New Collection → Footer.**

**Requirements**
- Hero carousel auto-rotates, supports swipe and manual controls, fully admin-managed.
- Category section links to filtered shop page.
- Best Selling shows admin-flagged and/or top-sold products.
- New Collection shows most recently added products.
- Footer with links, contact, social, and payment/COD note.

**Acceptance**
- All sections appear in order and are responsive at 360 / 768 / 1280px.
- Carousel slides reflect admin configuration in real time.

---

### 5.2 Storefront — Shop / Listing · *Must*
**Requirements**
- Product grid with image, name, price (৳), and stock badge.
- Filter by category and price range; sort by newest / price / best selling; search by name.
- Pagination.
- "Add to Cart" inline; Stock Out items visibly disabled.

**Acceptance**
- Filters, sort, search, and pagination work together without page reload jank.
- Stock Out products cannot be added to cart.

---

### 5.3 Storefront — Product Details · *Must*
**Requirements**
- Image gallery, name, description, price (৳), stock badge, quantity selector.
- "Add to Cart" disabled when Stock Out.
- Average rating, review count, and reviews list.
- Related products (Should).

**Acceptance**
- Correct stock badge and price formatting; gallery works on touch.
- Reviews section displays approved reviews with rating, name, date.

---

### 5.4 Storefront — Cart · *Must*
**Requirements**
- Line items (image, name, unit price, quantity, line total), update/remove.
- Subtotal, delivery charge, grand total in ৳.
- Persist cart across session (DB for logged-in customer — Should).

**Acceptance**
- Quantity changes recalculate totals instantly; empty-cart state handled.

---

### 5.5 Storefront — Checkout & Payment · *Must*
**Requirements**
- Capture shipping details (name, phone, email, address with district/area).
- Order summary with delivery charge logic (inside vs. outside Dhaka, configurable).
- **Payment methods:**
  - **COD** — order placed immediately with `payment_status = pending`.
  - **SSLCommerz** — redirect to gateway; on **success callback**, mark order paid and confirm; on failure/cancel, keep order unpaid and inform the customer.
- On successful order: generate order number + invoice, send confirmation email, show confirmation page.

**Acceptance**
- Both payment paths produce a valid order and invoice.
- SSLCommerz success/failure/cancel are each handled correctly and idempotently (no duplicate orders on callback retries).
- Confirmation email is sent within 1 minute.

---

### 5.6 Storefront — Reviews & Ratings · *Must*
**Requirements**
- Registered customers submit a 1–5 star rating + written comment.
- Optionally restrict reviews to verified purchasers (Should).
- Average rating and count shown on cards and details page.
- New reviews are **pending until admin approval**.

**Acceptance**
- Only authenticated users can submit; submitted reviews are hidden until approved.
- Average rating recalculates when reviews are approved/removed.

---

### 5.7 Storefront — Customer Account · *Should*
**Requirements**
- Register / login (Laravel starter kit auth).
- Order history with status; profile management.

**Acceptance**
- Customers can view their past orders and current statuses.

---

### 5.8 Admin — Authentication & Dashboard · *Must*
**Requirements**
- Protected admin login (role/middleware).
- Dashboard widgets: total orders, total sales (৳), pending orders, total products; recent orders.

**Acceptance**
- Non-admins cannot access any admin route; dashboard reflects live data.

---

### 5.9 Admin — Hero Management · *Must*
- CRUD slides: image, heading, subtext, CTA link, sort order, active/inactive; reorderable.
- **Acceptance:** changes reflect on the storefront hero immediately.

### 5.10 Admin — Category Management · *Must*
- CRUD categories: name, slug, image, description, active/inactive.
- **Acceptance:** deactivating a category hides it from storefront without breaking product references.

### 5.11 Admin — Product Management · *Must*
- CRUD products: name, category, description, price (৳), multiple images.
- Stock status toggle (In Stock / Stock Out) — **no quantity**.
- Flags: Best Seller, Featured; New Collection derived from created date.
- List view with search and filters (category, stock status).
- **Acceptance:** stock toggle instantly reflects on storefront badge and purchase ability.

### 5.12 Admin — Order Management · *Must*
- List/filter orders; view details (customer, items, totals, payment method & status).
- Order lifecycle: Pending → Processing → Shipped → Delivered → Cancelled.
- Status change triggers a customer email notification.
- **Acceptance:** status updates persist and notify the customer.

### 5.13 Admin — Invoice Management · *Must*
- Auto-generate invoice on order placement; view and print/download as PDF.
- **Acceptance:** every order has a unique, accurate invoice in ৳.

### 5.14 Admin — Review Moderation · *Must*
- List reviews; approve, hide, or delete; filter by product/rating/status.
- **Acceptance:** only approved reviews appear publicly.

### 5.15 Admin — Sales Reports · *Must*
**Requirements**
- Date-range filter (today / week / month / custom).
- Metrics: total sales (৳), order count, average order value, split by payment method (COD vs. SSLCommerz).
- Best-selling products and sales-by-category reports.
- Trend charts (bar/line); export to CSV/PDF (Should).

**Acceptance**
- Figures match underlying orders for the selected range; export produces a valid file.

### 5.16 Email Notifications · *Must*
**Requirements**
- Order confirmation email to customer on placement.
- Status-update email to customer on admin status change.
- New-order alert email to admin.
- Sent via Laravel Mail, **queued**, using configurable SMTP/transactional provider.

**Acceptance**
- All three email types fire on the correct events and render order details correctly in ৳.

---

## 6. User Flows (Narrative)

### 6.1 Browse → Purchase (COD)
1. Customer lands on home → browses categories / best sellers.
2. Opens a product → reviews rating & details → adds to cart.
3. Goes to cart → proceeds to checkout.
4. Enters shipping info → selects **COD** → places order.
5. Sees confirmation page; receives confirmation email. Admin gets new-order alert.

### 6.2 Browse → Purchase (SSLCommerz)
1. Same as above through checkout.
2. Selects **SSLCommerz** → redirected to gateway → completes payment.
3. On success callback → order marked paid & confirmed → invoice + confirmation email.
4. On failure/cancel → returned to checkout with an error; order remains unpaid.

### 6.3 Post-Purchase Review
1. Logged-in customer opens a purchased product → submits star rating + comment.
2. Review enters pending state → admin approves → review appears publicly; rating recalculates.

### 6.4 Admin Order Fulfillment
1. Admin sees new order on dashboard → opens details.
2. Updates status to Processing → Shipped → Delivered.
3. Each status change emails the customer.

---

## 7. Non-Functional Requirements

| Category | Requirement |
|----------|-------------|
| **Performance** | LCP < 2.5s on 4G; optimized/lazy-loaded images; queued emails |
| **Responsiveness** | Mobile-first; verified at 360 / 768 / 1280px |
| **Reliability** | SSLCommerz callbacks handled idempotently; no duplicate/lost orders |
| **Security** | CSRF, input validation, hashed passwords, role-based access, validated file uploads, secure payment callback verification |
| **Currency** | All amounts in BDT (৳) with thousands separators |
| **Usability** | Clean Tailwind UI, English throughout, clear stock/payment states |
| **SEO** | Friendly slugs, meta tags on product/category pages |
| **Maintainability** | Laravel + Vue conventions, reusable components |
| **Accessibility** | WCAG AA contrast; keyboard-navigable; status conveyed by text + color |
| **Browser Support** | Latest Chrome, Firefox, Safari, Edge |

---

## 8. Dependencies & Integrations

| Dependency | Purpose | Notes |
|------------|---------|-------|
| SSLCommerz | Online payments | Requires merchant account & sandbox/live credentials |
| SMTP / Transactional email | Order notifications | Configurable provider (e.g., Mailtrap for dev, SES/Mailgun for prod) |
| Laravel Queue | Async email/report jobs | Redis/database queue driver |
| File storage | Product/hero images | Laravel public disk |
| PDF generator | Invoices & report export | e.g., DomPDF |

---

## 9. Assumptions

- A valid SSLCommerz merchant account and email credentials are available.
- Single store, single currency (৳), English UI.
- Guest checkout allowed; reviews require a registered account.
- Stock is managed by badge only (no quantity counting).

---

## 10. Risks & Mitigations

| Risk | Impact | Mitigation |
|------|--------|------------|
| Payment callback failures / retries | Duplicate or unconfirmed orders | Idempotent callback handling; verify transaction with SSLCommerz before confirming |
| Email deliverability | Customers miss order updates | Use reputable transactional provider; queue + retry |
| Review spam / abuse | Low-quality content | Admin moderation; restrict to registered (optionally verified) buyers |
| Poor mobile performance | Lost conversions | Image optimization, lazy loading, lightweight UI |
| Stock badge stale | Selling unavailable items | Easy admin toggle; storefront enforces Stock Out block |

---

## 11. Release Plan (Phased)

| Phase | Scope |
|-------|-------|
| **Phase 1 (MVP)** | Storefront (home, shop, details, cart, checkout), COD + SSLCommerz, catalog admin, orders, invoices, email confirmation |
| **Phase 2** | Reviews & ratings + moderation, sales reports, status-update emails, customer accounts/order history |
| **Phase 3 (Future)** | Coupons, wishlist, SMS, additional gateways, Bangla language |

---

## 12. Open Questions

1. Should reviews be restricted to **verified purchasers** only, or any registered customer?
2. What are the exact **delivery charge** values (inside vs. outside Dhaka)?
3. Which **transactional email provider** will be used in production?
4. Is **guest checkout** required for v1, or must customers register to order?
5. Should sales report **export (CSV/PDF)** be in Phase 1 or Phase 2?

---

## 13. Acceptance Criteria (Summary)

- [ ] Home renders Hero → Categories → Best Selling → New Collection → Footer, responsively.
- [ ] Shop supports filter, sort, search, pagination; Stock Out blocked.
- [ ] Product details show gallery, ৳ price, stock badge, and reviews.
- [ ] Checkout works for **both COD and SSLCommerz**; payment states handled correctly.
- [ ] Every order generates an invoice and triggers a confirmation email.
- [ ] Customers can submit reviews; admin can moderate them.
- [ ] Status changes notify customers by email.
- [ ] Admin manages hero, categories, products, orders, invoices, reviews, and reports.
- [ ] Sales reports show accurate figures with date filtering.
- [ ] All currency in BDT (৳); UI in English; fully responsive.

---

*End of Document*
