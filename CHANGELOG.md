# Changelog

## 0.1.16 — 2026-08-17
- Lockstep-Versionsschub auf 0.1.16 (keine funktionale Änderung).

## 0.1.15 — 2026-08-17
- Lockstep-Versionsschub auf 0.1.15 (keine funktionale Änderung).

## 0.1.14 — 2026-08-17
- Lockstep-Versionsschub auf 0.1.14 (keine funktionale Änderung).

## 0.1.13 — 2026-08-17
- Lockstep-Versionsschub auf 0.1.13 (keine funktionale Änderung).

## 0.1.12 — 2026-08-17
- Lockstep-Versionsschub auf 0.1.12 (keine funktionale Änderung).

## 0.1.11 — 2026-08-17
- Lockstep-Versionsschub auf 0.1.11 (keine funktionale Änderung).

## 0.1.10 — 2026-08-17
- **Iteration 3: Selbstaktivierungsformular.** Temporäre Nutzer sehen in `view.php` ein Formular (E-Mail/Vorname/Nachname), das capability-/sesskey-gesichert an `auth_flexaccess\api::self_activate` postet und das Ergebnis anzeigt. Neue `form\self_activation_form`; `mod/flexaccess:activate` erzwungen. Kein Schema-Change.

## 0.1.9 — 2026-08-17
- **Iteration 2: verzweigte `view.php`.** Klassifizierung via `auth_flexaccess\api::classify_user` (runtime-lazy): authentifiziert → Hinweistext; temporär → Selbstaktivierungs-Hinweis; Facade fehlt → Warnung. Reiner `local\view_state` (+ PHPUnit `view_state_test`); Behat aktualisiert. Kein Schema-Change.

## 0.1.8 — 2026-08-17
- Lockstep-Versionsschub auf 0.1.8 (keine funktionale Änderung; kann `auth_flexaccess\api` + `account_service` konsumieren).

## 0.1.7 — 2026-08-17
- **CI-Fix (phpcs):** zu lange `addElement`-Zeile in `mod_form.php` umgebrochen.
- **CI-Fix:** pgsql-Workflow-createdb-Zeile entfernt.
- Lockstep-Versionsschub auf 0.1.7.

## 0.1.6 — 2026-08-17
- Lockstep-Versionsschub auf 0.1.6 (keine funktionale Änderung; kann nun `auth_flexaccess\api::classify_user` konsumieren).

## 0.1.5 — 2026-08-17
- Lockstep-Versionsschub auf 0.1.5 (keine funktionale Änderung).

## 0.1.4 — 2026-08-17
- Lockstep-Versionsschub auf 0.1.4 (keine funktionale Änderung).

## 0.1.3 — 2026-08-17
- Lockstep-Versionsschub auf 0.1.3 (keine funktionale Änderung; Modul-Iteration 2 folgt).

## 0.1.2 — 2026-08-17
- Lockstep-Versionsschub auf 0.1.2 (keine funktionale Änderung im Modul). Modul-Scope unverändert P0 (Self-Activation).

## 0.1.1 — 2026-08-17
- **Renamed `mod_flexaccessactivation` → `mod_flexaccess`** (component, function prefix `flexaccess_*`, namespaces, capabilities `mod/flexaccess:*`, database table `flexaccess`, language files, XMLDB path). Installs to `mod/flexaccess`.
- Version scheme moved to incremental `0.1.x` (release `0.1.1`).
- Dependency documentation aligned to the ecosystem hard/cycle model (ADR-010).

## 0.1.0-alpha — 2026-08-17
- Initial architecture scaffold.

## 0.1.0-alpha3 - 2026-08-17
- Add system/course shared access-key requirement for temporary-user entry; secrets are hash-only.
