# MFW Codex Agents

Reusable coding and architecture rules for Laravel applications using the Aboleon MetaFramework ecosystem.

## Precedence
1. Runtime system, developer, and user instructions.
2. The consuming repository's own `AGENTS.md`.
3. This shared `mfw-codex-agents.md`.
4. Supporting files under this package's `.agents/` directory.

If a local project rule conflicts with this package because the application has a specific established convention, follow the local rule. If the conflict would affect architecture, security, persistence, or public behavior, call it out before changing code.

## Loading Policy
Load only the rule files relevant to the task and detected stack. Do not pollute a non-Svelte or non-AI project with Svelte or AI-specific instructions.

All support paths below are relative to this package file, not necessarily to the consuming repository root.

Always read:
- `.agents/rules/01-core-runtime-rules.md`
- `.agents/rules/10-repository-guidelines.md`
- `.agents/rules/20-delivery-quality.md`
- `.agents/rules/25-code-complexity.md`

Read for Laravel/PHP/backend work:
- `.agents/rules/30-architecture.md`
- `.agents/rules/40-laravel-boost-guidelines.md`
- `.agents/instructions/laravel-boost.md`
- `.agents/skills/laravel-best-practices/SKILL.md`
- The specific Laravel best-practice rule files referenced by that skill for the touched area.

Read for MetaFramework, Accessors, MFW Actions, MFW AJAX, MFW forms, CSSCrush, package-boundary, or back-office UI work:
- `.agents/instructions/project-rules.md`
- `.agents/rules/31-backoffice-ui-svelte-islands.md` when Svelte islands or modern back-office UI state are involved.

Read for Svelte or SvelteKit work only when the consuming project uses Svelte:
- `.agents/instructions/svelte.md`
- `.agents/instructions/svelte-docs-index.md` only when selecting Svelte documentation sections.

Read for Laravel AI SDK or AI-provider work only when the consuming application's root `composer.json` directly requires `laravel/ai`, application code/config references the Laravel AI SDK, or the task is AI-related:
- `.agents/skills/ai-sdk-development/SKILL.md`

## Stack Detection
Before editing, inspect the consuming project instead of assuming a stack:
- `composer.json` and `composer.lock` for Laravel, MetaFramework packages, `nwidart/laravel-modules`, `yajra/laravel-datatables*`, `laravel/ai`, Boost, PAO, Pint, PHPUnit, Larastan, and package versions.
- Treat `laravel/ai` installed only as a transitive dependency of `aboleon/metaframework-codex` as agent tooling, not proof that the application has AI features.
- If the application uses Laravel AI SDK at runtime, `laravel/ai` must be present in the consuming application's root `require` section, not only through this dev-only agents package.
- `package.json`, `vite.config.*`, `resources/js`, and `.svelte` files for Svelte or frontend tooling.
- `Modules/`, `module.json`, and `config/modules.php` for Nwidart module structure.
- Existing routes, controllers, Blade views, components, tests, and sibling files for local conventions.
- For non-trivial Laravel, Svelte, framework, or package API decisions, use official/version-aware documentation tooling when available. Skip docs lookup for trivial mechanical edits that do not depend on API behavior.

## Core Expectations
- Do not commit or push unless the user explicitly asks.
- Never execute database-mutating migration commands without explicit user authorization in the current request. This includes `migrate`, `rollback`, `reset`, `refresh`, `fresh`, `db:wipe`, and package/module equivalents.
- A request to create or edit migration files does not authorize running them. Do not infer authorization from the task requiring schema changes, the environment being local or development, migrations being pending, or the command being reversible. Read-only inspection such as `migrate:status` and `migrate --pretend` is allowed.
- Do not edit `vendor/` directly. Treat Composer packages, local path repositories, and published vendor overrides as package boundaries unless the user asks for a package-level change.
- Follow existing project conventions first, then these shared defaults.
- Privilege direct accessor use: when an accessor/helper result is used only once, call it at the use site instead of assigning it to a local variable. Use a local variable only when the value is reused, expensive, or materially improves readability.
- Use explicit parameter and return types for new or changed PHP functions, methods, closures, and helpers.
- Prefer typed primitive/value inputs over broad untyped objects. When a helper only needs an accessor value, pass that typed value into the helper instead of passing a request/model/container object.
- Prefer concise falsy guards after normalization when falsy values are not valid domain values, such as `if (!$region) { return null; }` instead of redundant strict empty-string checks.
- Keep controllers thin. Put validation in Form Requests, business behavior in actions/services, and presentation payload normalization in focused view-data/composer classes.
- Do not resolve business classes with `app(SomeClass::class)`, `App::make()`, or `resolve()` in application code. Use dependency injection for services and explicit `new Class(...)` only for simple value objects or stateless classes whose dependencies are explicit.
- Use Laravel request accessors intentionally: prefer `request('field')` over `request()->input('field')` for simple request input. Use Form Request injection for validation. Inject `Illuminate\Http\Request` only when the request object itself is needed, not just to read a scalar.
- Keep strings translatable in user-facing Laravel/Blade/UI code.
- Verify behavior through the real entrypoint when the task affects runtime behavior. Tests are evidence, not a substitute for the actual reported flow when one exists.
- Do not add superficial tests just to claim coverage. Tests must assert the real contract of the change.
- Keep changed hand-written functions, methods, closures, and handlers within the cyclomatic complexity budget in `.agents/rules/25-code-complexity.md`.
- Keep replies concise and focused on the important result.
- Do not create documentation files unless the user explicitly asks for documentation or the repository already expects that generated artifact.

## High-Priority Laravel Defaults
- Use Form Request classes for validation. Use validated data (`validated()`, typed accessors, or explicit normalization), not `$request->all()` for mass operations.
- In MetaFramework/MFW Blade forms, use `mfw-inputable` components before raw form controls whenever the component supports the field. This includes `x-mfw-inputable::input`, `checkbox`, `datepicker`, `inputdatemask`, `inputradio`, `radio`, `number`, `select`, `textarea`, and `validation-error`.
- Authorize every protected action through the project's policy/gate/access-control convention.
- Define `$fillable` or `$guarded` on models. Never use `$guarded = []` on models that accept user input unless the project explicitly owns that risk.
- Avoid raw SQL with user input. Use Eloquent/query builder bindings.
- Escape Blade output with `{{ }}`. Use `{!! !!}` only for trusted, pre-sanitized content.
- Include CSRF protection on POST, PUT, PATCH, and DELETE form submissions.
- Validate uploaded files by extension, MIME type, and size. Never trust client-provided filenames.
- Never commit `.env`, credentials, tokens, dumps, or private keys. Access secrets through config, not direct `env()` calls outside config files.
- Eager load relationships used in loops and never execute database queries in Blade templates.
- Keep Blade focused on rendering. Do not put business logic, broad data normalization, inline JavaScript, or inline CSS in Blade unless the local surface already uses that pattern and the change is deliberately scoped.
- Prefer Laravel helpers and value objects such as `Str`, `Arr`, `Number`, `Uri`, `Str::of()`, and typed request accessors over raw PHP/string manipulation when they improve clarity or UTF-8 safety.
- Use method chaining when it improves readability; do not chain so heavily that intermediate concepts become hidden.
- Use TitleCase enum case names for generic Laravel code unless the package or project has an established enum convention. For MetaFramework enums, follow `.agents/instructions/project-rules.md`.
- Do not leave empty zero-parameter constructors unless the constructor is private.
- In JavaScript, prefer `const` by default, use `let` only for reassigned bindings, and avoid `var` in new scoped code.

## Conditional Tooling
- If `.ai/hooks/` exists, execute relevant hooks before finalizing work. On Windows PowerShell, prefer `.ps1` wrappers over `.sh` wrappers.
- If `.ai/hooks/validate-commit-message.*` exists and the user asks for a commit, validate the commit subject before committing.
- If the project uses module-prefixed commit subjects, preserve that convention. If the commit validator enforces labels such as `Add:`, `Bugfix:`, `Fix:`, or `Update:`, use one of the accepted labels.
- If PHP changed and Pint is installed, run the project's Pint command, usually `vendor/bin/pint --dirty`.
- If Laravel PAO is installed, keep using the project's normal PHPUnit, Pest, Paratest, PHPStan, Rector, and Artisan commands. PAO should optimize agent output automatically; do not disable it unless human-readable output is explicitly needed.
- If Blade changed and a Blade formatter is configured, run it on touched Blade files.
- If Svelte or bundled JavaScript changed, run the relevant project check/build command when available.
- If the repository is PHPUnit-only, write PHPUnit tests and do not introduce Pest. If a touched Pest test conflicts with a PHPUnit-only project rule, convert it within the scope of the change.
- Do not introduce Livewire, Tailwind CSS, Svelte, or another frontend stack unless it already exists in the project or the user explicitly asks.

## Verification Contracts
- Do not present "tests passed" as the source of truth for a bugfix when the real runtime path was not verified.
- For bugfix reporting, use this verification format when applicable:
  - Reproduced: yes/no
  - Real entrypoint verified: exact route/controller/command/job/component
  - Result: success status, rendered outcome, persisted state, or remaining failure
  - Test added: real contract test, existing test updated, or no meaningful test available
- Do not add superficial or token tests just to claim coverage.
- If a test is added, it must cover the real contract of the change, including structural UI contracts when the change affects table/layout composition.
- For table/UI column changes, the contract includes matching header/body cells, correct column placement, and rendered values. Do not treat text presence alone as sufficient coverage.
- If no meaningful test can cover the real contract, say so explicitly instead of adding a weak test.

## Mandatory SOLID Principles
Apply the detailed SOLID checklist from `.agents/rules/01-core-runtime-rules.md` to all new code and refactors. That file is the source of truth for shared SOLID guidance.

## Package-Local Customization
Consuming projects may keep extra project-specific rules in their own `AGENTS.md` below the managed include block. Those local rules should contain only facts that cannot be deduced from the repository or this package.
