# Changelog

## 0.1.19 — 2026-08-18
- **Verifiziert mit der exakten CI-Toolchain (moodle-plugin-ci 4.5.11 PHAR): phpcs 0/0, `validate` 0 Fehler, PHPUnit auf Moodle 5.3dev gruen.** Cross-Plugin-Integrationstests laufen in der Vollumgebung (alle vier Plugins) normal und ueberspringen sich nur in der Einzel-Plugin-CI.
- **Weitere CI-Fixes:** `activation_manager_test` ueberspringt sich sauber (markTestSkipped), wenn `auth_flexaccess` (Tabelle `auth_flexaccess_account`) in der Einzel-Plugin-CI fehlt. Behat `view.feature` mit `@mod`-Typ-Tag.

## 0.1.18 — 2026-08-17
- **Linting robust fuer aeltere moodle-cs gemacht (die lokale `make check`-Umgebung nutzt eine strengere/aeltere moodle-cs als die CI):** `@package`-Tag in jedem Datei-, Klassen-/Interface-/Trait- und Top-Level-Funktions-Docblock ergaenzt (aeltere moodle-cs verlangt dies ueberall; neuere ab 3.6 hat es gelockert). Test-Klassen erhielten `@covers` auf die jeweils geprueften Klassen (behebt die `missing coverage information`-Warnungen). **Gegengeprueft:** die echte CI (moodle-plugin-ci 4.5.11) meldet weiterhin 0 Verstoesse, PHPUnit auf Moodle 5.3dev bleibt gruen.

## 0.1.17 — 2026-08-17
- **Real auf Moodle 5.3dev (branch 503, PG17) verifiziert — PHPUnit gruen, phpcs 0/0.** Dabei behobener echter Installationsfehler: Aktivitaetsmodul-Sprachdatei von lang/*/mod_flexaccess.php in lang/*/flexaccess.php umbenannt — Moodle prueft fuer Module lang/en/<modulename>.php (bare name), sonst 'Missing mandatory en language pack'. Fehlende Capability-Sprachstrings (flexaccess:addinstance, flexaccess:view, flexaccess:activate) ergaenzt; install.xml ins kanonische XMLDB-Format regeneriert.
- **CI grün gemacht (phpcs, real verifiziert mit moodlehq/moodle-cs v3.7):** Sprachdateien alphabetisch sortiert + `@package` ergänzt (Moodle LangFilesOrdering); einzeilige Docblocks in Mehrzeilenform mit Beschreibungszeile überführt; Multiline-Funktionsaufrufe per phpcbf normalisiert; unnötige `MOODLE_INTERNAL`-Checks entfernt; Konstanten-Docblocks ergänzt.
- **Makefile:** Vorlage übernommen und an das Plugin-Verzeichnis angepasst (PLUGIN_NAME/PLUGIN_REL/MOODLE_ROOT); `make check` zeigt nur Fails, läuft volle Lintings + PHPUnit.
- **GitHub-Workflows:** getrennt für Development (`moodle-ci.yml`, branches-ignore main) und Main (`moodle-release.yml`); zusätzlich `playwright.yml` und `load.yml` bereitgestellt. Von vimipad-spezifischen Bundle/AMD/Node-Schritten befreit; Behat-Tags und Pfade je Komponente. `.gitattributes`/`.gitignore` adaptiert.
- `MOODLE_INTERNAL` aus lib.php entfernt; Hinweis: veraltete `lang/*/mod_flexaccessactivation.php` ggf. lokal löschen.

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
