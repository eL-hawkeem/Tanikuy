# Basic Tables Page Specification (`tables-basic.md`)

**Template:** Spark Admin  
**Page Title:** Basic Tables  
**Original File:** `tables-basic.html`  
**Layout Model:** Full-height Sidebar + Sticky Navbar + Breadcrumb Banner + Responsive Tabular Data Card + Pagination Footer  

---

## 1. Page Overview & Purpose

The **Basic Tables** page provides a responsive, production-ready tabular data interface for listing orders, customer records, inventory items, or transaction logs. It includes search filtering, status-based classification, quick action buttons, user avatar integration, and pagination controls.

---

## 2. Page Architecture & Wireframe

```
+----------------------------------------------------------------------------------------------------+
| SIDEBAR               | NAVBAR (Search, Quick Actions, Notifications, Profile)                     |
|                       +----------------------------------------------------------------------------+
|                       | PAGE HEADER BANNER                                                         |
|                       | Title: "Basic Tables"                                                      |
|                       | Subtitle: "Highly customizable, responsive tabular data component..."      |
|                       | Breadcrumb: Home / Components / Basic Tables                               |
|                       +----------------------------------------------------------------------------+
|                       | TABLE CARD CONTAINER (.table-card-custom)                                  |
|                       | +------------------------------------------------------------------------+ |
|                       | | TABLE CONTROLS BAR:                                                    | |
|                       | | [Search Input: "Search orders or products..."]                         | |
|                       | | [Status Filter Dropdown v] [Export Button]                             | |
|                       | +------------------------------------------------------------------------+ |
|                       | | RESPONSIVE TABLE (.table-custom)                                       | |
|                       | | Order ID | Customer (Avatar+Name) | Product | Cat | Amount | Status | Act |
|                       | | #ORD-9982| Eleanor Pena           | Hoodie  | App | $89.90 | [Paid] | ... |
|                       | | #ORD-9981| Wade Warren            | Console | Ele | $499.00| [Proc] | ... |
|                       | | #ORD-9978| Albert Flores          | Wallet  | Acc | $45.00 | [Paid] | ... |
|                       | | #ORD-9977| Kristin Watson         | Chair   | Fur | $249.00| [Proc] | ... |
|                       | | #ORD-9975| Bessie Cooper          | Tracker | Ele | $79.99 | [Fail] | ... |
|                       | +------------------------------------------------------------------------+ |
|                       | | TABLE FOOTER & PAGINATION:                                             | |
|                       | | "Showing 1 to 10 of 50 entries"                   [<] [1] [2] [3] [>]  | |
|                       | +------------------------------------------------------------------------+ |
|                       +----------------------------------------------------------------------------+
|                       | FOOTER COMPONENT: Brand © 2026 Spark Admin                                 |
+----------------------------------------------------------------------------------------------------+
```

---

## 3. Component Details & Design Tokens

### 3.1 Page Header Banner & Breadcrumbs
- **Page Title:** `Basic Tables` (1.65rem, weight 700, `--text-main` `#0B130F`)
- **Subtitle:** `Highly customizable, responsive tabular data component templates.`
- **Breadcrumbs:**
  - Item 1: `Home` (Link to `index.html`, text color `--text-muted-green`)
  - Item 2: `Components` (Muted)
  - Item 3: `Basic Tables` (Active item, text color `--text-main`, weight 600)

---

### 3.2 Table Header Controls (`.table-header-control`)
- **Container Layout:** Flexbox row with `justify-content: space-between` and `align-items: center`
- **Search Box (`.table-search-box`):**
  - Search Icon: `bi bi-search` positioned on the left
  - Input (`.table-search-input`): 10px rounded input with placeholder `Search orders or products...`
- **Filter Action Group (`.table-filter-group`):**
  - **Status Filter Dropdown (`#dropdownFilterStatus`):**
    - Trigger button with filter icon (`bi bi-funnel`)
    - Menu options: `All Statuses`, `Paid / Success`, `Processing`, `Cancelled / Failed`
  - **Export Action Button (`.btn-table-action`):**
    - Icon: `bi bi-file-earmark-arrow-down`
    - Label: `Export`

---

### 3.3 Table Structure & Columns (`.table-custom`)

| Column Name | CSS Class | Alignment | Example Content | Notes / Token Rules |
| :--- | :--- | :--- | :--- | :--- |
| **Order ID** | `.table-order-id` | Left | `#ORD-9982` | Medium weight, `--brand-forest-medium` |
| **Customer** | `.table-user-cell` | Left | Avatar + Eleanor Pena | 36px circular avatar, name (bold 0.875rem), email (`--text-muted-green`) |
| **Product Info**| `.table-product-name` | Left | `Oversized Hoodie` | Bold 0.875rem |
| **Category** | — | Left | `Apparel`, `Electronics` | Muted secondary label |
| **Amount** | `.table-amount` | Left | `$89.90` | Semi-bold numerical value |
| **Order Date** | — | Left | `Feb 14, 2026` | Standard date format |
| **Status** | `.badge-table` | Center/Left | `Paid`, `Processing`, `Cancelled` | Color-coded status pill badges |
| **Actions** | `.table-btn-action` | Center | Eye, Pencil, Trash icons | Action pills with hover state effects |

---

### 3.4 Status Badge Design Tokens

| Badge Class | Semantic Meaning | Background Color | Text Color | Example States |
| :--- | :--- | :--- | :--- | :--- |
| `.badge-table.success` | Complete / Successful | `#DCFCE7` (Light Green) | `#16A34A` (Forest Green) | `Paid`, `Delivered`, `Active` |
| `.badge-table.pending` | In Progress / Awaiting | `#FFEDD5` (Soft Amber) | `#EA580C` (Deep Orange) | `Processing`, `In Review` |
| `.badge-table.failed` | Failed / Terminated | `#FEE2E2` (Soft Red) | `#DC2626` (Crimson) | `Failed`, `Cancelled`, `Refunded` |

---

### 3.5 Action Buttons & Hover Effects (`.table-btn-action`)
- **Dimensions:** 32px x 32px circular pill buttons
- **Default State:** Background `#F4F6F5`, icon color `--text-muted-green` (`#6C7E75`)
- **Hover State:** Background `--brand-forest-light` (`#1A3E30`), icon color `--brand-lime` (`#B4F105`)
- **Delete Button (`.delete`):**
  - Default: Subtle red tint on icon
  - Hover: Background `#FEE2E2`, icon color `#DC2626`

---

### 3.6 Pagination & Footer Controls (`.table-footer-control`)
- **Info Label (`.table-pagination-info`):** `Showing 1 to 10 of 50 entries` (Muted 0.825rem)
- **Pagination Component:**
  - Prev Button: Disabled on page 1 with chevron icon (`bi bi-chevron-left`)
  - Active Page Number (`.page-item.active`): Background `--brand-lime` (`#B4F105`), text `--brand-forest-dark` (`#051C12`), font-weight 700
  - Inactive Page Numbers: Borderless buttons with light gray background on hover
  - Next Button: Enabled with chevron icon (`bi bi-chevron-right`)

---

## 4. Dependencies & Script Handlers
- **CSS:** Bootstrap 5.3.8, Bootstrap Icons 1.13.1, `assets/css/main.css`
- **JS:** Bootstrap Bundle JS, `assets/js/dashboard.js`
