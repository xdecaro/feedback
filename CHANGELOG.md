# Changelog

All notable changes to Feedback by xdecaro will be documented here.

## [0.2.0] - 2026-09-10

### Added
- Functional administrator Question library CRUD.
- Search and filtering by type, state and category.
- Joomla toolbar actions for create, edit, publish, unpublish, trash and delete.
- Initial question types: stars, numeric scale, satisfaction scale, yes/no, single choice, multiple choice, short text, long text and NPS.
- Friendly options editor using one option per line instead of exposing JSON.
- Server-side normalization and validation of option lists and numeric-scale settings.
- Responsive narrow-layout question records and dark-mode-safe local styling.

### Changed
- Dashboard now links to the Question library.
- Administrator manifest exposes the Question library submenu.

## [0.1.0] - 2026-09-10

### Added
- Initial Joomla package/component foundation.
- Administrator Dashboard and Information views.
- ACL foundation for management, responses, exports and reports.
- Database schema for templates, reusable questions, questionnaire snapshots, submissions and answers.
- Core dependency/integration boundary and optional cross-product target references.
- Deterministic build, smoke validation and GitHub Actions build workflow.

### Architecture
- Feedback is not a general-purpose Forms builder.
- Reusable library questions are copied as snapshots into templates/questionnaires so later library edits do not alter historical meaning.
- No private tables from other xdecaro products are accessed.
