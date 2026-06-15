# Comprehensive Project Master Plan: Western Visayas Investment Profile

## 1. Project Introduction & Goal
The **Western Visayas Region 6 Investment & Economic Profile** is a premium digital platform designed to modernize how regional economic data is presented to the world. It replaces static printed books with an interactive, real-time web experience, aiming to attract domestic and foreign investors through data transparency, visual excellence, and conversational AI assistance.

## 2. Core Features & Capabilities
- **Dynamic Content Dashboard:** Interactive visual widgets (Charts, Graphs, Statistical Grids) that adapt to data updates.
- **AI Chat Assistant:** A RAG (Retrieval-Augmented Generation) chatbot utilizing Pinecone and Gemini to answer economic queries instantly.
- **Automated PDF Export:** Converts live web statistics into a professionally formatted offline document in-memory.
- **Investor Inquiry System:** A direct communication pipeline secured by reCAPTCHA.
- **Advanced Administrative CMS:** A secure backend allowing staff to manage complex economic data via standard CRUD, bulk JSON editing, year duplication, and skeleton layouts.

## 3. The Process Workflow
### For Administrators:
1. **Manage Content:** Update statistical data, section titles, and layout types (Hero, Table, Chart) across different reporting years.
2. **Accelerate Data Entry:** Use bulk JSON mode or duplicate previous years to streamline data ingestion.
3. **Review Inquiries:** Track and respond to investor interest directly from the portal.

### For Investors/Users:
1. **Explore Data:** Navigate through regional economic indicators in an engaging format, separated by year.
2. **Query the AI:** Ask specific economic questions and receive accurate, cached responses.
3. **Download Report:** Generate a current PDF version of the profile for offline analysis.

## 4. Technical Architecture
The system follows the **MVC (Model-View-Controller)** pattern for scalability and security, enhanced by AI capabilities.
- **Backend:** Laravel 12 (PHP) handling logic, PDF generation, and security.
- **AI Layer:** `AIChatService` orchestrating embeddings, Pinecone vector search, and Gemini text generation.
- **Frontend:** Blade templates built with Vite.
- **Data Tier:** Relational SQL database storing structured `ProjectContent` and `Inquiry` logs.

## 5. Security & Reliability
The application adheres to modern security standards:
- **Injection Protection:** Secured via Eloquent ORM.
- **XSS/CSRF Defenses:** Built-in Laravel safeguards.
- **Authentication:** Obscured login routes and encrypted credential storage.
- **Anti-Abuse:** Strict API rate limiting (14 req/min) and caching to protect the AI integration, plus reCAPTCHA on public forms.

## 6. Resource Catalog (Supporting Documents)
For deeper technical details, refer to the following specialized documents:
1. [Project Overview](1_Project_Overview.md)
2. [Process Workflow](2_Process_Workflow.md)
3. [Functional Requirements](3_Functional_Requirements.md)
4. [System Architecture](4_System_Architecture.md)
5. [Database Documentation](5_Database_Documentation.md)
6. [Deployment Guide](6_Deployment_Guide.md)
7. [User Manual](7_User_Manual.md)
8. [VAPT Report](8_VAPT_Report.md)
9. [Presentation Deck](9_Presentation.md)
10. [Master Project Plan](10_Project_Plan.md)
