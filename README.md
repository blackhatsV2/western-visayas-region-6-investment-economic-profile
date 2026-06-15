# Western Visayas Region 6 Investment & Economic Profile

A premium, data-driven web application showcasing the investment potential and economic profile of Western Visayas (Region VI), Philippines. Built with Laravel 12, Tailwind CSS, and powered by an AI conversational assistant.

## 🚀 Core Capabilities

1. **Public Information Portal**: View dynamic economic data, charts, and statistics for the region, filterable by year.
2. **AI Assistant (RAG Chatbot)**: Ask an intelligent assistant questions about the regional economy. It uses Pinecone vector search and the Google Gemini API to formulate accurate answers based strictly on the profile's data.
3. **PDF Export Generation**: Download a full, dynamically generated A4 PDF version of the economic profile for offline reading using `barryvdh/laravel-dompdf`.
4. **Admin Content Management System (CMS)**: A secure backend providing standard CRUD, Bulk JSON Editing, and "Duplicate/Skeleton Year" tools to manage the region's data.
5. **Investor Inquiry System**: A reCAPTCHA-secured contact form that routes prospective investors directly to regional administrators.

## 📚 Documentation

Explore the project's documentation to understand the architecture, features, and deployment procedures. Click on any file below to view its contents:

- 📖 [1. Project Overview](1_Project_Overview.md)
- ⚙️ [2. Process Workflow](2_Process_Workflow.md)
- 📋 [3. Functional Requirements](3_Functional_Requirements.md)
- 🏗️ [4. System Architecture](4_System_Architecture.md)
- 🗄️ [5. Database Documentation](5_Database_Documentation.md)
- 🚀 [6. Deployment Guide](6_Deployment_Guide.md)
- 📘 [7. User Manual](7_User_Manual.md)
- 🛡️ [8. VAPT Report](8_VAPT_Report.md)
- 📊 [9. Presentation Deck](9_Presentation.md)
- 🗺️ [10. Master Project Plan / Roadmap](10_Project_Plan.md)

## 🚀 Getting Started

You can set up this project using either **Docker (Recommended)** or a **Manual Local Setup**.

---

### Option 1: Docker Setup (Fastest)

This project uses [Laravel Sail](https://laravel.com/docs/sail), a light-weight command-line interface for interacting with the project's Docker configuration.

#### Prerequisites
- [Docker Desktop](https://www.docker.com/products/docker-desktop) installed and running.

#### Setup Steps
1. **Clone the repository**:
   ```bash
   git clone https://github.com/blackhatsV1/western-visayas-region-6-investment-economic-profile
   cd western-visayas-region-6-investment-economic-profile
   ```

2. **Install dependencies**:
   ```bash
   docker run --rm \
       -u "$(id -u):$(id -g)" \
       -v "$(pwd):/var/www/html" \
       -w /var/www/html \
       laravelsail/php82-composer:latest \
       composer install --ignore-platform-reqs
   ```

3. **Configure Environment**:
   ```bash
   cp .env.example .env
   ```
   *Note: Sail automatically configures the database connection. You MUST also add your `GEMINI_API_KEY`, `PINECONE_API_KEY`, `RECAPTCHA_SITE_KEY`, and `RECAPTCHA_SECRET_KEY`.*

4. **Start the containers**:
   ```bash
   ./vendor/bin/sail up -d
   ```

5. **Initialize Application**:
   ```bash
   ./vendor/bin/sail artisan key:generate
   ./vendor/bin/sail artisan migrate --seed
   ./vendor/bin/sail npm install
   ./vendor/bin/sail npm run build
   ```

The app will be available at [http://localhost](http://localhost).

---

### Option 2: Manual Local Setup

Use this if you prefer running PHP and MySQL directly on your host machine.

#### Prerequisites
- **PHP 8.2+**
- **Composer**
- **Node.js & NPM**
- **MySQL 8.0+**

#### Setup Steps
1. **Clone and Install**:
   ```bash
   git clone https://github.com/blackhatsV1/western-visayas-region-6-investment-economic-profile
   cd western-visayas-region-6-investment-economic-profile
   composer install
   ```

2. **Environment Setup**:
   - `cp .env.example .env`
   - Update your `.env` file with your local MySQL credentials (`DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`).
   - Add your API keys (`GEMINI_API_KEY`, `PINECONE_API_KEY`, `RECAPTCHA_*`).

3. **Database Initialization**:
   ```bash
   php artisan key:generate
   php artisan migrate --seed
   ```

4. **Frontend Build**:
   ```bash
   npm install
   npm run build
   ```

5. **Start Server**:
   ```bash
   php artisan serve
   ```

The app will be available at [http://127.0.0.1:8000](http://127.0.0.1:8000).

---

## 📊 Data Management & Admin Tools

Administrators can access the backend via the secure `/portal-access-secret` route. The application supports standard CRUD operations, Bulk JSON Editing, and tools to Duplicate or Skeleton years for rapid content management.

## 🛠 Tech Stack
- **Backend**: Laravel 12 (PHP 8.2+)
- **Frontend**: Tailwind CSS, Alpine.js, Blade Templates (Vite build)
- **AI Integration**: Google Gemini API, Pinecone Vector DB
- **PDF Engine**: `barryvdh/laravel-dompdf`
- **Database**: MySQL

## 📄 License
The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
