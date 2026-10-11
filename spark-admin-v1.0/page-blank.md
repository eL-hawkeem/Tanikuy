# Blank Starter Page Specification (`page-blank.md`)

**Template:** Spark Admin  
**Page Title:** Blank Page (Starter Skeleton)  
**Original File:** `page-blank.html`  
**Layout Model:** Full-height Sidebar Navigation + Top Sticky Navbar + Page Header Banner + Card Content Container + Footer  

---

## 1. Page Overview & Purpose

The **Blank Page** serves as the official boiler-plate starter template for developers extending the Spark Admin template. It contains all global wrappers, navigation bindings, breadcrumb structures, and footer declarations with an empty central content canvas ready for custom modules.

---

## 2. Page Architecture & Wireframe

```
+----------------------------------------------------------------------------------------------------+
| SIDEBAR               | NAVBAR (Search, Quick Actions, Notifications, Profile)                     |
|                       +----------------------------------------------------------------------------+
|                       | PAGE HEADER BANNER                                                         |
|                       | Title: "Blank Page"                                                        |
|                       | Subtitle: "A blank starter template page for custom extensions"            |
|                       | Breadcrumb: Home / Blank Page                                              |
|                       +----------------------------------------------------------------------------+
|                       | EMPTY STATE STARTER CARD (.card .p-4 .border-light .shadow-sm)             |
|                       | +------------------------------------------------------------------------+ |
|                       | |                                                                        | |
|                       | |                     [Large Lime Document Icon]                         | |
|                       | |                                                                        | |
|                       | |                    <h3>Your Content Starts Here</h3>                   | |
|                       | |                                                                        | |
|                       | |       <p>Use this blank page starter template to build custom          | |
|                       | |       dashboard views, tables, forms, or any modules required          | |
|                       | |       for your Spark Admin system.</p>                                 | |
|                       | |                                                                        | |
|                       | +------------------------------------------------------------------------+ |
|                       +----------------------------------------------------------------------------+
|                       | FOOTER COMPONENT: Brand © 2026 Spark Admin                                 |
+----------------------------------------------------------------------------------------------------+
```

---

## 3. Component Details & Design Tokens

### 3.1 Page Header Banner & Breadcrumbs
- **Page Title:** `Blank Page` (1.65rem, weight 700, color `--text-main` `#0B130F`)
- **Subtitle:** `A blank starter template page for custom extensions.`
- **Breadcrumbs:**
  - Item 1: `Home` (`index.html`, text color `--text-muted-green`)
  - Item 2: `Blank Page` (Active item, text color `--text-main`, weight 600)

---

### 3.2 Starter Card Container & Empty State
- **Card Surface:** White background (`#FFFFFF`), border `1px solid #E9EFEF`, shadow `--shadow-sm`, border-radius `--radius-xl` (18px)
- **Empty State Icon (`.empty-state-icon`):**
  - Icon: `bi bi-file-earmark-fill`
  - Color: `--brand-lime` (`#B4F105`)
  - Font Size: `3.5rem` (56px)
  - Margin Bottom: `1.5rem`
- **Heading:** `Your Content Starts Here` (1.35rem, weight 700, `--text-main`)
- **Body Copy:** Muted green description guiding developers where to insert tables, forms, or analytical charts.

---

## 4. Developer Usage Checklist for New Pages
1. Copy `page-blank.md` layout structure.
2. Update the Page Title in `<head>` and `.page-title`.
3. Update Breadcrumb items in `.breadcrumb`.
4. Replace `.empty-state-icon` card with custom components (Tables, Form Cards, ApexCharts widgets).
5. Ensure active menu link class `.active` is set on the corresponding sidebar item.
