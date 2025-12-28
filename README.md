# Backend Documentation: Multi-Merchant E-Commerce Platform

## 1. Overview
This backend powers a multi-merchant e-commerce platform designed to handle large-scale product catalogs, bulk CSV imports, and high-volume collection operations concurrently.

The system prioritizes:
* **Non-blocking bulk operations**
* **Data consistency**
* **Fault tolerance**
* **Horizontal scalability**
* **Clean, maintainable architecture**

---

## 2. Tech Stack
* **Framework:** Laravel (API-first)
* **Database:** MySQL
* **Queue System:** Laravel Queues (Database driver)
* **Background Jobs:** Laravel ShouldQueue
* **Architecture:** Service + Repository pattern
* **Frontend Communication:** REST APIs with polling
* **Notifications:** Email (queued)

---

## 3. High-Level Architecture



**Flow:**
Controller -> Service Layer -> Repository Layer -> Eloquent Models -> Database

**Background Processing:**
Controller -> Service -> Queue Job -> Repository -> DB

**Why this architecture?**
* Keeps controllers thin
* Isolates business logic
* Makes jobs reusable and testable
* Allows swapping persistence layers if needed

---

## 4. Core Domain Models
* **Merchant:** Represents a seller operating independently.
* **Product:** Belongs to one merchant; identified by SKU; can belong to multiple collections.
* **Collection:** Belongs to a merchant; can contain thousands of products.
* **Import:** Tracks the lifecycle of a bulk product import.

---

## 5. Bulk Product Import System

### Key Requirements Addressed
- Non-blocking imports
- Real-time progress updates
- Partial failure handling
- Email notification on completion

### Import Lifecycle
1. Merchant uploads CSV
2. Import record is created (status: pending)
3. Background job is dispatched
4. Job processes rows incrementally
5. Progress is tracked in DB
6. Status updated to completed or failed
7. Email notification is sent

### Import Table Structure
- id
- merchant_id
- file_path
- status (pending | processing | completed | failed)
- total_rows
- processed_rows
- failed_rows
- timestamps

---

## 6. Background Job Design: ImportProductsJob
Runs asynchronously using queues and processes CSV row-by-row.

**Design Decisions:**
- Import ID passed to job, not full model (queue safety)
- Repository resolved in job, not constructor
- Row-level error isolation using transactions
- Idempotent SKU-based upserts

**Example Tracking Logic:**
$import->increment('processed_rows');
$import->increment('failed_rows');

---

## 7. Real-Time Progress Updates (Polling)

| Reason | Explanation |
| :--- | :--- |
| **Simplicity** | No socket infra needed |
| **Scalability** | Works across multiple servers |
| **Reliability** | Stateless and cache-friendly |

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

## 8. Collection Management
* Collections belong to merchants.
* Many-to-many relationship with products.
* Indexed pivot table for fast bulk operations.
* **Bulk Operations:** Attach/detach products in batches using chunking to avoid memory spikes.

---

## 9. Error Handling Strategy
* **Invalid CSV row:** Skipped, logged, counted.
* **Partial import failure:** Import continues.
* **File missing:** Import marked failed.
* **DB error:** Row rolled back only.
* **Worker crash:** Import resumes safely via job retries.

---

## 10. Scaling Strategy
1. **Queue Workers:** Horizontal scaling with separate queues per workload.
2. **Database:** Composite indexes (merchant_id, sku) and read replicas.
3. **Application:** Stateless API servers and chunked DB operations.

---

## 11. Summary
This backend is a production-ready foundation for large-scale e-commerce operations, emphasizing reliability and clean architecture.
