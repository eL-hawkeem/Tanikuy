# Buttons & Alerts Specification (`ui-buttons.md`)

**Template:** Spark Admin  
**Page Title:** Buttons & Alerts  
**Original File:** `ui-buttons.html`  
**Layout Model:** Full-height Sidebar + Sticky Navbar + Breadcrumb Banner + Two-Column Component Showcase Card Grid  

---

## 1. Page Overview & Purpose

The **Buttons & Alerts** page showcases the core interactive elements and notification feedback patterns used across the Spark Admin design system. It details solid color buttons, outline buttons, icon-enhanced triggers, button scale tiers, and contextual notification alert banners with dismissal controls.

---

## 2. Page Architecture & Wireframe

```
+----------------------------------------------------------------------------------------------------+
| SIDEBAR               | NAVBAR (Search, Quick Actions, Notifications, Profile)                     |
|                       +----------------------------------------------------------------------------+
|                       | PAGE HEADER BANNER                                                         |
|                       | Title: "Buttons & Alerts"                                                  |
|                       | Subtitle: "Reusable interactive triggers and notification banners"         |
|                       | Breadcrumb: Home / Components / Buttons & Alerts                           |
|                       +----------------------------------------------------------------------------+
|                       | TWO-COLUMN COMPONENT SHOWCASE (row g-4)                                    |
|                       | +------------------------------------+ +---------------------------------+ |
|                       | | COLUMN 1: BUTTONS GALLERY          | | COLUMN 2: NOTIFICATION ALERTS   | |
|                       | | 1. Solid Color Buttons:            | | 1. Primary Alert                | |
|                       | |    [Primary] [Secondary] [Light]   | |    [Info Icon] Custom primary.. | |
|                       | |    [Danger]  [Warning]             | | 2. Success Alert (Dismissible)  | |
|                       | | 2. Outline Buttons:                | |    [Check Icon] Sync complete [X| |
|                       | |    [Outline Pri] [Outline Sec]...  | | 3. Danger Alert (Dismissible)   | |
|                       | | 3. Buttons with Icons:             | |    [Exclamation] Error alert [X]| |
|                       | |    [(Q) Search] [(\/) Download]... | | 4. Warning Alert                | |
|                       | | 4. Button Sizing:                  | |    [Warning Icon] DB Notice...  | |
|                       | |    [Large Action] [Default] [Small]| | 5. Info Alert (Tip Accent)      | |
|                       | +------------------------------------+ +---------------------------------+ |
|                       +----------------------------------------------------------------------------+
|                       | FOOTER COMPONENT: Brand © 2026 Spark Admin                                 |
+----------------------------------------------------------------------------------------------------+
```

---

## 3. Component Details & Design Tokens

### 3.1 Button Gallery Components (`.btn-custom`)

#### Base Button Rules
- **Border Radius:** `--radius-md` (10px)
- **Typography:** 0.875rem font size, font-weight 600, letter-spacing -0.01em
- **Transition:** `all 0.2s ease-in-out`
- **Focus Ring:** 3px soft outer ring with 20% opacity

#### 1. Solid Button Variants

| Button Class | Background Color | Text Color | Hover State | Purpose |
| :--- | :--- | :--- | :--- | :--- |
| `.btn-custom-primary` | `--brand-forest-dark` (`#051C12`) | `#FFFFFF` | Background `--brand-forest-medium` (`#072F1F`) | Primary user actions, form submits |
| `.btn-custom-lime` | `--brand-lime` (`#B4F105`) | `--brand-forest-dark` (`#051C12`) | Background `#C1F824` | Key highlight actions, CTAs |
| `.btn-custom-secondary` | `#E2E8F0` (Muted Slate) | `--text-main` (`#0B130F`) | Background `#CBD5E1` | Secondary actions, cancellations |
| `.btn-custom-light` | `#FFFFFF` (White) | `--text-main` (`#0B130F`) | Background `#F1F5F9`, border `#E2E8F0` | Subtle background toolbars |
| `.btn-custom-danger` | `--sys-red` (`#EF4444`) | `#FFFFFF` | Background `#DC2626` | Destructive actions, delete records |
| `.btn-custom-warning` | `--sys-orange` (`#F97316`) | `#FFFFFF` | Background `#EA580C` | Warning triggers, alert confirmations |

#### 2. Outline Button Variants

| Button Class | Border Color | Text Color | Hover State |
| :--- | :--- | :--- | :--- |
| `.btn-custom-outline-primary` | 1.5px solid `--brand-forest-dark` | `--brand-forest-dark` | Inverts: background `#051C12`, text `#FFFFFF` |
| `.btn-custom-outline-secondary`| 1.5px solid `#CBD5E1` | `--text-muted-green` | Background `#F8FAFC`, text `--text-main` |
| `.btn-custom-outline-danger` | 1.5px solid `--sys-red` | `--sys-red` | Inverts: background `#EF4444`, text `#FFFFFF` |

#### 3. Buttons with Icons
Buttons integrate with Bootstrap Icons (`bi-*`) with automatic gap spacing (`gap-2`):
- `btn-custom-primary` + `bi-search`: Search triggers
- `btn-custom-secondary` + `bi-file-earmark-arrow-down`: Download reports / export files
- `btn-custom-light` + `bi-gear`: Settings & preferences

#### 4. Button Scale Tiers

| Sizing Tier | CSS Class | Padding | Font Size | Border Radius |
| :--- | :--- | :--- | :--- | :--- |
| **Large** | `.btn-custom-lg` | `0.75rem 1.5rem` | `1.0rem` (16px) | `12px` |
| **Default** | `.btn-custom` | `0.55rem 1.15rem` | `0.875rem` (14px) | `10px` |
| **Small** | `.btn-custom-sm` | `0.35rem 0.75rem` | `0.775rem` (12.4px) | `8px` |

---

### 3.2 Notification Alerts (`.alert-custom`)

#### Base Alert Architecture
- **Border Radius:** `--radius-lg` (14px)
- **Padding:** `1rem 1.25rem`
- **Flex Layout:** Left icon box (`.alert-custom-icon`) + Body text (`.alert-custom-content`) + Optional dismissal trigger (`.alert-custom-close`)
- **Icon Alignment:** Centered vertically with `font-size: 1.25rem`

#### Alert Variants & Color Tokens

| Alert Variant Class | Background Tint | Border Color | Icon & Title Color | Example Context |
| :--- | :--- | :--- | :--- | :--- |
| `.alert-custom-primary` | `#E8F5E9` (Soft Forest Mint) | `#C8E6C9` | `#072F1F` (Forest Dark) | General system notifications |
| `.alert-custom-success` | `#DCFCE7` (Pale Green) | `#BBF7D0` | `#16A34A` (Emerald Green)| Data synchronization successful |
| `.alert-custom-danger` | `#FEE2E2` (Pale Rose Red) | `#FECACA` | `#DC2626` (Crimson Red) | Subscription payment errors |
| `.alert-custom-warning` | `#FFEDD5` (Soft Apricot) | `#FED7AA` | `#EA580C` (Deep Orange) | Database connection notices |
| `.alert-custom-info` | `#E0F2FE` (Soft Sky Cyan) | `#BAE6FD` | `#0284C7` (Sky Blue) | Quick tips, navigational guidance |

#### Dismissal Control (`.alert-custom-close`)
- Icon: `bi bi-x-lg`
- Dimensions: 24px x 24px clickable target
- Action: Dismisses/removes the alert node from DOM with smooth transition.

---

## 4. Dependencies & Script Handlers
- **CSS:** Bootstrap 5.3.8, Bootstrap Icons 1.13.1, `assets/css/main.css`
- **JS:** Bootstrap Bundle JS, `assets/js/dashboard.js`
