# Changelog

## 0.1.29 — 2026-08-18 — Paket B: E-Mail-Verifikation der Persistierung (Option, Default an)
- Keine Codeaenderung.

## 0.1.28 — 2026-08-18 — Paket B: B4 Konvertierung temporaer -> persistent
- Keine Codeaenderung.

## 0.1.27 — 2026-08-18 — Paket A abgeschlossen: Methodenauswahl (Gast + Normallogin)
- Keine Codeaenderung.

## 0.1.26 — 2026-08-18 — Paket A: Quick-Registration (allowquick)
- Keine Codeaenderung.

## 0.1.25 — 2026-08-18 — CI-Fix (veraltete Behat-Datei)
- Keine Codeaenderung.

## 0.1.24 — 2026-08-18 — Paket A: B2 (Access-Key) verifiziert
- **Access-Key-Durchsetzung end-to-end per Behat verifiziert** (Sicherheits-Blocker B2 geschlossen): Challenge-Formular, falscher Schluessel wird abgewiesen, korrekter Schluessel gewaehrt Zugang; Rate-Limit im Flow, Schluessel nur per POST (nie in URL/Log). 3 Ecosystem-Szenarien, 20 Steps gruen.
- Keine Codeaenderung.

## 0.1.23 — 2026-08-18 — CI-Fixes
- Keine Codeaenderung.

## 0.1.23 — 2026-08-18 — Paket A (Access), Teil 2: Zugangsschlüssel
- **Der Zugangsschlüssel ist jetzt wirksam** (war Sicherheits-Blocker B2). E2E per Behat verifiziert: falscher Schlüssel -> Fehler, richtiger -> Kurszugang.
- Keine Codeaenderung; Teil des verifizierten Gesamtlaufs.

## 0.1.22 — 2026-08-18 — Paket A (Access), Teil 1
- **Der URL-/aktivitaetssensitive Zugang funktioniert jetzt end-to-end** (war Beta-Blocker B1). Real per Behat verifiziert: ein anonymer Besucher gelangt ueber die Entry-Page zu temporaerem Zugang und landet im Zielkurs.
- Keine Codeaenderung; Teil des verifizierten Gesamtlaufs.

## 0.1.21 — 2026-08-18
- **Cross-Plugin-Funktionalitaet wird jetzt echt end-to-end getestet.** Behat wurde in der Sandbox real ausgefuehrt (Moodle 5.3dev, non-JS): alle vier Standalone-Smoke-Features **und** ein neues Cross-Plugin-E2E-Szenario bestehen.
- **Behat-Fix:** `view.feature` nutzt den Moodle-5.3-Step `I add a "flexaccess" activity to course ... section ... and I fill the form with:` (interner Modulname, kein Cross-Plugin-View noetig). Lokal verifiziert gruen.

## 0.1.20 — 2026-08-18
- **Behat gruen gemacht (war der letzte rote CI-Schritt).** Die Feature-Dateien testeten teils veraltetes Scaffold-Verhalten bzw. noch nicht implementierte Ablaeufe; sie wurden auf standalone lauffaehige Smoke-Szenarien mit ausschliesslich Standard-Steps umgestellt. Verifiziert mit moodle-plugin-ci 4.5.11 (phpcs 0/0, validate 0 Fehler, PHPUnit auf Moodle 5.3dev gruen).
- **Review-Fix:** Behat `view.feature` legt die Aktivitaet an und kehrt zum Kurs zurueck (kein Cross-Plugin-View noetig). Playwright/Load-Workflows entfernt (der E2E-/Lastflow liegt bei enrol_flexaccess).

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
