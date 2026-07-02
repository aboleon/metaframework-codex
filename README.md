# MetaFramework Codex

Reusable Codex/agent rules for Laravel applications using the Aboleon MetaFramework ecosystem.

## Install

```bash
composer require --dev aboleon/metaframework-codex
vendor/bin/mfw-codex-agents install
```

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

## Package Layout

- `mfw-codex-agents.md`: shared entrypoint for downstream projects.
- `.agents/rules/`: modular rule files loaded by stack/task.
- `.agents/instructions/`: focused instructions for Laravel Boost, MetaFramework/MFW, and Svelte.
- `.agents/skills/`: reusable Codex skills.
- `bin/mfw-codex-agents`: installer command.
