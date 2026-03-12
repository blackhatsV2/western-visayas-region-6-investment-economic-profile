[⏮️ Previous: Deployment Guide](6_Deployment_Guide.md) &nbsp; | &nbsp; [🏠 Home / README](README.md) &nbsp; | &nbsp; [⏭️ Next: VAPT Report](8_VAPT_Report.md)

---

# User Manual

## 1. Introduction
Welcome to the Western Visayas Region 6 Investment Economic Profile. This guide helps administrators manage the platform, and outlines the frontend features available to public users.

## 2. Administrator Guide

### 2.1 Logging In
1. Navigate to your application's `/login` route.
2. Enter your registered Administrator Email and Password.
3. Click "Log in".

### 2.2 Managing Economic Profile Contents
1. On the dashboard menu, click **Manage Content**.
2. **Add New Section:** Click "Create Content".
3. **Fill in Details:**
   - **Page Number:** Assign an integer to set the display order.
   - **Section Title:** Provide a readable heading.
   - **Type:** Select the appropriate widget type (e.g., `chart`, `table`, `text`, `hero`, `stats_grid`).
   - **Year Range:** Indicate the relevant year limit for the statistical data.
   - **Data Source:** Attribute the data correctly (e.g., PSA, DTI).
   - **Content:** Supply the valid JSON structure matching the chosen Type.
4. Click **Save / Publish**. The changes will immediately reflect on the frontend and in newly generated PDFs.
5. **Editing:** Click the 'Edit' icon next to an existing content entry to adjust its data.

### 2.3 Managing Inquiries
1. From the dashboard menu, go to **Inquiries**.
2. A table list of recent inquiries will display.
3. Click on the inquiry row to expand and view the full message, email address, contact number, and the submitter’s name.

## 3. Public User Guide

### 3.1 Viewing the Profile
- Open the application URL in a modern web browser.
- Scroll vertically through the presentation. The sections flow logically based on the `page_number` configuration established by the admin.
- Interactive charts and map representations can be hovered over for detailed tooltips.

### 3.2 Downloading the PDF Report
- Locate the **"Download Economic Profile"** button typically found on the hero banner at the top of the homepage or floating on the screen.
- Click the button to automatically generate and download an offline-friendly PDF copy of the most up-to-date regional economic profile.

### 3.3 Contacting the Region
- Click "Contact Us" or scroll to the bottom footer.
- Fill out the Inquiry form with your details to express investment interest.
