# Multi-Merchant E-Commerce Platform Backend

This backend powers a multi-merchant e-commerce platform designed to handle large-scale product catalogs, bulk CSV imports, and high-volume collection operations concurrently.

---

## 1. Overview
The system prioritizes performance and reliability through:
* **Non-blocking bulk operations**
* **Data consistency**
* **Fault tolerance**
* **Horizontal scalability**
* **Clean, maintainable architecture**

---

## 2. Tech Stack
* **Framework:** Laravel (API-first)
* **Database:** MySQL
* **Queue System:** Laravel Queues (Redis / Database driver)
* **Background Jobs:** Laravel ShouldQueue
* **Architecture:** Service + Repository pattern
* **Frontend Communication:** REST APIs with polling
* **Notifications:** Email (queued)

---

## 3. Project Setup

### Requirements
* PHP >= 8.1
* Composer
* MySQL

### Installation Steps
1. **Clone the repository:**
   git clone <repo-url>
   cd <repo-folder>

2. **Install PHP dependencies:**
   composer install

3. **Configure Environment:**
   cp .env.example .env

4. **Update .env with your credentials:**
   - DB_DATABASE=e_commerce_multi_vendor
   - DB_USERNAME=root
   - DB_PASSWORD=
   - MAIL_USERNAME=your_mailtrap_username
   - MAIL_PASSWORD=your_mailtrap_password

5. **Initialize Application:**
   php artisan key:generate
   php artisan migrate
   php artisan db:seed

6. **Start Background Workers:**
   php artisan queue:work

7. **Serve Application:**
   php artisan serve

---

## 4. High-Level Architecture



**Logic Flow:**
Controller -> Service Layer -> Repository Layer -> Eloquent Models -> Database

**Background Processing:**
Controller -> Service -> Queue Job -> Repository -> DB

**Why this architecture?**
* Keeps controllers thin and focused on HTTP.
* Isolates business logic into reusable Services.
* Allows swapping persistence layers via Repositories.

---

## 5. Core Domain Models
* **Merchant:** Represents an independent seller.
* **Product:** SKU-based items; unique per merchant.
* **Collection:** Merchant-owned groups; contains thousands of products.
* **Import:** Tracks progress and state of bulk CSV operations.

---

## 6. Bulk Product Import System

### Key Features
- ✔ Non-blocking processing
- ✔ Real-time progress updates via polling
- ✔ Partial failure handling (atomic row processing)
- ✔ Queued email notifications upon completion

### Import Table Structure
- id | merchant_id | file_path | status (pending/processing/completed/failed)
- total_rows | processed_rows | failed_rows | timestamps

---

## 7. Background Job Design: ImportProductsJob
The job iterates through CSV rows asynchronously to keep the platform responsive.

**Design Logic:**
- Uses Row-level database transactions.
- Increments progress counters in the DB for real-time tracking.
- Idempotent SKU-based upserts to prevent duplicate data.

Tracking Logic:
$import->increment('processed_rows');
$import->increment('failed_rows');

---

## 8. Real-Time Progress Updates (Polling)

The system uses HTTP Polling instead of WebSockets for high scalability and lower infrastructure complexity.

### Progress API
GET /api/imports/{id}

**Sample Response:**
{
  "id": 12,
  "status": "processing",
  "processed_rows": 420,
  "failed_rows": 3,
  "total_rows": 1000,
  "progress": 42
}

---

## 9. Scaling Strategy
* **Queue Workers:** Run dedicated workers for different queues:
  php artisan queue:work --queue=imports
  php artisan queue:work --queue=emails
* **Database:** Composite indexes on (merchant_id, sku) for $O(1)$ lookups.
* **Horizontal Scaling:** Stateless API design allows adding more nodes under a load balancer.

---

## 10. Error Handling
* **Invalid Rows:** Skipped and logged; the rest of the import continues.
* **Database Deadlocks:** Handled via transaction retries in the Repository.
* **Worker Crashes:** Jobs remain in the queue or are marked as failed for manual restart.

---

## 11. Collections (Partial Implementation)

⚠️ **Note**: Due to time constraints, the collection management module is only partially implemented. Currently:

* Collections can be created and associated with products.

* CRUD operations for collections exist.

* Product attachment/detachment is functional.

**Limitations**:

* Full validation to ensure products belong to the same merchant is not fully enforced.

* Advanced features such as bulk import into collections, collection-level analytics, or nested collections are not implemented.

* Collection has not be implemented in the UI counterpart.