# Error 404 Page Specification (`page-404.md`)

**Template:** Spark Admin  
**Page Title:** 404 Page Not Found  
**Original File:** `page-404.html`  
**Layout Model:** Full-viewport Centered Card with Giant Typographic Error Hero and Ambient Glow Shapes  

---

## 1. Page Overview & Purpose

The **404 Page Not Found** provides an elegant, theme-consistent fallback route when users encounter missing resources, invalid routes, or broken navigation links. It utilizes large custom typography, an animated asterisk emblem as the zero digit, and a direct call-to-action button to return to the dashboard.

---

## 2. Page Architecture & Wireframe

```
+----------------------------------------------------------------------------------------------------+
| FULL-VIEWPORT ERROR CANVAS (.login-wrapper, Background #051C12)                                    |
|                                                                                                    |
|    [Ambient Radial Glow Shape 1: Lime]                     [Ambient Radial Glow Shape 2: Emerald] |
|                                                                                                    |
|                              +---------------------------------------+                             |
|                              | CENTERED ERROR CARD (.login-card)     |                             |
|                              |                                       |                             |
|                              |       [*] Spark Admin (Brand)         |                             |
|                              |                                       |                             |
|                              |             4  [*]  4                 |                             |
|                              |     (Giant Error Typography Hero)     |                             |
|                              |                                       |                             |
|                              |           Page Not Found              |                             |
|                              |                                       |                             |
|                              |  "The page you are looking for might   |                             |
|                              |   have been removed, had its name     |                             |
|                              |   changed, or is unavailable."        |                             |
|                              |                                       |                             |
|                              |      [ (H) Back to Dashboard ]        |                             |
|                              +---------------------------------------+                             |
|                                                                                                    |
+----------------------------------------------------------------------------------------------------+
```

---

## 3. Component Details & Design Tokens

### 3.1 Atmosphere & Canvas
- **Background:** `--brand-forest-dark` (`#051C12`)
- **Min Height:** `100vh`, display `flex`, centered both vertically and horizontally
- **Ambient Glow Background Shapes:** Matching the authentication screen with blurred radial orbs (`.login-bg-shape-1` in lime and `.login-bg-shape-2` in emerald).

---

### 3.2 Error Card & Typographic Hero

#### 1. Centered Card (`.login-card`)
- Max-width: `500px`, padding `3rem 2rem`, border-radius `--radius-xxl` (24px)
- White background (`#FFFFFF`), shadow `--shadow-lg`

#### 2. Giant 404 Code Display (`.error-title-huge`)
- **Number Glyphs:** `font-size: 6.5rem - 7.5rem`, font-weight `900`, color `--brand-forest-dark` (`#051C12`), letter-spacing -0.05em
- **Center Asterisk Zero:** Icon `bi bi-asterisk` in Electric Lime (`#B4F105`), animated with a slow continuous rotation or soft pulsing glow
- **Subtitle (`.error-subtitle`):** `Page Not Found` (1.5rem, weight 700, `--text-main`)
- **Description (`.error-desc`):** Muted paragraph text (0.9rem, `--text-muted-green`, max-width 380px centered)

---

### 3.3 Return CTA Action Button
- **Class:** `.btn-custom.btn-custom-primary`
- **Icon:** `bi bi-house`
- **Label:** `Back to Dashboard`
- **Target Route:** `index.html` (or `index.md`)

---

## 4. Dependencies & Script Handlers
- **CSS:** Bootstrap 5.3.8, Bootstrap Icons 1.13.1, `assets/css/main.css`
- **JS:** Bootstrap Bundle JS
