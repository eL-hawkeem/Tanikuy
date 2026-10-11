# Spark Admin — Developer Documentation & User Guide (`documentation/index.md`)

**Version:** 1.0.0 Freebie  
**Framework:** Bootstrap 5.3.8 + Vanilla CSS Design Tokens  
**Icons:** Bootstrap Icons v1.13.1  
**Charts:** ApexCharts v4.x  
**Datepicker:** Flatpickr v4.6.13  
**Authors:** Spark Admin Team  
**Distribution:** ThemeWagon  

---

## 1. Introduction

**Spark Admin** is a lightweight, fully responsive Bootstrap 5 Admin Template designed for SaaS platforms, web applications, dashboards, and enterprise web portals.

### Core Architecture Standards:
- **Zero Inline CSS:** All styling logic strictly resides inside structured stylesheets.
- **High Component Reusability:** Cards, Stat Badges, Progress Bars, Tables, and Action Buttons are modularly designed for developer reuse.
- **Centralized Fonts:** Google Fonts (`Plus Jakarta Sans`) are imported via CSS `@import`, avoiding multiple external `<link>` tags.
- **Modular Script Files:** Custom logic is separated into standalone JS files inside `assets/js/`.
- **Developer Friendly:** Clean HTML/Markdown structures with detailed design tokens and component documentation.

---

## 2. Workspace File Structure

```
spark-admin-v1.0.0/
├── assets/
│   ├── css/
│   │   └── main.css             # Core Design System & Custom Stylesheet
│   ├── images/
│   │   ├── avatar.png           # Administrator profile avatar
│   │   ├── favicon.ico          # Template Favicon
│   │   ├── spark-admin-free.png # Preview banner graphic
│   │   └── user_1.jpg ... 8.jpg # Customer demo avatars
│   ├── js/
│   │   ├── dashboard.js         # Core ApexCharts & Mobile Navigation logic
│   │   └── auth.js              # Login & Auth form interaction script
│   └── libs/
│       ├── apexcharts/          # ApexCharts v4.x Library
│       ├── bootstrap/           # Bootstrap v5.3.8 Framework
│       ├── bootstrap-icons/     # Bootstrap Icons v1.13.1 Icon Set
│       └── flatpickr/           # Flatpickr v4.6.13 Datepicker
├── documentation/
│   ├── assets/
│   │   ├── css/
│   │   │   └── docs.css         # Dedicated Documentation Stylesheet
│   │   └── js/
│   │       └── docs.js          # Documentation Interaction Script
│   └── index.md                 # Developer Documentation & User Guide
├── DESIGN.md                    # Master Semantic Design System Specification
├── index.md                     # Analytics Dashboard Page Specification
├── page-404.md                  # 404 Error Page Template Specification
├── page-blank.md                # Blank Starter Page Template Specification
├── page-login.md                # Authentication Login Page Specification
├── tables-basic.md              # Basic & Striped Data Tables Page Specification
├── ui-buttons.md                # Button Variants & Component Page Specification
├── ui-forms.md                  # Form Inputs, Selects & Validation Specification
└── README.md                    # Workspace README & Overview
```

---

## 3. Quick Start & Setup

To view and run Spark Admin locally:

### Using Simple HTTP Server
```bash
# Node.js npx serve
npx serve .

# Or Python 3 built-in server
python3 -m http.server 8000
```

Navigate to `http://localhost:8000/` in any modern web browser.

---

## 4. Design System & CSS Variables

Spark Admin is built on a dark-forest and neon lime design palette defined in `assets/css/main.css`:

```css
:root {
    /* Colors */
    --bs-body-bg: #F4F6F5;             /* Canvas background: light, warm grayish-green */
    --bs-body-font-family: 'Plus Jakarta Sans', sans-serif;
    
    --brand-forest-dark: #051C12;     /* Core sidebar/brand background */
    --brand-forest-medium: #072F1F;   /* Alert banner / dark card BG */
    --brand-forest-light: #1A3E30;    /* Interactive elements in dark mode */
    --brand-lime: #B4F105;            /* Lime-yellow accent highlight */
    --brand-lime-hover: #c1f824;      
    --brand-lime-translucent: rgba(180, 241, 5, 0.15);
    
    --text-main: #0B130F;             /* Dark headers, main labels */
    --text-muted-green: #6C7E75;      /* Secondary labels, muted details */
    --text-sidebar-muted: #879A91;    /* Sidebar inactive link labels */
    
    --card-background: #FFFFFF;
    --border-light: #E9EFEF;
    --border-dark-green: rgba(255, 255, 255, 0.1);
    
    /* System Feedback Colors */
    --sys-green: #22C55E;
    --sys-green-bg: #DCFCE7;
    --sys-red: #EF4444;
    --sys-red-bg: #FEE2E2;
    --sys-orange: #F97316;
    --sys-orange-bg: #FFEDD5;
    
    /* Layout Dimensions */
    --sidebar-width: 280px;
    --navbar-height: 80px;
    
    /* Border Radii */
    --radius-xxl: 24px;
    --radius-xl: 18px;
    --radius-lg: 14px;
    --radius-md: 10px;
    --radius-sm: 6px;
    
    /* Shadows */
    --shadow-sm: 0 2px 8px rgba(11, 19, 15, 0.02);
    --shadow-md: 0 10px 30px rgba(11, 19, 15, 0.04);
    --shadow-lg: 0 20px 50px rgba(11, 19, 15, 0.08);
}
```

---

## 5. JavaScript Architecture & Logic

Interactive features are divided into distinct script modules:

1. **`dashboard.js`:**
   - Controls mobile off-canvas sidebar drawer and backdrop overlay.
   - Initializes ApexCharts (Revenue chart, views donut chart, and sparklines).
   - Initializes Flatpickr date range picker with localized date formats.
2. **`auth.js`:**
   - Toggles password visibility (`type="password"` <-> `type="text"`).
   - Handles client-side form validation states.
3. **`docs.js`:**
   - Manages documentation copy-to-clipboard buttons and smooth anchor scrolling.

---

## 6. Reusable Utility Classes (Zero Inline CSS Policy)

| Class Name | Applied CSS / Behavior | Purpose |
| :--- | :--- | :--- |
| `.z-index-2` | `z-index: 2;` | Keeps content above decorative background SVGs |
| `.sparkline-card-footer`| `margin: 0 -15px -15px -15px;` | Full-bleed sparklines at card bottom |
| `.chart-legend-label` | `font-size: 0.75rem; font-weight: 600;` | Standard chart legend label style |
| `.stat-value-amount` | `font-size: 1.6rem; font-weight: 800;` | Large stat metric figure |
| `.fs-xs` | `font-size: 0.75rem;` | Micro typography |
| `.w-38`, `.w-50`, `.w-65`, `.w-85` | `width: 38% / 50% / 65% / 85%;` | Dynamic progress bar fills |
| `.empty-state-icon` | `font-size: 3.5rem; opacity: 0.7;` | Large translucent icon for empty pages |

---

## 7. Pre-built Page Specifications

| Specification File | Screen Name | Target Purpose |
| :--- | :--- | :--- |
| [`index.md`](file:///c:/Users/LENOVO/Downloads/spark-admin-v1.0.0%20%281%29/index.md) | Analytics Dashboard | Main analytics overview, KPI cards, charts, transaction feed |
| [`tables-basic.md`](file:///c:/Users/LENOVO/Downloads/spark-admin-v1.0.0%20%281%29/tables-basic.md) | Basic Data Tables | Order list, user avatar cells, status badges, pagination |
| [`ui-forms.md`](file:///c:/Users/LENOVO/Downloads/spark-admin-v1.0.0%20%281%29/ui-forms.md) | Forms & Inputs | Text fields, validation feedback, input groups, switches |
| [`ui-buttons.md`](file:///c:/Users/LENOVO/Downloads/spark-admin-v1.0.0%20%281%29/ui-buttons.md) | Buttons & Alerts | Solid & outline buttons, icon buttons, notification banners |
| [`page-blank.md`](file:///c:/Users/LENOVO/Downloads/spark-admin-v1.0.0%20%281%29/page-blank.md) | Blank Starter Page | Boilerplate layout skeleton for custom modules |
| [`page-login.md`](file:///c:/Users/LENOVO/Downloads/spark-admin-v1.0.0%20%281%29/page-login.md) | Authentication Screen | Centered login card, ambient radial glow, password toggle |
| [`page-404.md`](file:///c:/Users/LENOVO/Downloads/spark-admin-v1.0.0%20%281%29/page-404.md) | 404 Error Page | Giant error code hero, animated asterisk, home button |

---

## 8. Third-Party Plugins & Integration

### ApexCharts Setup
- **CSS:** `assets/libs/apexcharts/apexcharts.css`
- **JS:** `assets/libs/apexcharts/apexcharts.min.js`
- **Container:** `<div id="revenue-chart"></div>`

### Flatpickr Datepicker Setup
- **CSS:** `assets/libs/flatpickr/flatpickr.min.css`
- **JS:** `assets/libs/flatpickr/flatpickr.min.js`
- **Container:** `<button class="btn-date-picker" id="date-picker-trigger">`

---

## 9. Browser Compatibility

Spark Admin is compatible with:
- **Google Chrome:** Latest 2 versions
- **Mozilla Firefox:** Latest 2 versions
- **Microsoft Edge:** Latest 2 versions
- **Apple Safari:** macOS & iOS (Version 14+)
- **Opera:** Latest 2 versions

---

## 10. License & Footer Attribution Policy

- **Free Edition License:** Requires maintaining the footer backlink attribution (`Made with ♥ by Spark Admin • Distributed by ThemeWagon`).
- **Commercial White-Label:** Available via ThemeWagon for projects requiring removal of credits, additional pre-built apps, and premium developer support.
