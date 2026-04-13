# Performance Guide

## Implemented Performance Decisions

### Data Fetching

- Use `wp_remote_get` for pricing endpoint requests.
- Cache normalized pricing data in transients.
- Use URL-based transient key to prevent cache collisions.
- Validate response code and JSON shape before rendering.

### Rendering

- Avoid blocking behavior on API failures (show safe fallback text).
- Keep markup semantic and lightweight.
- Use responsive CSS grid instead of JavaScript layout calculations.

### Assets

- Register only required widget stylesheet.
- Keep CSS focused on component scope.
- Prefer reduced-shadow styles for lower paint cost.

## Optimization Checklist

### PHP / WordPress

- [x] Use transients for remote data
- [x] Handle API and decoding errors safely
- [x] Sanitize remote content before output
- [ ] Add cache invalidation action for admins

### Frontend

- [x] Responsive controls for desktop/tablet/mobile columns
- [x] Accessible, semantic HTML structure
- [x] Focus-visible styles for keyboard users
- [ ] Generate and load `style.min.css` in production

## Performance Targets

- Widget data source latency hidden by transient cache after first request.
- Stable render without layout shifts in card grid.
- Keep styles simple to reduce paint and composite cost.

## Monitoring Suggestions

- Query Monitor: verify transient hit/miss behavior.
- Browser DevTools Performance: inspect paint/composite cost.
- Network tab: ensure pricing endpoint is not called on every view.

Lighthoue report
Performance: 100
Accessibility: 92
Best Practices: 74
SEO: 92
