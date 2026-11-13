---
name: ui-awesome-html-helper
description: Use when rendering HTML attributes, encoding content, managing CSS classes, or composing form names and templates with ui-awesome/html-helper in PHP applications. Not for sanitizing untrusted HTML, validating URLs or scripts, or building tags and framework widgets.
license: BSD-3-Clause
metadata:
  guide: https://yii2-framework.github.io/docs/extensions/html-helper/
---

# Compose HTML with UI Awesome helpers

Use `UIAwesome\Html\Helper` in framework-agnostic PHP code. The package requires PHP 8.3 and `mbstring`;
installing this skill does not install its runtime dependencies. Do not substitute Yii widget APIs for these helpers.

## Match the installed revision

In a package checkout, inspect `composer show --self --format=json`, current `HEAD`, and actual files. For an installed
package, resolve the configured vendor directory and read `composer/installed.php`: the package's `install_path`,
`pretty_version`, and selected `reference`. Compare `composer.lock`; it may not describe the installed files.
For a symlinked or modified checkout, actual files govern. Do not report uncommitted changes as a published revision.
A lockfile without installed files is not proof of runtime behavior.

Read only the relevant local `docs/` page and source/PHPDoc. Concrete helper classes inherit their implementation from
`src/Base/Base*.php`; check that implementation before inventing a signature or quoting an output. When local guides
are excluded from a Composer archive, consult matching-revision sources at <https://github.com/ui-awesome/html-helper>
or these hosted Markdown pages after verifying their revision:

- [Installation](https://yii2-framework.github.io/docs/extensions/html-helper/installation/index.md).
- [Examples](https://yii2-framework.github.io/docs/extensions/html-helper/examples/index.md).
- [Testing](https://yii2-framework.github.io/docs/extensions/html-helper/testing/index.md).

If the documentation revision cannot be established, use installed source and report that limitation. Use the site
index only when the required page is unknown; do not load a complete API catalogue for a single helper.

## Select the operation and output context

- `Attributes::render()` serializes an attribute array for HTML. It handles class/style arrays and data/aria groups;
  inspect its normalization rules for special values. Keep encoding enabled when concatenating HTML strings.
- For DOM/XML APIs that encode attribute values themselves, use `Attributes::normalizeAttributes(..., encode: false)`.
  Boolean attributes require presence handling; do not blindly pass booleans to `DOMElement::setAttribute()`.
- `Encode::content()` encodes HTML text, not quoted attributes. Its HTML5 flags do not encode quotes.
  `Encode::value()` also encodes both quote characters for quoted HTML attribute values.
- Neither encoding helper sanitizes arbitrary HTML, validates URL schemes, or makes JavaScript/CSS safe. Use trusted
  attribute names and validate URL/event/style inputs for their destination. Do not treat an escaped `javascript:` URL
  or event-handler value as safe because it passed through attribute rendering.
- `Template::render()` substitutes tokens without encoding them. Encode each untrusted token for its final context
  before substitution. Its line-ending normalization and removal of empty lines are not an HTML sanitizer.

## Preserve helper contracts

- `Attributes::render()` emits attributes in the package's priority order (`class`, `id`, `name`, `type`, ...), not
  in insertion order. Assert against that order instead of reordering the input or the expected output.
- Both `Encode` methods re-encode existing entities by default (`$doubleEncode = true`), so `&amp;` becomes
  `&amp;amp;`. Pass `false` only for content that is already encoded on purpose.
- `AttributeBag` modifies an array by reference. `set()` resolves a closure; `null`, including a closure returning
  `null`, removes the normalized key. `replace()` replaces the complete bag rather than merging it.
- Prefixed boolean values such as `aria-expanded` are literal strings, not the same contract as HTML boolean attributes.
  Inspect `normalizeKey()` separately from mutators: accepting a `Stringable` key there does not mean every method's
  key parameter accepts it.
- `CSSClass::add()` merges or replaces classes in the attribute bag; `CSSClass::render()` validates a variant against
  its allowed values. Do not confuse class construction with arbitrary HTML rendering.
- `Naming` supplies nested input names and IDs without requiring a Yii model. `convertToPattern()` strips regex
  delimiters/modifiers; verify browser pattern semantics rather than claiming PHP and HTML regexes are equivalent.
- `Validator` methods differ: predicate methods return booleans, while allowed-value validation may throw.
  `Validator::oneOf()` accepts `null` and an empty string without checking the list; that is not a bug. Verify the
  installed signature, enum/Stringable normalization, bounds, and invalid-input behavior for the selected method.

## Verify the integration

Run PHP examples against the inspected package, asserting exact output for text, quotes, ampersands, existing entities,
booleans, and null removal as applicable. For DOM usage, inspect the serialized DOM rather than a pre-escaped input.
Test template tokens containing markup and preserve application-specific validation. Run the application's relevant
checks and distinguish an executed result from an untested snippet or a manually read skill from native activation.
