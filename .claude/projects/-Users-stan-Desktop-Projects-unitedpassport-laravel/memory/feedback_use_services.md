---
name: Use Service classes grouped by domain
description: Actions call Services (app/Services/), Services call Repos. Keep classes small, split by domain and function.
type: feedback
---

Don't make big files/classes. Split logic into Services grouped by domain.

**Why:** User wants clean separation and small focused classes. Functions like addUser, updateUser go in a dedicated service.

**How to apply:**
- Services live in `app/Services/` grouped by domain (e.g. `Users/UserService.php`, `Users/UserRoleService.php`)
- Flow: Controller → Action → Service → Repo
- Each service handles one domain entity (users, roles, etc.)
- Group related functions by common sense into separate service classes
- Services call Repos for data access, never Models directly
