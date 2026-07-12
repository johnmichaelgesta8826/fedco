# FEDCO Laundry Hub — PHP + MySQL (Full App)

## Paano i-set up (halimbawa gamit ang XAMPP)

1. **I-install ang XAMPP** (kung wala ka pa) — https://www.apachefriends.org
2. I-**copy ang buong `fedco_laundry_hub` folder** papunta sa:
   - Windows: `C:\xampp\htdocs\fedco_laundry_hub`
   - Mac: `/Applications/XAMPP/htdocs/fedco_laundry_hub`
3. **Buksan ang XAMPP Control Panel**, i-**Start** ang **Apache** at **MySQL**.
4. Pumunta sa **phpMyAdmin** (`http://localhost/phpmyadmin`):
   - Click **Import**
   - Piliin ang file na `database.sql`
   - Click **Go** — gagawin nito ang database na `fedco_laundry_hub` at lahat ng tables.
5. Buksan sa browser: `http://localhost/fedco_laundry_hub/seed_admin.php`
   - Gagawa ito ng **1 Admin account** at **1 sample Customer account**.
   - **Admin login:** `admin@fedco.com` / `admin123`
   - **Sample Customer login:** `juan@example.com` / `customer123`
   - Pagkatapos, **i-delete o i-rename** ang `seed_admin.php` (para hindi na ito ma-access ng iba).
6. Buksan ang app: `http://localhost/fedco_laundry_hub/login.php`

## Mga config na baka kailangan mong baguhin

Sa file na `config/db.php`, i-adjust kung iba ang setup mo:
```php
$DB_HOST = "localhost";
$DB_NAME = "fedco_laundry_hub";
$DB_USER = "root";
$DB_PASS = "";
```

## Mga Feature

### Login
- May **role picker (Customer / Admin)** sa login page.
- Customer → dadalhin sa Customer Home.
- Admin → dadalhin sa Admin Dashboard.

### Customer side
- Sign up / Log in
- Book new laundry order (auto-computed pricing)
- View order details / receipt
- Track order (Pending → Processing → Done timeline)
- Support hub: Reschedule Pickup, Report an Issue, Cancel Order, Feedback & Suggestions
- Profile page (order stats + Log Out)

### Admin side (`/admin/dashboard.php`)
- Makikita lahat ng orders, may **status filter tabs**: All / Pending / Processing / Done / Cancelled
- Puwedeng baguhin/i-update ang status ng bawat order (dropdown + Save)
- **Customer Service page** (`/admin/customer_service.php`) — may tabs:
  - **Reports** (mga reported issues ng customers)
  - **Reschedule** (mga reschedule requests)
  - **Cancels** (log ng mga cancelled orders + reason)
  - **Feedback** (star rating + comments ng customers)
- **Log Out** button sa parehong Dashboard at Customer Service.

## Folder Structure
```
fedco_laundry_hub/
├── config/db.php              -> database connection
├── includes/auth.php          -> session / login helpers
├── assets/style.css           -> shared styling
├── database.sql               -> import this sa phpMyAdmin
├── seed_admin.php             -> run once, then delete
├── index.php / login.php / login_process.php
├── register.php / register_process.php
├── logout.php
├── customer/                  -> lahat ng customer pages
└── admin/                     -> dashboard + customer_service
```

## Seguridad (mahalagang tandaan)
- Naka-hash (bcrypt) ang lahat ng passwords gamit ang PHP `password_hash()`.
- Gumagamit ng **prepared statements (PDO)** ang lahat ng queries para maiwasan ang SQL injection.
- I-delete ang `seed_admin.php` pagkatapos gamitin.
- Para sa production/live site, gumamit ng malakas na password para sa DB at Admin account, at paganahin ang HTTPS.
