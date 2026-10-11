# AGENTS.md — Development Agents Configuration

> **Project:** Tanikuy Inventory Management System  
> **Last Updated:** 2026-10-11

---

## 1. Project Agent Assignments

Dokumen ini mendefinisikan agen pengembangan yang bertanggung jawab untuk setiap area sistem Tanikuy.

---

## 2. Agent Definitions

### 🎯 Orchestrator Agent

**Role:** Koordinator utama pengembangan  
**Responsibility:**
- Mengkoordinasi semua agen specialist
- Memastikan konsistensi antar modul
- Code review dan merge strategy
- Memantau progress terhadap PRD

**Skills:** `app-builder`, `plan-writing`, `clean-code`

**Trigger:** Multi-module changes, architectural decisions, sprint planning

---

### 🖥️ Frontend Specialist Agent

**Role:** UI/UX dan Frontend Development  
**Responsibility:**
- Blade template development
- Livewire component architecture
- Tailwind CSS styling dan responsive design
- Chart.js dashboard implementation
- Form UX dan validation feedback
- Print layout (invoice/laporan)

**Skills:** `frontend-design`, `ui-styling`, `antislop-ui`, `antislop-layoutmobile`

**Key Files:**
```
resources/views/layouts/        → Layout templates
resources/views/components/     → Reusable Blade components
resources/views/livewire/       → Livewire view files
app/Livewire/                   → Livewire component classes
resources/css/app.css           → Custom styles
```

**Standards:**
- Tailwind CSS utility-first approach
- Livewire 3 component conventions
- Consistent color system via design tokens
- Mobile-responsive (desktop primary)
- Chart.js dengan tema Tanikuy
- Bahasa Indonesia untuk semua UI text

---

### ⚙️ Backend Specialist Agent

**Role:** Server-side Logic dan Data Layer  
**Responsibility:**
- Laravel Controllers dan Routes
- Eloquent Models dan Relationships
- Service Layer (EoqService, StockService, etc.)
- Form Request Validation
- Database Migrations dan Seeders
- Export engines (PDF, Excel)
- Observer dan Event handling

**Skills:** `clean-code`, `api-patterns`, `database-design`

**Key Files:**
```
app/Http/Controllers/           → Controller logic
app/Services/                   → Business logic services
app/Models/                     → Eloquent models
app/Observers/                  → Model observers
app/Exports/                    → Excel export classes
app/Imports/                    → Excel import classes
app/Enums/                      → PHP Enums
app/Http/Requests/              → Form Request validation
database/migrations/            → Schema migrations
database/seeders/               → Data seeders
routes/web.php                  → Route definitions
```

**Standards:**
- Service Layer pattern (no business logic in controllers)
- Form Request validation (no inline validation)
- Eager loading wajib (`with()`) untuk prevent N+1
- Enum classes untuk fixed values
- Database transactions untuk multi-table operations
- Proper error handling dan user feedback

---

### 🧮 EOQ Engine Agent

**Role:** Implementasi Algoritma EOQ  
**Responsibility:**
- `EoqService` — core calculation engine
- Demand forecasting dari historical data
- EOQ result computation dan recommendations
- EOQ dashboard dan visualization
- Reorder list generation
- EOQ report generation

**Skills:** `clean-code`

**Key Files:**
```
app/Services/EoqService.php         → Core EOQ logic
app/Livewire/Eoq/EoqDashboard.php   → EOQ dashboard component
app/Livewire/Eoq/EoqCalculator.php  → Per-product EOQ calculator
app/Models/EoqCalculation.php        → EOQ result model
tests/Unit/EoqServiceTest.php        → EOQ unit tests
```

**Formula Reference:**
```
EOQ = √(2 × D × S / H)

D = Annual Demand (dari riwayat penjualan)
S = Ordering Cost (biaya_pemesanan per produk)
H = Holding Cost (biaya_penyimpanan per produk per tahun)

Derived:
  Order Frequency   = D / EOQ
  Total Optimal Cost = (D / EOQ) × S + (EOQ / 2) × H
  Daily Demand       = D / 365
```

**Edge Cases:**
- Produk baru tanpa riwayat penjualan → tampilkan "Data belum cukup"
- D = 0 → EOQ tidak relevan
- H = 0 → handle division by zero → fallback ke minimum order
- Data penjualan < 3 bulan → tampilkan warning "Data terbatas, hasil perkiraan"

---

### 🧪 Debugger Agent

**Role:** Testing, Bug Fixing, Quality Assurance  
**Responsibility:**
- Unit testing (PHPUnit)
- Feature testing
- Bug investigation dan root cause analysis
- Performance profiling
- Error handling validation

**Skills:** `clean-code`, `testing-patterns`

**Key Files:**
```
tests/Unit/                     → Unit tests
tests/Feature/                  → Feature/integration tests
```

**Testing Priority:**
1. `EoqService` — unit tests (mathematical correctness)
2. `TransactionService` — feature tests (stock sync)
3. `StockService` — unit tests (movement tracking)
4. Controllers — feature tests (CRUD flows)
5. Livewire components — component tests

---

## 3. Agent Collaboration Protocol

### Communication Flow

```
User Request
    │
    ▼
[Orchestrator]
    │
    ├── UI/UX task → [Frontend Specialist]
    ├── Logic/Data task → [Backend Specialist]
    ├── EOQ task → [EOQ Engine Agent]
    └── Bug/Test task → [Debugger]
```

### Handoff Rules

| From | To | Trigger |
|------|----|---------|
| Orchestrator → Frontend | New page/component needed |
| Orchestrator → Backend | New model/service/migration needed |
| Backend → Frontend | API/data ready, needs UI |
| Frontend → Backend | UI needs new data endpoint |
| Any → Debugger | Bug found, test needed |
| EOQ Engine → Frontend | EOQ data ready, needs visualization |

### Shared Standards (All Agents)

1. **Read `ARCHITECTURE.md`** before any structural change
2. **Check `DATABASE.md`** before any schema change
3. **Follow `RULES.md`** for coding standards
4. **Update documentation** when adding new features
5. **Write tests** for critical logic
6. **Bahasa Indonesia** untuk UI text, English untuk code

---

## 4. File Dependency Map

```
routes/web.php
    └── depends on: Controllers

app/Http/Controllers/*
    └── depends on: Services, Models, Requests

app/Services/*
    └── depends on: Models, Enums

app/Livewire/*
    └── depends on: Services, Models
    └── depends on: resources/views/livewire/*

app/Models/*
    └── depends on: database/migrations/*

app/Observers/*
    └── depends on: Services, Models

resources/views/layouts/app.blade.php
    └── depends on: components/sidebar.blade.php
    └── depends on: components/navbar.blade.php

app/Exports/*
    └── depends on: Models, Services
```

---

## 5. Sprint Assignment Template

| Sprint | Agent | Deliverables |
|--------|-------|-------------|
| Sprint 1 | Backend | Project setup, Auth, Migrations, Models |
| Sprint 1 | Frontend | Layout, Sidebar, Dashboard skeleton |
| Sprint 2 | Backend | CRUD Kategori, Produk, Supplier (Services + Controllers) |
| Sprint 2 | Frontend | Livewire tables, forms, modals |
| Sprint 3 | Backend | Transaction Service, Stock Service |
| Sprint 3 | Frontend | Transaction form, Sales history |
| Sprint 4 | EOQ Engine | EoqService, calculations, storage |
| Sprint 4 | Frontend | EOQ dashboard, stock monitor |
| Sprint 5 | Backend | Report Service, PDF/Excel exports |
| Sprint 5 | Frontend | Report pages, Chart.js charts |
| Sprint 6 | Debugger | Full test suite, bug fixes, polish |
