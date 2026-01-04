
---

# Product Import & Synchronization

This project provides a **simple and scalable product import system** built with Laravel.
It supports importing products from **CSV files** and **external APIs**, and is designed to safely handle **large datasets (500k+ rows)** using streaming and chunking.

The system is **idempotent**, **memory-efficient**, and easy to extend.

---

## What This System Does

* Import products from **CSV**
* Import products from **API**
* Upsert products by **SKU**
* Store product names using **translations**
* Create product **variants** with quantity & availability
* Handle **product statuses** dynamically
* Soft-delete products when status = `deleted`
* Restore products if they reappear
* Safe to re-run imports multiple times

---

## Requirements

The project is built and tested with the following versions:

* **PHP:** `8.2+`
* **Laravel:** `12.x`
* **MySQL:** `8.0+`
* **Composer:** `2.x`
* **Redis:** optional (required only if queues are enabled)

> ⚠️ PHP 8.2 is required due to typed properties, enums, and modern language features.

---

## Installation

### 1️⃣ Clone the Repository

```bash
git clone <repository-url>
cd <project-directory>
```

---

### 2️⃣ Install PHP Dependencies

```bash
composer install
```

---

### 3️⃣ Environment Configuration

Copy the example environment file:

```bash
cp .env.example .env
```

Update `.env` with your database credentials:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database
DB_USERNAME=your_user
DB_PASSWORD=your_password
```

---

### 4️⃣ Generate Application Key

```bash
php artisan key:generate
```

---

### 5️⃣ Run Database Migrations

```bash
php artisan migrate
```

This will create:

* products
* product_translations
* product_variants
* product_statuses
* options / option_values (for future extensions)

---

## Running Imports

### Import from CSV

```bash
php artisan import:products csv --path=storage/app/products_500k.csv
```

### Import from API

```bash
php artisan import:products api --url=https://example.com/api/products
```

---

## Import Rules

* `sku` is **required**
* `status`

    * Defaults to `active` if missing
    * If `deleted` → product is soft deleted
    * New statuses are **created dynamically**
* `quantity`

    * Stored on variants
    * Controls `is_available`
* `variations`

    * Empty → default variant created
    * Present → variant SKU generated per variation

---

## Performance Notes

* Tested with **500,000+ CSV rows**
* Uses streaming (`SplFileObject`)
* No full file loaded into memory
* Chunked transactions ensure safety
* Can be safely re-run

---

## Documents

### Software Architecture Document

[https://docs.google.com/document/d/1WtiwRSduMrjxwbm91SEZXgLbggBppFbe_JkCALeHEf4/edit](https://docs.google.com/document/d/1WtiwRSduMrjxwbm91SEZXgLbggBppFbe_JkCALeHEf4/edit)

### Technical Design Document

[https://docs.google.com/document/d/1qqho62_VF1jW4stjwEVQeZMXtue7nj-Pua3a9BwVmSc/edit](https://docs.google.com/document/d/1qqho62_VF1jW4stjwEVQeZMXtue7nj-Pua3a9BwVmSc/edit)

---

## Notes

* Product names are stored **only** in `product_translations`
* `products` table contains only technical fields (SKU, status, delete info)
* Status handling is centralized in `ProductStatusResolver`
* CSV and API sources are interchangeable via the Strategy pattern
