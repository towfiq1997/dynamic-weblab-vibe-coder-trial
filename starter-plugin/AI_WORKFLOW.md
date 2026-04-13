# AI Workflow

## High-Value Prompt Flow

This file keeps only the important prompts used to build this widget.

1. **Build Elementor pricing widget from mock API**
   - Use `wp_remote_get` to fetch `mock-api/pricing.json`.
   - Keep logic in widget/plugin structure and follow WordPress conventions.

2. **Cache API response with transients**
   - First request fetches remote JSON.
   - Next requests read from transient until expiry.
   - Keep graceful fallback for API errors.

3. **Match UI with provided design**
   - Build responsive pricing cards.
   - Highlight popular plan.
   - Keep clean visual parity without heavy effects.

4. **Add Elementor controls**
   - Content controls: title, button text/url, API URL, currency symbol, cache TTL.
   - Style controls: typography and responsive layout settings.

5. **Improve accessibility and SEO**
   - Use semantic structure (`section`, `article`, proper headings).
   - Avoid fake links (`href="#"`), add keyboard focus visibility.
   - Add accessible labels and screen reader text for pricing context.

6. **Feature icon behavior**
   - Icon control applies to feature list items, not top card icon.
   - Keep icon decorative where needed via `aria-hidden`.

## Prompting Notes

- Ask for one focused change per step.
- Prioritize performance and accessibility before visual polish.
- Keep prompts specific: expected behavior, scope, and constraints.

