# Voice & Style Guide

This document defines the tone, language, and style to use across all content produced for the Western Visayas Region 6 Investment Economic Profile project.

---

## General Principles

1. **Professional & Authoritative**: This is a government platform. All content must convey credibility and institutional trust.
2. **Data-Driven**: Lead with facts, statistics, and evidence. Avoid subjective claims without data backing.
3. **Investor-Friendly**: Complex economic data must be presented in accessible, non-jargon language for both local and foreign investors.
4. **Concise**: Prefer short, clear sentences. Avoid bureaucratic filler phrases.

---

## Tone by Context

### User-Facing Content (Frontend / PDF)
- **Tone**: Warm, professional, inviting
- **Perspective**: Third person ("The region offers..." not "We offer...")
- **Language**: Plain English. Define technical terms on first use.
- **Numbers**: Use commas for thousands (1,234,567). Use "PHP" or "₱" for Philippine Peso amounts.

**✅ Good**: "Western Visayas recorded a GRDP of ₱1.2 trillion in 2024, reflecting a 6.8% growth from the previous year."

**❌ Bad**: "The region's GRDP was really impressive at PHP 1.2T, growing by like 6.8%."

### AI Chat Assistant Responses
- **Tone**: Conversational but accurate
- **Perspective**: First person as the assistant ("Based on the data, the region's top export...")
- **Guardrails**: Never fabricate statistics. If data is unavailable, say: "I don't have specific data on that topic in the current economic profile."
- **Length**: Keep responses under 200 words unless the user asks for detail

### Admin Backend / Developer Documentation
- **Tone**: Technical, precise, no ambiguity
- **Perspective**: Imperative ("Run the migration..." not "You should run...")
- **Format**: Use code blocks, bullet points, and tables for clarity

### Code Comments & Docblocks
- **Tone**: Terse, functional
- **Format**: PHPDoc for public methods, inline comments only for non-obvious logic
- **Example**:
  ```php
  /**
   * Generate a PDF of the economic profile for the given year.
   *
   * @param int $year The target year for profile generation
   * @return \Illuminate\Http\Response Streamed PDF download response
   */
  ```

---

## Formatting Rules

| Element | Rule |
|---|---|
| Headings | Title Case for H1-H2, Sentence case for H3+ |
| Lists | Use bullet points for unordered items, numbered for sequential steps |
| Emphasis | **Bold** for key terms on first use, *italic* for titles or foreign words |
| Links | Descriptive text, never "click here" |
| Tables | Always include a header row. Align numbers to the right |
| Dates | Full format: "June 29, 2026" or ISO: "2026-06-29" — never "6/29/26" |
| Percentages | Always include the % symbol: "6.8%" not "6.8 percent" |

---

## Prohibited Language

- No informal contractions in user-facing content ("don't" → "does not")
- No superlatives without data ("best", "leading", "top") — unless backed by a specific ranking
- No placeholder text ("Lorem ipsum", "TBD", "TODO") in production content
- No gendered language when referring to investors or users — use "they/their"
