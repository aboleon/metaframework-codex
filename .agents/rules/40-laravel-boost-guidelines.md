# Laravel Boost and Ecosystem Guidelines

Use this file for Laravel backend work, especially when Laravel Boost, Laravel MCP tools, or version-aware Laravel documentation tooling is available.

## Foundation
- Discover installed versions from `composer.lock`, `composer.json`, and package manager output.
- Do not assume Laravel, PHP, PHPUnit, Pint, Larastan, Sail, Livewire, or Boost versions from this package.
- Follow existing code conventions and sibling patterns before shared defaults.
- Reuse existing components before creating new ones.

## Documentation Search
- For Laravel ecosystem decisions where API behavior or version support matters, use version-aware official documentation tooling when available.
- Use broad topic queries such as `routing`, `form request validation`, `database transactions`, `queue unique jobs`, or `blade components`.
- Do not add package names to queries when the documentation tool already receives installed package metadata.
- Skip docs lookup for trivial mechanical edits that do not depend on API behavior.

## Laravel Way
- Prefer `php artisan make:*` generators for framework artifacts when working inside an application.
- Use Eloquent relationships, eager loading, scopes, policies, resources, and Form Requests where appropriate.
- Prefer named routes and `route()`.
- Do not call `env()` outside config files.
- When modifying existing database columns, preserve existing attributes in migrations.
- Follow the middleware, provider, scheduler, and command registration pattern for the detected Laravel version.

## Tools
When Laravel Boost or equivalent MCP tools are available:

- Prefer read-only database/schema tools over ad hoc SQL or tinker for inspection.
- Use URL resolution tools before sharing a local app URL.
- Use browser logs for recent frontend/runtime errors when debugging UI.
- Use `php artisan route:list` for route inspection when route behavior matters.

## Testing Rules
- Use the test framework already used by the project.
- For Laravel applications with PHPUnit, prefer `php artisan test --compact` or the project's documented command.
- If the repository is PHPUnit-only, write PHPUnit tests and do not introduce Pest. If a touched Pest test conflicts with a PHPUnit-only project rule, convert it within the scope of the change.
- Run focused tests while iterating.
- Ask before running a very large full suite after targeted tests pass.
- Do not remove tests without approval.

## Formatting
- Run the project's formatter for touched PHP files, usually `vendor/bin/pint --dirty` or the configured Composer script.

## Frontend Package Choices
- Do not introduce Livewire, Tailwind CSS, Svelte, or another frontend stack unless it already exists in the project or the user explicitly asks.
- If the project already uses one of these tools, follow its local conventions and relevant instruction files.
