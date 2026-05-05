[⏮️ Previous: System Architecture](4_System_Architecture.md) &nbsp; | &nbsp; [🏠 Home / README](README.md) &nbsp; | &nbsp; [⏭️ Next: Deployment Guide](6_Deployment_Guide.md)

---

# Database Documentation

## 1. Overview
The system relies on a relational database architecture. This document outlines the primary tables powering the application.

## 2. Tables

### 2.1 `users` Table
Handles application administrators and authorized personnel.

| Column | Type | Constraints | Description |
| :--- | :--- | :--- | :--- |
| `id` | BigInteger | Primary Key, Auto Increment | Unique user ID. |
| `name` | String | Not Null | Full name of the user. |
| `email` | String | Unique, Not Null | Email address for login configuration/contact. |
| `email_verified_at` | Timestamp | Nullable | Records when email validation occurred. |
| `password` | String | Not Null | Bcrypt hashed securely. |
| `remember_token` | String(100) | Nullable | For "remember me" functionality. |
| `created_at` / `updated_at` | Timestamp | Nullable | Standard Laravel timestamps. |

### 2.2 `project_contents` Table
The core table storing dynamic content sections for the economic profile pages and PDF generator.

| Column | Type | Constraints | Description |
| :--- | :--- | :--- | :--- |
| `id` | BigInteger | Primary Key, Auto Increment | Unique record ID. |
| `page_number` | Integer | Not Null | Defines order flow of the presentation. |
| `section_title` | String | Nullable | Human-readable title of the data section. |
| `type` | String | Not Null | Differentiates the layout/component (hero, text, list, chart, stats_grid, table). |
| `year_range` | String | Nullable | Applicable timeframe for statistics (e.g., '2021-2025', '2024'). |
| `content` | JSON | Not Null | Structured data specific to the widget type. |
| `source` | Text | Nullable | Citation metadata ensuring data credibility. |
| `created_at` / `updated_at` | Timestamp | Nullable | Standard Laravel timestamps. |

### 2.3 `inquiries` Table
Records messages from investors or the general public submitted through the contact forms.

| Column | Type | Constraints | Description |
| :--- | :--- | :--- | :--- |
| `id` | BigInteger | Primary Key, Auto Increment | Unique inquiry ID. |
| `name` | String | Not Null | Submitter's name. |
| `email` | String | Not Null | Submitter's email. |
| `contact` | String | Not Null | Submitter's phone or secondary contact details. |
| `message` | Text | Not Null | The body/intent of the submission. |
| `created_at` / `updated_at` | Timestamp | Nullable | Timestamps indicating when the message was received and potentially acted upon. |
