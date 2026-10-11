# RULES.md — Tanikuy Development Rules & Standards

> **Project:** Tanikuy Inventory Management System  
> **Stack:** Laravel 11 + Livewire 3 + Tailwind CSS + MySQL  
> **Last Updated:** 2026-10-11

---

## 1. General Coding Standards

### 1.1 Language & Naming

| Context | Language | Convention |
|---------|----------|------------|
| UI Text / Labels | Bahasa Indonesia | - |
| Code (variables, functions, classes) | English | camelCase / PascalCase |
| Database columns | Bahasa Indonesia (snake_case) | `nama_barang`, `stok_saat_ini` |
| Comments | English (concise) | Only for non-obvious logic |
| Git commits | English | Conventional Commits |

### 1.2 PHP / Laravel Conventions

```
Classes         → PascalCase       → ProductController, EoqService
Methods         → camelCase        → calculateEoq(), getStockStatus()
Variables       → camelCase        → $annualDemand, $orderFrequency
Constants       → UPPER_SNAKE      → MAX_UPLOAD_SIZE
Config keys     → snake_case       → 'app.items_per_page'
Route names     → dot.notation     → 'products.index', 'reports.sales'
Views           → kebab-case dirs  → 'products/create', 'stock/monitor'
Migrations      → snake_case       → create_products_table
```

### 1.3 File Size Limits

| File Type | Max Lines | Action if exceeded |
|-----------|-----------|--------------------|
| Controller | 150 | Extract to Service |
| Service | 200 | Split into focused services |
| Livewire Component | 150 | Extract sub-components |
| Blade View | 200 | Extract partials/components |
| Model | 100 | Use Traits for heavy logic |

---

## 2. Laravel-Specific Rules

### 2.1 Controller Rules

```php
// ✅ CORRECT — Controller delegates to Service
public function store(ProductRequest $request)
{
    $product = $this->productService->create($request->validated());
    return redirect()->route('products.index')
        ->with('success', 'Produk berhasil ditambahkan.');
}

// ❌ WRONG — Business logic in Controller
public function store(Request $request)
{
    $validated = $request->validate([...]);
    $product = new Product();
    $product->kode_barang = 'TK-' . str_pad(Product::count() + 1, 4, '0', STR_PAD_LEFT);
    // ... 50 more lines of logic
}
```

**Rules:**
- Controllers MUST NOT contain business logic
- Use Form Request classes for validation
- Return redirects with flash messages (Bahasa Indonesia)
- Resource controllers for CRUD operations
- Max 7 methods per controller (REST standard)

### 2.2 Model Rules

```php
// ✅ CORRECT
class Product extends Model
{
    protected $fillable = [
        'kode_barang', 'nama_barang', 'kategori_id', /* ... */
    ];

    protected $casts = [
        'satuan' => ProductUnit::class,
        'status' => ProductStatus::class,
        'harga_beli' => 'decimal:2',
        'harga_jual' => 'decimal:2',
    ];

    // Relationships
    public function kategori(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'kategori_id');
    }

    // Scopes
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', 'active');
    }

    public function scopeLowStock(Builder $query): Builder
    {
        return $query->whereColumn('stok_saat_ini', '<=', 'stok_minimum');
    }
}
```

**Rules:**
- Always define `$fillable` (never use `$guarded = []`)
- Use `$casts` for type casting
- Define return types on relationships
- Use scopes for reusable query logic
- No business logic in models (use Services)

### 2.3 Service Layer Rules

```php
// ✅ CORRECT — Focused, testable service
class StockService
{
    public function reduceStock(Product $product, int $quantity, string $reference): void
    {
        DB::transaction(function () use ($product, $quantity, $reference) {
            $stockBefore = $product->stok_saat_ini;

            $product->decrement('stok_saat_ini', $quantity);

            StockMovement::create([
                'product_id' => $product->id,
                'tipe' => StockMovementType::OUT,
                'jumlah' => $quantity,
                'stok_sebelum' => $stockBefore,
                'stok_sesudah' => $stockBefore - $quantity,
                'referensi' => $reference,
            ]);
        });
    }
}
```

**Rules:**
- One service per domain (StockService, EoqService, etc.)
- Use DB transactions for multi-model operations
- Throw specific exceptions for error cases
- Injectable via constructor (dependency injection)
- All public methods must be testable

### 2.4 Livewire Component Rules

```php
// ✅ CORRECT
class ProductTable extends Component
{
    public string $search = '';
    public string $kategoriFilter = '';
    public string $sortBy = 'nama_barang';
    public string $sortDir = 'asc';

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function render(): View
    {
        return view('livewire.products.product-table', [
            'products' => Product::query()
                ->with(['kategori', 'supplier'])
                ->when($this->search, fn ($q) => $q->where('nama_barang', 'like', "%{$this->search}%"))
                ->when($this->kategoriFilter, fn ($q) => $q->where('kategori_id', $this->kategoriFilter))
                ->orderBy($this->sortBy, $this->sortDir)
                ->paginate(15),
        ]);
    }
}
```

**Rules:**
- Typed properties with defaults
- Use `$this->resetPage()` when filters change
- Eager load relationships in queries
- Render method returns computed data (no side effects)
- Use Livewire events for cross-component communication

### 2.5 Validation Rules

```php
// ✅ CORRECT — Form Request
class ProductRequest extends FormRequest
{
    public function rules(): array
    {
        $productId = $this->route('product')?->id;

        return [
            'nama_barang' => ['required', 'string', 'max:200'],
            'kode_barang' => ['required', 'string', 'max:20', Rule::unique('products')->ignore($productId)],
            'kategori_id' => ['required', 'exists:categories,id'],
            'supplier_id' => ['nullable', 'exists:suppliers,id'],
            'satuan' => ['required', Rule::enum(ProductUnit::class)],
            'harga_beli' => ['required', 'numeric', 'min:0'],
            'harga_jual' => ['required', 'numeric', 'min:0', 'gte:harga_beli'],
            'stok_saat_ini' => ['required', 'integer', 'min:0'],
            'stok_minimum' => ['required', 'integer', 'min:0'],
            'biaya_pemesanan' => ['required', 'numeric', 'min:0'],
            'biaya_penyimpanan' => ['required', 'numeric', 'min:0'],
            'gambar' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ];
    }

    public function messages(): array
    {
        return [
            'nama_barang.required' => 'Nama barang wajib diisi.',
            'harga_jual.gte' => 'Harga jual harus lebih besar atau sama dengan harga beli.',
            'gambar.max' => 'Ukuran gambar maksimal 2MB.',
        ];
    }
}
```

**Rules:**
- Always use Form Request classes
- Custom error messages in Bahasa Indonesia
- Use `Rule::unique()->ignore()` for updates
- Use `Rule::enum()` for enum validation
- Validate FK existence with `exists:`

### 2.6 Route Rules

```php
// ✅ CORRECT
Route::middleware('auth')->group(function () {
    Route::get('/', DashboardController::class)->name('dashboard');

    Route::resource('products', ProductController::class);
    Route::resource('categories', CategoryController::class);
    Route::resource('suppliers', SupplierController::class);
    Route::resource('transactions', TransactionController::class)->only(['index', 'create', 'store', 'show']);
    Route::post('transactions/{transaction}/cancel', [TransactionController::class, 'cancel'])->name('transactions.cancel');

    Route::prefix('stock')->name('stock.')->group(function () {
        Route::get('/', [StockController::class, 'index'])->name('index');
        Route::get('/movements', [StockController::class, 'movements'])->name('movements');
        Route::post('/adjust', [StockController::class, 'adjust'])->name('adjust');
    });

    Route::prefix('eoq')->name('eoq.')->group(function () {
        Route::get('/', [EoqController::class, 'index'])->name('index');
        Route::get('/calculate/{product}', [EoqController::class, 'calculate'])->name('calculate');
        Route::get('/reorder-list', [EoqController::class, 'reorderList'])->name('reorder-list');
    });

    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/sales', [ReportController::class, 'sales'])->name('sales');
        Route::get('/stock', [ReportController::class, 'stock'])->name('stock');
        Route::get('/profit', [ReportController::class, 'profit'])->name('profit');
        Route::get('/eoq', [ReportController::class, 'eoq'])->name('eoq');
        Route::get('/export/{type}', [ReportController::class, 'export'])->name('export');
    });
});
```

---

## 3. Database Rules

### 3.1 Migration Rules

- Always define `down()` method for rollback
- Use `decimal(15,2)` for monetary values (Rupiah)
- Add indexes on: foreign keys, search columns, sort columns, status columns
- Use enum columns sparingly (prefer PHP Enums + string columns for flexibility)
- Add comments on complex columns

### 3.2 Query Rules

```php
// ✅ CORRECT — Eager loading
$products = Product::with(['kategori', 'supplier'])->paginate(15);

// ❌ WRONG — N+1 problem
$products = Product::paginate(15);
// then in blade: $product->kategori->nama  ← triggers N queries
```

- Always use `with()` for relationships displayed in views
- Use `select()` to limit columns on heavy queries
- Use `withCount()` instead of loading full relations for counts
- Paginate all list queries (15-25 per page)
- Use query scopes for reusable filters

---

## 4. Frontend Rules

### 4.1 Blade Template Rules

```blade
{{-- ✅ CORRECT — Named slots, semantic HTML --}}
<x-layouts.app>
    <x-slot:header>
        <h1 class="text-2xl font-bold text-gray-900">Kelola Barang</h1>
    </x-slot:header>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <livewire:products.product-table />
    </div>
</x-layouts.app>
```

**Rules:**
- Use Blade components (`x-`) for reusable UI elements
- Layout via component inheritance (`x-layouts.app`)
- All user-facing text in Bahasa Indonesia
- Use Livewire for interactive sections (tables, forms, charts)
- Static content stays in Blade (no unnecessary Livewire)

### 4.2 Tailwind CSS Rules

**Color Palette (Tanikuy Brand):**

| Token | Use Case | Tailwind Class |
|-------|----------|----------------|
| Primary | Buttons, links, accents | `emerald-600` → `#059669` |
| Primary Dark | Hover states | `emerald-700` → `#047857` |
| Secondary | Secondary actions | `amber-500` → `#F59E0B` |
| Success | Success states | `green-500` |
| Warning | Warning states | `amber-500` |
| Danger | Error/delete states | `red-500` |
| Neutral | Text, borders | `gray-*` scale |
| Background | Page bg | `gray-50` |
| Surface | Cards, panels | `white` |

**Rules:**
- Consistent spacing: `p-4`, `p-6`, `gap-4`, `gap-6`
- Border radius: `rounded-lg` (cards), `rounded-md` (inputs), `rounded-xl` (panels)
- Shadow: `shadow-sm` (cards), `shadow-md` (modals), `shadow-lg` (dropdowns)
- Font sizes: Use Tailwind scale (`text-sm`, `text-base`, `text-lg`)
- No custom CSS unless absolutely necessary

### 4.3 Component Library

| Component | Implementation | Use Case |
|-----------|---------------|----------|
| Data Table | Livewire + Blade | Product list, transaction history |
| Form | Livewire + Blade | CRUD forms |
| Modal | Livewire + Alpine.js | Confirm delete, quick view |
| Stat Card | Blade component | Dashboard statistics |
| Chart | Chart.js + Livewire | Sales chart, stock chart |
| Alert/Toast | Blade + Alpine.js | Flash messages |
| Badge | Blade component | Status indicators |
| Pagination | Livewire built-in | Table pagination |

---

## 5. Number & Currency Formatting

### Indonesian Rupiah (IDR)

```php
// Helper function (app/Helpers/format.php)
function formatRupiah(float $amount): string
{
    return 'Rp ' . number_format($amount, 0, ',', '.');
}

// Usage in Blade
{{ formatRupiah($product->harga_jual) }}
// Output: Rp 150.000
```

### Date Formatting

```php
// Indonesian date format
$tanggal->translatedFormat('d F Y');   // 11 Oktober 2026
$tanggal->translatedFormat('d/m/Y');   // 11/10/2026
$tanggal->format('H:i');              // 14:30
```

Set locale in `config/app.php`:
```php
'locale' => 'id',
'faker_locale' => 'id_ID',
```

---

## 6. Error Handling Rules

### User-Facing Errors (Bahasa Indonesia)

```php
// Flash messages
return redirect()->back()->with('success', 'Produk berhasil disimpan.');
return redirect()->back()->with('error', 'Gagal menyimpan produk. Silakan coba lagi.');
return redirect()->back()->with('warning', 'Stok produk ini sudah menipis.');
```

### Exception Handling

```php
// Custom exceptions
class InsufficientStockException extends Exception
{
    public function __construct(Product $product, int $requested)
    {
        parent::__construct(
            "Stok {$product->nama_barang} tidak mencukupi. " .
            "Tersedia: {$product->stok_saat_ini}, Diminta: {$requested}"
        );
    }
}
```

---

## 7. Git Workflow Rules

### Branch Strategy

```
main            → Production-ready code
develop         → Integration branch
feature/*       → New features (feature/add-eoq-dashboard)
fix/*           → Bug fixes (fix/stock-calculation-error)
docs/*          → Documentation updates
```

### Commit Messages (Conventional Commits)

```
feat: add EOQ calculation service
fix: correct stock reduction on transaction cancel
docs: update DATABASE.md with new indexes
style: format ProductController
refactor: extract stock validation to StockService
test: add unit tests for EoqService
chore: update composer dependencies
```

### PR Rules

- Feature branch → `develop` via Pull Request
- At least 1 review before merge
- All tests must pass
- No merge conflicts

---

## 8. Security Rules

| Rule | Implementation |
|------|----------------|
| CSRF Protection | `@csrf` in all forms, automatic via Livewire |
| XSS Prevention | Always use `{{ }}` (escaped), never `{!! !!}` unless HTML is sanitized |
| SQL Injection | Eloquent ORM only, no raw queries with user input |
| Mass Assignment | `$fillable` whitelist on all models |
| File Upload | Validate type + size, store outside public |
| Auth | Middleware `auth` on all routes except login |
| Passwords | bcrypt hashing (Laravel default) |

---

## 9. Testing Rules

### Test Naming

```php
// Pattern: test_{action}_{scenario}_{expected_result}
public function test_calculate_eoq_with_valid_data_returns_correct_result(): void
public function test_reduce_stock_below_zero_throws_exception(): void
public function test_create_transaction_reduces_product_stock(): void
```

### Test Priority

| Priority | Area | Type |
|----------|------|------|
| P0 | EoqService calculations | Unit |
| P0 | Stock reduction/restoration | Unit + Feature |
| P1 | Transaction CRUD flow | Feature |
| P1 | Product/Supplier CRUD | Feature |
| P2 | Report generation | Feature |
| P2 | Livewire component rendering | Component |

### Required Test Coverage

- Every Service class method → Unit test
- Every Controller CRUD action → Feature test
- EOQ formula → Mathematical accuracy test
- Stock operations → Integrity tests

---

## 10. Performance Rules

| Area | Rule | Threshold |
|------|------|-----------|
| Page Load | Max time | < 2 seconds |
| Query Count | Max per page | < 20 queries |
| N+1 Queries | Zero tolerance | Use `with()` always |
| Pagination | Required for lists | 15-25 items per page |
| Image Upload | Max size | 2 MB |
| Image Storage | Format | Resize to max 800px width |
| Cache | Dashboard stats | 5-minute TTL |
| Livewire Poll | Interval | Minimum 15 seconds |
