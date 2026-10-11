# Spark Admin — Semantic Design System (DESIGN.md)

**Project Title:** Spark Admin Dashboard Template  
**Version:** 1.0.0  
**Design Philosophy:** Modern Executive, Forest & Neon Precision, Clean Component Architecture  
**CSS Framework:** Bootstrap 5.3.8 + Vanilla CSS Design Tokens  
**Icon System:** Bootstrap Icons (v1.13.1)  
**Typography:** Plus Jakarta Sans (Google Fonts)

---

## 1. Visual Theme & Atmosphere

Spark Admin embodies an **executive, high-contrast, nature-meets-cyberpunk aesthetic**. It departs from generic tech blues and grays by pairing a deep organic forest palette (`#051C12`, `#072F1F`) with electric high-visibility neon lime (`#B4F105`) accents on top of an ultra-clean, warm grayish-green canvas (`#F4F6F5`).

### Key Aesthetic Principles
- **Controlled Density:** Generous white space (24px gutter grids) balances dense analytical data points, ensuring cognitive ease during intensive management sessions.
- **Modern Soft Geometry:** Cards and interactive components feature smooth, rounded geometry ranging from `--radius-md` (10px) to `--radius-xxl` (24px) for cards and pill-shaped badges, avoiding harsh 90-degree corners.
- **Whisper-Soft Elevation:** UI depth is communicated through multi-tiered diffused box shadows (`rgba(11, 19, 15, 0.02)` to `0.08`), giving components a floating, tactile card quality without visual clutter.
- **Signature Visual Accents:**
  - 6-pointed rounded asterisk / geometric star icon (`bi bi-asterisk`) as the core brand emblem.
  - Floating ambient radial glow shapes on dark canvas backgrounds (Login and 404 screens).
  - High-contrast stat cards featuring rich dark forest backgrounds with lime graphic accents.

---

## 2. Color Palette & Design Tokens

### 2.1 Core Brand Colors

| Token Name | Hex Code | Visual Description | Semantic & Functional Role |
| :--- | :--- | :--- | :--- |
| `--brand-forest-dark` | `#051C12` | Deep Obsidian Forest | Core sidebar background, brand identity foundation, ultra-dark contrast |
| `--brand-forest-medium` | `#072F1F` | Deep Emerald Pine | Hero announcement banners, dark cards, primary chart series, dropdown backgrounds |
| `--brand-forest-light` | `#1A3E30` | Muted Cypress Green | Interactive elements in dark mode, active list item backgrounds, icon badges |
| `--brand-lime` | `#B4F105` | Electric Neon Lime | Primary high-visibility accent, active indicators, CTA buttons, chart highlight series |
| `--brand-lime-hover` | `#C1F824` | Bright Neon Chartreuse | Interactive hover state for lime buttons, active links, and focus rings |
| `--brand-lime-translucent` | `rgba(180, 241, 5, 0.15)` | Translucent Lime Tint | Badge backgrounds, focused input rings, icon highlight containers |

### 2.2 Neutral & Surface Canvas Colors

| Token Name | Hex Code | Visual Description | Semantic & Functional Role |
| :--- | :--- | :--- | :--- |
| `--bs-body-bg` | `#F4F6F5` | Warm Pale Sage / Gray-Green | Global body background canvas; soft on the eyes, reduces white glare |
| `--card-background` | `#FFFFFF` | Pure Crisp White | Content surface for analytical cards, tables, forms, and widgets |
| `--border-light` | `#E9EFEF` | Soft Pale Mineral Gray | Standard card borders, table dividers, input boundaries |
| `--border-dark-green`| `rgba(255, 255, 255, 0.1)` | Translucent White Mist | Border lines inside dark forest sidebar and alert cards |

### 2.3 Typography & Text Roles

| Token Name | Hex Code | Visual Description | Semantic & Functional Role |
| :--- | :--- | :--- | :--- |
| `--text-main` | `#0B130F` | Deep Charcoal Black | Primary headings (H1–H6), stat figures, table titles, primary labels |
| `--text-muted-green`| `#6C7E75` | Muted Sage Slate | Secondary labels, breadcrumbs, table captions, helper copy |
| `--text-sidebar-muted`| `#879A91` | Silver-Green Muted | Inactive sidebar menu items, section titles, version indicators |

### 2.4 Semantic Feedback & Status Tokens

| Status | Primary Color | Background Tint | Functional Role |
| :--- | :--- | :--- | :--- |
| **Success / Paid** | `--sys-green` (`#22C55E`) | `--sys-green-bg` (`#DCFCE7`) | Positive trends (+35%), successful actions, "Paid" status badges |
| **Danger / Failed** | `--sys-red` (`#EF4444`) | `--sys-red-bg` (`#FEE2E2`) | Error alerts, negative trends (-24%), "Failed" order badges, logout action |
| **Warning / Pending**| `--sys-orange` (`#F97316`) | `--sys-orange-bg` (`#FFEDD5`)| "Processing" order status, low stock notifications, warning banners |

---

## 3. Typography Hierarchy & Rules

### Font Specification
- **Primary Font Family:** `'Plus Jakarta Sans', sans-serif` (Imported from Google Fonts)
- **Supported Weights:** Light (`300`), Regular (`400`), Medium (`500`), Semi-Bold (`600`), Bold (`700`), Extra-Bold (`800`)
- **Font Smoothing:** `-webkit-font-smoothing: antialiased`
- **Global Letter Spacing:** `-0.01em` (Body), `-0.025em` (Headings)

```css
body {
    background-color: var(--bs-body-bg);
    color: var(--text-main);
    font-family: 'Plus Jakarta Sans', sans-serif;
    font-size: 0.925rem;
    font-weight: 500;
    letter-spacing: -0.01em;
}
```

### Type Scale & Hierarchy

| Element | Size | Weight | Line Height | Usage Context |
| :--- | :--- | :--- | :--- | :--- |
| **Display / 404** | `6.0rem - 8.0rem` | `800 / 900` | `1.0` | 404 giant error code graphic |
| **Page Title (H1)** | `1.65rem (26.4px)` | `700` | `1.25` | Top page titles (Dashboard, Tables, Forms) |
| **Card Title (H2)** | `1.15rem (18.4px)` | `700` | `1.3` | Section headings inside cards & widgets |
| **Card Stat (Amount)**| `1.85rem (29.6px)` | `800` | `1.1` | Net Income, Total Return KPI metrics |
| **Section Label** | `0.75rem (12px)` | `700` | `1.4` | Sidebar section headers (`MENU`, `COMPONENTS`) |
| **Body Regular** | `0.925rem (14.8px)` | `500` | `1.5` | Standard paragraph text, table cell body |
| **Caption / Helper** | `0.80rem (12.8px)` | `500` | `1.4` | Subtitles, timestamps, metadata |
| **Extra Small (fs-xs)**| `0.75rem (12px)` | `600` | `1.3` | Mini trend badges, micro-indicators |

---

## 4. Spacing, Geometry & Elevation

### 4.1 Border Radius Scale
- `--radius-sm`: `6px` — Micro tags, tooltips, nested mini buttons
- `--radius-md`: `10px` — Standard inputs, buttons, table action pills
- `--radius-lg`: `14px` — Dropdown menus, sub-panels, modal dialogs
- `--radius-xl`: `18px` — Standard analytical cards, alert banners
- `--radius-xxl`: `24px` — Large hero promo banners, login authentication cards

### 4.2 Elevation & Shadow Tokens
- `--shadow-sm`: `0 2px 8px rgba(11, 19, 15, 0.02)` — Subtle flat card boundaries
- `--shadow-md`: `0 10px 30px rgba(11, 19, 15, 0.04)` — Elevated cards, interactive hover states
- `--shadow-lg`: `0 20px 50px rgba(11, 19, 15, 0.08)` — Modal dialogs, login card, floating popups

---

## 5. Layout Architecture & System Shell

### 5.1 Global Structural Grid

```
+-------------------------------------------------------------------------------+
| SIDEBAR (280px fixed)  | NAVBAR (80px height)                                 |
|                        | [Toggle] [Search Pill]                 [Actions] [User] |
| - Brand Identity       +------------------------------------------------------+
| - Section: Menu        | MAIN WRAPPER (Fluid Canvas #F4F6F5)                  |
|   * Dashboard (active) |                                                      |
| - Section: Components  | [Page Header Banner: Title + Breadcrumb/Date Picker] |
|   * Basic Tables       +------------------------------------------------------+
|   * Forms & Input      | [KPI Cards Row / Alert Banner]                       |
|   * Buttons & Alerts   |                                                      |
| - Section: Pages       | [Col-9: Charts + Tables]    [Col-3: Donut + Promo]   |
|   * Blank Page         |                                                      |
|   * Login Screen       +------------------------------------------------------+
|   * Error 404          | FOOTER (Brand Copyright | Status Dot | Useful Links) |
| - Profile Dock Bottom  |                                                      |
+-------------------------------------------------------------------------------+
```

### 5.2 Responsive Dimensions
- **Sidebar Desktop Width:** `280px` (Fixed sticky left sidebar)
- **Navbar Height:** `80px`
- **Main Wrapper Margin Desktop:** `margin-left: 280px`
- **Mobile Breakpoint (< 1200px):**
  - Sidebar transitions to off-canvas drawer (`transform: translateX(-100%)`).
  - Mobile hamburger toggle button appears (`.sidebar-toggle-btn`).
  - Darkened backdrop overlay (`.sidebar-overlay`) manages outside click dismissal.

---

## 6. Core Component Specifications

### 6.1 Sidebar Navigation (`.sidebar-wrapper`)
- **Background:** `--brand-forest-dark` (`#051C12`)
- **Brand Brand Logo:** 
  - Icon: `bi-asterisk` in Electric Lime (`#B4F105`)
  - Typography: 1.25rem Bold in Pure White
- **Section Headers:** Uppercase, 0.75rem, letter-spacing: 0.05em, Color: `--text-sidebar-muted`
- **Menu Links:**
  - Inactive State: Color `#879A91`, 10px padding, 8px border-radius, smooth 0.2s transition
  - Active State: Background `--brand-forest-light` (`#1A3E30`), Color `--brand-lime` (`#B4F105`), active left accent bar or neon dot indicator
  - Hover State: Text transitions to white, subtle background glow
- **Footer Profile Dock:**
  - 40px circular avatar with rounded border
  - Name: Administrator (White, 0.9rem Bold)
  - Subtitle: admin@email.com (`#879A91`, 0.75rem)

### 6.2 Top Navbar (`.navbar-custom`)
- **Height:** `80px`, Background: Transparent with blur / White canvas on scroll
- **Left Actions:**
  - Desktop sidebar minimize toggle (`.btn-desktop-toggle`)
  - Mobile hamburger button (`.sidebar-toggle-btn`)
  - Quick Action Dropdown (`.btn-quick-action`): Pill shape button with `+ Create`
- **Search Pill (`.navbar-search-wrapper`):**
  - Fully pill-shaped (`border-radius: 50px`)
  - Subtle background (`#FFFFFF` or `#EEF3F1`), search icon right-aligned
- **Right Utilities:**
  - Fullscreen toggle button (`#btn-fullscreen`)
  - Notifications bell with pulsing unread dot
  - User avatar dropdown with quick profile links and danger logout item

### 6.3 KPI Metric Stat Cards (`.card-stat`)
- **Surface:** White card with `--radius-xl` (18px)
- **Top Row:** Stat title label (`.stat-label`, 0.85rem muted green) + 3-dots action menu (`.card-more-btn`)
- **Middle Metric:** Large numeric stat (`$196.000`, 1.85rem, 800 weight)
- **Trend Pill:**
  - Upward: `.trend-up` (Green tint background `#DCFCE7`, text `#22C55E`, arrow `bi-arrow-up-right`)
  - Downward: `.trend-down` (Red tint background `#FEE2E2`, text `#EF4444`, arrow `bi-arrow-down-left`)
- **Footer Sparkline:** Embedded zero-margin ApexCharts mini sparkline (`.sparkline-card-footer`)

### 6.4 Hero Alert Green Card (`.alert-green-card`)
- **Background:** `--brand-forest-medium` (`#072F1F`)
- **Badge:** Neon lime pill badge `Update`
- **Date:** White 0.8rem timestamp
- **Text:** Large high-contrast white announcement copy (`Sales revenue increased 40% in 1 week`)
- **Action Link:** Lime text link with animated right arrow
- **Visual Motif:** 6-pointed geometric lime star SVG embedded in bottom right corner with soft opacity

### 6.5 Data Tables (`.table-custom`)
- **Card Wrapper:** `.table-card-custom` with search bar and filter controls header
- **Header Cells (`th`):** Light gray uppercase labels, 0.75rem, bold, letter-spacing 0.04em
- **Customer Cell:** 38px circular avatar + Customer Full Name + Subtle Email
- **Status Badges (`.badge-table`):**
  - `.success`: `#DCFCE7` background, `#16A34A` text ("Paid")
  - `.pending`: `#FFEDD5` background, `#EA580C` text ("Processing")
  - `.failed`: `#FEE2E2` background, `#DC2626` text ("Failed" / "Cancelled")
- **Action Buttons (`.table-btn-action`):**
  - 32px circular icon buttons (View, Edit, Delete)
  - Delete turns red on hover with light red background
- **Pagination:** Modern borderless pill page numbers with lime active background

### 6.6 Form Controls & Inputs
- **Base Input (`.form-control-custom`):**
  - 10px border radius, border `#E9EFEF`, background `#FFFFFF`
  - Focus State: Border color `#072F1F`, subtle lime outer shadow ring `0 0 0 3px rgba(180, 241, 5, 0.2)`
- **Feedback Validation:**
  - Valid (`.is-valid-custom`): Green border + check icon badge (`#22C55E`)
  - Invalid (`.is-invalid-custom`): Red border + alert icon badge (`#EF4444`)
- **Input Groups:** Icon prefixes (e.g. `@`, currency symbols) integrated with unified border radii
- **Custom Select (`.form-select-custom`):** Chevron dropdown with matching custom padding

### 6.7 Buttons & Interactive Triggers
- **Primary Brand Button (`.btn-custom-primary`):**
  - Background: `--brand-forest-dark` (`#051C12`), Text: `#FFFFFF`
  - Hover: Background `--brand-forest-medium` (`#072F1F`)
- **Lime Accent Button (`.btn-custom-lime` / `.btn-promo`):**
  - Background: `--brand-lime` (`#B4F105`), Text: `#051C12` (Bold)
  - Hover: Background `#C1F824`
- **Secondary Button (`.btn-custom-secondary`):** Soft slate gray background
- **Danger Button (`.btn-custom-danger`):** Crimson red with white text
- **Outline Buttons (`.btn-custom-outline-*`):** 1.5px solid border, transparent background, fills on hover

### 6.8 Charts & Analytical Visualizations
- **ApexCharts Integration:**
  - Revenue Area/Bar Chart: Dual series featuring Forest Medium (`#072F1F`) and Neon Lime (`#B4F105`)
  - Total View Performance: Donut chart featuring Lime (`#B4F105`), Forest Medium (`#072F1F`), and Orange Accent (`#F97316`)
  - Sparklines: Minimalist single-color curve charts embedded in stat cards
  - Tooltips: Dark glassmorphic tooltips with white text and colored legend circles

---

## 7. Reusable Utility Classes (Zero Inline CSS Policy)

| Class Name | Style Rule | Intended Purpose |
| :--- | :--- | :--- |
| `.z-index-2` | `z-index: 2;` | Elevate content above background SVG shapes |
| `.sparkline-card-footer` | `margin-bottom: -15px; margin-left: -15px; margin-right: -15px;` | Full-bleed bottom sparkline inside stat cards |
| `.chart-legend-label` | `font-size: 0.75rem; font-weight: 600;` | Standardized legend item text |
| `.stat-value-amount` | `font-size: 1.6rem; font-weight: 800;` | Bold KPI display amount |
| `.fs-xs` | `font-size: 0.75rem;` | Micro text helper |
| `.w-38`, `.w-50`, `.w-65`, `.w-85` | `width: 38% / 50% / 65% / 85%;` | Dynamic progress bar fills without inline style tags |
| `.empty-state-icon` | `font-size: 3.5rem; opacity: 0.7;` | Large centered graphic for blank pages |
| `.bg-forest-light` | `background-color: var(--brand-forest-light);` | Dark pine container background |
| `.bg-lime-accent` | `background-color: var(--brand-lime);` | Electric neon lime fill |
| `.text-lime` | `color: var(--brand-lime);` | Electric lime text highlight |

---

## 8. Screen Inventory & Route Map

```
Root Workspace
├── DESIGN.md                 <-- Master Semantic Design System Specification
├── index.md                  <-- Dashboard Page Layout & Specification
├── tables-basic.md           <-- Basic Data Tables Specification
├── ui-forms.md               <-- Form Controls & Validation Specification
├── ui-buttons.md             <-- Buttons & Notification Alerts Specification
├── page-blank.md             <-- Blank Starter Page Specification
├── page-login.md             <-- Authentication Login Screen Specification
├── page-404.md               <-- Error 404 Page Specification
└── documentation/
    └── index.md              <-- Technical Developer Guide & API Documentation
```

---

## 9. Design System Governance & Extension Guidelines

1. **Strict Zero-Inline CSS Rule:** Never inject `style="..."` directly on elements. Always declare reusable utility classes or custom component rules in `assets/css/main.css`.
2. **Color Token Fidelity:** Always use CSS custom variables (`var(--brand-*)`) rather than hardcoding hex codes across new pages.
3. **Contrast Accessibility:** High contrast between text and background must be maintained:
   - On `#051C12` (Forest Dark): Use Pure White (`#FFFFFF`) or Electric Lime (`#B4F105`).
   - On `#F4F6F5` (Body Canvas): Use Deep Charcoal (`#0B130F`) or Sage Slate (`#6C7E75`).
4. **State Feedback:** All interactive elements (buttons, table rows, form inputs, dropdowns) must provide distinct `:hover`, `:focus`, and `:active` visual states.
