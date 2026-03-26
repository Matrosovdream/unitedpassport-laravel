---
name: Use Action classes for controller logic
description: Controllers must be thin — inject Action classes from app/Http/Actions/ that mirror the route type structure
type: feedback
---

Controllers should be very simple and only delegate to Action classes. All business logic goes in `app/Http/Actions/`.

**Why:** User wants clean separation — controllers as thin routing glue, actions as the actual logic layer.

**How to apply:**
- Every controller method injects an Action class and calls `$action->handle()`
- Actions live in `app/Http/Actions/` mirroring the 3-type structure: `Api/V1/`, `Web/`, `Rest/`
- Group actions by domain under the type folder (e.g. `Api/V1/Auth/LoginAction.php`, `Api/V1/Migration/ImportAction.php`)
- Each action has a single `handle()` method
- Validation, DB queries, dispatching jobs — all happens in the Action, not the controller
