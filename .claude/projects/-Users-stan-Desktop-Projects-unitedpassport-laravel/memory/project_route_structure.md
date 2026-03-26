---
name: Route and controller structure
description: Project uses 3 route types (api, web, rest) organized in folders with versioning on API routes for SPA
type: project
---

The project has 3 types of routes, each organized in folders:

1. **API** (`/api/v{n}/...`) — SPA application routes. Versioned (v1 current). Files in `routes/api/v1/`. Controllers in `App\Http\Controllers\Api\V1\`.
2. **Web** (`/...`) — HTTP/web requests. Files in `routes/web/`. Controllers in `App\Http\Controllers\Web\`.
3. **REST** (`/rest/...`) — Webhooks and external calls. Files in `routes/rest/`. Controllers in `App\Http\Controllers\Rest\`.

**Why:** Clean separation between SPA frontend calls, traditional web routes, and external integrations. Versioning on API allows evolving the SPA contract without breaking existing clients.

**How to apply:**
- New SPA endpoints go in `routes/api/v1/` with controllers in `Api\V1\`
- New webhook/external endpoints go in `routes/rest/` with controllers in `Rest\`
- New web pages go in `routes/web/` with controllers in `Web\`
- Route files are auto-loaded via glob in the parent route file (`api.php`, `web.php`, `rest.php`)
- Controller folder structure mirrors route folder structure
- REST routes are registered in `bootstrap/app.php` via the `then` callback with prefix `rest`
