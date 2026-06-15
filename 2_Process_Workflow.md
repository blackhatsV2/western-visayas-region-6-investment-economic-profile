[⏮️ Previous: Project Overview](1_Project_Overview.md) &nbsp; | &nbsp; [🏠 Home / README](README.md) &nbsp; | &nbsp; [⏭️ Next: Functional Requirements](3_Functional_Requirements.md)

---

# Process Workflow

## 1. Administrator Workflow

### 1.1 Content Management
1. **Login:** Administrator logs into the secure backend portal via a hidden route (`/portal-access-secret`).
2. **Dashboard:** Views an overview of current content and administrative options.
3. **Manage Contents:** 
   - Manages `ProjectContent` entries via standard CRUD, Grid View, or Bulk JSON Editing.
   - Specifies `page_number`, `section_title`, and the display `type` (e.g., hero, chart, table, stats_grid).
   - Can Duplicate an entire year's data or create a Skeleton Year for rapid data entry.
4. **Publish:** The content is saved to the database and instantly reflects on the frontend public views and PDF exports.

### 1.2 Inquiry Handling
1. **Review:** Administrator navigates to the Inquiries module to review messages from interested investors.

## 2. User/Investor Workflow

### 2.1 Browsing Economic Data
1. **Homepage:** User lands on the platform and views the content for the most recent year.
2. **Explore Sections:** User navigates through various dynamic pages containing charts, tables, and lists.
3. **Download PDF:** User clicks the "Download Profile" button, triggering the system to compile the latest `ProjectContent` from the database into a downloadable PDF format.

### 2.2 Using the AI Chat Assistant
1. **Ask Question:** User opens the chat interface and asks a question regarding the economic profile.
2. **AI Processing:** The system retrieves relevant context from the Pinecone vector database and generates a conversational answer via the Gemini API.
3. **Response:** The user receives an accurate, contextual answer (responses are cached to optimize performance).

### 2.3 Submitting an Inquiry
1. **Contact Us:** User navigates to the inquiry form and completes the reCAPTCHA.
2. **Fill Form:** User provides their Name, Email, Contact Number, and their Message/Intent.
3. **Submit:** The system stores the inquiry in the database and generates a `mailto:` link pre-filled with the user's details for easy communication routing.
