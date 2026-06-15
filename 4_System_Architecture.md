[⏮️ Previous: Functional Requirements](3_Functional_Requirements.md) &nbsp; | &nbsp; [🏠 Home / README](README.md) &nbsp; | &nbsp; [⏭️ Next: Database Documentation](5_Database_Documentation.md)

---

# System Architecture

## 1. Architectural Pattern
The system is built on the **Model-View-Controller (MVC)** design pattern utilizing the robust Laravel 12 framework, enhanced with a Retrieval-Augmented Generation (RAG) AI layer.

1. **Model:** Represents the data structure, interfacing directly with the database (e.g., `User`, `ProjectContent`, `Inquiry`).
2. **View:** Handles the dynamic presentation of data using Blade templates (`resources/views`). This includes HTML layouts for web pages, the admin dashboard, and PDF rendering schemas.
3. **Controller:** Processes incoming requests, retrieves data from Models, orchestrates services (like PDF generation and AI integration), and passes data to the appropriate Views or JSON responses.

## 2. High-Level System Components

### 2.1 Frontend Tier (Client Side)
- **Web Browser:** Accesses HTML, CSS, JavaScript (built with Vite) served by the server, providing an interactive UI for administrators, public users, and the AI chatbot interface.

### 2.2 Application Tier (Server Side)
- **Laravel Framework (PHP ^8.2):** The core engine running routing, rate limiting (`throttle:14,1` for AI), authentication (`/portal-access-secret`), and application business logic.
- **PDF Engine (`barryvdh/laravel-dompdf`):** Processes specific Blade views populated with economic data and outputs them in-memory as a downloadable A4 PDF format.
- **AI RAG Service (`AIChatService`):** An abstraction layer that:
  - Generates embeddings for user queries.
  - Queries a Pinecone vector database for relevant economic profile context.
  - Prompts Google Gemini with the context and query to generate conversational, accurate responses.

### 2.3 Data Tier
- **Relational Database (MySQL / SQLite):** Persistent storage of system state, administrative users, dynamic page contents (stored securely as JSON), and user inquiries.
- **Vector Database (Pinecone):** Stores vector embeddings of the economic profile text data to allow for semantic search during AI chat resolution.
- **Cache (Redis / File):** Caches AI responses for 1 hour based on question hashes to reduce API costs and response times.

## 3. Data Flow Scenario (AI Chat Query)
1. **Request:** User submits a question via `POST /api/chat`.
2. **Rate Limiting:** The `throttle:14,1` middleware checks if the user has exceeded the allowed requests.
3. **Cache Check:** `ChatController` hashes the question and checks the cache. If found, returns immediately.
4. **Embedding:** `AIChatService` embeds the text string.
5. **Retrieval:** Service queries Pinecone with the embedding and retrieves the top 3 relevant text chunks.
6. **Generation:** Service sends the retrieved context and original question to the Gemini API.
7. **Response:** The generated text is cached and returned to the user as JSON.
