# Architecture Guidelines

## Stack and Version Awareness
- Do not hardcode framework assumptions. Detect PHP, Laravel, package, database, and frontend versions from the consuming repository.
- Use official/version-aware documentation tools when a Laravel, Svelte, package, or framework API detail matters.
- Follow sibling files before introducing a new architectural style.

## Laravel Boundaries
- Keep controllers thin and HTTP-focused.
- Put validation in Form Requests.
- Put business workflows in actions, services, jobs, commands, or domain-specific classes.
- When Actions are used, reference action classes explicitly with `SomeClassAction::class` for dispatch/configuration contracts.
- Put small reusable read/format/selection helpers in Accessors when the project uses the MetaFramework accessor pattern.
- Put presentation payload construction in view-data classes, composers, resources, or transformers rather than large Blade arrays.
- Keep Eloquent models focused on relationships, casts, scopes, and model-level invariants. Avoid fat models that own broad workflows.
- Prefer named routes, route model binding, policies/gates, and framework conventions unless the project has an established alternate pattern.

## SOLID Scope
- Apply the mandatory SOLID principles from `.agents/rules/01-core-runtime-rules.md`. This architecture file adds Laravel, module, MetaFramework, AJAX, Svelte-island, DataTables, CSS, view, and translation boundaries rather than restating the shared SOLID checklist.

## Nwidart Modules
Apply only when the project uses `nwidart/laravel-modules`.

- Treat a module as an ownership boundary.
- Use the module namespace and directory layout already present in the project.
- Keep module routes, controllers, requests, actions, views, translations, assets, migrations, factories, and tests in or near the module when the behavior belongs to that module.
- Reuse shared app/package services only when the behavior is genuinely cross-module.
- Do not create cross-module coupling by reaching into another module's internals. Use contracts, events, services, or existing public APIs when needed.

## MetaFramework Architecture
Apply only when MetaFramework packages are installed.

- Reuse existing MetaFramework response, form, navigation, validation, access, translation, and AJAX contracts before creating local equivalents.
- Keep Accessors as thin read/format/selection helpers. Do not turn them into workflow services.
- Keep backend action names, response shapes, and AJAX relay patterns compatible with existing MetaFramework surfaces.
- Use app configuration and app-owned adapters to customize package behavior.
- Treat package edits as reusable package work, not application shortcuts.

## MFW AJAX Pattern
Apply only to projects that already use the MetaFramework AJAX relay style.

- Use the existing relay route and `action={method}` dispatch for MFW actions instead of feature-specific AJAX routes.
- Let an `AjaxController` method act as a bridge only.
- Delegate validation, business logic, and response payload construction to an explicit dedicated Action class under the owning namespace.
- Do not derive Action class names from request input. If a relay must choose between classes, use an explicit allow-list of `SomeClassAction::class` references.
- Module-owned AJAX actions should live in the module, with the module route pointing to the module `AjaxController`.
- Do not override a reusable relay/distribution method just to manually delegate when the trait/convention already supports method dispatch.

## Svelte Islands Inside Laravel
Apply only when Svelte is present.

- Prefer Svelte as mounted islands inside Blade for interactive back-office surfaces unless the project is explicitly a full SvelteKit app.
- Let Blade own page shell, authorization, server-rendered forms, and Laravel submit contracts when that is the existing architecture.
- Let Svelte own interactive state, rendering, and local UI transitions for the island.
- Keep hidden inputs only as form submission artifacts, not as the primary state source.
- Normalize large UI payloads in PHP services/resources before passing them to Svelte.
- Prefer shared stores, controllers, and fetch helpers over DOM scraping, jQuery table parsing, or ad hoc `window.*` globals.

## Yajra DataTables
Apply only when Yajra DataTables is present.

- Keep query construction, filtering, ordering, and column rendering explicit and testable.
- Avoid N+1 queries from row callbacks and closures.
- Escape output by default; mark raw columns deliberately and narrowly.
- When changing columns, verify header/body alignment, column order, raw/escaped output, and any client-side dependencies.

## CSS and Views
- Use the stylesheet system already established by the project.
- If CSSCrush is present, write CSSCrush source with nesting and shared composition in the owning stylesheet or partial.
- Avoid inline `<style>` blocks and one-off component-local CSS unless the project already uses that pattern for the same surface or there is a strong technical reason.
- Keep Blade views focused on rendering. Avoid large inline data normalization, database queries, or business decisions inside Blade.

## Translations and Locales
- Keep user-facing strings translatable.
- Use the consuming project's translation storage and helper conventions.
- Do not assume specific active or fallback locales.
