# Requirements Document

## Introduction

This feature adds three capabilities to the Urska Laravel e-commerce application:
1. Guest users can submit a star rating and written review for any product.
2. Admins can view and manage (approve, reject, delete) submitted reviews from the admin panel.
3. The public product listing shows all active products with category and subcategory filtering, 12 per page.

---

## Glossary

- **Review**: A record submitted by a guest containing a reviewer name, review title, review message, and a star rating (1–5) for a specific product.
- **Pending**: A review that has been submitted but not yet acted on by an admin.
- **Approved**: A review accepted by an admin and visible to the public.
- **Rejected**: A review declined by an admin and not visible to the public.

---

## Requirements

### Requirement 1: Guest Review Submission

**User Story:** As a guest user, I want to submit a star rating and written review for a product, so that I can share my experience with other shoppers.

#### Acceptance Criteria

1. WHEN a guest submits a POST request to `/api/shop/products/{slug}/reviews` with valid `reviewer_name`, `title`, `message`, and `rating`, THE system SHALL create a Review record with status `pending` and return a 201 JSON response with the created review's `id`, `reviewer_name`, `title`, `message`, `rating`, `status`, and `created_at`.
2. THE system SHALL accept `reviewer_name` as a string of 2–100 characters.
3. THE system SHALL accept `title` as a string of 3–150 characters.
4. THE system SHALL accept `message` as a string of 10–2000 characters.
5. THE system SHALL accept `rating` as an integer between 1 and 5 inclusive.
6. IF the `slug` does not match a product with `status = true`, THEN THE system SHALL return a 404 JSON response with a `message` field.
7. IF any required field is missing or invalid, THEN THE system SHALL return a 422 JSON response with an `errors` object containing field-level messages.
8. IF a `reviewer_name` already has a review for the same product, THEN THE system SHALL return a 422 JSON response indicating a duplicate submission is not allowed.

---

### Requirement 2: Admin Review Management

**User Story:** As an admin, I want to view, approve, reject, and delete submitted reviews, so that I can control what content is shown to customers.

#### Acceptance Criteria

1. WHEN an authenticated admin visits the review management page, THE system SHALL display a paginated list of all reviews (all statuses) with 20 per page, showing `reviewer_name`, `title`, `message`, `rating`, `status`, `created_at`, and the associated product title.
2. WHEN an admin filters by `status` (pending, approved, or rejected), THE system SHALL return only reviews matching that status.
3. WHEN an admin filters by `product_id`, THE system SHALL return only reviews for that product.
4. WHEN an admin approves a review, THE system SHALL set its status to `approved` and recalculate the product's average `rating` and `reviews_count` from all approved reviews.
5. WHEN an admin rejects a review, THE system SHALL set its status to `rejected` and recalculate the product's average `rating` and `reviews_count`.
6. WHEN an admin deletes a review, THE system SHALL permanently remove it and, if it was `approved`, recalculate the product's `rating` and `reviews_count`.
7. IF an unauthenticated request is made to any admin review endpoint, THE system SHALL redirect to the admin login page.
8. IF a PATCH or DELETE request references a non-existent review `id`, THE system SHALL return a 404 JSON response with a `message` field.

---

### Requirement 3: Product Listing with Category Filter

**User Story:** As a guest user, I want to browse all products filtered by category or subcategory, so that I can find products relevant to me.

#### Acceptance Criteria

1. WHEN a guest sends a GET request to `/api/shop/products`, THE system SHALL return active products paginated at 12 per page by default.
2. WHEN the `category` query parameter is provided with a valid slug, THE system SHALL return only products whose category matches that slug or whose category's parent matches that slug.
3. WHEN the `subcategory` query parameter is provided with a valid slug, THE system SHALL return only products whose category exactly matches that subcategory slug.
4. IF `category` or `subcategory` is provided with a slug that matches no category, THE system SHALL return an empty `data` array with `total` equal to `0`.
5. WHEN `sort=rating` is provided, THE system SHALL return products ordered by `rating` descending.
6. IF no `sort` is provided, THE system SHALL return products ordered by `created_at` descending.
7. THE system SHALL include `rating` and `reviews_count` in each product item in the response.
8. THE system SHALL include pagination metadata (`current_page`, `last_page`, `per_page`, `total`, `from`, `to`) and pagination links (`first`, `last`, `prev`, `next`) in the response.
