# MetaFramework Codex Agents Package

This repository packages reusable Codex/agent instructions for Laravel applications that may use Aboleon MetaFramework packages, Nwidart Laravel Modules, Yajra DataTables, Svelte islands, and Laravel AI SDK.

## Shared Entry
- The canonical shared entrypoint is `mfw-codex-agents.md`.
- Keep this file and `mfw-codex-agents.md` generic. Do not add project names, module names, routes, locales, database versions, or business rules that only apply to one downstream application.
- Preserve `.agents/instructions/**` and `.agents/skills/**` as reusable assets. Update them only when the rule is broadly applicable.
- Project-specific applications should include this package from their own `AGENTS.md`; their local `AGENTS.md` has priority over this shared package when a rule conflict is truly project-specific.

## Package Work
- Do not commit or push unless explicitly asked.
- Do not edit `vendor/` directly.
- Keep installer behavior additive and non-destructive: never overwrite a consumer `AGENTS.md` outside the managed block.
- Privilege direct accessor use: when an accessor/helper result is used only once, call it at the use site instead of assigning it to a local variable. Use a local variable only when the value is reused, expensive, or materially improves readability.
- Use explicit parameter and return types for new or changed PHP functions, methods, closures, and helpers.
- Prefer typed primitive/value inputs over broad untyped objects.
- Do not resolve business classes with `app(SomeClass::class)`, `App::make()`, or `resolve()` in application code.
- Prefer `request('field')` over `request()->input('field')` for simple Laravel request input in consumer guidance.
- After PHP changes in this repository, run `php -l` on changed PHP files and `composer validate`.
