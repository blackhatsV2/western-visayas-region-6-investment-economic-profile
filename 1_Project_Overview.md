[🏠 Home / README](README.md) &nbsp; | &nbsp; [⏭️ Next: Process Workflow](2_Process_Workflow.md)

---

# Project Overview: Western Visayas Region 6 Investment Economic Profile

## 1. Introduction
The **Western Visayas Region 6 Investment Economic Profile** is a web-based digital platform designed to showcase the economic landscape, investment opportunities, and vital statistics of Region 6 in the Philippines. It combines dynamic data visualization with an AI-powered assistant to provide a comprehensive, interactive experience.

## 2. Objective
The primary goal of this system is to attract domestic and foreign investors by providing them with easily accessible, up-to-date, and visually appealing economic data. It digitizes the traditional economic profile book into an interactive web experience, a downloadable PDF format, and features a conversational AI assistant to instantly answer investor queries.

## 3. Scope
The project covers:
- **Dynamic Content Management:** Allowing administrators to easily update texts, charts, statistical grids, and tables through a structured backend, including bulk JSON editing, year duplication, and skeleton creation.
- **Interactive Data Presentation:** Frontend views displaying various economic indicators based on data from specific years.
- **PDF Generation:** Automated in-memory conversion (`barryvdh/laravel-dompdf`) of the online economic profile into a professionally formatted offline document.
- **AI Chat Assistant:** A Retrieval-Augmented Generation (RAG) chatbot using Pinecone and the Gemini API to intelligently answer user questions based on the region's economic data.
- **Inquiry Management:** A communication channel secured with Google reCAPTCHA for prospective investors to send inquiries directly to the region's economic development administrators.

## 4. Target Audience
- Foreign and Local Investors
- Business Development Agencies
- Government Officials & Policy Makers
- Researchers and Academics

## 5. Key Technologies
* **Framework:** Laravel 12 (PHP)
* **Database:** MySQL / SQLite
* **Frontend:** Blade Templating, HTML/CSS/JS (Vite)
* **Export:** `barryvdh/laravel-dompdf` for PDF Generation
* **AI & Search:** Google Gemini API, Pinecone Vector Database
