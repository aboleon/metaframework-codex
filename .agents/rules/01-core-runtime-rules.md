# Core Runtime Rules

## Critical Constraints
- Do not commit or push unless explicitly asked by the user.
- When the user explicitly asks the agent to create a commit, follow the agent-created commit documentation workflow below.
- Never execute database-mutating migration commands without explicit user authorization in the current request. Creating or editing a migration file is not authorization to run it.
- Never edit `vendor/` code directly.
- Do not change dependencies without approval unless the task explicitly requires dependency work.
- Keep user-facing strings translatable via the project's established translation helpers.
- Use dedicated Form Request classes for Laravel validation unless an existing local pattern clearly requires a different boundary.
- Do not create project-specific documentation files unless the user asks for documentation or the repository already expects that artifact.

## Agent-Created Commit Documentation
- This workflow applies whenever the agent creates a commit at the user's explicit request.
- Create the requested commit only after the relevant formatting and verification steps pass.
- Before creating the commit, generate a unique 40-character lowercase hexadecimal commit version. Keep this value stable throughout the workflow; it is an explicit identifier and is separate from Git's content-derived object SHA.
- Include `Commit-Version: {commit-version}` as a Git commit-message trailer when creating the requested commit.
- Create `_docs/commits/{commit-version}.md` and briefly summarize what the commit changed. The filename without `.md` and the `Commit-Version` trailer value must be visually identical.
- If the calculated summary path already exists, do not overwrite it. Stop and ask the user how to resolve the collision.
- Stage the new summary file without staging unrelated changes, then run `git commit --amend --no-edit` so the code changes and summary are contained in the same final commit.
- Verify that the amended commit contains the intended changes, the unchanged `Commit-Version: {commit-version}` trailer, and `_docs/commits/{commit-version}.md` with an exactly matching identifier.
- The generated commit summary is an expected repository artifact and is exempt from the restriction on unsolicited documentation files.
- Do not push unless the user explicitly asks for a push. A commit request alone never authorizes pushing.

## Code Quality
- Follow existing project conventions before shared defaults.
- Privilege direct accessor use: when an accessor/helper result is used only once, call it at the use site instead of assigning it to a local variable. Use a local variable only when the value is reused, expensive, or materially improves readability.
- For PHP, follow PSR-12 and use explicit parameter and return types for new or changed functions, methods, closures, and helpers.
- Prefer typed primitive/value inputs over broad untyped objects. When a helper only needs an accessor value, pass that typed value into the helper instead of passing a request/model/container object.
- Prefer concise guards after normalization when falsy values are not valid domain values.
- Use Laravel request accessors intentionally: prefer `request('field')` over `request()->input('field')` for simple request input. Use Form Request injection for validation. Inject `Illuminate\Http\Request` only when the request object itself is needed, not just to read a scalar.
- Do not resolve business classes with `app(SomeClass::class)`, `App::make()`, or `resolve()` in application code. Use dependency injection for services and explicit `new Class(...)` only for simple value objects or stateless classes whose dependencies are explicit.
- Use method chaining when it improves readability; do not chain so heavily that intermediate concepts become hidden.
- Prefer Laravel helpers and value objects such as `Str`, `Arr`, `Number`, `Uri`, `Str::of()`, and typed request accessors over raw PHP/string manipulation when they improve clarity or UTF-8 safety.
- Use TitleCase enum case names for generic Laravel code unless the package or project has an established enum convention. MetaFramework enums have their own convention in `.agents/instructions/project-rules.md`.
- Do not leave empty zero-parameter constructors unless the constructor is private.
- Keep comments sparse and useful. Prefer clear names and small units over explanatory comments.
- Do not create documentation files unless the user explicitly asks for documentation or the repository already expects that generated artifact.

## Mandatory SOLID Principles
- All new code and refactors must apply SOLID principles. Review touched code for violations before finalizing.
- Single Responsibility: each class, method, and module should have one clear responsibility and one primary reason to change.
- Open/Closed: add behavior through composition, typed configuration, strategies, or focused extension points instead of duplicated configuration blocks and growing type-based conditionals.
- Liskov Substitution: implementations of a contract or parent type must remain interchangeable without changing expected behavior, inputs, outputs, or error semantics.
- Interface Segregation: define small, role-specific contracts and do not force consumers to depend on unused methods or data.
- Dependency Inversion: high-level behavior should depend on contracts or explicit collaborators rather than concrete infrastructure.
- Do not create god objects or fat methods. Split broad responsibilities, long workflows, and unrelated state into cohesive services, actions, value objects, strategies, or focused private methods.
- Prefer cohesive typed objects and explicit dependencies over associative arrays, hidden mutable state, static coupling, and repeated conditional configuration.
- Tests must verify public behavior and contract compatibility so architectural improvements remain safe to extend.

## Frontend Baseline
- Detect the frontend stack before applying frontend rules.
- If the project uses legacy jQuery surfaces, preserve the existing event and AJAX contracts while moving new stateful behavior into focused modules when practical.
- If the project uses Svelte, load the Svelte instruction file before writing Svelte code.
- Prefer `const` by default in JavaScript, `let` for reassigned bindings, and avoid `var` in new scoped code unless an existing legacy file requires it.
- Do not introduce Livewire, Tailwind CSS, Svelte, or another frontend stack unless it already exists in the project or the user explicitly asks.

## Formatting and Verification
- Run the repository's existing formatting and verification commands for touched files.
- If `.ai/hooks/` exists, use the relevant hook wrappers. On Windows PowerShell, prefer `.ps1` wrappers over `.sh` wrappers.
- If `.ai/hooks/validate-commit-message.*` exists and the user asks for a commit, validate the commit subject before committing.
- If the project uses module-prefixed commit subjects, preserve that convention. If the commit validator enforces labels such as `Add:`, `Bugfix:`, `Fix:`, or `Update:`, use one of the accepted labels.
- For PHP changes, run the project's Pint command when available, usually `vendor/bin/pint --dirty`.
- For Blade changes, run the project's Blade formatter if configured.
- For Svelte or related bundled JavaScript changes, run the relevant check/build command when available, such as `npm run build`, `npm run check`, or the project's documented equivalent.
- Run targeted tests for touched behavior. Ask before running very large suites when targeted verification has already passed.
- If the repository is PHPUnit-only, write PHPUnit tests and do not introduce Pest. If a touched Pest test conflicts with a PHPUnit-only project rule, convert it within the scope of the change.

## High-Priority Laravel Defaults
- Use Form Request classes for validation and validated data for mass operations.
- Authorize protected actions through the project's policy/gate/access-control convention.
- Define `$fillable` or `$guarded` on models. Never use `$guarded = []` on models that accept user input unless the project explicitly owns that risk.
- Avoid raw SQL with user input. Use Eloquent/query builder bindings.
- Escape Blade output with `{{ }}`. Use `{!! !!}` only for trusted, pre-sanitized content.
- Include CSRF protection on POST, PUT, PATCH, and DELETE form submissions.
- Validate uploaded files by extension, MIME type, and size. Never trust client-provided filenames.
- Never commit `.env`, credentials, tokens, dumps, or private keys. Access secrets through config, not direct `env()` calls outside config files.
- Eager load relationships used in loops and never execute database queries in Blade templates.
- Keep Blade focused on rendering. Do not put business logic, broad data normalization, inline JavaScript, or inline CSS in Blade unless the local surface already uses that pattern and the change is deliberately scoped.
