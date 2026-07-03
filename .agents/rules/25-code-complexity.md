# Code Complexity Guidelines

## Cyclomatic Complexity Budget
Cyclomatic complexity counts the independent execution paths through a function, method, closure, component handler, command handler, or equivalent code unit. Use it as a practical maintainability and testability budget for all changed hand-written code.

- Target a cyclomatic complexity of 10 or less per changed code unit.
- Treat 11-20 as moderate risk. Review the shape of the code, reduce accidental branching where practical, and make sure tests cover the meaningful branch families.
- Treat 21-50 as high risk. Refactor before delivery unless the complexity is essential to the domain and splitting it would make the code harder to verify.
- Treat 50+ as unacceptable for newly written or materially changed hand-written code. Redesign, decompose, or replace the branching structure before adding more behavior.
- Do not increase the complexity of an already-high-complexity legacy unit unless the task cannot be completed otherwise. When that happens, keep the increase minimal, add focused tests, and call out the residual risk.

The threshold is a guardrail, not a scoring game. The goal is code that is easier to reason about, review, and test.

## How To Estimate
Use the repository's configured analyzer when one exists. If no tool is configured, estimate quickly by counting decision points in the code unit and adding 1. The formal graph formula is `M = E - N + 2P`, where `E` is control-flow edges, `N` is nodes, and `P` is connected components. For normal single-entry functions, the decision-point shortcut is the practical review method.

Count decision points such as:

- `if`, `elseif`, and `else if`
- loops such as `for`, `foreach`, `while`, and `do while`
- `switch` cases and `match` arms that branch behavior
- `catch` or equivalent exception handlers
- ternary expressions and conditional expressions
- logical branches inside compound conditions, especially `&&` and `||`

Exact counting varies by language and analyzer, especially for compound boolean expressions and expression-oriented syntax. Prefer the project's tool result when available, but use the manual estimate during review to catch obvious risk before tooling runs.

## Tooling And CI
Use existing complexity tooling before introducing new dependencies. Common examples include PHPMD or PHP metrics tooling for PHP, ESLint `complexity` for JavaScript/TypeScript, SonarQube/SonarLint, CodeClimate, IDE metrics, or repository-specific static analysis.

- If the consuming repository already gates complexity, obey its configured threshold even when it is stricter than this package.
- If adding or tuning complexity enforcement is in scope, prefer a gradual policy for existing codebases: warn around 15, fail new or worsened hotspots around 25, then tighten as the codebase improves.
- Keep the target of 10 or less for newly written and materially changed code even if CI allows a higher legacy threshold.
- Do not add a new analyzer, CI gate, or repository-wide cleanup as a side effect of a narrow feature or bugfix unless the user requested that scope.
- When the task is complexity cleanup, measure first, sort by highest-risk code units, and refactor hotspots with tests instead of making broad stylistic edits.
- For multi-repository or large monorepo work, use search/static-analysis reports to find repeated complexity patterns and track whether hotspot counts are improving over time.

## Review Requirements
For every non-trivial changed function, method, closure, or handler:

- Check whether the unit now exceeds the target of 10.
- Check whether nesting, mixed responsibilities, or broad conditionals make the code difficult to read even when the numeric score is under 10.
- Prefer improving touched complexity hotspots over preserving a poor shape unchanged.
- Keep unrelated legacy refactors out of scope unless the existing complexity blocks a correct change.
- Do not split code into tiny unnamed fragments only to lower the number. Each extracted unit must have a clear responsibility and a useful name.
- Do not move complex branching into arrays, callbacks, magic dispatch, service locators, or framework configuration just to hide it from a metric.

Cyclomatic complexity measures paths. It does not measure nesting depth, readability, data coupling, or architectural clarity. Use it together with cognitive complexity and SOLID review.

## Refactoring Patterns
Use these patterns to reduce accidental complexity while preserving behavior:

- Extract a method, function, action, service, strategy, or value object when a branch block has a clear, nameable responsibility.
- Replace repeated type, status, mode, or provider conditionals with polymorphism, strategies, explicit handlers, or enum methods when the branch decision appears in more than one place.
- Use guard clauses and early returns to remove deep nesting. This may not lower the cyclomatic score, but it reduces cognitive load and review risk.
- Normalize input before branching so the main behavior does not repeatedly handle nulls, aliases, string variants, or loosely typed values.
- Split validation, authorization, data loading, business decisions, persistence, and presentation shaping into their proper boundaries instead of stacking them in one method.
- Replace pure input-to-output switches with lookup tables, maps, enum metadata, or configuration only when the mapping is simple data and has no side effects.
- Keep explicit branches when they carry important domain explanation, authorization behavior, persistence side effects, or error semantics that would become less clear as indirection.
- Simplify boolean expressions by naming domain predicates, extracting reusable specifications, or separating independent decisions.
- For Laravel code, use Form Requests for validation, policies/gates for authorization, actions/services/jobs for workflows, resources/view-data classes for presentation payloads, and model scopes or query objects for reusable query branches.
- For frontend handlers, split parsing, state transition, rendering, and network effects so event handlers stay small and directly testable.

## Essential Complexity Exceptions
Some domains are inherently branch-heavy. Examples include state machines, protocol parsers, command dispatchers, permission matrices, configuration validators, tax/rate calculators, and error-code-to-action mappings.

High complexity may be acceptable only when all of these are true:

- The branching reflects essential domain rules rather than mixed responsibilities or incremental patching.
- A simpler decomposition would scatter the same decision across multiple files or make the behavior harder to audit.
- Branches are named, grouped, or structured so a reviewer can understand the decision table.
- Tests cover the branch families and edge cases that make the unit complex.
- The exception is documented close to the code, in an ADR, or in the PR/final response when code comments would add noise.

When keeping high complexity, compensate with stronger tests and clearer structure. A complex state machine with 30 meaningful paths needs branch-aware test coverage, not only a happy-path test.

## Testing Expectations
Cyclomatic complexity is a lower-bound signal for the number of independent paths that may need testing. It does not require one test per raw branch in every case, but it does require tests that match the behavior risk.

- For units at or below 10, cover the meaningful success, failure, and boundary paths introduced or changed.
- For units above 10, use a branch matrix or equivalent reasoning to identify which paths, guards, exceptions, and combinations are covered.
- For retained high-complexity units, include tests for each behavior category, critical edge case, and historically fragile branch.
- Prefer public contract tests over private implementation tests.
- Do not rely on line coverage alone; a line can be executed without proving the important branch outcome.
- When meaningful tests cannot be added in the current repository, state the gap and verify the real entrypoint as far as the environment allows.

## Definition Of Done
A change that touches branch-heavy code is not complete until one of these is true:

- The changed units are at or below the target complexity and covered by appropriate tests or runtime verification.
- The remaining complexity is essential, documented, and covered by branch-aware tests or a clearly stated verification strategy.
- The complexity is pre-existing, the task did not safely allow refactoring it, and the change avoids making the hotspot materially worse.

Use the smallest refactor that makes the behavior understandable and testable. Do not turn a local complexity problem into a wider architectural problem.
