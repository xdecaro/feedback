# Feedback architecture

## Scope

Feedback owns satisfaction questionnaires, reusable questionnaire templates, a reusable question library, questionnaire question snapshots, submissions, answers and feedback-oriented reporting.

Forms remains the general-purpose form builder. Feedback deliberately uses a simpler workflow: choose a template, adjust/add questions, order them, configure publication/identity rules and publish.

## Domain model

- `questions`: reusable library questions.
- `templates`: reusable questionnaire blueprints.
- `template_questions`: snapshots used by a template.
- `questionnaires`: concrete feedback campaigns/questionnaires.
- `questionnaire_questions`: immutable-in-meaning snapshots used by a questionnaire.
- `submissions`: one response envelope.
- `answers`: answer values bound to the questionnaire-question snapshot.

The snapshot rule is intentional. Editing a library question never rewrites wording/type/options in an existing template or questionnaire.

## Cross-product targets

A questionnaire may optionally target an external entity using three neutral fields:

- `target_extension` (for example `com_xdecarocourses`)
- `target_type` (for example `edition`)
- `target_identifier` (opaque identifier)

These fields are references, not authorization. Runtime integrations should expose/consume Core `EntityReference`, `CapabilityRegistry` and integration events. Feedback must never read or mutate another product's private tables.

Optional integrations include Courses, Events, Competitions, Membership and Bookings. Their absence must not damage standalone Feedback data.

## Privacy and integrity

Anonymous mode minimizes personal data. Feedback does not store IP addresses by default. `respondent_key_hash` is reserved for enforcing a single-response policy with a non-reversible identifier/token hash; raw tokens must never be persisted.

Identified mode may store Joomla `user_id` only when the questionnaire requires identity. ACL is always enforced server-side and an external entity reference never bypasses ACL.

## Question values

Question types are application-level identifiers, not database enums, to allow safe future extension. Initial planned types include stars, numeric scale, satisfaction scale, yes/no, single choice, multiple choice, short text, long text and NPS.

Options/settings are stored as JSON text for MariaDB/MySQL/Joomla portability. Numeric values may additionally populate `numeric_value` for reporting without reparsing display text.

## UI

No full drag-and-drop form builder. Authoring should provide templates, a question library, a compact editor and lightweight drag-and-drop ordering only. Shared Core UI is opt-in under `.xdecaro-scope`; Feedback-specific styles stay inside the component.

## Release boundary

`0.1.0` is a development foundation, not the stable product release. Before `1.0.0`, verify clean installation/update, real Joomla runtime, ACL, CSRF, questionnaire publication rules, submission idempotency, single-response enforcement, snapshot integrity, report calculations, multilingual output, responsive behavior and light/dark mode.
