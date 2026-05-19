# Dynamic Discount System 🎉

A comprehensive Laravel-based e-commerce platform designed to manage and showcase products, coupons, offers, and discounts. This system includes both frontend and admin interfaces with complete order management capabilities, powered by a robust **MongoDB** backend.

## 📋 Table of Contents

- [Features](#features)
- [Tech Stack](#tech-stack)
- [Installation & Setup](#installation--setup)
- [How to Run](#how-to-run)
- [Routes Documentation](#routes-documentation)
- [Admin Access](#admin-access)
- [Database Models](#database-models)
- [Project Structure](#project-structure)

---

## ✨ Features

### Frontend Features
- **Home Page** - Banner carousel, featured products, flash deals, and exclusive promo codes
- **Product Browsing** - Browse all products with category filtering
- **Product Details** - View detailed information about each product
- **Shopping Cart** - Add/remove items, manage quantities
- **Coupon System** - Apply discount codes with validation (minimum purchase, expiry dates)
- **Offers & Deals** - Browse active time-limited offers
- **Checkout** - Complete order purchase workflow
- **Order Management** - View order history and details
- **User Account** - Dashboard for logged-in customers

### Admin Features
- **Dashboard** - KPIs, sales reports, best-selling products, most-used coupons
- **Product Management** - Create, edit, delete products with images and pricing
- **Category Management** - Organize products by categories
- **Banner Management** - Create promotional banners for the homepage
- **Coupon Management** - Create and manage discount coupons with rules
- **Offer Management** - Create time-limited offers and deals
- **Order Management** - View and manage customer orders with status updates
- **Status Toggles** - Enable/disable products, categories, banners, coupons, and offers

### System Features
- **Role-Based Access Control** - Separate admin and customer roles
- **Session Management** - Database-driven sessions
- **Shopping Cart Sessions** - Persistent cart stored in sessions
- **Coupon Validation** - Automatic validation of minimum purchase, expiry dates, and usage limits
- **Failed Attempt Tracking** - Track failed coupon applications for analytics

---

## 🛠️ Tech Stack

- **Backend**: Laravel 11.x (PHP 8.3+)
- **Database**: MongoDB (via `mongodb/laravel-mongodb`)
- **Frontend**: Bootstrap 5, Blade Templates
- **Assets**: Vite, CSS
- **PDF Generation**: Laravel DOMPDF
- **UI Components**: Bootstrap Icons
- **Authentication**: Laravel Authentication

---

## 📦 Installation & Setup

### Prerequisites
- PHP 8.3+
- MongoDB (running locally or a MongoDB Atlas URI)
- Composer
- Node.js & npm

### Step 1: Clone the Repository
```bash
git clone <repository-url>
cd dynamic-discount-system
```

### Step 2: Install Dependencies
```bash
composer install
npm install
```

### Step 3: Configure Environment
```bash
# Copy example env file
cp .env.example .env

# Update your MongoDB connection details in .env:
# DB_CONNECTION=mongodb
# DB_URI="mongodb://127.0.0.1:27017/dynamic_discount_system"
# DB_DATABASE=dynamic_discount_system
```

### Step 4: Generate Application Key
```bash
php artisan key:generate
```

### Step 5: Run Migrations
```bash
# Run migrations to set up the collections
php artisan migrate

# Or use migrate:fresh to reset database
php artisan migrate:fresh
```

### Step 6: Seed Sample Data (Optional)
```bash
# Populates the database with a catalog and test accounts
php artisan db:seed
```

---

## 🚀 How to Run

### Development Server

**Option 1: Using Laravel Artisan**
```bash
php artisan serve
```
- Server runs at: `http://localhost:8000`

**Option 2: Development Mode (with Vite & Queue)**
```bash
npm run dev
```
- Compiles assets in watch mode
- Runs server with hot module replacement

### Access the Application

| Route | URL | Purpose |
|-------|-----|---------|
| Home | `http://localhost:8000/` | Frontend home page |
| Products | `http://localhost:8000/products` | Browse all products |
| Offers | `http://localhost:8000/offers` | View active offers |
| Cart | `http://localhost:8000/cart` | Shopping cart |
| Checkout | `http://localhost:8000/checkout` | Place order (auth required) |
| Admin | `http://localhost:8000/admin/dashboard` | Admin panel (admin auth required) |
| Register | `http://localhost:8000/register` | Create account |
| Login | `http://localhost:8000/login` | Login |

---

## 📍 Routes Documentation

### Public Routes (No Authentication Required)

#### Frontend Routes
```
GET  /                    → FrontendController@home              (Home page)
GET  /products            → FrontendController@products          (All products)
GET  /product/{slug}      → FrontendController@productDetails    (Product details)
GET  /offers              → FrontendController@offers            (Active offers)
```

#### Authentication Routes
```
GET  /login               → Login form
POST /login               → Login submission
GET  /register            → Registration form
POST /register            → Registration submission
GET  /forgot-password     → Password reset
```

#### Cart Routes (Can be used without login)
```
POST /cart/add                  → Add item to cart
GET  /cart                      → View cart
POST /cart/remove               → Remove item from cart
POST /cart/apply-coupon         → Apply coupon code
POST /cart/remove-coupon        → Remove coupon
```

---

### Customer Routes (Auth Required + 'customer' Middleware)

```
GET  /user/home              → UserController@home           (User dashboard)
GET  /checkout               → CartController@checkout       (Checkout page)
POST /orders                 → OrderController@store         (Place order)
GET  /orders/success         → OrderController@success       (Order success)
GET  /my-orders              → OrderController@index         (Order history)
GET  /my-orders/{order}      → OrderController@show          (Order details)
```

---

### Admin Routes (Auth Required + 'admin' Middleware)

#### Dashboard
```
GET  /admin/dashboard        → AdminController@dashboard     (Admin dashboard with KPIs)
```

#### Categories
```
GET    /admin/categories              → CategoryController@index       (List categories)
GET    /admin/categories/create       → CategoryController@create      (Create form)
POST   /admin/categories              → CategoryController@store       (Save category)
GET    /admin/categories/{id}         → CategoryController@show        (View category)
GET    /admin/categories/{id}/edit    → CategoryController@edit        (Edit form)
PUT    /admin/categories/{id}         → CategoryController@update      (Update category)
DELETE /admin/categories/{id}         → CategoryController@destroy     (Delete category)
POST   /admin/categories/{id}/toggle-status → CategoryController@toggleStatus (Toggle active/inactive)
```

#### Products
```
GET    /admin/products              → ProductController@index       (List products)
GET    /admin/products/create       → ProductController@create      (Create form)
POST   /admin/products              → ProductController@store       (Save product)
GET    /admin/products/{id}         → ProductController@show        (View product)
GET    /admin/products/{id}/edit    → ProductController@edit        (Edit form)
PUT    /admin/products/{id}         → ProductController@update      (Update product)
DELETE /admin/products/{id}         → ProductController@destroy     (Delete product)
POST   /admin/products/{id}/toggle-status → ProductController@toggleStatus (Toggle availability)
```

#### Banners
```
GET    /admin/banners              → BannerController@index       (List banners)
GET    /admin/banners/create       → BannerController@create      (Create form)
POST   /admin/banners              → BannerController@store       (Save banner)
GET    /admin/banners/{id}         → BannerController@show        (View banner)
GET    /admin/banners/{id}/edit    → BannerController@edit        (Edit form)
PUT    /admin/banners/{id}         → BannerController@update      (Update banner)
DELETE /admin/banners/{id}         → BannerController@destroy     (Delete banner)
POST   /admin/banners/{id}/toggle-status → BannerController@toggleStatus (Toggle visibility)
```

#### Coupons
```
GET    /admin/coupons              → CouponController@index       (List coupons)
GET    /admin/coupons/create       → CouponController@create      (Create form)
POST   /admin/coupons              → CouponController@store       (Save coupon)
GET    /admin/coupons/{id}         → CouponController@show        (View coupon)
GET    /admin/coupons/{id}/edit    → CouponController@edit        (Edit form)
PUT    /admin/coupons/{id}         → CouponController@update      (Update coupon)
DELETE /admin/coupons/{id}         → CouponController@destroy     (Delete coupon)
POST   /admin/coupons/{id}/toggle-status → CouponController@toggleStatus (Toggle availability)
```

#### Offers
```
GET    /admin/offers              → OfferController@index       (List offers)
GET    /admin/offers/create       → OfferController@create      (Create form)
POST   /admin/offers              → OfferController@store       (Save offer)
GET    /admin/offers/{id}         → OfferController@show        (View offer)
GET    /admin/offers/{id}/edit    → OfferController@edit        (Edit form)
PUT    /admin/offers/{id}         → OfferController@update      (Update offer)
DELETE /admin/offers/{id}         → OfferController@destroy     (Delete offer)
POST   /admin/offers/{id}/toggle-status → OfferController@toggleStatus (Toggle availability)
```

#### Orders
```
GET  /admin/orders           → OrderController@index       (List all orders)
GET  /admin/orders/{id}      → OrderController@show        (Order details)
POST /admin/orders/{id}/update-status → OrderController@updateStatus (Update order status)
```

---

## 🔐 Admin Access

### Default Test Credentials (from Seeder)
- **Email**: `admin@example.com`
- **Password**: `admin123`

- **Customer Email**: `test@example.com`
- **Customer Password**: `password`

### Creating Admin Users Using Tinker (REPL)
```bash
php artisan tinker

# Create admin user
User::create([
    'name' => 'Admin User',
    'email' => 'admin@example.com',
    'password' => bcrypt('password123'),
    'role' => 'admin',
    'status' => 'active'
]);

exit
```

### Admin Login
1. Navigate to: `http://localhost:8000/login`
2. Enter admin credentials.
3. After login, access: `http://localhost:8000/admin/dashboard`

---

## 📊 Database Models

| Model | Purpose | Relationships |
|-------|---------|---|
| **User** | Users & Admins | Orders, CouponUsages |
| **Category** | Product Categories | Products |
| **Product** | Store Products | Category, OrderItems, Banner |
| **Banner** | Homepage Banners | Product (optional) |
| **Coupon** | Discount Codes | CouponUsages, FailedAttempts |
| **CouponUsage** | Coupon Application Log | User, Coupon, Order |
| **Offer** | Limited-time Offers | None |
| **Order** | Customer Orders | User, OrderItems, CouponUsages |
| **OrderItem** | Order Line Items | Product, Order |
| **FailedCouponAttempt** | Failed Coupon Logs | Coupon, User |

---

## 📁 Project Structure

```
dynamic-discount-system/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Admin/              # Admin resource controllers
│   │   │   ├── Auth/               # Authentication controllers
│   │   │   ├── AdminController.php # Admin dashboard
│   │   │   ├── CartController.php  # Cart management
│   │   │   ├── FrontendController.php
│   │   │   ├── OrderController.php
│   │   │   └── UserController.php
│   │   └── Middleware/
│   │       ├── AdminMiddleware.php    # Check admin role
│   │       └── CustomerMiddleware.php # Check customer role
│   ├── Models/
│   │   ├── User.php, Product.php, Order.php
│   │   ├── Category.php, Banner.php, Coupon.php
│   │   ├── Offer.php, OrderItem.php, etc.
│   └── ...
├── database/
│   ├── migrations/  # Database schema
│   ├── seeders/     # Sample data
│   └── factories/   # Model factories
├── resources/
│   ├── views/
│   │   ├── layouts/         # Layout templates
│   │   ├── frontend/        # Customer pages
│   │   ├── admin/           # Admin pages
│   │   └── auth/            # Authentication pages
│   ├── css/         # Stylesheets
│   └── js/          # JavaScript files
├── routes/
│   └── web.php      # Route definitions
├── public/          # Web-accessible files
├── config/          # Configuration files
└── tests/           # Unit & feature tests
```

---

## 🎯 Key Features Explained

### 1. **Coupon System**
- Percentage-based and fixed amount discounts
- Minimum purchase requirements
- Expiry date validation
- Usage limits per coupon
- Failed attempt tracking

### 2. **Product Management**
- Images with storage support
- Inventory tracking
- Price management
- Category assignment
- Enable/disable products

### 3. **Order Management**
- Order status workflow (pending, confirmed, shipped, delivered, cancelled)
- Order items with quantities
- Final amount calculation with discounts
- Coupon usage tracking

### 4. **Admin Dashboard**
- Total revenue analytics
- Best-selling products
- Most-used coupons
- Monthly sales reports
- Failed coupon attempts

---

## 🐛 Troubleshooting

### Database Connection Error
```bash
# Ensure MongoDB is running
# Update .env with correct MongoDB credentials
# Run migrations
php artisan migrate:fresh
```

### Middleware Issues
```bash
# Clear cache
php artisan cache:clear
php artisan config:clear
```

### Asset Not Found
```bash
# Rebuild assets
npm install
npm run build

# Or in development
npm run dev
```

---

## 📝 Notes

- **Database System**: This application strictly uses **MongoDB**, configured via the `mongodb/laravel-mongodb` package. Do not use MySQL configuration.
- **Session Driver**: Database (ensure sessions collection exists from migrations)
- **File Storage**: Local storage in `storage/app/public`
- **Queue**: Database queue driver
- **Cache**: Database cache driver

---

## 📄 License

This project is open-source software licensed under the MIT license.
