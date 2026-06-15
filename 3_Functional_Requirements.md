[⏮️ Previous: Process Workflow](2_Process_Workflow.md) &nbsp; | &nbsp; [🏠 Home / README](README.md) &nbsp; | &nbsp; [⏭️ Next: System Architecture](4_System_Architecture.md)

---

# Functional Requirements

## 1. Frontend / Public Interface

### 1.1 Dynamic Data Presentation
- The system MUST display economic profile content dynamically based on data stored in the database, filterable by year.
- The system MUST support multiple UI layout types (Hero headers, Text blocks, Charts, Statistical grids, Data tables).

### 1.2 AI Chat Assistant
- The system MUST provide an AI chatbot interface.
- The chatbot MUST utilize Retrieval-Augmented Generation (RAG) to provide answers strictly based on the region's economic data.
- The chatbot API MUST be rate-limited (max 14 requests/minute) and responses MUST be cached for 1 hour to prevent API abuse.

### 1.3 Document Export
- The system MUST provide an automated feature to export the digital economic profile into a well-formatted A4 PDF document in-memory.

### 1.4 Communication (Inquiries)
- The system MUST provide a public contact form protected by Google reCAPTCHA.
- The form MUST capture the user's name, email, contact number, and message, saving it to the database and returning a pre-filled `mailto:` link.

## 2. Backend / Administrative Interface

### 2.1 Authentication & Authorization
- The system MUST require administrators to securely log in via a hidden route (`/portal-access-secret`).
- The system MUST restrict access to management routes (`/admin/*`) to authenticated users only.

### 2.2 Content Management System (CMS)
- The system MUST allow admins to Create, Read, Update, and Delete (CRUD) `ProjectContent` records.
- The system MUST support Bulk JSON Editing for rapid content modification.
- The system MUST provide tools to Duplicate an entire year of data or generate an empty Skeleton Year structure.
- The system MUST allow tracking of data `source` and `year_range` for citation purposes.

### 2.3 Inquiry Management
- The system MUST allow admins to view all submitted inquiries and delete them if necessary.

## 3. System Data
- The system MUST store `ProjectContent` data robustly, allowing flexible JSON structures for the `content` field.
- The system MUST store `Inquiry` data securely for lead management.
