[⏮️ Previous: Deployment Guide](6_Deployment_Guide.md) &nbsp; | &nbsp; [🏠 Home / README](README.md) &nbsp; | &nbsp; [⏭️ Next: VAPT Report](8_VAPT_Report.md)

---

# User Manual

## 1. Introduction
Welcome to the Western Visayas Region 6 Investment Economic Profile. This guide helps administrators manage the platform, and outlines the frontend features available to public users.

## 2. Administrator Guide

### 2.1 Logging In
1. Navigate to your application's secure hidden login route: `/portal-access-secret`.
2. Enter your registered Administrator Email and Password.
3. Click "Log in".

### 2.2 Managing Economic Profile Contents
1. On the dashboard menu, click **Manage Content**.
2. **Standard Editing:** Add or Edit entries one by one, specifying the widget type, page number, title, and inserting the corresponding JSON configuration for charts and texts.
3. **JSON View (Bulk Edit):** Access the `/admin/json` view to edit the entire raw database JSON structure simultaneously for rapid content formatting across multiple pages.
4. **Duplicate Year:** Use the "Duplicate" functionality to copy an entire previous year's structure into a new year, eliminating repetitive data entry layout work.
5. **Skeleton Year:** Quickly bootstrap an empty set of components for a new reporting year.
6. **Export Data:** Download current statistics datasets directly from the admin panel.

### 2.3 Managing Inquiries
1. From the dashboard menu, go to **Inquiries**.
2. Review the list of inquiries. Click to expand and read messages submitted through the contact form, and use the provided details to respond externally.

## 3. Public User Guide

### 3.1 Viewing the Profile
- Select a specific **Year** from the dropdown menu to view the relevant data.
- Scroll vertically through the presentation. The sections flow logically based on the `page_number` configuration established by the admin.
- Interactive charts and map representations can be hovered over for detailed tooltips.

### 3.2 Using the AI Assistant
- Locate the Chat interface.
- Ask questions about the regional economy (e.g., "What are the major agricultural exports in 2024?").
- The AI will provide a summarized, conversational answer referencing the profile data.

### 3.3 Downloading the PDF Report
- Locate the **"Download Profile"** button on the interface.
- Click the button to automatically generate and download an offline-friendly A4 PDF copy of the selected year's profile.

### 3.4 Contacting the Region
- Click "Contact Us" to jump to the inquiry form.
- Fill out your name, email, contact, and message.
- Complete the Google reCAPTCHA challenge and submit. A pre-filled email client link will optionally open for direct mailing.
