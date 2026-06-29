# Coding Conventions

This document defines the coding standards and architectural patterns for the Western Visayas Region 6 Investment Economic Profile project. All stages must follow these conventions.

---

## PHP / Laravel Conventions

### General
- **PHP Version**: 8.2+
- **Standard**: PSR-12 (Extended Coding Style)
- **Strict Types**: Use `declare(strict_types=1);` in all new PHP files

### Naming
| Element | Convention | Example |
|---|---|---|
| Classes | PascalCase | `ProjectContentController` |
| Methods | camelCase | `getActiveProjects()` |
| Variables | camelCase | `$contentData` |
| Constants | UPPER_SNAKE_CASE | `MAX_CHAT_REQUESTS` |
| DB Tables | snake_case, plural | `project_contents` |
| DB Columns | snake_case | `page_number`, `section_title` |
| Routes (named) | dot.notation | `admin.contents.index` |
| Config keys | snake_case | `services.pinecone.api_key` |

### File Organization
```
app/
├── Http/
│   ├── Controllers/       ← Thin controllers, max ~50 lines per method
│   ├── Middleware/         ← Custom middleware
│   └── Requests/          ← Form Request validation classes
├── Models/                ← Eloquent models (User, ProjectContent, Inquiry)
├── Services/              ← Business logic (AIChatService, PdfService)
└── Providers/             ← Service providers
```

### Controller Rules
- Controllers are **thin**: no business logic, no direct DB queries beyond simple CRUD
- Complex operations go into `app/Services/` classes
- Always return typed responses: `JsonResponse`, `View`, `RedirectResponse`

### Model Rules
- Use `$fillable` or `$guarded` on every model — never leave both empty
- Define relationships explicitly with return types
- Use accessors/mutators for computed attributes
- Always include `$casts` for JSON columns (e.g., `ProjectContent->content`)

### Eloquent Patterns
- Prefer Eloquent over raw queries
- Use `firstOrFail()` / `findOrFail()` to auto-404
- Eager load relationships to avoid N+1: `with(['relation'])`
- Use scopes for reusable query constraints

---

## Blade / Frontend Conventions

### Templates
- Layouts go in `resources/views/layouts/`
- Partials/components go in `resources/views/components/`
- Admin views go in `resources/views/admin/`
- Public views go in `resources/views/public/`

### Output Escaping
- **Default**: `{{ $variable }}` (auto-escaped via `htmlspecialchars`)
- **Raw HTML**: `{!! $variable !!}` — only when the source is trusted AND sanitized
- **Never** render user-supplied content with `{!! !!}`

### Asset Management
- Use Vite for all CSS/JS bundling
- Reference assets with `@vite(['resources/css/app.css', 'resources/js/app.js'])`
- Static images go in `public/images/` or are served via Storage

---

## Database Conventions

### Migrations
- Migration names describe the action: `create_project_contents_table`, `add_year_column_to_project_contents`
- Always include `$table->timestamps()` and consider `$table->softDeletes()`
- Foreign keys use `constrained()->cascadeOnDelete()` pattern

### Seeders
- Use factories for test data
- Production seeders go in `database/seeders/` with clear naming

---

## Git Conventions

### Commit Messages
Format: `type(scope): description`

Types: `feat`, `fix`, `docs`, `style`, `refactor`, `test`, `chore`

Examples:
- `feat(chat): add response caching with 1hr TTL`
- `fix(pdf): resolve DomPDF memory leak on large profiles`
- `test(inquiry): add CSRF validation test for contact form`

### Branches
- `main` — production-ready
- `develop` — integration branch
- `feature/short-description` — feature work
- `fix/short-description` — bug fixes
