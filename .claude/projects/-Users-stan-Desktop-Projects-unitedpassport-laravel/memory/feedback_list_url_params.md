---
name: Lists must use server-side pagination with URL params
description: All data lists must use lazy server-side pagination/sorting, sync state to URL query params, and restore on page reload
type: feedback
---

All list/table pages must:
1. Use server-side (lazy) pagination and sorting — every page/sort change fires an AJAX call
2. Sync page, per_page, sort_field, sort_order to URL query params via `router.replace`
3. On page load, read params from URL so refresh preserves state

**Why:** User wants bookmarkable/shareable list states and no full dataset loading.

**How to apply:**
- Use the `useListParams` composable from `resources/js/composables/useListParams.js`
- It provides: `currentPage`, `perPage`, `sortField`, `sortOrder`, `apiParams()`, `onPage()`, `onSort()`
- DataTable must use `lazy` mode with `:first="(currentPage - 1) * perPage"`
- Bind `@page="onPage($event, loadFn)"` and `@sort="onSort($event, loadFn)"`
- Use `apiParams()` when calling the API
- Backend actions accept `page`, `per_page`, `sort_field`, `sort_order` query params
