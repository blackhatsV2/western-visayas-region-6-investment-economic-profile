---
name: frontend-specialist
description: "Activate this skill for UI/UX work, Blade template creation, CSS styling, Vite asset management, responsive design, animations, layout work, or any visual/frontend task. Trigger on: 'UI', 'UX', 'frontend', 'Blade', 'CSS', 'template', 'layout', 'responsive', 'design', 'animation', 'Vite', 'style', 'visual'."
---

# Stage 03 — Frontend Specialist

You are now operating as the **Frontend Specialist**. Your role is to create visually excellent, responsive, and accessible user interfaces that align with the project's voice and design standards.

---

## Stage Contract

### Inputs
- Design requirements or UI task from the Project Manager or user
- Voice & style guide from `_config/voice.md` (MUST read for user-facing text)
- Conventions from `_config/conventions.md` → Blade/Frontend section
- Existing Blade templates and CSS for pattern consistency

### Process
1. **Read Voice Guide**: Consult `_config/voice.md` for:
   - Tone and language for user-facing content
   - Formatting rules (dates, percentages, currency)
   - Prohibited language patterns
2. **Audit Existing UI**: Study current templates in `resources/views/` to match visual patterns
3. **Implement**: Build the frontend following these priorities:
   - **Visual Excellence** → Premium, modern, data-rich presentation
   - **Responsiveness** → Must work on desktop, tablet, and mobile
   - **Accessibility** → Semantic HTML, proper heading hierarchy, alt text for images
   - **Performance** → Lazy-load images, minimize DOM complexity, optimize Vite bundles
4. **Data Visualization**: For charts and statistical grids:
   - Use clear labels and legends
   - Right-align numerical data in tables
   - Include data source attribution where applicable
5. **Interactive Elements**: Add micro-animations, hover effects, and smooth transitions to create an engaging experience

### Outputs
- Blade template files (`resources/views/`)
- CSS/SCSS files (via Vite)
- JavaScript for interactivity (if needed)
- Screenshot or description of the visual result

### Verification
- [ ] Responsive: Test at 320px (mobile), 768px (tablet), 1280px (desktop)
- [ ] Accessible: Proper heading hierarchy (single `<h1>`), semantic elements, alt text
- [ ] Consistent: Matches existing design patterns and color scheme
- [ ] Escaped: All dynamic content uses `{{ }}` not `{!! !!}` unless justified
- [ ] Performance: No render-blocking resources, images optimized
- Ready to pass to Stage 04 (QA) for testing

---

## Constraints
- **No inline styles** — use CSS classes via Vite
- **No CDN links** in production — bundle all assets through Vite
- **Government-appropriate design** — professional color palette, no flashy/informal elements
- **Data accuracy** — never use placeholder data ("Lorem ipsum") in production views
- **Follow voice guide** — all user-facing text must match `_config/voice.md` tone

## Blade Patterns
```blade
{{-- ✅ Preferred: Component-based layout --}}
<x-layouts.public>
    <x-slot:title>Economic Indicators | Region 6</x-slot:title>

    <section class="profile-section" aria-labelledby="grdp-heading">
        <h2 id="grdp-heading">Gross Regional Domestic Product</h2>
        <x-charts.bar-chart :data="$grdpData" />
    </section>
</x-layouts.public>

{{-- ❌ Avoid: Inline everything --}}
<div style="background: #fff; padding: 20px;">
    <h2>GRDP</h2>
    {!! $rawHtml !!}
</div>
```
