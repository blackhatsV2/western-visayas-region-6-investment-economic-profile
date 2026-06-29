# Domain Glossary

This glossary defines domain-specific terms, acronyms, and technical vocabulary for the Western Visayas Region 6 Investment Economic Profile project. All stages must use these terms consistently.

---

## Project-Specific Terms

| Term | Definition |
|---|---|
| **Economic Profile** | A comprehensive data publication showcasing the economic indicators, investment opportunities, and statistics of Western Visayas Region 6 |
| **ProjectContent** | The primary Eloquent model representing a content entry (chart, table, hero section, stats grid) tied to a specific year, page number, and section |
| **Section Type** | The display format of a `ProjectContent` entry. Valid types: `hero`, `chart`, `table`, `stats_grid`, `text`, `list`, `image` |
| **Year Duplication** | Admin feature to clone all `ProjectContent` entries from one year to another for rapid data entry |
| **Skeleton Year** | Admin feature to create empty `ProjectContent` structures for a new year, preserving page/section layout without data |
| **Bulk JSON Editing** | Admin feature allowing direct editing of `ProjectContent.content` JSON fields through a code editor interface |
| **Profile PDF** | The downloadable A4 PDF compiled from `ProjectContent` data using `barryvdh/laravel-dompdf` |
| **Grid View** | The admin interface displaying `ProjectContent` entries in a card-based grid layout for visual management |

## Government / Regional Terms

| Term | Definition |
|---|---|
| **Region 6** | Western Visayas — a region in the Philippines comprising Aklan, Antique, Capiz, Guimaras, Iloilo, and Negros Occidental |
| **DTI** | Department of Trade and Industry — the primary government agency promoting economic development |
| **NEDA** | National Economic and Development Authority — responsible for economic planning |
| **BOI** | Board of Investments — government body managing investment incentives |
| **PEZA** | Philippine Economic Zone Authority — manages special economic zones |
| **LGU** | Local Government Unit — provincial, city, or municipal government |
| **GRDP** | Gross Regional Domestic Product — total economic output of the region |

## Technical Terms

| Term | Definition |
|---|---|
| **RAG** | Retrieval-Augmented Generation — AI pattern that retrieves relevant context from a knowledge base before generating a response |
| **Pinecone** | Cloud-hosted vector database used to store and query text embeddings for the AI chat assistant |
| **Gemini API** | Google's generative AI API used for embedding generation and conversational response generation |
| **Embedding** | A numerical vector representation of text, used for semantic similarity search |
| **Throttle** | Laravel rate-limiting middleware. The AI chat endpoint uses `throttle:14,1` (14 requests/minute) |
| **DoW Attack** | Denial of Wallet — an attack that forces excessive API calls to inflate costs |
| **VAPT** | Vulnerability Assessment and Penetration Testing — security evaluation methodology |
| **OWASP Top 10** | The ten most critical web application security risks as defined by OWASP |
| **CSRF** | Cross-Site Request Forgery — an attack forcing authenticated users to execute unwanted actions |
| **XSS** | Cross-Site Scripting — an attack injecting malicious scripts into web content |

## Acronyms Quick Reference

| Acronym | Expansion |
|---|---|
| MVC | Model-View-Controller |
| ORM | Object-Relational Mapping |
| PDO | PHP Data Objects |
| API | Application Programming Interface |
| CRUD | Create, Read, Update, Delete |
| SPA | Single Page Application |
| ICM | Interpretable Context Methodology |
