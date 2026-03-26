---
name: Use Repositories not Models
description: Always work through Repository classes, not Models directly. Flag conflicts if they arise.
type: feedback
---

Work through Repos (app/Repositories/) instead of Models directly for all data operations.

**Why:** User preference for repository pattern as the data access layer throughout the project.

**How to apply:** When writing controllers, services, or any code that reads/writes data, instantiate and use the appropriate Repo class. If a situation arises where using the Repo doesn't fit (e.g., auth internals, seeders), flag it to the user before proceeding.
