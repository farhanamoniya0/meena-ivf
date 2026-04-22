# Meena IVF Center — Setup Guide

## Requirements
- PHP 8.3 (bundled with Laragon)
- MySQL 8.4 (bundled with Laragon)
- Composer (bundled with Laragon)

---

## Step 1: Open Laragon Terminal
1. Open **Laragon**
2. Click **Start All** (starts Apache + MySQL)
3. Click **Terminal** (top menu) → opens a terminal WITH php/composer on PATH

---

## Step 2: Go to project folder
In the Laragon terminal, type:
```
cd /d "D:\Meena IVF"
```

---

## Step 3: Install PHP dependencies
```
composer install
```
Wait for it to finish (2-3 minutes first time).

---

## Step 4: Generate application key
```
php artisan key:generate
```

---

## Step 5: Run database migrations
This creates all tables in MySQL:
```
php artisan migrate
```
Type `yes` if asked to confirm.

---

## Step 6: Seed demo data
This creates demo users, consultants, packages, medicines:
```
php artisan db:seed
```

---

## Step 7: Create storage link (for photo uploads)
```
php artisan storage:link
```

---

## Step 8: Start the application
```
php artisan serve
```
Then open your browser and go to: **http://127.0.0.1:8000**

---

## Demo Login Accounts

| Role         | Email                      | Password     |
|-------------|----------------------------|--------------|
| Admin        | admin@meenaivf.com         | password123  |
| Doctor       | doctor@meenaivf.com        | password123  |
| Consultant   | consultant@meenaivf.com    | password123  |
| Billing      | billing@meenaivf.com       | password123  |
| Accountant   | accounts@meenaivf.com      | password123  |
| Pharmacy     | pharmacy@meenaivf.com      | password123  |
| Lab          | lab@meenaivf.com           | password123  |
| Reception    | reception@meenaivf.com     | password123  |

---

## Features Available

### Patient Management
- Quick Registration (fast, 4 fields)
- Full Registration (couple-based with photo upload)
- Patient code auto-generated: MIV-2024-XXXX

### IVF Packages & Billing
- Create IVF packages with services list
- Assign package to patient
- Record multiple payments (Cash / Bank / Card / bKash / Nagad / Rocket)
- Auto-calculated remaining balance
- Printable payment receipts

### Accounts
- Daily collection summary
- End-of-day close button
- Closing history

### Pharmacy
- Add medicines with batch/expiry tracking
- Low stock and expiry alerts
- Assign medicines to patients
- Requisition system

### Admin Panel
- One-click approve/reject payments
- Approve/reject pharmacy requisitions
- User management (add, activate/deactivate)
- Department management

---

## Troubleshooting

**"php is not recognized"** → Use Laragon Terminal, not Windows Command Prompt

**Database error** → Make sure Laragon MySQL is started (green light in Laragon)

**Blank page / error** → Run `php artisan config:clear` then `php artisan serve` again

**Upload not working** → Run `php artisan storage:link`
