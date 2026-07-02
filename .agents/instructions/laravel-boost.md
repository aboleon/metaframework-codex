# Laravel Boost, PHP, and Test Rules

> Read this before Laravel, PHP, Pint, PHPUnit, routing, database, backend, or Laravel ecosystem work.

## Version Discovery
- Inspect the consuming repository's `composer.json` and `composer.lock` before relying on Laravel, PHP, PHPUnit, Pint, Larastan, Boost, or package API behavior.
- Do not assume the versions used by the project that originally generated these rules.
- If Laravel Boost or a Laravel MCP server is available, prefer its version-aware tools for framework/package documentation and app inspection.

## Skills Activation
- Activate the relevant local skill from `**/skills/**` when working in that domain.
- For Laravel backend code, use `.agents/skills/laravel-best-practices/SKILL.md` and the specific rule files it references for the touched area.
- For Laravel AI SDK work, use `.agents/skills/ai-sdk-development/SKILL.md`.

## Conventions
- Follow existing code conventions used by the consuming application.
- Check sibling files for structure, approach, naming, dependencies, and tests.
- Use descriptive names for variables and methods.
- Reuse existing components before creating new ones.
- Do not commit or push changes unless the user explicitly asks for `commit`, `push`, or both.

## Documentation Search
- Use version-aware official documentation search before non-trivial Laravel ecosystem code changes where API behavior, package conventions, or framework version differences matter.
- Use broad topic-based queries.
- Skip docs search for trivial mechanical edits that do not depend on framework behavior.

## Laravel Application Structure
- Keep the existing directory structure.
- Do not create new base folders without approval unless the project clearly has the same pattern elsewhere.
- Do not change dependencies without approval.
- Prefer `php artisan make:*` generators for framework artifacts inside applications.
- Pass `--no-interaction` to Artisan commands when available.

## PHP Rules
- Use curly braces for all control structures.
- Use explicit parameter and return types for new or changed PHP functions, methods, closures, and helpers.
- Privilege direct accessor use: when an accessor/helper result is used only once, call it at the use site instead of assigning it to a local variable.
- Prefer typed primitive/value inputs over broad untyped objects. When a helper only needs an accessor value, pass that typed value into the helper.
- Prefer constructor property promotion when it improves clarity.
- Do not resolve business classes with `app(SomeClass::class)`, `App::make()`, or `resolve()` in application code. Use dependency injection for services and explicit `new Class(...)` only for simple value objects or stateless classes whose dependencies are explicit.
- Use TitleCase enum case names for generic Laravel code unless the package or project has an established enum convention. MetaFramework enums have their own convention in `.agents/instructions/project-rules.md`.
- Do not leave empty zero-parameter constructors unless the constructor is private.
- Prefer PHPDoc blocks for useful generics, array shapes, and complex value contracts.
- Avoid inline comments unless they explain non-obvious behavior.

## Laravel Rules
- Use Form Requests for validation.
- Use Laravel request accessors intentionally: prefer `request('field')` over `request()->input('field')` for simple request input. Use Form Request injection for validation. Inject `Illuminate\Http\Request` only when the request object itself is needed, not just to read a scalar.
- Use policies/gates for authorization.
- Use Eloquent relationships and eager loading to avoid N+1 queries.
- Prefer named routes and `route()`.
- Do not call `env()` outside config files.
- Preserve existing column attributes when modifying database columns.
- Follow the middleware, provider, scheduler, and command registration pattern for the detected Laravel version.

## AJAX Conventions
- For MFW-style AJAX interactions, use the existing `action={methodName}` relay methodology instead of ad hoc REST endpoints.
- Use an `AjaxController` method as a bridge and delegate business behavior to a dedicated Action class.
- When using MFW modal components, follow the component's callback/confirmation pattern instead of native browser confirmations.

## Testing Rules
- Use the test framework already used by the project. For Laravel apps this is usually PHPUnit.
- Create tests with Artisan generators when adding new test classes inside an application.
- Do not create ad hoc verification scripts or tinker snippets when proper tests can cover the functionality and prove the contract.
- If the repository is PHPUnit-only, write PHPUnit tests and do not introduce Pest. If a touched Pest test conflicts with a PHPUnit-only project rule, convert it within the scope of the change.
- Run the smallest relevant test target while iterating.
- When a test has been updated, run that test.
- Do not remove tests or test files without approval.
- Ask before running a very large full suite after targeted tests pass.

## Formatting
- If PHP files changed, run the project's Pint command when available, usually `vendor/bin/pint --dirty` or the configured Composer script.
- If Blade files changed, run the configured Blade formatter when available.
- If `.ai/hooks/validate-commit-message.*` exists and the user asks for a commit, validate the commit subject before committing.

## Frontend Bundling
- If a frontend change is not visible, stale assets may be the cause. Run or ask for the project's relevant build/dev command, such as `npm run build`, `npm run dev`, or `composer run dev`.
