# Dashboard Page Specification (`index.md`)

**Template:** Spark Admin  
**Page Title:** Dashboard / Analytics Overview  
**Original File:** `index.html`  
**Layout Model:** Full-height Sidebar Navigation + Top Sticky Navbar + Multi-column Analytical Canvas + Footer  

---

## 1. Page Overview & Purpose

The **Dashboard** is the flagship view of Spark Admin. It consolidates financial performance, sales revenue metrics, recent transactions, inventory progress bars, and audience viewing statistics into a coherent, high-impact executive interface.

---

## 2. Page Architecture & Wireframe

```
+----------------------------------------------------------------------------------------------------+
| SIDEBAR (Sticky 280px) | TOP NAVBAR (80px)                                                         |
| [Asterisk] Spark Admin | [Toggle] [Create Action] | [Search Pill] | [Fullscreen] [Bell] [Profile]  |
|                        +---------------------------------------------------------------------------+
| MENU                   | PAGE HEADER BANNER                                                        |
| • Dashboard (Active)   | Title: "Dashboard" - Subtitle: "An easy way to manage sales with care..." |
|                        | Action: Date Range Selector Button (Jan 12, 2026 - Jan 23, 2026)          |
| COMPONENTS             +---------------------------------------------------------------------------+
| • Basic Tables         | TOP STATS ROW (3 Columns - Grid g-4)                                      |
| • Forms & Input        | +--------------------+ +--------------------+ +--------------------+      |
| • Buttons & Alerts     | | Card 1: Alert Promo| | Card 2: Net Income | | Card 3: Total Return |      |
|                        | | Forest Medium BG   | | $196.000 (+35%)    | | $32.000 (-24%)     |      |
| PAGES                  | | Lime Asterisk SVG  | | Income Sparkline   | | Return Sparkline   |      |
| • Blank Page           | +--------------------+ +--------------------+ +--------------------+      |
| • Login Screen         +---------------------------------------------------------------------------+
| • Error 404            | MAIN ANALYTICS GRID                                                       |
|                        | +-----------------------------------------------+ +---------------------+ |
| BOTTOM PROFILE DOCK    | | Col-9 (Left Main Analytics Area)              | | Col-3 (Right Panel) | |
| [Avatar] Administrator | | 1. Revenue Chart (ApexCharts Bar/Line)        | | 1. Total View       | |
| admin@email.com        | |    Dual Series: Income ($196k) vs Expenses    | |    Performance      | |
|                        | | 2. Transaction List     3. Product Overview   | |    Donut Chart      | |
|                        | |    - Spotify (-$15)        - Launched: 233    | | 2. Level Up Promo   | |
|                        | |    - Paypal (+$1,250)      - Sold: 482        | |    Banner Card with | |
|                        | |    - Stripe (+$3,400)      - In Stock: 1,420  | |    Lime Asterisk    | |
|                        | |    - Slack Pro (-$45)      - Pending: 64      | |    CTA Button       | |
|                        | +-----------------------------------------------+ +---------------------+ |
|                        +---------------------------------------------------------------------------+
|                        | FOOTER COMPONENT: Brand © 2026 Spark Admin | Overview, Stats, Docs Links  |
+----------------------------------------------------------------------------------------------------+
```

---

## 3. Component Details & Design Tokens

### 3.1 Page Header Banner (`.page-header`)
- **Title (`.page-title`):** "Dashboard", 1.65rem, weight 700, color `--text-main` (`#0B130F`).
- **Subtitle (`.page-subtitle`):** "An easy way to manage sales with care and precision.", 0.875rem, color `--text-muted-green` (`#6C7E75`).
- **Date Picker Trigger (`.btn-date-picker`):**
  - Icon: `bi bi-calendar4-event`
  - Range Label: `January 12, 2026 - January 23, 2026`
  - Dropdown caret: `bi bi-chevron-down`
  - Integrated with Flatpickr date range selection library.

---

### 3.2 Top Stats Row (3 KPI Cards)

#### Card 1: Alert Green Update Card (`.alert-green-card`)
- **Background:** `--brand-forest-medium` (`#072F1F`)
- **Badge:** Pill shape with text `Update`, background `--brand-lime` (`#B4F105`), text `#051C12`
- **Date:** `Feb 14th 2026`, text color rgba(255, 255, 255, 0.7)
- **Text:** `Sales revenue increased 40% in 1 week`, font-size 1.15rem, weight 700, color `#FFFFFF`
- **Action Link (`.alert-green-link`):** `See Statistics ->`, text color `--brand-lime`
- **Background SVG:** Floating 6-pointed rounded star/asterisk geometric motif in `--brand-lime` with subtle opacity.

#### Card 2: Net Income Stat Card (`.card-stat`)
- **Card Background:** White (`#FFFFFF`), shadow `--shadow-md`, radius `--radius-xl` (18px)
- **Label:** `Net Income` (`.stat-label`, 0.85rem, `--text-muted-green`)
- **Actions Menu:** Three-dots icon trigger with dropdown (Refresh, Export Report, Hide Details)
- **Primary Metric:** `$196.000` (1.85rem, font-weight 800)
- **Trend Indicator:** Upward trend badge (`.trend-badge.trend-up`)
  - Background: `#DCFCE7`
  - Text: `#22C55E`
  - Icon: `bi bi-arrow-up-right`
  - Text: `+35% from last month`
- **Embedded Sparkline Chart:** `#income-sparkline` rendered with ApexCharts inside `.sparkline-card-footer`.

#### Card 3: Total Return Stat Card (`.card-stat`)
- **Card Background:** White (`#FFFFFF`), shadow `--shadow-md`, radius `--radius-xl` (18px)
- **Label:** `Total Return` (`.stat-label`, 0.85rem, `--text-muted-green`)
- **Actions Menu:** Three-dots dropdown trigger
- **Primary Metric:** `$32.000` (1.85rem, font-weight 800)
- **Trend Indicator:** Downward trend badge (`.trend-badge.trend-down`)
  - Background: `#FEE2E2`
  - Text: `#EF4444`
  - Icon: `bi bi-arrow-down-left`
  - Text: `-24% from last month`
- **Embedded Sparkline Chart:** `#return-sparkline` rendered with ApexCharts inside `.sparkline-card-footer`.

---

### 3.3 Analytics Details (Col-9 Left Area)

#### 1. Revenue Chart (`#revenue-chart`)
- **Header:** Title `Revenue` + Custom legend dots:
  - Dot 1: Forest Medium (`#072F1F`) for **Income**
  - Dot 2: Lime Accent (`#B4F105`) for **Expenses**
- **Headline Stat:** `$196.000` with micro-badge `+35% from last month`
- **Chart Type:** ApexCharts multi-series Area / Bar chart with smooth gradient fills and responsive axis formatting.

#### 2. Recent Transactions List (`.transaction-list`)
- **Card Header:** Title `Transaction` + Options Dropdown (Filter Status, Export CSV)
- **Item Row Structure:**
  - Icon Box: 40px rounded box with background `--brand-forest-light` (`#1A3E30`) and lime icon (`--brand-lime`)
  - Info: Name (bold, 0.9rem) + Timestamp (muted, 0.8rem)
  - Amount: Bold value with green indicator for positive income or dark for expense:
    - Spotify Subscription: `-$15.00`
    - Paypal Transfer: `+$1,250.00` (text-success)
    - Stripe Payout: `+$3,400.00` (text-success)
    - Slack Pro Workspace: `-$45.00`

#### 3. Product Overview Progress Bars
- **Card Header:** Title `Product Overview` + Options Dropdown (Add Product, Manage)
- **Metrics & Progress Bars:**
  - **Product Launched:** 233 (`.w-65`, lime bar)
  - **Ongoing Product:** 23 (`.w-50`, lime bar 50% opacity)
  - **Product Sold:** 482 (`.w-85`, lime bar)
  - **Product Returned:** 8 (`.w-38`, brand orange bar `#F97316`)
  - **Product In Stock:** 1,420 (`.w-75`, lime bar)
  - **Pending Shipment:** 64 (`.w-45`, lime bar 50% opacity)

---

### 3.4 Performance Details (Col-3 Right Panel)

#### 1. Total View Performance Donut Chart (`#views-chart`)
- **Card Header:** `Total View Performance`
- **Visualization:** ApexCharts radial / donut chart with high contrast:
  - Slice 1: Lime Accent (`#B4F105`) - View Count
  - Slice 2: Forest Medium (`#072F1F`) - Percentage
  - Slice 3: Brand Orange (`#F97316`) - Sales
- **Custom Legends Container (`.chart-legends-container`):** Muted green labels with matching colored legend indicator dots.

#### 2. Level Up Promo Banner (`.promo-banner-card`)
- **Surface:** Deep forest background with smooth rounded corners (`--radius-xxl`)
- **Decorative Shape:** Lime 6-pointed asterisk SVG in the background
- **Title:** `Level up your sales managing to the next level.` (White, 1.25rem bold)
- **Description:** `An easy way to manage sales with care and precision.` (Translucent white)
- **Action Button (`.btn-promo`):** Full-width lime button with dark text: `Check the updates now`

---

## 4. Dependencies & Script Handlers
- **CSS:** `assets/libs/bootstrap/css/bootstrap.min.css`, `assets/libs/bootstrap-icons/bootstrap-icons.css`, `assets/libs/apexcharts/apexcharts.css`, `assets/libs/flatpickr/flatpickr.min.css`, `assets/css/main.css`
- **JS:** `assets/libs/bootstrap/js/bootstrap.bundle.min.js`, `assets/libs/apexcharts/apexcharts.min.js`, `assets/libs/flatpickr/flatpickr.min.js`, `assets/js/dashboard.js`
