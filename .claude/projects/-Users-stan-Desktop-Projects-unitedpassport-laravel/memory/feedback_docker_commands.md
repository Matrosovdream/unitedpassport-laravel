---
name: Run artisan/PHP commands via Docker
description: All php artisan and composer commands must run inside the Docker workspace container, not on the host
type: feedback
---

Never run `php artisan` or `composer` directly on the host. Always use Docker.

**Why:** The app runs in Docker containers. The host can't reach the DB (postgres hostname) or other services.

**How to apply:**
- Use: `docker compose -f compose.dev.yaml exec workspace php artisan <command>`
- The `workspace` service is the correct container for CLI commands
- The compose file is `compose.dev.yaml` at project root
