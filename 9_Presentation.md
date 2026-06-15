[⏮️ Previous: VAPT Report](8_VAPT_Report.md) &nbsp; | &nbsp; [🏠 Home / README](README.md) &nbsp; | &nbsp; [⏭️ Next: Project Plan](10_Project_Plan.md)

---

# Presentation Deck: Western Visayas Region 6 Investment Economic Profile

## Slide 1: Title
**Western Visayas Region 6**
*Investment Economic Profile Platform*
Empowering regional investment through digital transparency, automated reporting, and AI assistance.

---

## Slide 2: The Need
Traditional economic profiles are printed books. By the time they are printed, data is often stale, and searching through hundreds of pages is inefficient.
**The Challenge:** Provide up-to-date, engaging, and instantly accessible economic indicators to potential investors worldwide.

---

## Slide 3: Our Solution
A dynamic, automated web platform built on Laravel that digitizes the regional context.
- Live Public Web Portal with Interactive Data
- Conversational AI Assistant for instant answers
- Automated PDF Engine for standardized document generation
- Admin CMS for instant data updates

---

## Slide 4: Key Features
1. **Dynamic Content:** Different visual widgets (charts, graphs, statistical grids) controlled via database `ProjectContent` JSON.
2. **AI-Powered RAG Chatbot:** Users can chat with an intelligent assistant powered by Google Gemini and Pinecone Vector DB to extract specific insights without manual reading.
3. **Contact Conversion:** Direct Inquiry pipeline linking investors immediately with the regional authorities, secured by reCAPTCHA.
4. **On-the-Fly Formatting:** "Download Profile" converts live web data into an offline A4 PDF brochure.
5. **Advanced Admin Toolkit:** Bulk JSON editors, Year Duplication, and Skeleton generation for rapid data entry.

---

## Slide 5: The Technology Stack
- **Backend:** Laravel 12 (PHP)
- **Database:** Relational SQL DB + Pinecone Vector Database
- **AI Integration:** Google Gemini Generative API
- **Frontend Architecture:** Blade Templating, Vite-compiled CSS/JS
- **Security:** Built-in safeguards against XSS, CSRF, SQL Injection, and API Abuse (Rate Limiting & Caching).

---

## Slide 6: Q&A
Thank you. We welcome any discussions about the platform architecture, deployment, or user experience.
