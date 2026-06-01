# Sello Manager — CLAUDE.md

## প্রজেক্ট পরিচিতি
সেলো (শ্যালো পাম্প) সেচ ব্যবস্থাপনা সিস্টেম। কৃষকদের পানি সরবরাহ ট্র্যাক করা, বিল তৈরি ও পেমেন্ট ম্যানেজমেন্টের জন্য।

## প্রজেক্ট পাথ
```
C:\laragon\www\sello-manager
```

## টেক স্ট্যাক
- **Backend:** Laravel 11 (PHP)
- **Frontend:** Blade Templates + Bootstrap 5
- **Database:** SQLite (dev) → MySQL (production)
- **PDF:** `barryvdh/laravel-dompdf`
- **Excel Import:** `maatwebsite/excel`
- **Icons:** Bootstrap Icons
- **Fonts:** Hind Siliguri (Bengali)

## ডেভেলপমেন্ট সার্ভার চালু করা
```bash
cd C:/laragon/www/sello-manager
php artisan serve --port=8001
# URL: http://127.0.0.1:8001
```

## ডেটাবেস
- **Driver:** SQLite
- **File:** `database/database.sqlite`
- **Migrate:** `php artisan migrate`
- **Fresh migrate:** `php artisan migrate:fresh`

### MySQL-এ সুইচ করতে (Laragon চালু থাকলে)
`.env` ফাইলে পরিবর্তন করুন:
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=sello_manager
DB_USERNAME=root
DB_PASSWORD=
```
তারপর: `php artisan migrate`

---

## ডেটাবেস স্কিমা

### `pump_owners` — সেলো মালিকের প্রোফাইল (একটিই রেকর্ড)
| কলাম | টাইপ | বিবরণ |
|-------|------|--------|
| id | bigint | PK |
| name | string | মালিকের নাম |
| mobile | string(20) | মোবাইল |
| pump_name | string nullable | সেলোর নাম/পরিচিতি |
| village | string nullable | গ্রাম |
| address | string nullable | ঠিকানা |
| rate_per_hour | decimal(10,2) | ডিফল্ট রেট প্রতি ঘণ্টা |
| nid | string(30) nullable | NID নম্বর |
| notes | text nullable | মন্তব্য |

### `farmers` — কৃষক তালিকা
| কলাম | টাইপ | বিবরণ |
|-------|------|--------|
| id | bigint | PK |
| name | string | নাম |
| mobile | string(20) | মোবাইল |
| village | string nullable | গ্রাম |
| union | string nullable | ইউনিয়ন |
| upazila | string nullable | উপজেলা |
| land_area | decimal(10,3) | জমির পরিমাণ |
| land_unit | enum(acre,shotok,bigha) | জমির একক |
| land_description | text nullable | জমির বিবরণ |
| nid | string(30) nullable | NID |
| is_active | boolean | সক্রিয় কিনা |
| notes | text nullable | মন্তব্য |

### `water_entries` — পানি সরবরাহ এন্ট্রি
| কলাম | টাইপ | বিবরণ |
|-------|------|--------|
| id | bigint | PK |
| farmer_id | FK → farmers | কৃষক |
| supply_date | date | সরবরাহের তারিখ |
| hours | decimal(8,2) | ঘণ্টার পরিমাণ |
| rate_per_hour | decimal(10,2) | প্রতি ঘণ্টা রেট |
| total_amount | decimal(12,2) | মোট বিল (hours × rate, auto calc) |
| season | string nullable | মৌসুম (রবি/খরিপ-১/খরিপ-২) |
| notes | text nullable | মন্তব্য |

### `payments` — পেমেন্ট হিস্ট্রি
| কলাম | টাইপ | বিবরণ |
|-------|------|--------|
| id | bigint | PK |
| farmer_id | FK → farmers | কৃষক |
| water_entry_id | FK → water_entries nullable | নির্দিষ্ট এন্ট্রি (ঐচ্ছিক) |
| amount | decimal(12,2) | পেমেন্টের পরিমাণ |
| payment_date | date | তারিখ |
| method | enum(cash,bkash,nagad,rocket,bank,other) | মাধ্যম |
| reference | string nullable | ট্রানজেকশন ID |
| notes | text nullable | মন্তব্য |

---

## ফাইল স্ট্রাকচার

```
app/
├── Http/Controllers/
│   ├── DashboardController.php       ← ড্যাশবোর্ড
│   ├── PumpOwnerController.php       ← সেলো মালিকের প্রোফাইল
│   ├── FarmerController.php          ← কৃষক CRUD
│   ├── WaterEntryController.php      ← পানি সরবরাহ CRUD
│   ├── PaymentController.php         ← পেমেন্ট + AJAX farmer-due
│   ├── ReportController.php          ← রিপোর্ট
│   ├── InvoiceController.php         ← PDF ইনভয়েস
│   └── ImportController.php          ← Excel/CSV import
├── Imports/
│   ├── FarmersImport.php             ← কৃষক ইমপোর্ট
│   └── WaterEntriesImport.php        ← এন্ট্রি ইমপোর্ট
└── Models/
    ├── PumpOwner.php
    ├── Farmer.php                    ← computed: total_billed, total_paid, total_due, payment_status
    ├── WaterEntry.php                ← computed: paid_amount, due_amount, payment_status, status_badge
    └── Payment.php                   ← computed: method_label

resources/views/
├── layouts/app.blade.php             ← Main layout (sidebar + bottom nav mobile)
├── dashboard.blade.php
├── pump-owner/edit.blade.php
├── farmers/
│   ├── index.blade.php               ← Table (desktop) + Cards (mobile)
│   ├── create.blade.php
│   ├── edit.blade.php
│   ├── show.blade.php                ← entries + payments history
│   └── _form.blade.php
├── water-entries/
│   ├── index.blade.php               ← Table (desktop) + Cards (mobile)
│   ├── create.blade.php
│   ├── edit.blade.php
│   ├── show.blade.php
│   └── _form.blade.php               ← auto total calculation JS
├── payments/
│   ├── index.blade.php               ← Table (desktop) + Cards (mobile)
│   └── create.blade.php              ← AJAX: farmer due entries load
├── reports/index.blade.php           ← daily/monthly/seasonal/custom
├── invoices/
│   ├── show.blade.php                ← web invoice view
│   ├── pdf.blade.php                 ← DomPDF template (A5)
│   └── farmer-bill.blade.php         ← monthly bill PDF (A4)
└── import/index.blade.php

routes/web.php                        ← সব routes
```

---

## Routes সংক্ষেপ

| Route | Method | Name | বিবরণ |
|-------|--------|------|--------|
| `/` | GET | dashboard | ড্যাশবোর্ড |
| `/profile` | GET/PUT | pump-owner.edit/update | সেলো মালিকের প্রোফাইল |
| `/farmers` | Resource | farmers.* | কৃষক CRUD |
| `/farmers/{farmer}/bill` | GET | invoices.farmer-bill | মাসিক বিল PDF |
| `/water-entries` | Resource | water-entries.* | পানি এন্ট্রি CRUD |
| `/payments` | GET/POST | payments.index/store | পেমেন্ট |
| `/payments/farmer-due` | GET | payments.farmer-due | AJAX: বাকি এন্ট্রি |
| `/payments/create` | GET | payments.create | পেমেন্ট ফর্ম |
| `/payments/{payment}` | DELETE | payments.destroy | পেমেন্ট মুছুন |
| `/reports` | GET | reports.index | রিপোর্ট |
| `/invoices/{waterEntry}` | GET | invoices.show | ইনভয়েস দেখুন |
| `/invoices/{waterEntry}/pdf` | GET | invoices.pdf | PDF ডাউনলোড |
| `/import` | GET | import.index | ইমপোর্ট পেজ |
| `/import/farmers` | POST | import.farmers | কৃষক ইমপোর্ট |
| `/import/water-entries` | POST | import.water-entries | এন্ট্রি ইমপোর্ট |
| `/import/template/{type}` | GET | import.template | CSV টেমপ্লেট ডাউনলোড |

---

## মডেল — গুরুত্বপূর্ণ Computed Attributes

### Farmer
```php
$farmer->total_billed      // water_entries.total_amount এর sum
$farmer->total_paid        // payments.amount এর sum
$farmer->total_due         // total_billed - total_paid
$farmer->payment_status    // 'paid' | 'partial' | 'due'
```

### WaterEntry
```php
$entry->paid_amount        // এই entry-র payments sum
$entry->due_amount         // total_amount - paid_amount
$entry->payment_status     // 'paid' | 'partial' | 'due'
$entry->status_badge       // HTML badge string
```

### WaterEntry → booted()
`saving` event-এ `total_amount = hours × rate_per_hour` auto-calculate হয়।

---

## Mobile Design
- **Desktop:** Sidebar (240px fixed left) + Table views
- **Mobile:** Sidebar hidden (hamburger toggle + overlay), Card views, **Bottom Navigation Bar**
- Bottom nav: হোম / কৃষক / +(নতুন এন্ট্রি) / পেমেন্ট / রিপোর্ট
- CSS breakpoint: `768px`

---

## Pending / ভবিষ্যৎ কাজ
- [ ] SMS/WhatsApp নোটিফিকেশন (বাকি টাকার রিমাইন্ডার) — SSL Wireless বা Twilio
- [ ] Authentication (login/logout) — সেলো মালিকের লগইন
- [ ] MySQL-এ migrate করা (Laragon MySQL চালু করে)
- [ ] Push notification (PWA)
- [ ] Annual summary report

---

## সাধারণ Artisan কমান্ড
```bash
php artisan serve --port=8001        # সার্ভার চালু
php artisan migrate                  # নতুন migration চালানো
php artisan migrate:fresh            # সব টেবিল মুছে নতুন করে বানানো
php artisan migrate:fresh --seed     # seeder সহ
php artisan route:list               # সব route দেখা
php artisan cache:clear              # cache পরিষ্কার
php artisan view:clear               # view cache পরিষ্কার
```
