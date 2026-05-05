<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Style\Font;
use PhpOffice\PhpWord\Style\ListItem;
use PhpOffice\PhpWord\SimpleType\Jc;
use PhpOffice\PhpWord\IOFactory;

class GenerateDocumentation extends Command
{
    protected $signature = 'docs:generate';
    protected $description = 'Generate User Documentation and Technical Documentation in DOCX format';

    // Shared style constants
    private const COLOR_PRIMARY = '1B5E20';
    private const COLOR_DARK = '212121';
    private const COLOR_ACCENT = '2E7D32';
    private const COLOR_LIGHT_BG = 'E8F5E9';
    private const COLOR_WHITE = 'FFFFFF';
    private const COLOR_TABLE_HEADER = '1B5E20';
    private const COLOR_TABLE_ALT = 'F1F8E9';

    public function handle()
    {
        ini_set('memory_limit', '512M');
        set_time_limit(0);

        $outputDir = storage_path('app/documentation');
        if (!is_dir($outputDir)) {
            mkdir($outputDir, 0755, true);
        }

        $this->info('Generating User Documentation...');
        $this->generateUserDocumentation($outputDir);
        $this->info('User Documentation generated.');

        $this->info('Generating Technical Documentation...');
        $this->generateTechnicalDocumentation($outputDir);
        $this->info('Technical Documentation generated.');

        $this->info('');
        $this->info('✅ Documentation generated successfully!');
        $this->info("📁 Files saved to: {$outputDir}/");
        $this->info('   • User_Documentation.docx');
        $this->info('   • Technical_Documentation.docx');

        return Command::SUCCESS;
    }

    // =========================================================================
    // SHARED HELPERS
    // =========================================================================

    private function registerStyles(PhpWord $phpWord): void
    {
        // Explicitly set default font and size
        $phpWord->setDefaultFontName('Calibri');
        $phpWord->setDefaultFontSize(11);

        $phpWord->addTitleStyle(1, [
            'bold' => true, 'size' => 26, 'color' => self::COLOR_PRIMARY,
            'spaceAfter' => 120,
        ]);
        $phpWord->addTitleStyle(2, [
            'bold' => true, 'size' => 18, 'color' => self::COLOR_ACCENT,
            'spaceBefore' => 240, 'spaceAfter' => 120,
        ]);
        $phpWord->addTitleStyle(3, [
            'bold' => true, 'size' => 14, 'color' => self::COLOR_DARK,
            'spaceBefore' => 200, 'spaceAfter' => 80,
        ]);

        $phpWord->addParagraphStyle('Normal', ['spaceAfter' => 120, 'lineHeight' => 1.15]);
        $phpWord->addFontStyle('BoldText', ['bold' => true]);
        $phpWord->addFontStyle('CodeFont', ['name' => 'Consolas', 'size' => 9, 'color' => self::COLOR_DARK]);
    }

    private function addCoverPage($section, string $title, string $subtitle, string $docType): void
    {
        $section->addTextBreak(4);

        $section->addText(
            $this->esc($title),
            ['bold' => true, 'size' => 36, 'color' => self::COLOR_PRIMARY, 'name' => 'Calibri'],
            ['alignment' => Jc::CENTER, 'spaceAfter' => 60]
        );

        $section->addText(
            $this->esc($subtitle),
            ['size' => 16, 'color' => self::COLOR_ACCENT, 'italic' => true],
            ['alignment' => Jc::CENTER, 'spaceAfter' => 200]
        );

        $section->addTextBreak(1);

        $section->addText(
            $this->esc($docType),
            ['bold' => true, 'size' => 20, 'color' => self::COLOR_DARK],
            ['alignment' => Jc::CENTER, 'spaceAfter' => 80]
        );

        $section->addText(
            $this->esc('Version 1.0'),
            ['size' => 12, 'color' => '757575'],
            ['alignment' => Jc::CENTER, 'spaceAfter' => 40]
        );

        $section->addText(
            $this->esc(date('F j, Y')),
            ['size' => 12, 'color' => '757575'],
            ['alignment' => Jc::CENTER, 'spaceAfter' => 200]
        );

        $section->addTextBreak(4);

        $section->addText(
            $this->esc('Department of Trade and Industry — Region VI (Western Visayas)'),
            ['size' => 11, 'color' => '9E9E9E'],
            ['alignment' => Jc::CENTER]
        );
    }

    private function addTableOfContents($section, array $items): void
    {
        $section->addTitle($this->esc('Table of Contents'), 1);
        foreach ($items as $number => $title) {
            $section->addText(
                "{$number}.  " . $this->esc($title),
                ['size' => 12, 'color' => self::COLOR_DARK],
                ['spaceAfter' => 60, 'indent' => 0.5]
            );
        }
        $section->addPageBreak();
    }

    private function addStyledTable($section, array $headers, array $rows): void
    {
        $tableStyle = [
            'borderSize' => 4,
            'borderColor' => 'BDBDBD',
            'cellMargin' => 80,
        ];

        $table = $section->addTable($tableStyle);

        // Header row
        $table->addRow(null, ['tblHeader' => true]);
        foreach ($headers as $header) {
            $cell = $table->addCell(null, ['bgColor' => self::COLOR_TABLE_HEADER, 'valign' => 'center']);
            $cell->addText($this->esc($header), ['bold' => true, 'color' => self::COLOR_WHITE, 'size' => 10], ['spaceAfter' => 0]);
        }

        // Data rows
        foreach ($rows as $i => $row) {
            $bgColor = ($i % 2 === 0) ? self::COLOR_WHITE : self::COLOR_TABLE_ALT;
            $table->addRow();
            foreach ($row as $cellText) {
                $cell = $table->addCell(null, ['bgColor' => $bgColor, 'valign' => 'center']);
                $cell->addText($this->esc($cellText), ['size' => 10], ['spaceAfter' => 0]);
            }
        }

        $section->addTextBreak(1);
    }

    private function addBulletList($section, array $items): void
    {
        foreach ($items as $item) {
            $section->addListItem($this->esc($item), 0, ['size' => 11], ['listType' => ListItem::TYPE_BULLET_FILLED], ['spaceAfter' => 40]);
        }
        $section->addTextBreak(0);
    }

    private function addCodeBlock($section, string $code): void
    {
        $lines = explode("\n", $code);
        $codeTable = $section->addTable(['borderSize' => 1, 'borderColor' => 'E0E0E0']);
        $codeTable->addRow();
        $cell = $codeTable->addCell(null, ['bgColor' => 'F5F5F5']);
        foreach ($lines as $line) {
            $cell->addText(
                $this->esc($line),
                ['name' => 'Consolas', 'size' => 9, 'color' => self::COLOR_DARK],
                ['spaceAfter' => 0, 'lineHeight' => 1.0]
            );
        }
        $section->addTextBreak(1);
    }

    private function addNote($section, string $text): void
    {
        $noteTable = $section->addTable(['borderSize' => 0]);
        $noteTable->addRow();
        $cell = $noteTable->addCell(null, ['bgColor' => self::COLOR_LIGHT_BG, 'borderLeftSize' => 18, 'borderLeftColor' => self::COLOR_ACCENT]);
        $cell->addText(
            $this->esc('💡 ' . $text),
            ['size' => 10, 'italic' => true, 'color' => self::COLOR_ACCENT],
            ['spaceAfter' => 0, 'indent' => 0.2]
        );
        $section->addTextBreak(1);
    }

    // =========================================================================
    // USER DOCUMENTATION
    // =========================================================================

    private function generateUserDocumentation(string $outputDir): void
    {
        $phpWord = new PhpWord();
        $this->registerStyles($phpWord);

        // ----- Cover Page -----
        $coverSection = $phpWord->addSection();
        $this->addCoverPage(
            $coverSection,
            'Western Visayas Investment Profile',
            'Region VI Economic & Investment Data Platform',
            'User Documentation'
        );

        // ----- Table of Contents -----
        $tocSection = $phpWord->addSection();
        $this->addTableOfContents($tocSection, [
            1 => 'User Manual',
            2 => 'Installation Guide',
            3 => 'Frequently Asked Questions (FAQs)',
            4 => 'Tutorials',
        ]);

        // =====================================================================
        // SECTION 1: USER MANUAL
        // =====================================================================
        $section = $phpWord->addSection();
        $section->addTitle($this->esc('1. User Manual'), 1);

        $section->addTitle($this->esc('1.1 System Overview'), 2);
        $section->addText(
            $this->esc('The Western Visayas Investment Profile is a premium, data-driven web application that showcases the investment potential and economic profile of Western Visayas (Region VI), Philippines. It serves as a digital investment funnel, presenting key economic indicators, infrastructure data, and industry profiles to attract potential investors to the region.'),
            null, 'Normal'
        );
        $section->addText(
            $this->esc('The platform is designed for two types of users: Public Visitors who browse the investment data, and Administrators who manage the content through a secure admin panel.'),
            null, 'Normal'
        );

        // 1.2 Key Features
        $section->addTitle($this->esc('1.2 Key Features'), 2);

        $section->addTitle($this->esc('Public Website'), 3);
        $this->addBulletList($section, [
            'Interactive economic profile with dynamic charts and data visualizations (ApexCharts)',
            'Interactive maps showing regional infrastructure (Leaflet.js)',
            'Year-based data filtering — switch between different year ranges to compare data',
            'PDF download of the full investment profile for any year',
            'Contact/inquiry form to reach out to DTI Region VI directly',
            'Mobile-responsive design with PWA support for home screen installation',
            'Smooth animations and modern "Mint & Black" premium theme',
        ]);

        $section->addTitle($this->esc('Admin Panel'), 3);
        $this->addBulletList($section, [
            'Secure login authentication (hidden URL for added security)',
            'Dashboard view for managing all economic content cards and sections',
            'Grid view for visual content management',
            'CRUD operations — Create, update, and delete content sections',
            'Year management — Add new years, duplicate existing year data, delete entire years',
            'Inquiry management — View and delete visitor inquiries',
            'Excel export of economic data (formatted for Power BI compatibility)',
            'Profile settings — Update admin name, email, and password',
            'Unsaved changes warning to prevent accidental data loss',
        ]);

        // 1.3 Navigating the Public Site
        $section->addTitle($this->esc('1.3 Navigating the Public Website'), 2);

        $section->addTitle($this->esc('Hero Section'), 3);
        $section->addText(
            $this->esc('The landing page features a full-screen hero section with the region\'s headline investment data, including GRDP growth rate and population statistics. Use the year selector dropdown at the top to switch between different data periods.'),
            null, 'Normal'
        );

        $section->addTitle($this->esc('Economic Drivers Section'), 3);
        $section->addText(
            $this->esc('Scroll down to explore the 12 Economic Drivers of Western Visayas. Each driver is presented as a data-rich card showing key statistics. Click on cards with a "View Details" indicator to open a modal with additional information, charts, and infrastructure maps.'),
            null, 'Normal'
        );

        $section->addTitle($this->esc('Contact Section'), 3);
        $section->addText(
            $this->esc('At the bottom of the page, fill out the inquiry form with your name, email, contact number, and message. The system will save your inquiry and offer to open your email client with a pre-filled message to DTI Region VI.'),
            null, 'Normal'
        );

        $section->addTitle($this->esc('PDF Download'), 3);
        $section->addText(
            $this->esc('Click the "Download Profile" button to download a comprehensive PDF of the investment profile for the currently selected year. The PDF is automatically generated from the database content.'),
            null, 'Normal'
        );

        // 1.4 Navigating the Admin Panel
        $section->addTitle($this->esc('1.4 Using the Admin Panel'), 2);

        $section->addTitle($this->esc('Accessing the Admin Panel'), 3);
        $section->addText(
            $this->esc('The admin panel is accessible via a hidden URL: /portal-access-secret. Navigate to this URL and log in with your administrator credentials (email and password).'),
            null, 'Normal'
        );
        $this->addNote($section, $this->esc('The admin URL is intentionally hidden from the public site for security. Only authorized personnel should know this URL.'));

        $section->addTitle($this->esc('Dashboard Overview'), 3);
        $section->addText(
            $this->esc('After logging in, you will see the admin dashboard which displays all content cards for the selected year. The dashboard includes:'),
            null, 'Normal'
        );
        $this->addBulletList($section, [
            'Year Selector — Switch between different data years at the top',
            'Content Cards — Editable cards for each section (hero, stats, charts, lists, grids)',
            'Inquiry Panel — View inquiries submitted through the public contact form',
            'Action Buttons — Export to Excel, switch to Grid View, manage years, and update profile',
        ]);

        $section->addTitle($this->esc('Editing Content'), 3);
        $section->addText(
            $this->esc('Click on any content card to open its editor. Modify the section title, data values, and source citations. Changes are saved when you click the Save button. The system will warn you if you try to navigate away with unsaved changes.'),
            null, 'Normal'
        );

        $section->addTitle($this->esc('Managing Years'), 3);
        $section->addText(
            $this->esc('Use the year management features to: add a new empty year, duplicate an existing year\'s data into a new year range, or delete an entire year and all its associated content. All year operations are permanent and saved to the database.'),
            null, 'Normal'
        );

        $section->addPageBreak();

        // =====================================================================
        // SECTION 2: INSTALLATION GUIDE
        // =====================================================================
        $section->addTitle($this->esc('2. Installation Guide'), 1);

        $section->addTitle($this->esc('2.1 System Requirements'), 2);
        $this->addStyledTable($section,
            ['Requirement', 'Minimum Version', 'Notes'],
            [
                ['PHP', '8.2+', 'Required for Laravel 12 framework'],
                ['Composer', '2.x', 'PHP dependency manager'],
                ['Node.js', '18+', 'For frontend asset compilation'],
                ['NPM', '9+', 'Comes with Node.js installation'],
                ['MySQL', '8.0+', 'Primary database engine'],
                ['Docker Desktop', 'Latest', 'Only needed for Docker setup'],
            ]
        );

        // Docker Setup
        $section->addTitle($this->esc('2.2 Option A: Docker Setup (Recommended)'), 2);
        $section->addText(
            $this->esc('This project uses Laravel Sail, a light-weight command-line interface for interacting with Docker. This is the fastest way to get started.'),
            null, 'Normal'
        );

        $section->addTitle($this->esc('Step 1: Clone the Repository'), 3);
        $this->addCodeBlock($section, "git clone https://github.com/blackhatsV1/western-visayas-region-6-investment-economic-profile\ncd western-visayas-region-6-investment-economic-profile");

        $section->addTitle($this->esc('Step 2: Install PHP Dependencies'), 3);
        $this->addCodeBlock($section, "docker run --rm \\\n    -u \"\$(id -u):\$(id -g)\" \\\n    -v \"\$(pwd):/var/www/html\" \\\n    -w /var/www/html \\\n    laravelsail/php82-composer:latest \\\n    composer install --ignore-platform-reqs");

        $section->addTitle($this->esc('Step 3: Configure Environment'), 3);
        $this->addCodeBlock($section, "cp .env.example .env");
        $this->addNote($section, $this->esc('Sail automatically configures the database connection for you. No manual .env editing is needed for Docker setup.'));

        $section->addTitle($this->esc('Step 4: Start the Containers'), 3);
        $this->addCodeBlock($section, "./vendor/bin/sail up -d");

        $section->addTitle($this->esc('Step 5: Initialize the Application'), 3);
        $this->addCodeBlock($section, "./vendor/bin/sail artisan key:generate\n./vendor/bin/sail artisan migrate --seed\n./vendor/bin/sail npm install\n./vendor/bin/sail npm run build");

        $section->addText($this->esc('The application will be available at: http://localhost'), ['bold' => true, 'color' => self::COLOR_ACCENT], 'Normal');

        // Manual Setup
        $section->addTitle($this->esc('2.3 Option B: Manual Local Setup'), 2);
        $section->addText(
            $this->esc('Use this method if you prefer running PHP and MySQL directly on your host machine without Docker.'),
            null, 'Normal'
        );

        $section->addTitle($this->esc('Step 1: Clone and Install Dependencies'), 3);
        $this->addCodeBlock($section, "git clone https://github.com/blackhatsV1/western-visayas-region-6-investment-economic-profile\ncd western-visayas-region-6-investment-economic-profile\ncomposer install");

        $section->addTitle($this->esc('Step 2: Environment Setup'), 3);
        $this->addCodeBlock($section, "cp .env.example .env");
        $section->addText($this->esc('Edit the .env file and update the following database credentials:'), null, 'Normal');
        $this->addCodeBlock($section, "DB_DATABASE=your_database_name\nDB_USERNAME=your_mysql_username\nDB_PASSWORD=your_mysql_password");

        $section->addTitle($this->esc('Step 3: Initialize the Database'), 3);
        $this->addCodeBlock($section, "php artisan key:generate\nphp artisan migrate --seed");

        $section->addTitle($this->esc('Step 4: Build Frontend Assets'), 3);
        $this->addCodeBlock($section, "npm install\nnpm run build");

        $section->addTitle($this->esc('Step 5: Start the Development Server'), 3);
        $this->addCodeBlock($section, "php artisan serve");
        $section->addText($this->esc('The application will be available at: http://127.0.0.1:8000'), ['bold' => true, 'color' => self::COLOR_ACCENT], 'Normal');

        // Northflank Deployment
        $section->addTitle($this->esc('2.4 Production Deployment'), 2);
        $section->addText(
            $this->esc('The project includes a multi-stage Dockerfile for production deployment. It builds frontend assets with Node.js, installs PHP vendor dependencies with Composer, and runs on PHP 8.4 with Apache. The Docker image can be deployed to any container hosting service (e.g., Northflank, Railway, AWS ECS).'),
            null, 'Normal'
        );

        $section->addPageBreak();

        // =====================================================================
        // SECTION 3: FAQs
        // =====================================================================
        $section->addTitle($this->esc('3. Frequently Asked Questions (FAQs)'), 1);

        $faqs = [
            ['How do I access the admin panel?', 'Navigate to /portal-access-secret in your browser. Enter your admin email and password to log in. The URL is intentionally hidden from the public site for security.'],
            ['What are the default admin credentials?', 'The default admin account is created by the AdminUserSeeder during setup (php artisan migrate --seed). Check the seeder file at database/seeders/AdminUserSeeder.php for the default email and password. You should change these immediately after first login.'],
            ['How do I add data for a new year?', 'In the admin panel, use the "Add Year" or "Duplicate Year" feature. Duplicating copies all content from an existing year, which you can then edit with updated statistics.'],
            ['Can I export the data to Excel?', 'Yes. In the admin panel, click the "Export" button. This downloads an Excel file (.xlsx) containing all economic data for the selected year, formatted for compatibility with Power BI and other analytics tools.'],
            ['How do I download the PDF profile?', 'On the public website, click the "Download Profile" button. The system generates a PDF from the current year\'s database content and downloads it automatically.'],
            ['Why are my changes not showing on the public site?', 'Make sure you clicked "Save" after editing content in the admin panel. The public site reads directly from the database, so saved changes appear immediately.'],
            ['How do I update the seeder data?', 'Edit the file database/seeders/ProjectContentSeeder.php and then run: php artisan db:seed --class=ProjectContentSeeder. This will re-seed the database with updated content.'],
            ['Is the application mobile-friendly?', 'Yes. The public site features a fully responsive design with a bottom tab navigation on mobile, swipeable cards, and PWA support for home screen installation.'],
            ['How do I reset the database?', 'Run php artisan migrate:fresh --seed to drop all tables, re-run all migrations, and re-seed the database with default data. Warning: This deletes all existing data.'],
            ['What browsers are supported?', 'The application supports all modern browsers including Chrome, Firefox, Safari, and Edge. Internet Explorer is not supported.'],
        ];

        foreach ($faqs as $i => $faq) {
            $qNum = $i + 1;
            $section->addText(
                $this->esc("Q{$qNum}: {$faq[0]}"),
                ['bold' => true, 'size' => 12, 'color' => self::COLOR_PRIMARY],
                ['spaceBefore' => 160, 'spaceAfter' => 60]
            );
            $section->addText($this->esc($faq[1]), null, 'Normal');
        }

        $section->addPageBreak();

        // =====================================================================
        // SECTION 4: TUTORIALS
        // =====================================================================
        $section->addTitle($this->esc('4. Tutorials'), 1);

        // Tutorial 1
        $section->addTitle($this->esc('4.1 Adding a New Economic Driver Card'), 2);
        $section->addText($this->esc('Follow these steps to add a new content card in the admin panel:'), null, 'Normal');
        $this->addBulletList($section, [
            'Step 1: Log in to the admin panel at /portal-access-secret',
            'Step 2: Select the target year from the year dropdown at the top of the dashboard',
            'Step 3: Scroll to the bottom of the content cards and click "Add New Section"',
            'Step 4: Choose the content type (e.g., stats_grid, chart, list, grid, text, or hero)',
            'Step 5: Enter the section title, fill in the content fields, and optionally add a source citation',
            'Step 6: Set the page number to control the display order',
            'Step 7: Click "Save" to create the new card — it will appear immediately on the public site',
        ]);

        // Tutorial 2
        $section->addTitle($this->esc('4.2 Editing Existing Content'), 2);
        $section->addText($this->esc('To modify an existing content card:'), null, 'Normal');
        $this->addBulletList($section, [
            'Step 1: Navigate to the admin dashboard and select the correct year',
            'Step 2: Click on the content card you want to edit',
            'Step 3: Modify the section title, data values, or source information as needed',
            'Step 4: Click "Save" to apply changes — the yellow "unsaved changes" indicator will clear',
            'Step 5: Verify the changes on the public site by opening the homepage in a new tab',
        ]);

        // Tutorial 3
        $section->addTitle($this->esc('4.3 Managing Year Data'), 2);

        $section->addTitle($this->esc('Duplicating a Year'), 3);
        $section->addText($this->esc('To create a new year based on existing data:'), null, 'Normal');
        $this->addBulletList($section, [
            'Step 1: In the admin panel, click the "Duplicate Year" button',
            'Step 2: Select the source year to copy data from',
            'Step 3: Enter the new target year range (e.g., "2025-2026")',
            'Step 4: Click "Duplicate" — all content cards will be copied to the new year',
            'Step 5: Edit the duplicated cards to update statistics for the new year',
        ]);

        $section->addTitle($this->esc('Deleting a Year'), 3);
        $section->addText($this->esc('To permanently remove all data for a specific year:'), null, 'Normal');
        $this->addBulletList($section, [
            'Step 1: Select the year you want to delete from the year dropdown',
            'Step 2: Click the "Delete Year" button',
            'Step 3: Confirm the deletion in the popup dialog',
        ]);
        $this->addNote($section, 'Deleting a year permanently removes ALL content cards for that year. This action cannot be undone.');

        // Tutorial 4
        $section->addTitle('4.4 Exporting Data to Excel', 2);
        $this->addBulletList($section, [
            'Step 1: In the admin panel, select the year you want to export',
            'Step 2: Click the "Export to Excel" button',
            'Step 3: A .xlsx file will automatically download containing all economic data',
            'Step 4: Open the file in Microsoft Excel or import it into Power BI for analysis',
        ]);
        $section->addText(
            'The exported spreadsheet contains columns: Year Range, Section Title, Type, Source, Category/Label, Value/Description, and Series/Sub-Type. This flat structure is optimized for direct import into Power BI.',
            null, 'Normal'
        );

        // Tutorial 5
        $section->addTitle('4.5 Handling Visitor Inquiries', 2);
        $this->addBulletList($section, [
            'Step 1: Log in to the admin panel',
            'Step 2: Scroll to the "Inquiries" section on the dashboard',
            'Step 3: Review each inquiry\'s name, email, contact number, and message',
            'Step 4: To respond, contact the visitor via their provided email address',
            'Step 5: To remove a processed inquiry, click the "Delete" button next to it',
        ]);

        // Save
        $writer = IOFactory::createWriter($phpWord, 'Word2007');
        $writer->save("{$outputDir}/User_Documentation.docx");
    }

    // =========================================================================
    // TECHNICAL DOCUMENTATION
    // =========================================================================

    private function generateTechnicalDocumentation(string $outputDir): void
    {
        $phpWord = new PhpWord();
        $this->registerStyles($phpWord);

        // ----- Cover Page -----
        $coverSection = $phpWord->addSection();
        $this->addCoverPage(
            $coverSection,
            'Western Visayas Investment Profile',
            'Region VI Economic & Investment Data Platform',
            'Technical Documentation'
        );

        // ----- Table of Contents -----
        $tocSection = $phpWord->addSection();
        $this->addTableOfContents($tocSection, [
            1 => 'System Architecture',
            2 => 'API Documentation',
            3 => 'Database Structure',
            4 => 'Source Code Reference',
        ]);

        // =====================================================================
        // SECTION 1: SYSTEM ARCHITECTURE
        // =====================================================================
        $section = $phpWord->addSection();
        $section->addTitle($this->esc('1. System Architecture'), 1);

        $section->addTitle($this->esc('1.1 Technology Stack'), 2);
        $this->addStyledTable($section,
            [$this->esc('Layer'), $this->esc('Technology'), $this->esc('Version'), $this->esc('Purpose')],
            [
                [$this->esc('Backend Framework'), $this->esc('Laravel'), $this->esc('12.x'), $this->esc('PHP web framework (MVC architecture)')],
                [$this->esc('Language'), $this->esc('PHP'), $this->esc('8.2+'), $this->esc('Server-side programming language')],
                [$this->esc('Database'), $this->esc('MySQL'), $this->esc('8.0+'), $this->esc('Relational database for persistent storage')],
                [$this->esc('CSS Framework'), $this->esc('Tailwind CSS'), $this->esc('3.x'), $this->esc('Utility-first CSS framework for styling')],
                [$this->esc('JS Framework'), $this->esc('Alpine.js'), $this->esc('3.x'), $this->esc('Lightweight reactive JavaScript framework')],
                [$this->esc('Charts'), $this->esc('ApexCharts'), $this->esc('Latest'), $this->esc('Interactive chart and data visualizations')],
                [$this->esc('Maps'), $this->esc('Leaflet.js'), $this->esc('Latest'), $this->esc('Interactive maps for infrastructure data')],
                [$this->esc('PDF Generation'), $this->esc('DomPDF (barryvdh)'), $this->esc('3.1'), $this->esc('Server-side PDF rendering from Blade templates')],
                [$this->esc('Excel Export'), $this->esc('Maatwebsite Excel'), $this->esc('3.1'), $this->esc('Export data to Excel (.xlsx) format')],
                [$this->esc('Build Tool'), $this->esc('Vite'), $this->esc('Latest'), $this->esc('Frontend asset bundling and hot-reload')],
                [$this->esc('Containerization'), $this->esc('Docker'), $this->esc('Latest'), $this->esc('Multi-stage Dockerfile for production deployment')],
                [$this->esc('Dev Environment'), $this->esc('Laravel Sail'), $this->esc('1.41'), $this->esc('Docker-based local development environment')],
                [$this->esc('Caching'), $this->esc('Redis'), $this->esc('Alpine'), $this->esc('Session and cache store (via Docker)')],
            ]
        );

        // 1.2 MVC Architecture
        $section->addTitle($this->esc('1.2 Application Architecture (MVC)'), 2);
        $section->addText(
            $this->esc('The application follows the Model-View-Controller (MVC) architectural pattern provided by Laravel:'),
            null, 'Normal'
        );

        $section->addTitle($this->esc('Models (app/Models/)'), 3);
        $section->addText(
            $this->esc('Eloquent ORM models represent database tables and encapsulate data access logic. Each model defines fillable fields and attribute casts.'),
            null, 'Normal'
        );
        $this->addStyledTable($section,
            [$this->esc('Model'), $this->esc('Table'), $this->esc('Description')],
            [
                [$this->esc('User'), $this->esc('users'), $this->esc('Admin user accounts with authentication support')],
                [$this->esc('ProjectContent'), $this->esc('project_contents'), $this->esc('Economic profile content cards with JSON data storage')],
                [$this->esc('Inquiry'), $this->esc('inquiries'), $this->esc('Visitor contact form submissions')],
            ]
        );

        $section->addTitle($this->esc('Views (resources/views/)'), 3);
        $section->addText(
            $this->esc('Blade templates handle the presentation layer. The application uses the following view structure:'),
            null, 'Normal'
        );
        $this->addStyledTable($section,
            [$this->esc('View'), $this->esc('Description')],
            [
                [$this->esc('welcome.blade.php'), $this->esc('Main public site — single-page application with all economic data sections')],
                [$this->esc('admin/dashboard.blade.php'), $this->esc('Admin panel dashboard — content management interface')],
                [$this->esc('admin/grid.blade.php'), $this->esc('Admin grid view — visual card-based content layout')],
                [$this->esc('admin/login.blade.php'), $this->esc('Admin login page')],
                [$this->esc('pdf/profile.blade.php'), $this->esc('PDF template for downloadable investment profile')],
                [$this->esc('emails/'), $this->esc('Email templates for notifications')],
            ]
        );

        $section->addTitle($this->esc('Controllers (app/Http/Controllers/)'), 3);
        $section->addText(
            $this->esc('Controllers handle HTTP requests, apply business logic, and return responses. Three controllers manage all application functionality:'),
            null, 'Normal'
        );
        $this->addStyledTable($section,
            [$this->esc('Controller'), $this->esc('Responsibility')],
            [
                [$this->esc('PublicController'), $this->esc('Serves the public website, handles contact form submissions, generates PDF downloads')],
                [$this->esc('AdminController'), $this->esc('Manages all CRUD operations for content, year management, inquiries, and Excel export')],
                [$this->esc('AuthController'), $this->esc('Handles login, logout, session management, and profile updates')],
            ]
        );

        // 1.3 Directory Structure
        $section->addTitle($this->esc('1.3 Project Directory Structure'), 2);
        $this->addCodeBlock($section,
            $this->esc("├── app/\n" .
            "│   ├── Console/Commands/     # Artisan CLI commands\n" .
            "│   ├── Exports/              # Excel export classes (Maatwebsite)\n" .
            "│   ├── Http/Controllers/     # Request handlers (Public, Admin, Auth)\n" .
            "│   ├── Mail/                 # Mailable classes\n" .
            "│   ├── Models/               # Eloquent models (User, ProjectContent, Inquiry)\n" .
            "│   └── Providers/            # Service providers\n" .
            "├── config/                   # Application configuration files\n" .
            "├── database/\n" .
            "│   ├── migrations/           # Database schema migrations\n" .
            "│   ├── seeders/              # Data seeders (ProjectContentSeeder, AdminUserSeeder)\n" .
            "│   └── factories/            # Model factories for testing\n" .
            "├── docker/                   # Docker configuration (php.ini, entrypoint.sh)\n" .
            "├── public/                   # Web root (index.php, manifest.json, compiled assets)\n" .
            "├── resources/\n" .
            "│   ├── views/                # Blade templates\n" .
            "│   ├── css/                  # Source CSS files\n" .
            "│   └── js/                   # Source JavaScript files\n" .
            "├── routes/                   # Route definitions (web.php, console.php)\n" .
            "├── storage/                  # Logs, cache, compiled views, file uploads\n" .
            "├── tests/                    # PHPUnit and browser tests\n" .
            "├── Dockerfile                # Multi-stage production Docker image\n" .
            "├── docker-compose.yml        # Sail development environment (MySQL, Redis)\n" .
            "├── composer.json             # PHP dependencies\n" .
            "├── package.json              # Node.js dependencies\n" .
            "└── vite.config.js            # Vite build configuration")
        );

        // 1.4 Request Lifecycle
        $section->addTitle($this->esc('1.4 Request Lifecycle'), 2);
        $section->addText(
            $this->esc('The application follows the standard Laravel request lifecycle:'),
            null, 'Normal'
        );
        $this->addBulletList($section, [
            $this->esc('1. HTTP request enters through public/index.php'),
            $this->esc('2. Request is loaded into the Laravel application kernel'),
            $this->esc('3. Middleware stack processes the request (authentication, CSRF, sessions)'),
            $this->esc('4. Router matches the URL to a route defined in routes/web.php'),
            $this->esc('5. Controller method is invoked with dependency-injected parameters'),
            $this->esc('6. Controller queries Eloquent models for database data'),
            $this->esc('7. Response is rendered via a Blade view template or JSON response'),
            $this->esc('8. Response passes back through middleware and is sent to the client'),
        ]);

        // 1.5 Deployment Architecture
        $section->addTitle($this->esc('1.5 Deployment Architecture'), 2);
        $section->addText(
            $this->esc('The production Dockerfile uses a 3-stage build process:'),
            null, 'Normal'
        );
        $this->addStyledTable($section,
            [$this->esc('Stage'), $this->esc('Base Image'), $this->esc('Purpose')],
            [
                [$this->esc('1. Frontend'), $this->esc('node:20-alpine'), $this->esc('Installs NPM dependencies, compiles CSS/JS with Vite')],
                [$this->esc('2. Vendor'), $this->esc('composer:2'), $this->esc('Installs PHP dependencies with optimized autoloader')],
                [$this->esc('3. Production'), $this->esc('php:8.4-apache'), $this->esc('Final image with Apache, PHP extensions, and all assets')],
            ]
        );
        $section->addText($this->esc('Required PHP extensions installed: pdo_mysql, mbstring, gd, zip, bcmath, xml, opcache.'), null, 'Normal');

        $section->addPageBreak();

        // =====================================================================
        // SECTION 2: API DOCUMENTATION
        // =====================================================================
        $section->addTitle($this->esc('2. API Documentation'), 1);
        $section->addText(
            $this->esc('All routes are defined in routes/web.php. The application uses web routes (session-based authentication) rather than API tokens.'),
            null, 'Normal'
        );

        // 2.1 Public Routes
        $section->addTitle($this->esc('2.1 Public Routes'), 2);
        $section->addText($this->esc('These routes are accessible without authentication:'), null, 'Normal');
        $this->addStyledTable($section,
            [$this->esc('Method'), $this->esc('URI'), $this->esc('Controller@Method'), $this->esc('Description')],
            [
                [$this->esc('GET'), $this->esc('/'), $this->esc('PublicController@index'), $this->esc('Display the main public investment profile page. Accepts ?year= query parameter for year filtering.')],
                [$this->esc('POST'), $this->esc('/contact'), $this->esc('PublicController@submitContactForm'), $this->esc('Submit a visitor inquiry. Expects JSON body: name, email, contact, message. Returns JSON with mailto link.')],
                [$this->esc('GET'), $this->esc('/download-profile/{year}'), $this->esc('PublicController@downloadPdf'), $this->esc('Download PDF investment profile for the specified year. Returns a PDF file download.')],
            ]
        );

        // 2.2 Authentication Routes
        $section->addTitle($this->esc('2.2 Authentication Routes'), 2);
        $this->addStyledTable($section,
            [$this->esc('Method'), $this->esc('URI'), $this->esc('Controller@Method'), $this->esc('Description')],
            [
                [$this->esc('GET'), $this->esc('/portal-access-secret'), $this->esc('AuthController@showLogin'), $this->esc('Display the admin login page. Redirects to /admin if already authenticated.')],
                [$this->esc('POST'), $this->esc('/portal-access-secret'), $this->esc('AuthController@login'), $this->esc('Process login. Expects: email, password. Redirects to /admin on success.')],
                [$this->esc('POST'), $this->esc('/logout'), $this->esc('AuthController@logout'), $this->esc('Log out the current user, invalidate session, and redirect to homepage.')],
            ]
        );

        // 2.3 Admin Routes
        $section->addTitle($this->esc('2.3 Admin Routes (Authenticated)'), 2);
        $section->addText($this->esc('All admin routes require authentication via the auth middleware and are prefixed with /admin.'), null, 'Normal');
        $this->addStyledTable($section,
            [$this->esc('Method'), $this->esc('URI'), $this->esc('Controller@Method'), $this->esc('Description')],
            [
                [$this->esc('GET'), $this->esc('/admin'), $this->esc('Admin dashboard. Accepts ?year= query parameter. Displays content cards and inquiries.')],
                [$this->esc('GET'), $this->esc('/admin/grid'), $this->esc('Grid-based content view. Accepts ?year= query parameter.')],
                [$this->esc('GET'), $this->esc('/admin/export'), $this->esc('Download Excel export of economic data. Accepts ?year= query parameter.')],
                [$this->esc('POST'), $this->esc('/admin/content'), $this->esc('Create new content card. Expects JSON: year_range, type, section_title, content (array), page_number.')],
                [$this->esc('PATCH'), $this->esc('/admin/content/{id}'), $this->esc('Update existing content card. Expects JSON: section_title, content (array), source.')],
                [$this->esc('DELETE'), $this->esc('/admin/content/{id}'), $this->esc('Delete a specific content card. Returns JSON success response.')],
                [$this->esc('DELETE'), $this->esc('/admin/year/{year}'), $this->esc('Delete ALL content cards for a specific year. Returns JSON success response.')],
                [$this->esc('DELETE'), $this->esc('/admin/inquiry/{id}'), $this->esc('Delete a specific visitor inquiry. Returns JSON success response.')],
                [$this->esc('POST'), $this->esc('/admin/year/duplicate'), $this->esc('Duplicate all content from source_year to target_year. Expects JSON: source_year, target_year.')],
                [$this->esc('POST'), $this->esc('/admin/profile'), $this->esc('Update admin profile. Expects: name, email, password (optional), password_confirmation.')],
            ]
        );

        // 2.4 Request/Response Formats
        $section->addTitle($this->esc('2.4 Request & Response Formats'), 2);

        $section->addTitle($this->esc('Contact Form Submission'), 3);
        $section->addText($this->esc('POST /contact'), ['bold' => true, 'name' => 'Consolas', 'size' => 10], 'Normal');
        $section->addText($this->esc('Request Body (JSON):'), 'BoldText', 'Normal');
        $this->addCodeBlock($section, "{\n  \"name\": \"John Doe\",\n  \"email\": \"john@example.com\",\n  \"contact\": \"+639123456789\",\n  \"message\": \"I am interested in investing in Western Visayas.\"\n}");
        $section->addText($this->esc('Success Response:'), 'BoldText', 'Normal');
        $this->addCodeBlock($section, "{\n  \"success\": true,\n  \"mailto\": \"mailto:r06@dti.gov.ph?subject=Inquiry%3A%20John%20Doe&body=...\"\n}");

        $section->addTitle($this->esc('Content CRUD Responses'), 3);
        $section->addText($this->esc('All admin CRUD operations return JSON:'), null, 'Normal');
        $this->addCodeBlock($section, "// Success\n{\"success\": true, \"content\": { ... }}\n\n// Error (e.g., duplicate year with no source data)\n{\"success\": false, \"message\": \"Source year has no content.\"}");

        $section->addPageBreak();

        // =====================================================================
        // SECTION 3: DATABASE STRUCTURE
        // =====================================================================
        $section->addTitle($this->esc('3. Database Structure'), 1);
        $section->addText(
            $this->esc('The application uses MySQL 8.0+ as its primary database. All schema changes are managed through Laravel migrations located in database/migrations/.'),
            null, 'Normal'
        );

        // 3.1 Users Table
        $section->addTitle($this->esc('3.1 users Table'), 2);
        $section->addText($this->esc('Stores admin user accounts for authentication.'), null, 'Normal');
        $this->addStyledTable($section,
            [$this->esc('Column'), $this->esc('Type'), $this->esc('Nullable'), $this->esc('Description')],
            [
                [$this->esc('id'), $this->esc('BIGINT (PK)'), $this->esc('No'), $this->esc('Auto-incrementing primary key')],
                [$this->esc('name'), $this->esc('VARCHAR(255)'), $this->esc('No'), $this->esc('User display name')],
                [$this->esc('email'), $this->esc('VARCHAR(255)'), $this->esc('No'), $this->esc('Unique email address for login')],
                [$this->esc('email_verified_at'), $this->esc('TIMESTAMP'), $this->esc('Yes'), $this->esc('Email verification timestamp')],
                [$this->esc('password'), $this->esc('VARCHAR(255)'), $this->esc('No'), $this->esc('Bcrypt-hashed password')],
                [$this->esc('remember_token'), $this->esc('VARCHAR(100)'), $this->esc('Yes'), $this->esc('Token for "remember me" sessions')],
                [$this->esc('created_at'), $this->esc('TIMESTAMP'), $this->esc('Yes'), $this->esc('Record creation timestamp')],
                [$this->esc('updated_at'), $this->esc('TIMESTAMP'), $this->esc('Yes'), $this->esc('Record last update timestamp')],
            ]
        );

        // 3.2 Project Contents Table
        $section->addTitle($this->esc('3.2 project_contents Table'), 2);
        $section->addText(
            $this->esc('Core data table storing all economic profile content as structured JSON. Each row represents one content card/section on the public site.'),
            null, 'Normal'
        );
        $this->addStyledTable($section,
            [$this->esc('Column'), $this->esc('Type'), $this->esc('Nullable'), $this->esc('Description')],
            [
                [$this->esc('id'), $this->esc('BIGINT (PK)'), $this->esc('No'), $this->esc('Auto-incrementing primary key')],
                [$this->esc('page_number'), $this->esc('INTEGER'), $this->esc('No'), $this->esc('Display order / page number for sorting')],
                [$this->esc('section_title'), $this->esc('VARCHAR(255)'), $this->esc('Yes'), $this->esc('Title of the content section (e.g., "Agriculture")')],
                [$this->esc('type'), $this->esc('VARCHAR(255)'), $this->esc('No'), $this->esc('Content type: hero, text, list, chart, stats_grid, grid, marquee, cta, metadata')],
                [$this->esc('year_range'), $this->esc('VARCHAR(255)'), $this->esc('Yes'), $this->esc('Year period (e.g., "2024-2025"). Used for multi-year data filtering')],
                [$this->esc('content'), $this->esc('JSON'), $this->esc('No'), $this->esc('Structured JSON containing all section data (stats, chart data, items, etc.)')],
                [$this->esc('source'), $this->esc('TEXT'), $this->esc('Yes'), $this->esc('Data source citation (e.g., "PSA 2024")')],
                [$this->esc('created_at'), $this->esc('TIMESTAMP'), $this->esc('Yes'), $this->esc('Record creation timestamp')],
                [$this->esc('updated_at'), $this->esc('TIMESTAMP'), $this->esc('Yes'), $this->esc('Record last update timestamp')],
            ]
        );

        $section->addTitle($this->esc('Content JSON Structures by Type'), 3);
        $section->addText($this->esc('The content JSON column structure varies based on the type field:'), null, 'Normal');

        $section->addText($this->esc('Hero Type:'), 'BoldText', 'Normal');
        $this->addCodeBlock($section, "{\n  \"title\": \"Why Invest in Western Visayas\",\n  \"subtitle\": \"Region VI Economic Profile\",\n  \"highlight_stats\": [\n    {\"label\": \"GRDP Growth Rate\", \"value\": \"6.2%\"},\n    {\"label\": \"Population\", \"value\": \"8.1M\"}\n  ]\n}");

        $section->addText($this->esc('Stats Grid Type:'), 'BoldText', 'Normal');
        $this->addCodeBlock($section, "{\n  \"description\": \"Key economic indicators...\",\n  \"stats\": [\n    {\"label\": \"Total Exports\", \"value\": \"$2.5B\"},\n    {\"label\": \"Employment Rate\", \"value\": \"94.8%\"}\n  ]\n}");

        $section->addText($this->esc('Chart Type:'), 'BoldText', 'Normal');
        $this->addCodeBlock($section, "{\n  \"categories\": [\"2020\", \"2021\", \"2022\", \"2023\"],\n  \"series\": [\n    {\"name\": \"GDP Growth\", \"data\": [3.1, 5.2, 6.8, 6.2]}\n  ]\n}");

        $section->addText($this->esc('Grid / List Type:'), 'BoldText', 'Normal');
        $this->addCodeBlock($section, "{\n  \"items\": [\n    {\"name\": \"Iloilo City\", \"details\": \"Regional center, BPO hub...\"},\n    {\"name\": \"Bacolod City\", \"details\": \"Sugar capital, IT growth...\"}\n  ]\n}");

        // 3.3 Inquiries Table
        $section->addTitle($this->esc('3.3 inquiries Table'), 2);
        $section->addText($this->esc('Stores contact form submissions from public site visitors.'), null, 'Normal');
        $this->addStyledTable($section,
            [$this->esc('Column'), $this->esc('Type'), $this->esc('Nullable'), $this->esc('Description')],
            [
                [$this->esc('id'), $this->esc('BIGINT (PK)'), $this->esc('No'), $this->esc('Auto-incrementing primary key')],
                [$this->esc('name'), $this->esc('VARCHAR(255)'), $this->esc('No'), $this->esc('Visitor full name')],
                [$this->esc('email'), $this->esc('VARCHAR(255)'), $this->esc('No'), $this->esc('Visitor email address')],
                [$this->esc('contact'), $this->esc('VARCHAR(20)'), $this->esc('No'), $this->esc('Visitor phone/contact number')],
                [$this->esc('message'), $this->esc('TEXT'), $this->esc('No'), $this->esc('Inquiry message body (max 1000 chars validated)')],
                [$this->esc('created_at'), $this->esc('TIMESTAMP'), $this->esc('Yes'), $this->esc('Submission timestamp')],
                [$this->esc('updated_at'), $this->esc('TIMESTAMP'), $this->esc('Yes'), $this->esc('Record last update timestamp')],
            ]
        );

        // 3.4 System Tables
        $section->addTitle($this->esc('3.4 System Tables'), 2);
        $section->addText($this->esc('Laravel automatically creates these tables for framework functionality:'), null, 'Normal');
        $this->addStyledTable($section,
            [$this->esc('Table'), $this->esc('Purpose')],
            [
                [$this->esc('cache'), $this->esc('Key-value cache storage (database driver)')],
                [$this->esc('cache_locks'), $this->esc('Atomic lock management for cache operations')],
                [$this->esc('sessions'), $this->esc('Server-side session storage')],
                [$this->esc('jobs'), $this->esc('Queue job storage for background processing')],
                [$this->esc('job_batches'), $this->esc('Batch job tracking and metadata')],
                [$this->esc('failed_jobs'), $this->esc('Failed queue job logging for debugging')],
            ]
        );

        // 3.5 Entity Relationship
        $section->addTitle($this->esc('3.5 Entity Relationships'), 2);
        $section->addText(
            $this->esc('The database uses a simple, flat schema with no foreign key relationships between the main domain tables:'),
            null, 'Normal'
        );
        $this->addBulletList($section, [
            $this->esc('users — Standalone table. No direct relationships to other domain tables.'),
            $this->esc('project_contents — Standalone table. Filtered by year_range column. No FK constraints.'),
            $this->esc('inquiries — Standalone table. Stores visitor submissions independently.'),
        ]);
        $this->addNote($section, $this->esc('The project_contents table uses the year_range column as a logical grouping mechanism rather than a formal foreign key. This allows flexible year range formats (e.g., "2024-2025", "2023").'));

        $section->addPageBreak();

        // =====================================================================
        // SECTION 4: SOURCE CODE REFERENCE
        // =====================================================================
        $section->addTitle($this->esc('4. Source Code Reference'), 1);
        $section->addText(
            $this->esc('This section provides a reference guide to the key source code files, their purpose, and important implementation details.'),
            null, 'Normal'
        );

        // 4.1 Controllers
        $section->addTitle($this->esc('4.1 Controllers'), 2);

        $section->addTitle($this->esc('PublicController (app/Http/Controllers/PublicController.php)'), 3);
        $section->addText($this->esc('Handles all public-facing functionality:'), null, 'Normal');
        $this->addStyledTable($section,
            [$this->esc('Method'), $this->esc('Description')],
            [
                [$this->esc('index($request)'), $this->esc('Fetches all ProjectContent for the selected year, retrieves available years, and renders the welcome view. Passes $contents, $selectedYear, $years, and $noContent to the view.')],
                [$this->esc('submitContactForm($request)'), $this->esc('Validates the contact form (name, email, contact, message), creates an Inquiry record, and returns a JSON response with a mailto: link pre-filled with the inquiry data for DTI Region VI (r06@dti.gov.ph).')],
                [$this->esc('downloadPdf($year)'), $this->esc('Loads all ProjectContent for the given year, renders the pdf.profile Blade template via DomPDF, and triggers a file download named "Western_Visayas_Investment_Profile_{year}.pdf".')],
            ]
        );

        $section->addTitle($this->esc('AdminController (app/Http/Controllers/AdminController.php)'), 3);
        $section->addText($this->esc('Manages all content CRUD and admin features:'), null, 'Normal');
        $this->addStyledTable($section,
            [$this->esc('Method'), $this->esc('Description')],
            [
                [$this->esc('index($request)'), $this->esc('Loads dashboard with content cards (ordered by page_number), available years, and all inquiries for the selected year.')],
                [$this->esc('store($request)'), $this->esc('Creates a new ProjectContent record. Validates: year_range, type, section_title, content (array), page_number.')],
                [$this->esc('update($request, $content)'), $this->esc('Updates an existing ProjectContent. Validates: section_title, content (array), source.')],
                [$this->esc('destroy($content)'), $this->esc('Soft-deletes a specific content card. Returns JSON success.')],
                [$this->esc('destroyYear($year)'), $this->esc('Deletes ALL content cards matching the given year_range.')],
                [$this->esc('destroyInquiry($inquiry)'), $this->esc('Deletes a specific inquiry record.')],
                [$this->esc('duplicateYear($request)'), $this->esc('Replicates all content from source_year to target_year using Eloquent\'s replicate() method.')],
                [$this->esc('export($request)'), $this->esc('Downloads an Excel file via Maatwebsite Excel using the ProjectContentExport class.')],
                [$this->esc('gridView($request)'), $this->esc('Alternative card-based grid view of content for the selected year.')],
            ]
        );

        $section->addTitle($this->esc('AuthController (app/Http/Controllers/AuthController.php)'), 3);
        $section->addText($this->esc('Handles authentication and profile management:'), null, 'Normal');
        $this->addStyledTable($section,
            [$this->esc('Method'), $this->esc('Description')],
            [
                [$this->esc('showLogin()'), $this->esc('Renders the login page. Redirects to /admin if already authenticated.')],
                [$this->esc('login($request)'), $this->esc('Authenticates user via Auth::attempt(). Regenerates session on success. Returns validation error on failure.')],
                [$this->esc('logout($request)'), $this->esc('Logs out user, invalidates session, regenerates CSRF token, and redirects to homepage.')],
                [$this->esc('updateProfile($request)'), $this->esc('Updates current user\'s name, email, and optionally password (with bcrypt hashing). Validates email uniqueness.')],
            ]
        );

        // 4.2 Models
        $section->addTitle($this->esc('4.2 Models'), 2);

        $section->addTitle($this->esc('ProjectContent (app/Models/ProjectContent.php)'), 3);
        $this->addBulletList($section, [
            $this->esc('Fillable fields: page_number, section_title, type, content, source, year_range'),
            $this->esc('Casts: content => array (automatic JSON encoding/decoding)'),
            $this->esc('No custom relationships or scopes defined — uses Eloquent query methods directly in controllers'),
        ]);

        $section->addTitle($this->esc('Inquiry (app/Models/Inquiry.php)'), 3);
        $this->addBulletList($section, [
            $this->esc('Fillable fields: name, email, contact, message'),
            $this->esc('Simple model with no casts or relationships'),
        ]);

        $section->addTitle($this->esc('User (app/Models/User.php)'), 3);
        $this->addBulletList($section, [
            $this->esc('Standard Laravel User model with HasFactory and Notifiable traits'),
            $this->esc('Fillable fields: name, email, password'),
            $this->esc('Hidden fields: password, remember_token'),
            $this->esc('Casts: email_verified_at => datetime, password => hashed'),
        ]);

        // 4.3 Exports
        $section->addTitle($this->esc('4.3 Excel Export'), 2);
        $section->addTitle($this->esc('ProjectContentExport (app/Exports/ProjectContentExport.php)'), 3);
        $section->addText(
            $this->esc('Implements Maatwebsite Excel\'s FromCollection and WithHeadings interfaces. Transforms nested JSON content into flat rows optimized for Power BI import.'),
            null, 'Normal'
        );
        $section->addText($this->esc('Content type mapping:'), null, 'Normal');
        $this->addStyledTable($section,
            [$this->esc('Content Type'), $this->esc('Data Extracted')],
            [
                [$this->esc('hero'), $this->esc('highlight_stats array (label → Key, value → Value)')],
                [$this->esc('stats_grid'), $this->esc('stats array (label → Key, value → Value)')],
                [$this->esc('chart'), $this->esc('Cross-product of categories × series (category → Key, data point → Value, series name → Extra)')],
                [$this->esc('grid / list'), $this->esc('items array (name → Key, details → Value)')],
                [$this->esc('marquee, cta, metadata'), $this->esc('Ignored — structural/branding content, not data')],
            ]
        );

        // 4.4 Seeders
        $section->addTitle($this->esc('4.4 Database Seeders'), 2);
        $this->addStyledTable($section,
            [$this->esc('Seeder'), $this->esc('Description')],
            [
                [$this->esc('DatabaseSeeder.php'), $this->esc('Master seeder that calls AdminUserSeeder and ProjectContentSeeder')],
                [$this->esc('AdminUserSeeder.php'), $this->esc('Creates the default admin user account with hashed password')],
                [$this->esc('ProjectContentSeeder.php'), $this->esc('Seeds all economic profile content including hero sections, stats grids, charts, lists, grids, marquees, CTAs, and metadata. Contains ~36KB of structured economic data.')],
            ]
        );
        $this->addNote($section, $this->esc('To re-seed content data without affecting users: php artisan db:seed --class=ProjectContentSeeder'));

        // 4.5 Configuration
        $section->addTitle($this->esc('4.5 Key Configuration Files'), 2);
        $this->addStyledTable($section,
            [$this->esc('File'), $this->esc('Purpose')],
            [
                [$this->esc('config/app.php'), $this->esc('Application name, environment, URL, timezone, locale settings')],
                [$this->esc('config/database.php'), $this->esc('Database connection settings (MySQL primary, SQLite for testing)')],
                [$this->esc('config/auth.php'), $this->esc('Authentication guards, providers, and password rules')],
                [$this->esc('config/mail.php'), $this->esc('Mail transport configuration (SMTP settings)')],
                [$this->esc('config/session.php'), $this->esc('Session driver, lifetime, and cookie configuration')],
                [$this->esc('config/cache.php'), $this->esc('Cache store configuration (Redis via Docker, file fallback)')],
                [$this->esc('config/filesystems.php'), $this->esc('Storage disk configuration (local, public, S3)')],
                [$this->esc('config/queue.php'), $this->esc('Queue connection settings (database, Redis)')],
                [$this->esc('.env'), $this->esc('Environment-specific settings (DB credentials, app key, mail config). Not committed to Git.')],
                [$this->esc('.env.example'), $this->esc('Template environment file with all available configuration keys.')],
                [$this->esc('vite.config.js'), $this->esc('Vite bundler configuration — Laravel plugin with resources/js/app.js and resources/css/app.css entry points.')],
                [$this->esc('Dockerfile'), $this->esc('Multi-stage production build: Node.js (frontend) → Composer (vendor) → PHP 8.4 Apache (runtime).')],
                [$this->esc('docker-compose.yml'), $this->esc('Laravel Sail development environment with MySQL 8.0, Redis, and PHP 8.2 containers.')],
            ]
        );

        // 4.6 Testing
        $section->addTitle($this->esc('4.6 Testing'), 2);
        $section->addText(
            $this->esc('The project includes PHPUnit for unit and feature testing, plus browser tests. Tests are located in the tests/ directory:'),
            null, 'Normal'
        );
        $this->addStyledTable($section,
            [$this->esc('Directory'), $this->esc('Description')],
            [
                [$this->esc('tests/Unit/'), $this->esc('Unit tests for isolated component testing')],
                [$this->esc('tests/Feature/'), $this->esc('Feature/integration tests for HTTP endpoints')],
                [$this->esc('tests/Browser/'), $this->esc('Browser-based tests for UI verification')],
            ]
        );
        $section->addText($this->esc('Run tests with:'), null, 'Normal');
        $this->addCodeBlock($section, "php artisan test\n# or\n./vendor/bin/phpunit");

        // Save
        $writer = IOFactory::createWriter($phpWord, 'Word2007');
        $writer->save("{$outputDir}/Technical_Documentation.docx");
    }

    /**
     * Helper to escape strings for Word XML to prevent corruption.
     */
    private function esc(?string $text): string
    {
        if ($text === null) {
            return '';
        }
        // Strip invalid XML control characters: \x00-\x08, \x0B, \x0C, \x0E-\x1F, \x7F
        $text = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/', '', $text);
        
        return htmlspecialchars($text, ENT_QUOTES | ENT_XML1, 'UTF-8');
    }
}
