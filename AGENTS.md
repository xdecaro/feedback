# Repository Guidelines — Feedback by xdecaro

Feedback owns reusable satisfaction questionnaires, templates, the reusable question library, questionnaire question snapshots, submissions, answers and feedback reporting.

Feedback is intentionally not a second Forms builder. Authoring must stay fast: templates, reusable questions, simple editing and lightweight ordering. Do not add general-purpose form-builder complexity unless it is genuinely required by feedback workflows.

Dependency direction is `Feedback -> Core`. Courses, Events, Competitions, Membership, Bookings and other products are optional integrations. Use Core `EntityReference`, capabilities and integration events; never query or mutate another product's private tables and never create circular dependencies.

Questionnaire questions are snapshots. Changes to a reusable library question must not silently alter questionnaires that were already assembled or published. Published response meaning must remain stable over time.

Anonymous mode must minimize stored personal data. A Core entity reference never grants authorization. Enforce Joomla ACL, CSRF protection, validated input, escaped output, bound queries and `#__` server-side.

Use Joomla language files for translatable text, Web Asset Manager, responsive layouts and dark-mode-safe tokens. Prefer the shared Core UI only inside `.xdecaro-scope`; keep Feedback-specific CSS local.

Version `0.1.0` is the development foundation. Do not advertise `1.0.0` or a stable release until clean install/update, runtime behavior, ACL, submission integrity, reporting semantics, responsive/light/dark UI and package ZIPs have been verified.
