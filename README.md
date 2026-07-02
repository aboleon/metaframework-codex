# MetaFramework Codex

Reusable Codex/agent rules for Laravel applications using the Aboleon MetaFramework ecosystem.

## Install

```bash
composer require --dev aboleon/metaframework-codex
vendor/bin/mfw-codex-agents install
```

Install this package as a dev dependency. Its normal Composer dependencies intentionally include Laravel Boost, Laravel PAO, and the Laravel AI SDK so upstream guideline, skill, `boost:update`, and agent-optimized command-output tooling are available while developing agent rules.

If a consuming application uses Laravel AI SDK at runtime, that application must still require `laravel/ai` in its own root `require` section. Do not rely on this dev-only agents package to provide runtime AI dependencies in production installs.

The installer adds a managed include block to the consuming project's `AGENTS.md`. Existing local project instructions are preserved.

Use a custom target when needed:

```bash
vendor/bin/mfw-codex-agents install --target=docs/AGENTS.md
```

Preview without writing:

```bash
vendor/bin/mfw-codex-agents install --dry-run
```

## Consumer Contract

The consuming project's `AGENTS.md` should keep project-specific facts and overrides. The managed block points agents to `mfw-codex-agents.md`, which then loads supporting files conditionally:

- Always: core runtime, repository, and delivery quality rules.
- Laravel/backend: architecture, Boost, and Laravel best-practice skill rules.
- MetaFramework/MFW/CSSCrush: project rules.
- Svelte: Svelte instructions and Svelte-island UI rules.
- Laravel AI SDK: AI SDK skill.

Do not copy project-specific generated rules into this package. Add only normalized rules that can apply across projects.

## Updating Upstream Skills

Laravel, PAO, and AI SDK skill/tooling content is sourced from Laravel packages. To refresh it, update Composer dependencies in a disposable branch or scratch app, run `php artisan boost:update --no-interaction`, then sync the generated upstream skill directories into this package and review the diff before tagging a new package release.

## Package Layout

- `mfw-codex-agents.md`: shared entrypoint for downstream projects.
- `.agents/rules/`: modular rule files loaded by stack/task.
- `.agents/instructions/`: focused instructions for Laravel Boost, MetaFramework/MFW, and Svelte.
- `.agents/skills/`: reusable Codex skills.
- `bin/mfw-codex-agents`: installer command.
