[⏮️ Previous: Project Overview](1_Project_Overview.md) &nbsp; | &nbsp; [🏠 Home / README](README.md) &nbsp; | &nbsp; [⏭️ Next: Functional Requirements](3_Functional_Requirements.md)

---

# Process Workflow

## 1. Administrator Workflow

### 1.1 Content Management
1. **Login:** Administrator logs into the secure backend portal.
2. **Dashboard:** Views an overview of current content and recent inquiries.
3. **Manage Contents:** 
   - Adds a new `ProjectContent` entry.
   - Specifies `page_number`, `section_title`, and the display `type` (e.g., hero, chart, table, stats_grid).
   - Inputs the corresponding data as structured JSON.
   - Sets the `year_range` and data `source` to ensure data validity.
4. **Publish:** The content is saved to the database and instantly reflects on the frontend public views and PDF exports.

### 1.2 Inquiry Handling
1. **Review:** Administrator navigates to the Inquiries module.
2. **Read & Respond:** Reviews incoming messages from interested investors and contacts them via the provided email or phone number.

## 2. User/Investor Workflow

### 2.1 Browsing Economic Data
1. **Homepage:** User lands on the platform and views the "Hero" section highlighting key regional statistics.
2. **Explore Sections:** User navigates through various dynamic pages containing charts, tables, and lists detailing the economic profile.
3. **Download PDF:** User clicks the "Download Profile" button, triggering the system to compile the latest `ProjectContent` from the database into a downloadable PDF format.

### 2.2 Submitting an Inquiry
1. **Contact Us:** User navigates to the inquiry form.
2. **Fill Form:** User provides their Name, Email, Contact Number, and their Message/Intent.
3. **Submit:** The system stores the inquiry in the database and potentially sends a notification to the admin. User receives a success acknowledgment.
