# Back-Office UI and Svelte Islands

Use this file only when the consuming project has Svelte islands, legacy jQuery/MFW screens, or modern interactive back-office UI work.

## Scope
- Blade-hosted Laravel pages with mounted Svelte components.
- Back-office/admin screens that mix server-rendered forms, MFW AJAX, jQuery, DataTables, or Svelte.
- Migration work from DOM-owned state to component-owned state.

Do not load this file for a project that does not use Svelte or a comparable island/component frontend.

## Ownership Model
- Blade owns the page shell, authorization, route/form contract, CSRF, initial server data, and non-interactive fallback markup.
- PHP services/resources own normalization of large UI payloads.
- Svelte owns interactive island state, local rendering, local validation hints, and client-side derived display values.
- Laravel remains authoritative for persistence, authorization, validation, and final recomputation of business-critical values.
- Hidden inputs may mirror Svelte state for an existing form submit contract, but they are not the state source.

## Modernization Rules
- Prefer shared Svelte stores/controllers over custom DOM events for cross-island state.
- Prefer a shared fetch/AJAX helper for existing `action=...` relay endpoints.
- Do not duplicate CSRF lookup, AJAX route resolution, URLSearchParams creation, datepicker setup, or response normalization in every island.
- Do not introduce new jQuery/DOM scraping as the primary state model for Svelte-owned UI.
- Do not add new `window.*` globals unless they are temporary compatibility shims with a clear boundary.
- When touching legacy files, prefer shrinking legacy responsibilities instead of adding new responsibilities to them.

## Server Payloads
- Do not assemble large Svelte props or JSON payloads inline in Blade.
- Build normalized payloads in focused PHP services, resources, view composers, or action classes.
- Keep payload identities stable across server preload, client add/remove flows, and save/reload.
- Include only data needed by the island. Avoid dumping broad models into the frontend.

## Forms and Persistence
- Preserve existing Laravel field names and submit contracts when a screen still submits a regular form.
- Use hidden inputs only for values that must be submitted through the existing backend contract.
- Backend validation and recomputation remain authoritative. Do not trust calculated client inputs for persisted totals, permissions, prices, inventory, or workflow state.

## Datepickers and Third-Party Widgets
- Wrap third-party widgets in reusable Svelte components or actions when used inside Svelte-owned UI.
- Prevent legacy global bootstrappers from attaching to Svelte-owned roots.
- Localize labels, months, days, and formats from the current project locale source.

## DataTables and Tables
- Treat table structure as a contract:
  - header and body cells must align
  - column order must remain intentional
  - rendered values must match the server and client state
  - raw HTML columns must be deliberate and narrowly scoped
- Do not prove a table change with text-presence assertions alone.

## Verification
For interactive back-office changes, verify the meaningful path:

- initial page load renders the island
- relevant controls update the UI state
- form submit or AJAX action sends the expected payload
- save/reload or response render confirms persisted/authoritative state
- targeted tests cover the real contract when feasible
