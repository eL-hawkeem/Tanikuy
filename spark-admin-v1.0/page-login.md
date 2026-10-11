# Authentication Login Screen Specification (`page-login.md`)

**Template:** Spark Admin  
**Page Title:** Login Screen  
**Original File:** `page-login.html`  
**Layout Model:** Full-viewport Centered Authentication Card with Floating Ambient Glow Shapes  

---

## 1. Page Overview & Purpose

The **Login Screen** is an ultra-modern authentication portal designed with a deep-forest dark canvas, ambient radial glow backdrops, frosted card elevation, interactive password visibility controls, and dual third-party social authentication options (Google & GitHub).

---

## 2. Page Architecture & Wireframe

```
+----------------------------------------------------------------------------------------------------+
| FULL-VIEWPORT LOGIN WRAPPER (.login-wrapper, Background #051C12)                                   |
|                                                                                                    |
|    [Ambient Radial Glow Shape 1: Lime]                     [Ambient Radial Glow Shape 2: Emerald] |
|                                                                                                    |
|                              +---------------------------------------+                             |
|                              | CENTERED AUTHENTICATION CARD          |                             |
|                              | (.login-card)                         |                             |
|                              |                                       |                             |
|                              |       [*] Spark Admin (Brand)         |                             |
|                              |   "Please sign in to access dashboard"|                             |
|                              |                                       |                             |
|                              | Email Address                         |                             |
|                              | [(M) name@company.com               ] |                             |
|                              |                                       |                             |
|                              | Password                              |                             |
|                              | [(L) **********                   (E)]|                             |
|                              |                                       |                             |
|                              | [v] Remember Me      Forgot Password? |                             |
|                              |                                       |                             |
|                              | [     Sign In to Dashboard    (->)   ]|                             |
|                              |                                       |                             |
|                              | ---------- Or sign in with ---------- |                             |
|                              |                                       |                             |
|                              | [ (G) Google ]       [ (H) GitHub ]   |                             |
|                              |                                       |                             |
|                              | Don't have an account? Register Now   |                             |
|                              +---------------------------------------+                             |
|                                                                                                    |
+----------------------------------------------------------------------------------------------------+
```

---

## 3. Component Details & Design Tokens

### 3.1 Atmosphere & Canvas (`.login-wrapper`)
- **Background:** `--brand-forest-dark` (`#051C12`)
- **Min Height:** `100vh`, display `flex`, centered both vertically and horizontally
- **Ambient Glow Shapes (`.login-bg-shape`):**
  - Shape 1 (`.login-bg-shape-1`): Top-left blur orb with radial gradient of `--brand-lime` (`#B4F105`, opacity 0.12), filter `blur(80px)`
  - Shape 2 (`.login-bg-shape-2`): Bottom-right blur orb with radial gradient of `--brand-forest-medium` (`#072F1F`, opacity 0.25), filter `blur(100px)`

---

### 3.2 Authentication Card Container (`.login-card`)
- **Dimensions:** Max-width `460px`, width `100%`, padding `2.5rem 2rem`
- **Surface:** White background (`#FFFFFF`) or frosted glass elevation
- **Border Radius:** `--radius-xxl` (24px)
- **Shadow:** `--shadow-lg` (`0 20px 50px rgba(11, 19, 15, 0.08)`)

---

### 3.3 Brand Header & Subtitle
- **Brand Identity (`.login-brand`):**
  - Icon: 6-pointed rounded asterisk `bi-asterisk` in Electric Lime (`#B4F105`)
  - Typography: 1.5rem, weight 800, `--text-main` (`#0B130F`)
- **Subtitle (`.login-subtitle`):**
  - Copy: `Please sign in to access your dashboard`
  - Typography: 0.875rem, color `--text-muted-green` (`#6C7E75`), margin-bottom 1.75rem

---

### 3.4 Form Fields & Interactive Controls

#### 1. Input Group Wrapper (`.login-input-group`)
- **Border:** 1.5px solid `#E9EFEF`
- **Border Radius:** `--radius-md` (10px)
- **Left Icon Box:** Prefix icon (`bi-envelope` for email, `bi-shield-lock` for password) in muted green (`#6C7E75`)
- **Focus Glow:** Forest green border with 20% lime outline halo

#### 2. Password Toggle Control (`#toggle-password`)
- **Trigger:** Eye icon button (`bi bi-eye` / `bi bi-eye-slash`)
- **Interaction (via `assets/js/auth.js`):** Switches `<input>` type between `password` and `text` on click.

#### 3. Options Bar (`.login-options`)
- **Remember Me Checkbox:** Custom styled checkbox with label (0.825rem)
- **Forgot Password Link:** Right-aligned link in `--brand-forest-medium`, underline on hover

#### 4. Submit Action Button (`.btn-login`)
- **Background:** `--brand-forest-dark` (`#051C12`)
- **Hover State:** `--brand-forest-medium` (`#072F1F`)
- **Text:** White, 0.95rem bold + trailing arrow icon (`bi-arrow-right`)
- **Dimensions:** Full width (100%), padding `0.85rem`

---

### 3.5 Social Authentication Grid & Registration Footer
- **Divider (`.login-divider`):** Horizontal rule with centered text badge: `Or sign in with`
- **Social Grid (`.social-login-grid`):** 2 equal columns:
  - **Google Button (`#btn-google`):** Icon `bi-google` in red + "Google" label
  - **GitHub Button (`#btn-github`):** Icon `bi-github` in dark + "GitHub" label
- **Footer Registration Notice (`.login-footer-text`):**
  - Copy: `Don't have an account?`
  - Action: `Register Now` link in bold forest green

---

## 4. Dependencies & Script Handlers
- **CSS:** Bootstrap 5.3.8, Bootstrap Icons 1.13.1, `assets/css/main.css`
- **JS:** Bootstrap Bundle JS, `assets/js/auth.js`
