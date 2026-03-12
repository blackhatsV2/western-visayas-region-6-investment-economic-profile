[⏮️ Previous: Functional Requirements](3_Functional_Requirements.md) &nbsp; | &nbsp; [🏠 Home / README](README.md) &nbsp; | &nbsp; [⏭️ Next: Database Documentation](5_Database_Documentation.md)

---

# System Architecture

## 1. Architectural Pattern
The system is built on the **Model-View-Controller (MVC)** design pattern utilizing the robust Laravel framework.

1. **Model:** Represents the data structure, interfacing directly with the database (e.g., `User`, `ProjectContent`, `Inquiry`).
2. **View:** Handles the dynamic presentation of data using Blade templates (`resources/views`). This includes HTML layouts for web pages and PDF rendering schemas.
3. **Controller:** Processes incoming requests, retrieves data from Models, and passes that data to the appropriate Views.

## 2. High-Level System Components

### 2.1 Frontend Tier (Client Side)
- **Web Browser:** Accesses HTML, CSS, JavaScript served by the server, providing an interactive UI for administrators and public users.

### 2.2 Application Tier (Server Side)
- **Laravel Framework (PHP):** The core engine running routing, middleware for authentication, logging, and application business logic.
- **PDF Engine:** An integration (e.g., laravel-dompdf, snappy) that processes specific Blade views and outputs them as a downloadable PDF format.

### 2.3 Data Tier
- **Relational Database (MySQL):** Persistent storage of system state, configuration data, dynamic page contents, and user inquiries.

## 3. Data Flow Scenario (Content Retrieval)
1. **Request:** User visits `GET /profile/show` to view the economic profile.
2. **Route:** Laravel's routing engine directs the request to `ProfileController@show`.
3. **Controller/Model:** The Controller calls `ProjectContent::orderBy('page_number', 'asc')->get()` to fetch all available contents.
4. **View Compilation:** Controller passes the dataset to a Blade view (`profile.blade.php`). The View conditionally renders different semantic HTML based on the `type` parameter (e.g., rendering a chart if `type == 'chart'`).
5. **Response:** A fully populated HTML page is returned to the user's browser.
