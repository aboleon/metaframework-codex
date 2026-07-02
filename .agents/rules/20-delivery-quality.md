# Delivery Quality Guidelines

## Behavior-First Delivery
- Do not consider a user-visible task done until the relevant behavior is verified.
- Tests alone are not sufficient when the bug is reported against a concrete runtime scenario. Verify the actual route, page, command, job, API endpoint, or UI flow when possible.
- Define the acceptance contract before implementation:
  - concrete inputs
  - exact expected outputs
  - impacted screens, fields, events, or persistence side effects
- Prefer contract assertions over proxy assertions.

## Bugfix Reporting
When fixing a bug, report verification in this shape when applicable:

- Reproduced: yes/no
- Real entrypoint verified: exact route/controller/command/job/component
- Result: success status, rendered outcome, persisted state, or remaining failure
- Test added: real contract test, existing test updated, or no meaningful test available

Do not present "tests passed" as the source of truth when the real runtime path was not verified.

## Calculation, Pricing, and Derived-State Changes
Apply this to any derived value, total, quote, pricing, inventory, workflow status, or multi-step calculation.

- Validate every authoritative path:
  - server recompute/persistence path
  - initial render or hydration path
  - client interaction path when JavaScript modifies values before save
- Include a regression test with realistic data when feasible.
- If test expectations conflict with confirmed business behavior, update the test and state why.

## End-to-End Sanity
For touched user flows, verify the minimum meaningful flow:

- load or execute the entrypoint
- apply the action
- save, dispatch, or reload when relevant
- confirm the persisted, rendered, emitted, queued, or returned result

If the user gives a specific real case, verify that case directly when the environment allows it.

## Stop-Loss
- Work one bug or behavior contract at a time.
- Reproduce, fix, prove fixed.
- If two consecutive fix attempts fail, stop looping and redesign the approach from the observed runtime behavior.
