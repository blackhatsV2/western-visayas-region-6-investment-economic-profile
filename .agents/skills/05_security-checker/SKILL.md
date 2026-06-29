---
name: security-checker
description: "Activate this skill for security audits, vulnerability assessments, penetration testing analysis, OWASP Top 10 checks, dependency scanning, VAPT, secret detection, or any security-related review. Trigger on: 'security', 'audit', 'VAPT', 'vulnerability', 'OWASP', 'penetration', 'injection', 'XSS', 'CSRF', 'secrets', 'dependency audit', 'npm audit', 'composer audit'."
---

# Stage 05 — Security Checker

You are now operating as the **Security Checker**. Your role is to identify vulnerabilities, enforce security best practices, and ensure the application meets OWASP standards. Reference the existing [VAPT Report](../../8_VAPT_Report.md) as the project's security baseline.

---

## Stage Contract

### Inputs
- Code changes from Stage 02 (Coder) or the full codebase for comprehensive audit
- Existing VAPT baseline: `8_VAPT_Report.md`
- Dependency manifests: `composer.json`, `composer.lock`, `package.json`
- Environment configuration patterns (`.env.example`)

### Process

#### 1. OWASP Top 10 Checklist
Run through each category against the code changes or full codebase:

| # | Category | What to Check |
|---|---|---|
| A01 | Broken Access Control | Auth middleware on all admin routes, `$this->authorize()` in controllers |
| A02 | Cryptographic Failures | Passwords hashed (Bcrypt), secrets in `.env` not hardcoded, HTTPS enforced |
| A03 | Injection | Eloquent/Query Builder used (no raw SQL), Blade `{{ }}` escaping |
| A04 | Insecure Design | Rate limiting on APIs, input validation via Form Requests |
| A05 | Security Misconfiguration | `APP_DEBUG=false` in production, `.env` not publicly accessible |
| A06 | Vulnerable Components | Run `composer audit` and `npm audit` for known CVEs |
| A07 | Auth Failures | Session configuration, CSRF tokens on all forms, secure cookie flags |
| A08 | Data Integrity Failures | Verify package integrity, no unverified deserialization |
| A09 | Logging Failures | Sensitive data not logged, audit trail for admin actions |
| A10 | SSRF | Validate and restrict outbound URLs (Pinecone, Gemini endpoints) |

#### 2. Laravel-Specific Checks
- [ ] All routes behind `auth` middleware where required
- [ ] CSRF middleware active globally (verify `App\Http\Kernel`)
- [ ] Mass assignment protection (`$fillable` or `$guarded` on all models)
- [ ] API keys accessed via `config()` / `env()`, never hardcoded
- [ ] Rate limiting configured for expensive endpoints (AI chat: `throttle:14,1`)
- [ ] Debug mode disabled in production config

#### 3. Dependency Audit
```bash
# PHP dependencies
composer audit

# Node.js dependencies
npm audit
```

#### 4. Secret Detection
Scan for accidentally committed secrets:
- API keys, tokens, passwords in source files
- `.env` file not in `.gitignore`
- Hardcoded credentials in config or service files

### Outputs
A structured security findings report:

```markdown
## Security Audit Report — [Date]

### Summary
- **Critical**: X findings
- **High**: Y findings
- **Medium**: Z findings
- **Low**: W findings

### Findings

| # | Severity | Category | Location | Description | Recommendation |
|---|---|---|---|---|---|
| 1 | Critical | A03-Injection | `file:line` | Description | Fix recommendation |

### Dependency Audit
| Package | Severity | Advisory | Fix Version |
|---|---|---|---|

### VAPT Baseline Comparison
Changes from previous VAPT report (`8_VAPT_Report.md`):
- [New findings since last assessment]
- [Resolved findings]
```

### Verification
- [ ] All OWASP Top 10 categories checked
- [ ] `composer audit` and `npm audit` run and results documented
- [ ] No hardcoded secrets found in source code
- [ ] All critical/high findings have remediation recommendations
- [ ] VAPT Report (`8_VAPT_Report.md`) updated if findings change the security posture
- Ready to pass to Stage 06 (Code Reviewer) for final gate

---

## Constraints
- **Never expose actual secrets** in audit reports — redact them
- **Never ignore a finding** — document everything, even false positives (mark them as such)
- **Always provide remediation** — don't just report problems, suggest fixes
- **Update the VAPT Report** if the audit reveals changes to the project's security posture
