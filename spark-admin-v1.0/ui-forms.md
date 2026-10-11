# Forms & Inputs Specification (`ui-forms.md`)

**Template:** Spark Admin  
**Page Title:** Forms & Input  
**Original File:** `ui-forms.html`  
**Layout Model:** Full-height Sidebar + Sticky Navbar + Breadcrumb Banner + Multi-Card Form Grid  

---

## 1. Page Overview & Purpose

The **Forms & Input** page establishes the comprehensive data-entry design standard for the Spark Admin template. It standardizes basic text fields, custom select dropdowns, multi-line text areas, input groups, sizing tiers, validation feedback messaging, checkboxes, radio buttons, and iOS-style toggle switches.

---

## 2. Page Architecture & Wireframe

```
+----------------------------------------------------------------------------------------------------+
| SIDEBAR               | NAVBAR (Search, Quick Actions, Notifications, Profile)                     |
|                       +----------------------------------------------------------------------------+
|                       | PAGE HEADER BANNER                                                         |
|                       | Title: "Forms & Input"                                                     |
|                       | Subtitle: "Customized input controls, floating labels & form validation"   |
|                       | Breadcrumb: Home / Components / Forms & Input                              |
|                       +----------------------------------------------------------------------------+
|                       | FORM CONTROLS GRID ROW 1 (row g-4)                                         |
|                       | +-----------------------------------+ +----------------------------------+ |
|                       | | CARD 1: BASIC FIELDS              | | CARD 2: SELECTION & VALIDATION   | |
|                       | | - Username input                  | | - Custom Select dropdown         | |
|                       | | - Email address + helper text     | | - Textarea input                 | |
|                       | | - Password input                  | | - Valid input (Green checkmark)  | |
|                       | | - Disabled input field            | | - Invalid input (Red alert icon) | |
|                       | | - Read-only input field           | |                                  | |
|                       | +-----------------------------------+ +----------------------------------+ |
|                       | FORM CONTROLS GRID ROW 2 (row g-4)                                         |
|                       | +-----------------------------------+ +----------------------------------+ |
|                       | | CARD 3: SIZING & INPUT GROUPS     | | CARD 4: CHECKS, RADIOS & SWITCH  | |
|                       | | - Large, Default, Small inputs    | | - Custom Checkboxes (Active/Dis) | |
|                       | | - Prepend icon group (@username)  | | - Radio Buttons (Group 1, 2)     | |
|                       | | - Append input group              | | - Toggle Switches (On/Off)       | |
|                       | | - Dual addon group ($ ... .00)    | |                                  | |
|                       | +-----------------------------------+ +----------------------------------+ |
|                       +----------------------------------------------------------------------------+
|                       | FOOTER COMPONENT: Brand © 2026 Spark Admin                                 |
+----------------------------------------------------------------------------------------------------+
```

---

## 3. Component Details & Design Tokens

### 3.1 Input Styling Foundations (`.form-control-custom`)
- **Background:** Crisp White (`#FFFFFF`)
- **Border:** 1px solid `--border-light` (`#E9EFEF`)
- **Border Radius:** `--radius-md` (10px)
- **Padding:** `0.65rem 1rem`
- **Typography:** 0.875rem, font-weight 500, color `--text-main` (`#0B130F`)
- **Placeholder:** Color `--text-muted-green` (`#6C7E75`), opacity 0.7
- **Focus State:**
  - Border Color: `--brand-forest-medium` (`#072F1F`)
  - Box Shadow: `0 0 0 3px rgba(180, 241, 5, 0.2)` (Soft electric lime glow)
- **Disabled State:** Background `#F8FAFC`, border `#E2E8F0`, cursor `not-allowed`, opacity 0.65

---

### 3.2 Form Labels & Helper Text
- **Label (`.form-label-custom`):**
  - Font Size: `0.825rem` (13.2px)
  - Font Weight: `600`
  - Color: `--text-main` (`#0B130F`)
  - Margin Bottom: `0.4rem`
- **Helper Note (`.form-text` / `.text-muted`):**
  - Font Size: `0.775rem`
  - Color: `--text-muted-green` (`#6C7E75`)

---

### 3.3 Form Validation States & Feedback Badges

#### Valid State (`.is-valid-custom`)
- **Border Color:** `--sys-green` (`#22C55E`)
- **Feedback Message (`.form-feedback-custom.valid-custom`):**
  - Text Color: `#16A34A`
  - Icon: `bi bi-check-circle-fill`
  - Example: `Username is available!`

#### Invalid State (`.is-invalid-custom`)
- **Border Color:** `--sys-red` (`#EF4444`)
- **Feedback Message (`.form-feedback-custom.invalid-custom`):**
  - Text Color: `#DC2626`
  - Icon: `bi bi-exclamation-circle-fill`
  - Example: `Please enter a valid email address.`

---

### 3.4 Sizing & Input Groups (`.input-group-custom`)

#### Sizing Tiers
- **Large Input (`.form-control-custom-lg`):** Padding `0.85rem 1.25rem`, font-size `1.0rem`, radius `12px`
- **Default Input:** Padding `0.65rem 1.0rem`, font-size `0.875rem`, radius `10px`
- **Small Input (`.form-control-custom-sm`):** Padding `0.45rem 0.75rem`, font-size `0.775rem`, radius `8px`

#### Input Groups
- **Addon Container (`.input-group-text-custom`):**
  - Background: `#F8FAFC`
  - Border: 1px solid `#E9EFEF`
  - Color: `--text-muted-green` (`#6C7E75`)
  - Integration: Glueless corner fusion (`border-radius` only on outer edges)

---

### 3.5 Checkboxes, Radios & Switches

#### 1. Custom Checkboxes (`.custom-checkbox-input`)
- 18px x 18px rounded square (4px radius)
- Unchecked: White background, 1.5px solid `#CBD5E1`
- Checked: Background `--brand-forest-medium` (`#072F1F`), checkmark in `--brand-lime` (`#B4F105`)

#### 2. Custom Radio Buttons (`.custom-radio-input`)
- 18px x 18px circular container
- Checked: 2px solid `--brand-forest-medium`, inner dot in `--brand-lime`

#### 3. Toggle Switches (`.form-switch-custom`)
- Width: `42px`, Height: `22px`, fully rounded pill (`border-radius: 50px`)
- Off State: Background `#E2E8F0`, white circular knob on the left
- On State: Background `--brand-forest-medium` (`#072F1F`), knob slides right with electric lime highlight

---

## 4. Dependencies & Script Handlers
- **CSS:** Bootstrap 5.3.8, Bootstrap Icons 1.13.1, `assets/css/main.css`
- **JS:** Bootstrap Bundle JS, `assets/js/dashboard.js`
