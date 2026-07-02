# MetaFramework Project Rules

> Load this file only for projects that use Aboleon MetaFramework packages, MFW UI/components, MFW AJAX, CSSCrush, or related package boundaries.

## Dependency and Construction Rules

- Do not resolve business collaborators with `app(SomeClass::class)`, `App::make()`, or `resolve()` in application code.
- Prefer constructor or method injection for services that need container dependencies.
- Use direct `new Class(...)` only for simple value objects or stateless classes whose dependencies are explicit.
- Keep package, module, and application ownership boundaries clear.

## MetaFramework Enum Rules

- Apply this section to MetaFramework packages and applications that follow MetaFramework package conventions.
- Use the singular `Enum` directory/namespace used by existing packages, such as `src/Enum` and `MetaFramework\{Package}\Enum`.
- In Nwidart modules, use the owning module enum namespace, such as `Modules\{Module}\Enum`.
- Prefer native string-backed PHP enums for values persisted to config, database fields, forms, filters, or package APIs.
- For MetaFramework-selectable/translatable enums, implement `MetaFramework\Interfaces\BackedEnumInteface` and use `MetaFramework\Traits\BackedEnum`.
- MetaFramework enum cases use UPPER_SNAKE names with lowercase string values, matching existing package enums such as `SYSTEM = 'system'` and `GENERIC = 'generic'`.
- Every enum implementing `BackedEnumInteface` must define `default(): string` and return a backed value via `self::CASE->value`.
- Follow the `Modules\Seller\Enum\PriceSellTypeEnum` pattern for module enums that drive UI/options: `BackedEnumInteface`, `BackedEnum`, `default()`, `translationPrefix()`, `translated()`, `selectables()`, `normalize()`, and enum-local label helpers when the labels are value-dependent.
- Define `translationPrefix(): string` when enum translations live under a package/module namespace. The `BackedEnum` trait resolves labels as `{translationPrefix()}enum.{varname}.{value}`.
- Use the auto-wired translation convention first. Override `translated(string $value)` only when the enum needs a custom translation path or normalization step.
- Use enum helpers such as `::values()`, `::keys()`, `::translations()`, `::translated()`, and `::default()` instead of duplicating value or label arrays in views, config, controllers, or tests.
- For select inputs, expose a focused `selectables(): array` when the UI needs a stable `value => translated label` array.
- When normalizing untrusted enum input, use `tryFrom()` or a focused enum resolver that trims/normalizes input and returns a safe fallback or `null`.
- Keep enum-specific presentation keys, such as a label key derived from the selected enum value, inside the enum when the mapping is intrinsic to the enum.
- Do not hardcode enum translation labels in Blade or JavaScript. Keep labels in language files and feed UI controls through enum translation helpers.

## MetaFramework Services and Validation Rules

- Use the `MetaFramework\Services\Validation` process for MetaFramework service/action validation.
- Use dedicated Form Request classes for normal HTTP/controller validation when the request can be injected by Laravel.
- Use `MetaFramework\Services\Validation\ValidationAbstract` for reusable package-level validation definitions that must run inside MetaFramework services, actions, AJAX relays, or non-controller workflows.
- A `ValidationAbstract` class should implement only the hooks it needs: `authorize()`, `prepareForValidation()`, `rules()`, `messages()`, `attributes()`, `after()`, `withValidator()`, `passedValidation()`, `stopOnFirstFailure()`, or `validationData()`.
- Keep `rules()` and `messages()` as typed array-returning methods. Prefer array rule syntax when adding new rules.
- Use `MetaFramework\Services\Validation\ValidationTrait` in services/actions that own validation state and call `$this->validation(...)` with either a `ValidationAbstract`, a Form Request, or their class names.
- Respect the `ValidationTrait::validation(...)` flow.
- Form Request object/class path: `validateResolved()` then store validated data in `$validated_data`.
- `ValidationAbstract` object/class path: `authorize()`, `prepareForValidation()`, merged rules/messages, `Validator::make(...)` with `validationData()`, `attributes()`, and `stopOnFirstFailure()`, `withValidator()`, `after()` callbacks, `passedValidation()`, then store validated data in `$validated_data`.
- Inline rule path: `addValidationRules()` / `addValidationMessages()` compose one `request()->validate(...)` pass.
- After validation, consume `$this->validatedData()` / `$this->validatedData('key')` instead of reading raw request payloads again.
- For scalar values from validated state, use `validatedDataStringable('key')` when the value must not be an array.
- Use `addValidationRules()` and `addValidationMessages()` only to compose extra rules before one validation pass. Do not scatter ad hoc validation fragments through business logic.
- For MFW AJAX relay actions that cannot receive a typed Form Request directly, pass the Form Request class or a `ValidationAbstract` class to the service/action validation method rather than duplicating validation in the controller bridge.
- Do not bypass the MetaFramework validation process with ad hoc `Validator::make()` calls inside MFW services/actions unless the local package already owns that lower-level validation abstraction.
- Keep validation classes in the owning package/module namespace. Do not put module-owned validation definitions in the app root.
- If validation also needs response errors/warnings, use the existing MetaFramework response conventions from `MetaFramework\Support\Traits\Responses` instead of ad hoc payload shapes.
- Treat the current internal `app($validationClass)` resolution inside `ValidationTrait` as framework infrastructure. Do not copy that service-locator pattern into application business code.

## MetaFramework Action Rules

- Use Actions for explicit business operations, MFW AJAX relay behavior, and workflows that do more than read or format data.
- Package-owned actions live in the package `Actions` namespace. Nwidart module actions live in the owning module namespace, such as `Modules\{Module}\Actions`.
- Name action classes after the business operation or domain behavior, usually `SomethingAction`. Use an existing plural `SomethingActions` grouping only when the local codebase already groups several closely related relay methods that way.
- When an action class is passed as a contract, dispatch target, handler, relay, job payload, tool target, or configuration value, pass the class reference explicitly as `SomeClassAction::class`.
- Do not derive action class names from request input, route strings, model names, table names, or concatenated namespaces.
- If the frontend sends `action={method}`, the request may select only the public bridge method supported by the existing MFW AJAX relay. It must not choose the Action class.
- When class selection is needed behind a relay, map request values through a small explicit allow-list of `SomeClassAction::class` references.
- Keep `AjaxController` methods as bridge methods: normalize route/request context, call one explicit Action class/method, and return the standard response.
- Keep validation in a Form Request or the `MetaFramework\Services\Validation` process. Do not duplicate validation rules inside the controller bridge.
- MFW AJAX actions that own response payloads should use the existing MetaFramework response/AJAX traits, such as `MetaFramework\Support\Traits\Ajax` or `MetaFramework\Support\Traits\Responses`, instead of ad hoc response arrays.
- For AJAX-mode action classes, prefer the established response helpers such as `ajaxMode()`, `responseSuccess()`, `responseError()`, `responseElement()`, `responseException()`, and `fetchResponse()` when the local class already follows that contract.
- Pure domain actions should not depend on AJAX response traits. Return a typed domain object, DTO, scalar, collection, or `void`, and let the caller map the result to an HTTP/MFW response.
- Action methods must declare explicit parameter and return types.
- Do not resolve actions with `app(SomeClassAction::class)`, `App::make()`, or `resolve()` in application code. Use constructor/method injection, explicit class references, or direct construction for stateless action classes with explicit dependencies.

## MetaFramework Accessor Rules

- Use Accessors for small, reusable read/format/selection helpers that expose application/package state without owning a workflow.
- Package accessors live in the package namespace, such as `MetaFramework\Accessors`, `MetaFramework\Accounts\Accessors`, or `MetaFramework\Dictionnaries\Accessors`.
- Nwidart module accessors live in the owning module namespace, such as `Modules\{Module}\Accessors`.
- Accessors should be named after the domain they expose, usually `SomethingAccessor` when wrapping a model/domain concept, or a concise plural/value name when the package already uses it, such as `Prices`, `Locale`, or `Routing`.
- Prefer static methods for stateless accessors, formatters, config readers, selectable lists, and cached lookups, matching existing patterns like `Prices::readableFormat()`, `Locale::localesAsSelectable()`, and `VatAccessor::selectables()`.
- Use an instance accessor only when it intentionally wraps a specific model or value object. Keep it thin and typed.
- Accessors must not become workflow services. If the code mutates state, coordinates multiple writes, dispatches jobs, calls external APIs, or owns business workflow, use an Action or Service instead.
- Keep accessor methods typed, small, and named for the value they return, such as `selectables()`, `defaultId()`, `rate()`, `readableFormat()`, or `backend()`.
- For UI option lists, expose `selectables(): array` or an established equivalent and return stable `value => label` arrays.
- Cache expensive accessor lookups with deliberate keys. Include locale, tenant, seller/account context, or other dimensions in the cache key when the returned value depends on them.
- Do not cache request-specific or user-specific accessor results under global keys.
- Prefer direct accessor calls at the use site when the result is used once. Store the result in a local variable only when reused, expensive, or materially clearer.
- Pass typed accessor results into helpers/services instead of passing broad models, requests, or containers when only the accessor value is needed.
- Do not query from Blade directly. If an accessor query is needed for a view, prefer resolving it in a controller, view-data class, component, or presenter unless the existing package view convention explicitly uses a simple accessor call.
- Keep accessor output presentation-safe: format scalar display values, selectable arrays, config paths, route prefixes, or lookup values; avoid returning broad mutable models unless that is the established local accessor contract.

## CSSCrush Rules

- Apply only when CSSCrush is present in the project.
- Before adding CSS, check which Blade view or layout loads the stylesheet with `csscrush_tag()` and whether child views override `@section('css')`.
- Do not duplicate the same CSS rules in multiple files. Reuse the stylesheet that owns the component or move styles only when the owning render path requires it.
- Do not add inline `<style>` blocks in Blade partials unless the project already uses that pattern for the same kind of component or the user explicitly asks for self-contained markup.
- For partials reused across multiple layouts, verify each render path and place styles where all relevant paths load them.
- After frontend CSS changes, verify the rendered HTML references the expected CSSCrush output and that the target selector appears in the served CSS or intentionally in the partial.
- Keep CSS changes narrow. Do not introduce unrelated spacing, palette, typography, or layout changes while moving or reusing a block.
- Keep component-local CSS class names short and scoped by their owning block when that is the project convention.

## MFW Form Rules

- High priority: use `mfw-inputable` Blade components instead of raw input markup whenever the component supports the field.
- Prefer available components such as `x-mfw-inputable::input`, `checkbox`, `datepicker`, `inputdatemask`, `inputradio`, `radio`, `number`, `select`, `textarea`, and `validation-error`.
- Use raw form markup only when no `mfw-inputable` component supports the control, the local surface already owns a specialized custom control, or the user explicitly asks for raw markup.
- Match panel action buttons to established administration UI before introducing new styling.
- Do not use Bootstrap `btn-outline-*` classes for buttons or links styled as buttons unless the user explicitly asks for an outline button or the local screen already uses outline buttons for that action type.
- For common panel actions, preserve the local icon/color language: edit actions should look like existing edit actions, destructive actions like existing destructive actions, and create actions like neighboring create actions.
- Use existing modal/confirmation components such as `x-mfw::simple-modal` for destructive confirmations when available.
- Do not use native `confirm()` prompts or ad hoc confirmation markup in MFW admin interfaces unless that is the established local convention.
- When using `x-mfw::simple-modal`, follow the component callback pattern: give the confirm button a dedicated selector/class, bind it inside the named callback, and submit through the area's existing MFW AJAX relay.

## JavaScript Rules

- Preserve existing legacy contracts on jQuery/MFW pages.
- Do not use `var` in new scoped functions. Prefer `const` by default and `let` for reassigned bindings.
- Group related bindings deliberately when it improves readability, but do not force a style that conflicts with the local file.

## MFW AJAX Pattern

- Use the existing MFW AJAX relay for actions that already belong to an MFW surface.
- Typical flow: frontend call with `action={method}` -> `AjaxController` bridge method -> dedicated Action class -> standard response payload.
- Do not create feature-specific AJAX routes for MFW actions when an existing relay route owns that area.
- Respect module boundaries: module-owned AJAX actions should live in the module namespace, with the module route pointing to the module `AjaxController`.
- Module AJAX controllers should use the existing MetaFramework AJAX trait/distributor when the project already uses it, typically `MetaFramework\Support\Traits\Ajax` and its `distribute()` method. Do not override a distributor method just to delegate manually.
- Dedicated AJAX action classes should live under the owning namespace, such as `Modules/{Module}/Actions`, when the behavior belongs to a module.
- For MFW AJAX relay actions that cannot receive a typed Form Request directly, expose explicit validation/normalization methods on the dedicated Request class and call those methods from the action or bridge.
- Use MetaFramework validation classes/traits when they match the local package convention.

## Package Boundary Rules

- Treat Composer packages, local path repositories, `vendor/` code, and published package view overrides under `resources/views/vendor/**` as upstream dependency surfaces.
- The application should adapt to package contracts through configuration, app models, adapters, wrappers, or app-owned views/components.
- Prefer documented extension points before considering package changes.
- Modify a package or published package override only when the user asks for package-level work or the issue is a reusable package bug/feature that cannot be solved correctly in the application layer.
- When changing a package, state the package boundary, keep the change generic, and verify it in the package repository separately.
