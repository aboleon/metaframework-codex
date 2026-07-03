# Agent Rules Index

This folder contains reusable rule modules for projects that include `mfw-codex-agents.md`.

Read order:
1. `01-core-runtime-rules.md`
2. `10-repository-guidelines.md`
3. `20-delivery-quality.md`
4. `25-code-complexity.md`
5. `30-architecture.md` for Laravel, MetaFramework, Nwidart modules, Yajra DataTables, backend architecture, or database work
6. `31-backoffice-ui-svelte-islands.md` for Svelte islands, back-office UI state, or legacy-to-modern frontend migration
7. `40-laravel-boost-guidelines.md` when Laravel Boost or Laravel ecosystem docs/tools are available

Conditional rule loading matters:
- Do not read Svelte-specific rules unless the consuming project uses Svelte or the task is about Svelte.
- Do not read AI SDK rules unless the consuming project uses `laravel/ai` or the task is AI-related.
- Do not assume Nwidart modules, Yajra DataTables, CSSCrush, or MetaFramework packages are present. Detect them from the project first.

If a rule conflicts:
- Direct runtime system/developer/user instructions have priority.
- The consuming repository's own `AGENTS.md` has priority over this shared package for project-specific facts.
- This package's `mfw-codex-agents.md` has priority over supporting files in this folder.
