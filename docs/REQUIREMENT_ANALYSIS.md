# ShopEase — Requirement Analysis Document

| | |
|---|---|
| **Project Name** | ShopEase |
| **Type** | E-commerce Web Application |
| **Target Market** | Bangladesh |
| **Language** | English |
| **Currency** | Bangladeshi Taka (৳ / BDT) |
| **Document Version** | 1.1 |
| **Date** | 2026-06-14 |
| **Status** | Draft |

---

## 1. Introduction

### 1.1 Purpose
This document defines the functional and non-functional requirements for **ShopEase**, a simple yet complete e-commerce platform built for the Bangladeshi market. It serves as the single source of truth for design, development, and acceptance.

### 1.2 Scope
ShopEase consists of two major parts:

1. **Storefront (Customer-facing)** — Browse products, view details, add to cart, and check out.
2. **Admin Panel (Management)** — Manage hero banners, categories, products, orders, and invoices.

Inventory quantity is **not tracked**. Instead, each product carries a simple stock status badge: **In Stock** or **Stock Out**.

### 1.3 Definitions & Abbreviations
| Term | Meaning |
|------|---------|
| BDT / ৳ | Bangladeshi Taka, the platform currency |
| SKU | Stock Keeping Unit (product identifier) |
| COD | Cash on Delivery |
| Hero Section | The top banner/carousel area on the home page |
| Stock Badge | A visual label showing "In Stock" or "Stock Out" |

---

## 2. Technology Stack

| Layer | Technology |
|-------|-----------|
| Backend Framework | Laravel 13 |
| Frontend Framework | Vue 3 |
| Bridge | Inertia.js |
| Starter | Laravel Starter Kit (Inertia + Vue) |
| Styling | Tailwind CSS |
| Database | MySQL (recommended) |
| Authentication | Laravel built-in (starter kit) |
| File Storage | Laravel Storage (local/public disk for images) |

---

## 3. User Roles

| Role | Description | Access |
|------|-------------|--------|
| **Guest** | Unauthenticated visitor | Browse storefront, add to cart, checkout as guest (optional) |
| **Customer** | Registered user | All guest features + order history, saved profile |
| **Admin** | Store manager | Full access to the admin panel |

---

## 4. Functional Requirements — Storefront

### 4.1 Home Page
The home page must follow this exact section sequence (top to bottom):

1. **Hero Section** — Image carousel/slider
   - Auto-rotating banners with manual navigation (prev/next, dots)
   - Each slide: image, optional heading, subtext, and a CTA button/link
   - Managed from the admin panel
2. **Category Section**
   - Display product categories with image/icon and name
   - Clicking a category navigates to the shop page filtered by that category
3. **Best Selling Products**
   - Showcase top-selling products (based on order count) or admin-flagged "best seller"
   - Product card: image, name, price (৳), stock badge, "Add to Cart" action
4. **New Collection**
   - Display the most recently added products
   - Same product card layout as above
5. **Footer**
   - Site links, contact info, social links, copyright

### 4.2 Shop Page (Product Listing)
- Grid of product cards (image, name, price in ৳, stock badge)
- **Filters:** by category, price range
- **Sorting:** newest, price low→high, price high→low, best selling
- **Search:** by product name
- Pagination
- "Add to Cart" directly from listing
- Stock Out products are visibly marked and cannot be added to cart

### 4.3 Product Details Page
- Product image gallery (main image + thumbnails)
- Product name, full description, price (৳)
- Stock badge (In Stock / Stock Out)
- Quantity selector
- "Add to Cart" button (disabled if Stock Out)
- Category reference
- Related / similar products section (optional)
- **Reviews & Ratings** section (see 4.7)

### 4.4 Cart
- List of added items: image, name, unit price (৳), quantity, line total
- Update quantity / remove item
- Cart subtotal, shipping (if any), grand total in ৳
- Persist cart (session for guest, DB for logged-in customer — optional)
- "Proceed to Checkout" button

### 4.5 Checkout Page
- **Customer/Shipping details:** name, phone, email, full address (district, area, etc.)
- **Order summary:** items, subtotal, delivery charge, total in ৳
- **Payment methods:**
  - **Cash on Delivery (COD)**
  - **SSLCommerz** payment gateway (cards, mobile banking, internet banking)
- **Delivery charge logic:** e.g., inside Dhaka vs. outside Dhaka (configurable)
- Place Order → generates order + invoice
- Order confirmation page with order number

### 4.6 Customer Account (Optional / Phase 2)
- Registration & login
- Order history & order status tracking
- Profile management

### 4.7 Product Reviews & Ratings
- Customers can leave a **star rating (1–5)** and a written review on a product.
- Display average rating and total review count on product cards and the details page.
- Reviews list on the product details page (reviewer name, rating, comment, date).
- **Eligibility:** only registered customers (optionally restricted to those who purchased the product) may submit a review.
- **Moderation:** admin can approve, hide, or delete reviews (see 5.8).

---

## 5. Functional Requirements — Admin Panel

### 5.1 Authentication
- Secure admin login (Laravel starter kit auth)
- Role/middleware protection for all admin routes

### 5.2 Dashboard
- Summary widgets: total orders, total sales (৳), pending orders, total products
- Recent orders list

### 5.3 Hero Section Management
- CRUD for hero slides
- Fields: image (upload), heading, subtext, link/CTA, display order, active/inactive
- Reorder slides

### 5.4 Category Management
- CRUD for categories
- Fields: name, slug, image/icon, description, active/inactive
- Soft delete / deactivate to preserve product references

### 5.5 Product Management
- CRUD for products
- Fields:
  - Name, slug
  - Category (relation)
  - Description
  - Price (৳)
  - Images (multiple upload)
  - **Stock status:** In Stock / Stock Out (toggle — no quantity tracking)
  - Flags: Best Seller (yes/no), New Collection auto by created date, Featured (optional)
  - Active/inactive
- List view with search, filter by category, and stock status

### 5.6 Order Management
- List all orders with status filter
- Order details: customer info, items, totals (৳), payment method, **payment status** (paid/unpaid for SSLCommerz; COD pending)
- **Order statuses:** Pending → Processing → Shipped → Delivered → Cancelled
- Update order status (status change triggers customer email notification — see 5.10)
- Search orders by order number / customer phone

### 5.7 Invoice Management
- Auto-generate invoice when an order is placed
- Invoice fields: invoice number, order reference, customer details, itemized list, totals in ৳, date
- View invoice
- Print / download invoice (PDF — recommended)

### 5.8 Review & Rating Management
- List all customer reviews with product reference and rating
- **Moderate:** approve, hide/unpublish, or delete reviews
- Filter by product, rating, or approval status

### 5.9 Sales Reports
- Sales overview with date-range filter (today, this week, this month, custom)
- Metrics: total sales (৳), number of orders, average order value, by payment method (COD vs. SSLCommerz)
- Best-selling products report
- Sales by category
- Export report (CSV / PDF — recommended)
- Visual charts (bar/line) for sales trends

### 5.10 Email Order Notifications
- **Order confirmation email** sent to customer when an order is placed.
- **Order status update email** sent when admin changes order status (e.g., Shipped, Delivered, Cancelled).
- **Admin notification email** sent to store admin when a new order is received.
- Emails sent via Laravel Mail (queued for performance); use a configurable SMTP/transactional provider.

---

## 6. Non-Functional Requirements

| Category | Requirement |
|----------|-------------|
| **Performance** | Pages should load within ~2–3s on standard broadband; optimize images |
| **Responsiveness** | Fully responsive (mobile-first) — high mobile usage in Bangladesh |
| **Currency Display** | All monetary values formatted in BDT (৳) with proper thousands separators (e.g., ৳ 1,250) |
| **Security** | CSRF protection, input validation, hashed passwords, role-based access, file-upload validation |
| **Usability** | Clean, intuitive UI with Tailwind; English language throughout |
| **SEO** | Friendly slugs, meta tags on product/category pages |
| **Scalability** | Modular structure to allow future features (payment gateways, coupons) |
| **Browser Support** | Latest Chrome, Firefox, Safari, Edge |
| **Maintainability** | Follow Laravel + Vue conventions; reusable Vue components |

---

## 7. Data Model (High-Level)

### Core Entities & Key Fields

**users**
`id, name, email, password, role, phone, created_at`

**hero_slides**
`id, image, heading, subtext, link, sort_order, is_active`

**categories**
`id, name, slug, image, description, is_active`

**products**
`id, category_id, name, slug, description, price, stock_status (enum: in_stock|stock_out), is_best_seller, is_featured, is_active, created_at`

**product_images**
`id, product_id, image_path, is_primary`

**orders**
`id, user_id (nullable), order_number, customer_name, phone, email, address, subtotal, delivery_charge, total, payment_method (cod|sslcommerz), payment_status (pending|paid|failed), status, created_at`

**order_items**
`id, order_id, product_id, product_name, unit_price, quantity, line_total`

**invoices**
`id, order_id, invoice_number, issued_at, total`

**reviews**
`id, product_id, user_id, rating (1–5), comment, is_approved, created_at`

> Full schema, indexes, and relationships will be detailed in the separate **Database Design** document.

### Relationships
- A **Category** has many **Products**
- A **Product** has many **ProductImages** and belongs to a **Category**
- An **Order** has many **OrderItems** and has one **Invoice**
- A **User** (customer) has many **Orders**

---

## 8. Page / Route Map

### Storefront
| Route | Page |
|-------|------|
| `/` | Home (Hero → Category → Best Selling → New Collection → Footer) |
| `/shop` | Product listing with filters |
| `/product/{slug}` | Product details |
| `/cart` | Cart |
| `/checkout` | Checkout |
| `/order/confirmation/{orderNumber}` | Order confirmation |

### Admin
| Route | Page |
|-------|------|
| `/admin/login` | Admin login |
| `/admin/dashboard` | Dashboard |
| `/admin/hero` | Hero slide management |
| `/admin/categories` | Category management |
| `/admin/products` | Product management |
| `/admin/orders` | Order management |
| `/admin/invoices` | Invoice management |
| `/admin/reviews` | Review & rating moderation |
| `/admin/reports` | Sales reports |

---

## 9. Business Rules

1. Prices are stored and displayed in **BDT (৳)** only.
2. **Stock Out** products cannot be added to the cart or checked out.
3. Inventory **quantity is not tracked**; only the stock status badge applies.
4. Every placed order automatically generates a unique **order number** and an **invoice**.
5. **New Collection** = products sorted by most recent `created_at`.
6. **Best Selling** = products flagged by admin and/or ranked by total quantity sold.
7. Delivery charge is configurable (e.g., inside vs. outside Dhaka).
8. Two payment methods are supported: **Cash on Delivery (COD)** and **SSLCommerz**.
9. For SSLCommerz orders, the order is confirmed only after a **successful payment callback**; failed/cancelled payments leave the order unpaid.
10. Only registered customers may post reviews; reviews appear publicly only after admin approval.
11. Order placement and order status changes trigger **automated email notifications**.

---

## 10. Assumptions & Constraints

- Payment: **COD and SSLCommerz** are both in scope for this version. Other gateways (bKash, Nagad direct) are future.
- Multi-vendor functionality is **not** required — single store.
- Multi-language is **not** required — English only.
- Inventory management beyond the stock badge is **not** required.
- Customer registration may be optional for checkout (guest checkout supported), but **reviews require a registered account**.
- A valid SSLCommerz merchant account and transactional email/SMTP credentials are available.

---

## 11. Future Enhancements (Out of Scope for v1)

- Additional payment gateways (bKash/Nagad direct integration)
- Discount coupons & promo codes
- Wishlist
- SMS order notifications
- Multi-language support (Bangla)
- Advanced inventory tracking

---

## 12. Acceptance Criteria (Summary)

- [ ] Home page renders all sections in the specified order.
- [ ] Hero carousel works and is fully admin-managed.
- [ ] Shop page supports filtering, sorting, search, and pagination.
- [ ] Product details page shows gallery, price in ৳, and correct stock badge.
- [ ] Cart and checkout flow completes and produces an order + invoice.
- [ ] Checkout supports both **COD** and **SSLCommerz**; SSLCommerz payment success/failure is handled correctly.
- [ ] Stock Out products are blocked from purchase.
- [ ] Customers can submit star ratings & reviews; admin can moderate them.
- [ ] Order placement and status changes send **email notifications**.
- [ ] Admin can perform full CRUD on hero, categories, and products.
- [ ] Admin can view/update orders, manage invoices, and view **sales reports**.
- [ ] All currency displayed in BDT (৳); UI in English.
- [ ] Application is fully responsive.

---

*End of Document*
