# ShopEase — Design Guidelines

| | |
|---|---|
| **Project** | ShopEase (E-commerce Storefront) |
| **Stack** | Vue 3 + Inertia.js + Tailwind CSS |
| **Goal** | Professional, attractive, fully responsive storefront |
| **Market** | Bangladesh (mobile-first) |
| **Version** | 1.0 |
| **Date** | 2026-06-14 |

---

## 1. Design Principles

1. **Mobile-first** — Bangladesh has very high mobile traffic. Design for small screens first, then scale up.
2. **Clarity over decoration** — Every element earns its place. Generous whitespace, clear hierarchy.
3. **Product-forward** — Imagery and price are the heroes. UI chrome stays quiet.
4. **Consistency** — Reuse the same spacing, radius, and color tokens everywhere.
5. **Fast & lightweight** — Optimize images, lazy-load, avoid heavy effects that hurt performance on mid-range phones.
6. **Trust & professionalism** — Clean alignment, legible type, and obvious affordances build buyer confidence.

---

## 2. Color System

A clean, modern palette: a confident primary, a warm accent for CTAs/sales, and neutral grays for structure.

### 2.1 Brand Colors
| Token | Hex | Usage |
|-------|-----|-------|
| `primary-600` | `#2563EB` | Primary buttons, links, active states |
| `primary-700` | `#1D4ED8` | Hover on primary |
| `primary-50` | `#EFF6FF` | Soft primary backgrounds, badges |
| `accent-500` | `#F59E0B` | Highlights, sale tags, "Best Seller" |
| `accent-600` | `#D97706` | Accent hover |

> You may swap the primary to a brand color of choice (e.g., emerald `#059669` or rose `#E11D48`). Keep one primary + one accent only.

### 2.2 Neutrals
| Token | Hex | Usage |
|-------|-----|-------|
| `gray-900` | `#111827` | Headings, primary text |
| `gray-600` | `#4B5563` | Body / secondary text |
| `gray-400` | `#9CA3AF` | Muted text, placeholders |
| `gray-200` | `#E5E7EB` | Borders, dividers |
| `gray-100` | `#F3F4F6` | Section backgrounds, cards |
| `white` | `#FFFFFF` | Base background, card surfaces |

### 2.3 Semantic / Status Colors
| Purpose | Token | Hex |
|---------|-------|-----|
| Success / **In Stock** | `green-600` | `#16A34A` |
| Error / **Stock Out** | `red-600` | `#DC2626` |
| Warning | `amber-500` | `#F59E0B` |
| Info | `blue-500` | `#3B82F6` |

### 2.4 Tailwind Config Hint
```js
// tailwind.config.js
theme: {
  extend: {
    colors: {
      primary: { 50:'#EFF6FF', 600:'#2563EB', 700:'#1D4ED8' },
      accent:  { 500:'#F59E0B', 600:'#D97706' },
    },
  },
}
```

---

## 3. Typography

### 3.1 Font Family
- **Primary:** `Inter` (or `Poppins` for a friendlier look) via Google Fonts.
- Fallback: `system-ui, -apple-system, "Segoe UI", Roboto, sans-serif`.
- Use a single font family across the site; vary weight, not face.

```html
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
```

### 3.2 Type Scale (Tailwind classes)
| Element | Mobile | Desktop | Weight |
|---------|--------|---------|--------|
| Hero heading | `text-3xl` | `md:text-5xl` | `font-bold` |
| Section title | `text-2xl` | `md:text-3xl` | `font-bold` |
| Card / product name | `text-sm` | `md:text-base` | `font-medium` |
| Price | `text-base` | `md:text-lg` | `font-semibold` |
| Body text | `text-sm` | `md:text-base` | `font-normal` |
| Caption / meta | `text-xs` | `text-sm` | `font-normal` |

### 3.3 Rules
- Line height: `leading-relaxed` for body, `leading-tight` for headings.
- Limit body line length to ~`max-w-prose` for readability.
- Headings in `gray-900`, body in `gray-600`.

---

## 4. Spacing & Layout

### 4.1 Spacing Scale
Use Tailwind's 4px-based scale consistently. Prefer `4, 6, 8, 12, 16, 24` increments (`p-4`, `gap-6`, `mb-8`, `py-12`, `py-16`, `py-24`).

### 4.2 Container
```html
<div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">...</div>
```
- Max content width: `max-w-7xl` (1280px).
- Horizontal padding: `px-4` mobile → `lg:px-8` desktop.

### 4.3 Section Rhythm
- Vertical section padding: `py-12 md:py-16 lg:py-24`.
- Consistent gap between cards: `gap-4 md:gap-6`.

### 4.4 Responsive Breakpoints (Tailwind defaults)
| Prefix | Min width | Target |
|--------|-----------|--------|
| (base) | 0 | Mobile phones |
| `sm` | 640px | Large phones |
| `md` | 768px | Tablets |
| `lg` | 1024px | Laptops |
| `xl` | 1280px | Desktops |
| `2xl` | 1536px | Large screens |

---

## 5. Core UI Tokens

| Token | Value | Tailwind |
|-------|-------|----------|
| Border radius (cards/buttons) | 8–12px | `rounded-lg` / `rounded-xl` |
| Border radius (pills/badges) | full | `rounded-full` |
| Card shadow | subtle | `shadow-sm` → `hover:shadow-md` |
| Border | 1px | `border border-gray-200` |
| Transition | smooth | `transition duration-200 ease-in-out` |
| Focus ring | accessible | `focus:ring-2 focus:ring-primary-600 focus:outline-none` |

---

## 6. Component Guidelines

### 6.1 Buttons
| Variant | Style |
|---------|-------|
| **Primary** | `bg-primary-600 text-white hover:bg-primary-700 rounded-lg px-5 py-2.5 font-medium transition` |
| **Secondary** | `bg-gray-100 text-gray-900 hover:bg-gray-200 rounded-lg px-5 py-2.5` |
| **Outline** | `border border-gray-300 text-gray-900 hover:bg-gray-50 rounded-lg px-5 py-2.5` |
| **Disabled** | `opacity-50 cursor-not-allowed` (used for Stock Out "Add to Cart") |

- Minimum tap target: **44×44px** for mobile.
- Always include hover, focus, and disabled states.

### 6.2 Product Card
The most-used component — keep it tight and consistent.
- Container: `rounded-xl border border-gray-200 bg-white overflow-hidden hover:shadow-md transition`
- **Image:** fixed aspect ratio `aspect-square`, `object-cover`, lazy-loaded.
- **Stock badge:** top-left overlay pill (see §6.3).
- **Best Seller / New tag:** top-right pill in `accent-500`.
- Body padding: `p-3 md:p-4`.
- Product name: 1–2 lines, truncate with `line-clamp-2`.
- Price: `text-primary-600 font-semibold` in **৳** format.
- "Add to Cart": full-width primary button; disabled when Stock Out.

### 6.3 Stock Badge
| State | Style |
|-------|-------|
| In Stock | `bg-green-50 text-green-700 text-xs font-medium px-2.5 py-1 rounded-full` |
| Stock Out | `bg-red-50 text-red-700 text-xs font-medium px-2.5 py-1 rounded-full` |

### 6.4 Hero Carousel
- Full-width, height `h-[40vh] sm:h-[55vh] lg:h-[70vh]`.
- Image `object-cover` with a subtle dark gradient overlay for text legibility: `bg-gradient-to-r from-black/50 to-transparent`.
- Text block left-aligned: heading, subtext, CTA primary button.
- Controls: prev/next arrows (hidden on small screens, visible `md:flex`), dot indicators at bottom.
- Auto-rotate every 5s; pause on hover; swipeable on touch.

### 6.5 Navbar (Header)
- Sticky: `sticky top-0 z-50 bg-white/95 backdrop-blur border-b border-gray-200`.
- Left: logo. Center/right: nav links (Home, Shop, Categories). Far right: search, cart icon with item count badge.
- **Mobile:** hamburger menu → slide-in drawer; cart and search always visible.

### 6.6 Footer
- Background `bg-gray-900 text-gray-300`.
- Columns: About, Quick Links, Customer Service, Contact/Social.
- Stack into single column on mobile (`grid grid-cols-1 md:grid-cols-4 gap-8`).
- Bottom bar: copyright + payment/COD note.

### 6.7 Forms (Checkout / Search)
- Inputs: `rounded-lg border border-gray-300 px-4 py-2.5 focus:ring-2 focus:ring-primary-600`.
- Labels above inputs, `text-sm font-medium text-gray-700`.
- Inline validation messages in `text-red-600 text-sm`.
- Full-width inputs on mobile; multi-column grid on desktop.

### 6.8 Badges, Tags & Toasts
- Sale/discount tags: `accent-500`.
- Cart toast/notification on add-to-cart: top-right, auto-dismiss, success color.

---

## 7. Responsive Grid Patterns

| Section | Mobile | Tablet | Desktop |
|---------|--------|--------|---------|
| Product grid (Shop) | `grid-cols-2` | `sm:grid-cols-3` | `lg:grid-cols-4` |
| Best Selling / New Collection | `grid-cols-2` | `md:grid-cols-3` | `lg:grid-cols-4` |
| Category tiles | `grid-cols-2` | `sm:grid-cols-3` | `lg:grid-cols-6` |
| Footer | `grid-cols-1` | `md:grid-cols-4` | `md:grid-cols-4` |

Example:
```html
<div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4 md:gap-6">
  <!-- product cards -->
</div>
```

---

## 8. Imagery Guidelines

- **Aspect ratios:** product images `1:1` (square); hero banners `16:9` or wider.
- Use `object-cover` to avoid distortion.
- Serve optimized **WebP** where possible; provide width/height to prevent layout shift.
- Lazy-load below-the-fold images (`loading="lazy"`).
- Provide meaningful `alt` text for accessibility and SEO.
- Use a neutral placeholder/skeleton while loading.

---

## 9. Currency & Number Formatting

- Always prefix prices with **৳** (Taka symbol).
- Use thousands separators: `৳ 1,250`, `৳ 12,999`.
- No decimal places for whole-Taka prices; use `৳ 1,250.50` only if needed.
- Keep a single helper/filter for currency formatting across all Vue components.

```js
// Vue helper
const taka = (n) => '৳ ' + Number(n).toLocaleString('en-BD');
```

---

## 10. Accessibility (a11y)

- Color contrast: meet **WCAG AA** (4.5:1 for body text).
- All interactive elements keyboard-focusable with visible focus ring.
- Carousel controls and cart actions need `aria-label`s.
- Images have `alt` text; decorative images use `alt=""`.
- Don't rely on color alone — stock status uses both color **and** text label.

---

## 11. Motion & Interaction

- Keep animations subtle and fast (150–300ms).
- Use `transition` on hover for cards, buttons, and images (slight `scale-105` zoom on product image hover).
- Avoid large parallax/heavy effects that hurt mobile performance.
- Loading states: skeleton placeholders for product grids; spinner only for full-page loads.

---

## 12. Page-Level Layout Notes

### Home
Hero (full-width carousel) → Category strip → Best Selling grid → New Collection grid → Footer. Alternate section backgrounds (`white` / `gray-100`) for visual rhythm.

### Shop
Left sidebar filters on desktop (`lg:block`), collapsible filter drawer/modal on mobile. Sort dropdown + result count at top. Responsive product grid.

### Product Details
Two-column on desktop (`lg:grid-cols-2`): gallery left, info right. Single column stacked on mobile. Sticky "Add to Cart" bar on mobile bottom (optional).

### Cart & Checkout
Cart: item list + sticky order summary card. Checkout: form (left, 2/3) + order summary (right, 1/3) on desktop; stacked on mobile with summary collapsible.

---

## 13. Do's and Don'ts

**Do**
- ✅ Reuse component tokens and Tailwind utilities consistently.
- ✅ Test every page at 360px, 768px, and 1280px widths.
- ✅ Keep CTAs visually dominant (primary color).
- ✅ Maintain consistent card heights/alignment in grids.

**Don't**
- ❌ Mix multiple accent colors or fonts.
- ❌ Use tiny tap targets on mobile.
- ❌ Distort product images with wrong aspect ratios.
- ❌ Overload pages with effects that slow mid-range phones.

---

*End of Document*
