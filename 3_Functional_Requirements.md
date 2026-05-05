[⏮️ Previous: Process Workflow](2_Process_Workflow.md) &nbsp; | &nbsp; [🏠 Home / README](README.md) &nbsp; | &nbsp; [⏭️ Next: System Architecture](4_System_Architecture.md)

---

# Functional Requirements

## 1. Frontend / Public Interface

### 1.1 Dynamic Data Presentation
- The system MUST display economic profile content dynamically based on data stored in the database.
- The system MUST support multiple UI layout types:
  - Hero headers
  - Text blocks
  - Bulleted lists
  - Charts & Graphs
  - Statistical grids (stats_grid)
  - Data tables

### 1.2 Document Export
- The system MUST provide an automated feature to export the digital economic profile into a well-formatted PDF document.

### 1.3 Communication (Inquiries)
- The system MUST provide a public contact form.
- The form MUST capture the user's name, email, contact number, and message.
- The system MUST validate these inputs before submission.

## 2. Backend / Administrative Interface

### 2.1 Authentication & Authorization
- The system MUST require administrators to securely log in.
- The system MUST restrict access to management routes to authenticated users only.

### 2.2 Content Management System (CMS)
- The system MUST allow admins to Create, Read, Update, and Delete (CRUD) `ProjectContent` records.
- Each content record MUST accept structured JSON data to accommodate varying data needs for charts, tables, and text.
- The system MUST allow ordering of contents via `page_number`.
- The system MUST allow tracking of data `source` and `year_range` for citation purposes.

### 2.3 Inquiry Management
- The system MUST allow admins to view all submitted inquiries.
- The system MUST display inquiry details (timestamp, sender details, message body).

## 3. System Data
- The system MUST store `ProjectContent` data robustly, allowing flexible JSON structures.
- The system MUST store `Inquiry` data securely for lead management.
