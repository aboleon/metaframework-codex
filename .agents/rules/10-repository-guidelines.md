# Repository Guidelines

## Discover Before Editing
- Inspect the consuming repository before applying package assumptions.
- Use `composer.json` and `composer.lock` to detect Laravel, MetaFramework packages, Nwidart modules, Yajra DataTables, Laravel AI SDK, Boost, PAO, Pint, PHPUnit, and Larastan.
- Use `package.json`, `vite.config.*`, `resources/js`, and `.svelte` files to detect Svelte, Vite, frontend build commands, and test/check scripts.
- Check sibling files and nearby tests for naming, structure, and response conventions.

## Common Laravel Structure
- Laravel app root is usually the repository root.
- Routes usually live in `routes/`; modular projects may also use `Modules/*/Routes`.
- Shared views/assets/lang usually live under `resources/` and `lang/`.
- Public assets live under `public/`.
- Tests live under `tests/` unless the project has a module-specific test layout.

## Nwidart Laravel Modules
Apply only when `nwidart/laravel-modules`, `Modules/`, or `module.json` is present.

- Keep module-owned code inside the owning module namespace and folder.
- Prefer module generators such as `php artisan module:make`, `module:make-controller`, `module:make-model`, and `module:make-migration` when creating module artifacts.
- Put module routes in the module route files and module migrations in the module migration folder.
- Do not put module-specific AJAX actions, controllers, requests, or views into the application root unless the existing project already centralizes that concern.

## MetaFramework Packages
Apply only when `aboleon/metaframework*` packages or `config/mfw*.php` files are present.

- Reuse MetaFramework components, response traits, validation helpers, input components, and AJAX conventions already used by the project.
- Respect package boundaries. Prefer configuration, adapters, app-owned views/components, and documented extension points before package edits.
- If a behavior belongs in a reusable package, keep it generic and verify it in the package repository rather than patching a downstream application around it.

## Yajra DataTables
Apply only when `yajra/laravel-datatables*` packages or existing DataTable classes/controllers are present.

- Follow existing server-side DataTables conventions for query builders, column definitions, filters, ordering, escaping, and raw columns.
- Keep expensive rendering and per-row lookup logic out of row callbacks where possible. Eager load or precompute required data.
- Treat HTML columns as explicit UI contracts: test column order, header/body alignment, escaped/raw behavior, and rendered values when changing table structure.

## Build and Development Commands
- Use the project's own scripts first, such as `composer test`, `php artisan test`, `vendor/bin/phpunit`, `npm run build`, `npm run check`, or hook scripts.
- Run `php artisan optimize:clear` only when stale Laravel caches are plausibly involved.
- Never run database-mutating migration commands unless the user explicitly authorizes execution in the current request. Do not treat a request to create or edit migrations as permission to execute them.
- Prohibited without explicit authorization: `migrate`, `rollback`, `reset`, `refresh`, `fresh`, `db:wipe`, and package/module equivalents. Read-only `migrate:status` and `migrate --pretend` checks are allowed.

## Coding Style and Naming
- PHP classes use StudlyCase; variables and methods use camelCase.
- Blade file naming follows local convention, commonly snake_case.
- Keep CSS in the stylesheet system already used by the project. If CSSCrush is present, prefer CSSCrush-managed stylesheets and partials over inline styles.
- Keep UI action styling consistent with neighboring screens instead of inventing a new button system.

## Testing
- Add or update tests for changed behavior where feasible.
- Prefer intent-based test names.
- For UI table/layout changes, assertions must cover structural contracts, not just text presence.

## Security
- Never commit `.env`, credentials, tokens, dumps, or private keys.
- Keep `.env.example` aligned when adding required config keys.
