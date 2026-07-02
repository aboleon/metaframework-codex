# Svelte Instructions

> Read this only before Svelte or SvelteKit work, and only when the consuming project actually uses Svelte or the task asks for Svelte.

## Detection
- Confirm Svelte from `package.json`, `vite.config.*`, `.svelte` files, SvelteKit structure, or existing Svelte entries.
- Distinguish a Blade-hosted Svelte island from a full SvelteKit application before choosing patterns.
- For Blade-hosted islands, also read `.agents/rules/31-backoffice-ui-svelte-islands.md`.

## Svelte MCP Tools
Use the official Svelte MCP server whenever Svelte development is involved.

### list-sections
- Use when you need to discover relevant Svelte/SvelteKit documentation sections.
- If `.agents/instructions/svelte-docs-index.md` is available and current enough for selecting sections, you may use it as the section index instead of calling `list-sections` again.

### get-documentation
- Fetch the documentation sections relevant to the task before relying on version-sensitive Svelte or SvelteKit behavior.
- Be selective. Do not request broad documentation sets when one or two sections cover the task.

### svelte-autofixer
- You must use this whenever writing or modifying Svelte component/module code.
- Keep applying fixes and rerunning it until no issues or suggestions remain.

### playground-link
- Use only after user confirmation.
- Do not generate a playground link for code written directly into the user's project files.

## Svelte Coding Rules
- Follow the project's existing Svelte version and syntax mode.
- For Svelte 5 code, prefer runes and current documented patterns unless the existing file is intentionally legacy mode.
- Keep component state inside the component/store/controller that owns it.
- Avoid DOM scraping, global mutable state, and jQuery as the state owner for Svelte-owned UI.
- Keep Laravel/Blade submit contracts compatible when Svelte is mounted inside a Blade form.
- Run the project's Svelte check/build/test command after edits when available.
