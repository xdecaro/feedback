# Feedback by xdecaro

**Feedback by xdecaro** is the Joomla component for reusable satisfaction questionnaires, structured feedback and result analysis across the xdecaro ecosystem.

- Component: `com_xdecarofeedback`
- Package: `pkg_xdecarofeedback`
- Namespace: `xdecaro\Component\Feedback`
- Tables: `#__xdecarofeedback_*`
- Development version: `0.1.0`
- Repository: `xdecaro/feedback`

## Product scope

Feedback owns questionnaires, reusable templates, a reusable question library, questionnaire questions, submissions, answers and feedback reporting.

Feedback deliberately does **not** implement a full Forms-style builder. The authoring experience is based on templates, reusable questions, a simple editor and lightweight drag-and-drop ordering. Forms remains the general-purpose form builder.

Feedback may be linked to Courses, Events, Competitions, Membership, Bookings or future products, but those integrations must remain optional. Cross-product references use Core contracts such as `EntityReference` and capabilities/events; Feedback must never read or write another product's private tables.

## Planned administrator areas

- Dashboard
- Questionnaires
- Templates
- Question library
- Responses
- Reports
- Settings
- Information

## Compatibility goals

Target Joomla 4, 5 and 6 where technically possible, using modern Joomla APIs, server-side ACL, CSRF protection, validated input, escaped output, bound database queries, Web Asset Manager, multilingual language files, responsive layouts and dark-mode-safe shared UI.

The first development milestone is `0.1.0`; it establishes the package/component foundation and domain contracts without advertising a stable public release.
