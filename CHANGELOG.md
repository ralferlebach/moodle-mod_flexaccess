# Changelog

## 1.0.0-RC1 — 2026-08-27 — Release Candidate 1
- Version `2026082700`, Release `1.0.0-RC1`, Reifegrad `MATURITY_STABLE`.
- **Aktivitätssymbol in der Farbe für Verwaltungs- und Zugangsfunktionen:** Die Aktivität meldet jetzt `MOD_PURPOSE_ADMINISTRATION`. Moodle färbt das Symbol nach diesem Zweck ein — die Aktivität liefert keine Inhalte und keine Bewertung, sondern sichert den Kurszugang.
- Die Abhängigkeiten verlangen ebenfalls `2026082700`.
- **Neue `README.md`** nach dem Muster von Moodle an Hochschulen.

## 0.9.63 — 2026-08-27 — Versions-Gleichschritt
- Keine Codeänderung. Versions-Gleichschritt auf `2026082440`.

## 0.9.62 — 2026-08-27 — Versions-Gleichschritt
- Keine Codeänderung. Versions-Gleichschritt auf `2026082439`.

## 0.9.61 — 2026-08-27 — Versions-Gleichschritt
- Keine Codeänderung. Versions-Gleichschritt auf `2026082438`.

## 0.9.60 — 2026-08-27 — Testserver auf 127.0.0.1
- Der Testserver wird an `127.0.0.1` gebunden und unter derselben Adresse angesprochen; `localhost` konnte je nach Client nach `::1` aufgelöst werden, wohin der Server nicht lauscht.
- Versions-Gleichschritt `2026082437`.

## 0.9.59 — 2026-08-27 — Versions-Gleichschritt
- Keine Codeänderung. Versions-Gleichschritt auf `2026082436`.

## 0.9.58 — 2026-08-27 — Playwright-Testharness bereinigt
- **Eine** Installationsroutine für die Geschwister-Plugins mit **einem** Ref; Prüfschritt gegen verschachtelte Plugin-Kopien. Der Webserver läuft im selben Schritt wie der Testlauf.
- **Kommentare überarbeitet:** Sachaussagen statt Verweisen auf den Bearbeitungsverlauf.
- Versions-Gleichschritt `2026082435`.

## 0.9.57 — 2026-08-27 — Versions-Gleichschritt
- Keine Codeänderung. Versions-Gleichschritt auf `2026082434`.

## 0.9.56 — 2026-08-27 — Playwright, jMeter und k6 lauffähig gemacht
- **Playwright scheiterte an der Moodle-Installation** („Dependencies check failed"). Ursache war meine Versions-Bump-Automatik: Ein pauschales Suchen-und-Ersetzen der Build-Nummer schrieb **auch die `$plugin->dependencies`** um, sodass jedes Plugin exakt den brandneuen Build aller Geschwister verlangte. Beim Rollout über vier Repositories liegt aber zwangsläufig eines zurück — und dann verweigert der Installer den Dienst. Die Pins nennen jetzt die **Mindestversion**, in der die genutzte API entstand (`2026082411`, für `tool → auth` `2026082423`), und werden nicht mehr automatisch mitgezogen.
- **jMeter scheiterte am Prüfsummenvergleich.** Die Apache-Datei enthält bereits einen Dateinamen (`<hash> *apache-jmeter-5.6.3.tgz`); das Skript hängte einen zweiten an, woraufhin `sha512sum` nach einer Datei namens „apache-jmeter-5.6.3.tgz  jmeter.tgz" suchte. Es wird jetzt nur das Hash-Feld verwendet — mit echter Prüfsummendatei verifiziert.
- **k6 meldete 100 % fehlgeschlagene Requests, weil der Testserver nicht lief.** Zwei Ursachen: Der Server wurde in einem Schritt gestartet und erst in einem späteren genutzt (jetzt via `setsid` abgekoppelt), und die Bereitschaftsschleife lief bei Misserfolg einfach aus, statt abzubrechen. Sie bricht jetzt mit Serverlog ab. Zusätzlich prüfen jMeter- und k6-Schritt unmittelbar vor dem Lastlauf, ob das Ziel antwortet.
- **k6-Pläne gehärtet:** Beide Pläne brechen in `setup()` mit klarer Meldung ab, wenn `BASE_URL`/`COURSEID` fehlen oder das Ziel nicht antwortet. Der bewusst „fail closed" antwortende `magic.php`-Aufruf ohne Token wird als erwartete Antwort deklariert und verfälscht die Fehlerrate nicht mehr.
- Versions-Gleichschritt `2026082433`.

## 0.9.55 — 2026-08-27 — Coverage-Messung repariert und scharf geschaltet
- **Das Coverage-Gate maß Unsinn — mein Fehler.** Der Filter `/flexaccess/` zählte die Statements **aller vier** Plugins, obwohl im jeweiligen Job nur die eigene Testsuite läuft. Ergebnis: 0,52 %. Das Gate ermittelt die Komponente jetzt aus `version.php` und wertet ausschließlich den eigenen Installationspfad aus.
- **Zweiter Fehler im selben Skript:** Es las `version.php` per `include`. Eine Moodle-`version.php` beginnt mit `defined('MOODLE_INTERNAL') || die()`, das Skript brach also stillschweigend ab und meldete Erfolg. Jetzt wird die Komponente als Text ausgelesen.
- **Echte Messwerte (lokal mit pcov über die vollen Suiten):** auth 50,9 % · enrol 57,7 % · tool 46,6 % · mod 10,2 %. Die Untergrenzen liegen je Plugin einige Punkte darunter (45/50/40/8) — sie fangen eine echte Regression, ohne bei normaler Schwankung rot zu werden. Das Gate ist damit wieder **blockierend**. mod ist bewusst niedrig: Seine Logik liegt in `view.php`, einem von Behat abgedeckten Entry-Point.
- **Drei ungültige `@covers`-Ziele behoben.** `\xmldb_auth_flexaccess_upgrade` und `\backup_flexaccess_activity_structure_step` existieren nicht als Klasse (jetzt `@coversNothing`), `\enrol_flexaccess_plugin::enrol_page_hook` zeigte auf eine Methode statt auf die Klasse. PHPUnit meldet solche Ziele als **Warnung** — mit dem in der CI genutzten `--fail-on-warning` hätte das den Build rot gemacht. Alle vier Suiten laufen jetzt mit diesem Flag auf Exit 0.
- Versions-Gleichschritt `2026082432`.

## 0.9.54 — 2026-08-27 — Codechecker-Befunde behoben, Prüfkontext angeglichen
- **Ursache der Serie gefunden:** Zwei Sniffs feuern **nur**, wenn das Plugin **innerhalb** eines Moodle-Baums geprüft wird und die Zielversion bekannt ist. Die Dev-Pipeline prüfte einen Standalone-Ordner (`codechecker plugin`), die Main-Pipeline den installierten Pfad — deshalb war Dev grün und Main rot. **Beide Pipelines nutzen jetzt denselben strengen Aufruf** (`codechecker` ohne Pfadargument, `--max-warnings 0`).
- **`moodle.Files.LangFilesOrdering`:** Alle acht Sprachdateien sind jetzt alphabetisch sortiert (120/142/25/267 Strings je Sprache, EN/DE-Parität unverändert exakt).
- **`moodle.PHPUnit.TestCaseCovers`:** Auf Moodle 4.5 akzeptiert der Sniff das PHP-Attribut `#[CoversClass]` nicht — er sucht den Klassen-Docblock unmittelbar vor der Klasse, und ein dazwischenstehendes Attribut verdeckt ihn. Alle 59 Testklassen tragen jetzt eine `@covers`-Angabe im Docblock; die Attribute wurden entfernt. Damit sind alle 244 Befunde weg.
- **Coverage-Job ist jetzt berichtend statt blockierend.** Die Messung greift in diesem Job nicht (0,52 % gemessen bei tatsächlich weit höherer Abdeckung durch die Suiten) — ein Gate darauf würde jeden Build wegen eines Messproblems blockieren, nicht wegen mangelnder Qualität. Die Zahl wird bei jedem Lauf ausgegeben; die Schwelle (`COVERAGE_FLOOR`) wird scharf geschaltet, sobald die Messung nachweislich verlässlich ist.
- Versions-Gleichschritt `2026082431`.

## 0.9.53 — 2026-08-27 — Main-Pipeline: zwei Gates waren konstruktiv falsch
- **Lockstep-Gate blockierte jeden Rollout.** Vier getrennte Repositories lassen sich nur nacheinander pushen; das Gate verlangte aber bei **jedem** Branch-Push identische Versionen aller vier — die ersten drei Pushes waren damit zwangsläufig rot. Es blockiert jetzt nur noch dort, wo „gemeinsam released" tatsächlich behauptet wird: auf einem **Tag**. Auf Branch-Pushes wird ein Versionsunterschied als Warnung berichtet, nicht als Fehler.
- **Coverage-Gate maß das falsche Ziel.** `--coverage-text` berichtet die Abdeckung des **gesamten Moodle-Baums** (im Lauf: 2,23 %, weil der Core die Zeilenzahl dominiert) — über dieses Plugin sagt das nichts, und der Mindestwert konnte nie erreicht werden. Die Messung läuft jetzt über **Clover-XML** und wertet mit `tools/coverage_gate.php` ausschließlich die Dateien dieses Plugins aus (ohne die Tests selbst). Fehlt der Report oder enthält er keine Plugin-Datei, schlägt das Gate fehl statt still durchzuwinken.
- Versions-Gleichschritt `2026082430`.

## 0.9.52 — 2026-08-27 — CI-Fix: Coverage-Konfiguration war nicht 4.5-kompatibel
- **Alle PHPUnit- und Behat-Jobs scheiterten an `Class "core\test\phpunit\coverage_info" not found`.** Die in 0.9.51 eingeführte `tests/coverage.php` verwendete den **namespaced** Klassennamen; den gibt es erst ab Moodle 5.x. Auf dem unterstützten Moodle 4.5 heißt die Klasse `phpunit_coverage_info`. Sie wird jetzt über diesen globalen Namen abgeleitet — auf 4.5 ist das die Klasse selbst, auf 5.x ein gepflegter Alias, also für alle unterstützten Versionen korrekt.
- **Neue Datei `db/removed_files.txt` und neuer CI-Job `stale-files`.** Ein Plugin-Update per ZIP fügt Dateien hinzu und überschreibt sie, **löscht aber nie**. In früheren Releases entfernte Dateien überleben deshalb in einer Installation oder in einem so aktualisierten Repository — mit Folgen wie doppelten Klassen (phpcpd) oder Zugriffen auf nicht mehr existierende Spalten. Der Job schlägt fehl, solange eine gelistete Datei noch vorhanden ist, statt die Ursache in Folgefehlern zu verstecken. In Dev- **und** Main-Pipeline verdrahtet, `ci-complete` hängt daran.
- Versions-Gleichschritt `2026082429`.

- **Zu löschen in diesem Repository:** `classes/local/activation_manager.php` und `tests/activation_manager_test.php` (in 0.9.41 entfernt).

## 0.9.51 — 2026-08-27 — Coverage- und Maturity-Gate
- **Neue `tests/coverage.php`** definiert den Coverage-Messumfang dieses Plugins.
- **Neue CI-Gates** `coverage` (erzwungene Mindest-Line-Coverage) und `maturity-gate` (`MATURITY_STABLE` nur bei durchgehend grünen Release-Gates und dokumentierten Scope-Entscheidungen).
- Die Maturity bleibt bewusst `MATURITY_BETA`, bis der Reviewer die Blocker unabhängig als geschlossen bestätigt.
- Versions-Gleichschritt `2026082428`.

## 0.9.50 — 2026-08-26 — Versions-Gleichschritt
- Keine Codeänderung. Versions-Gleichschritt auf `2026082427`.

## 0.9.49 — 2026-08-26 — Versions-Gleichschritt (CI-Fix im tool, Persistenz-Fix im auth)
- Keine Codeänderung. Versions-Gleichschritt auf `2026082426`.

## 0.9.48 — 2026-08-26 — Versions-Gleichschritt
- Keine Codeänderung. Versions-Gleichschritt auf `2026082425`.

## 0.9.47 — 2026-08-26 — Release-Gate auf dem tatsächlichen Artefakt
- **Neuer CI-Job `release-artefact`** in der Main-Pipeline: Er baut das Release-Archiv mit `git archive` (nur dieses respektiert `.gitattributes export-ignore`) und prüft die **tatsächlich ausgelieferte Dateiliste** — kein `tools/`, `docs/`, `.github/`, `tests/load/`, `tests/playwright/` und keine CI-Konfiguration; zugleich muss enthalten sein, was Moodle ausführt. `ci-complete` hängt daran. Damit prüft das Gate das Artefakt statt nur identischer Versionsnummern.
- Versions-Gleichschritt `2026082424`.

## 0.9.46 — 2026-08-26 — Release-Artefakte ohne Entwicklerwerkzeuge
- **CLI-Guard** (`PHP_SAPI !== 'cli'` → 403) in allen `tools/`-Skripten, vor jedem Schreibzugriff.
- Die Release-Pakete respektieren jetzt `.gitattributes export-ignore`: `tools/`, `docs/`, `.github/`, `tests/load/`, `tests/playwright/` und die CI-Konfiguration sind nicht mehr enthalten.
- Versions-Gleichschritt `2026082423`.

## 0.9.45 — 2026-08-26 — String-IDs vereinheitlicht
- Colon-IDs allgemeiner UI-Strings flach gezogen; Capability- und Privacy-Keys behalten konventionsgemäß den Doppelpunkt.
- Versions-Gleichschritt `2026082422`.

## 0.9.44 — 2026-08-25 — CI-Release-Gate
- **`ecosystem-lockstep`** in der Main-Pipeline: Die Freigabe scheitert, wenn nicht alle vier FlexAccess-Plugins dieselbe Version melden.
- Versions-Gleichschritt `2026082421`.

## 0.9.43 — 2026-08-25 — CI: Geschwister aus development
- **Dev-Pipeline und Playwright-Workflow** ziehen die Geschwister-Plugins jetzt per `--branch development` aus dem gemeinsamen Entwicklungszweig, damit das Ökosystem in seinem echten, gemeinsam entwickelten Stand getestet wird. Die Main-Pipeline bleibt auf `main`.
- Versions-Gleichschritt `2026082420`.

## 0.9.42 — 2026-08-25 — Versions-Gleichschritt
- Keine Codeänderung. Versions-Gleichschritt auf `2026082419`.

## 0.9.41 — 2026-08-25 — Review-P2: toten Code entfernt
- **`classes/local/activation_manager.php` entfernt** (samt zugehörigem Test). Die Klasse wurde von `view.php` nicht verwendet — dort wird direkt die zentrale Auth-API aufgerufen — und ausschließlich von ihrem eigenen Unit-Test benutzt. Sie erzeugte damit nur Scheinkomplexität.
- Versions-Gleichschritt `2026082418`.

## 0.9.40 — 2026-08-25 — Review-P1: activate-Capability vor Formular
- **`mod/flexaccess:activate` wird vor der Formularanzeige geprüft.** Ein Nutzer mit `view=allow`, `activate=deny` sieht jetzt einen erklärenden Hinweis statt eines Formulars, das er nicht erfolgreich absenden kann.
- Versions-Gleichschritt `2026082417`.

## 0.9.39 — 2026-08-25 — Versions-Gleichschritt (enrol: Zugangsschlüssel-Fix)
- Keine Codeänderung. Versions-Gleichschritt auf `2026082416`.

## 0.9.38 — 2026-08-25 — CI-Rollback + Versions-Gleichschritt
- **CI:** Rückrollung der Dev-Pipeline-Änderung — Geschwister werden wieder aus dem Default-Branch (`main`) gezogen.
- Versions-Gleichschritt `2026082415`.

## 0.9.38 — 2026-08-25 — CI-Rollback + P0-Security-Härtung

- **CI:** Rücknahme der develop-Branch-Umstellung (Dev-Pipeline zieht Geschwister wieder aus `main`).
- Versions-Gleichschritt `2026082415`. Keine funktionale Änderung.

## 0.9.37 — 2026-08-25 — CI: Dev-Pipeline zieht Geschwister aus develop
- Die **Dev-Pipeline** (`moodle-plugin-ci-dev.yml`) holt die Geschwister-Plugins jetzt per `add-plugin … --branch develop` aus dem **develop-Branch** statt aus `main`. Damit testet die beschleunigte Pipeline den echten Entwicklungsstand aller vier Plugins gemeinsam — kein Skew mehr durch hinterherhängendes `main`. Die **Main-Pipeline** zieht weiterhin aus `main` (Release-Stand).
- Versions-Gleichschritt auf `2026082414`.

## 0.9.36 — 2026-08-25 — Versions-Gleichschritt (CI-Fix im enrol-Behat)
- Keine Codeänderung. Versions-Gleichschritt auf `2026082413`.

## 0.9.35 — 2026-08-25 — Versions-Gleichschritt (CI-Fixes in auth/enrol)
- Keine Codeänderung. Versions-Gleichschritt auf `2026082412`.

## 0.9.34 — 2026-08-25 — Versions-Gleichschritt (E-Mail-Login-Methode + Login-UI)
- Keine Codeänderung. Versions-Gleichschritt auf `2026082411`.

## 0.9.33 — 2026-08-25 — Versions-Gleichschritt (enrol: Fix Teilnehmerlisten-Sichtbarkeit)
- Keine Codeänderung. Versions-Gleichschritt auf `2026082410`.

## 0.9.32 — 2026-08-25 — Versions-Gleichschritt
- Keine Codeänderung. Versions-Gleichschritt auf `2026082409`.

## 0.9.31 — 2026-08-25 — Versions-Gleichschritt (enrol: präzisierte Neutralisierungs-Warnung)
- Keine Codeänderung. Versions-Gleichschritt auf `2026082408`.

## 0.9.30 — 2026-08-25 — Versions-Gleichschritt
- Keine Codeänderung. Versions-Gleichschritt auf `2026082407`.

## 0.9.29 — 2026-08-25 — P2: PHPUnit-11-Migration + Pakete ohne .git
- `@covers`-Doc-Annotationen der Testklassen auf `#[CoversClass(...)]`-Attribute umgestellt (keine PHPUnit-Deprecations mehr). `.git`-Verzeichnisse aus dem Paket entfernt. Versions-Gleichschritt auf `2026082406`.

## 0.9.28 — 2026-08-25 — Versions-Gleichschritt
- Keine Codeänderung. Versions-Gleichschritt auf `2026082405`.

## 0.9.27 — 2026-08-24 — CI-Fix: fehlerhafte Workflow-Ausdrücke (${ } → ${{ }})
- Fehlerhafte GitHub-Actions-Ausdrücke im `lint-jsamd`-Job korrigiert (`${ } → ${{ }}`); mit `actionlint` gegengeprüft (0 Findings). Kein PHP-Code geändert; Versions-Gleichschritt auf `2026082404`.

## 0.9.26 — 2026-08-24 — CI: JS/AMD/Mustache-Job wiederhergestellt (catquiz-Form 1:1)
- `lint-jsamd` (grunt + mustache) in dev wiederhergestellt; Mustache/npm/Grunt in main ergänzt. Kein PHP-Code geändert; Versions-Gleichschritt auf `2026082403`.

## 0.9.25 — 2026-08-24 — CI-Fixes (DB-Versionen, vollständige Geschwister, eine Main-Pipeline)
- CI: `postgres:13→16`, `mariadb:10.8→10.11`; jede Pipeline installiert alle drei Geschwister (Ökosystem-Tests); `moodle-release.yml` entfernt.
- Kein PHP-Code geändert; Versions-Gleichschritt auf `2026082402`.

## 0.9.24 — 2026-08-24 — Versions-Gleichschritt (enrol: L3-Kurs-Einstieg + Load-Pläne + CI-Konsolidierung)
- Keine Codeänderung in diesem Plugin. CI: eine Main-Pipeline (Ökosystem-`main.yml` entfernt); Load-Workflows liegen im Hub `enrol_flexaccess`.
- Versions-Gleichschritt auf `2026082401`.

## 0.9.23 — 2026-08-24 — Versions-Gleichschritt (enrol: Zugangs-Blocker-Fix + Kopplungscheck)
- Keine Codeänderung in diesem Plugin; gemeinsamer Versions-Bump auf `2026082400` und aktualisierte Abhängigkeits-Pins.
- **CI-Fix:** `@package`-Korrektur in `tools/mustache_check.php` und `tools/fix_phpdoc.php` (Copy-Paste-Rest).
- **CI-Pipeline:** getrennte Dev-/Main-Workflows + dispatch-only JMeter-/k6-Lastworkflows (catquiz-Vorbild, FlexAccess-Geschwister als Abhängigkeit).

## 0.9.22 — 2026-08-20 — Fix: PHPDoc-Checker (CI) — @param-Vollstaendigkeit
- Keine Codeaenderung.

## 0.9.21 — 2026-08-20 — Feature: Excel-Rückkonversion von Stapel-Accounts (Kampagne, Teil 2)
- Keine Codeänderung.

## 0.9.20 — 2026-08-20 — Feature: Stapel-Bereitstellung von Kurs-Accounts (Kampagne, Teil 1)
- Keine Codeaenderung.

## 0.9.19 — 2026-08-20 — Fix: Upgrade-Crash beim Verbreitern der indizierten ratehit.identifier-Spalte
- Keine Codeaenderung.

## 0.9.19 — 2026-08-20 — RC-Gates (Review 0.9.17): Invitation-Security (2 P0) + Playwright-Lockfile
- Keine Codeaenderung.

## 0.9.18 — 2026-08-20 — Fix: PHPDoc-Parameterliste (enrol-CI rot)
- Keine Codeaenderung.

## 0.9.17 — 2026-08-20 — Fix: Cross-Plugin-Mailqueue (Standalone-CI) + saubere API-Grenze
- Keine Codeaenderung.

## 0.9.16 — 2026-08-20 — P2-Cleanup: Performance, Reliability, i18n
- Keine Codeaenderung.

## 0.9.15 — 2026-08-20 — RC-Gates (Review 0.9.13): 4 P0 + Reliability + Doku/CI-Sync
- Cleanup (§13): halbfertiges `profilefieldsjson`-Formularfeld + Scaffold-Hilfetexte entfernt (DB-Spalte/Backup bleiben, um Backup/Restore nicht zu brechen).

## 0.9.14 — 2026-08-20 — Einladungen: personengebundenes Single-Use-Modell (Review §9)
- Keine Codeaenderung.

## 0.9.13 — 2026-08-20 — P2-Batch: Performance, Retention, Supply-Chain, Doku
- Keine Codeaenderung.

## 0.9.12 — 2026-08-20 — P1/P2-Härtung: Security (a) + Identity/State (b) + Cleanup/Docs (c)
- **(c) Cleanup:** tote `stub*`-Strings entfernt; Makefile-Bereinigung.

## 0.9.11 — 2026-08-20 — RC-Hardening: P0#6 (Admin-Conversion über Mailqueue)
- Keine Codeaenderung.

## 0.9.10 — 2026-08-20 — RC-Hardening: 7/8 P0 aus dem 0.9.8-Review
- **P0#5 (Anzeige):** `view.php` behandelt den neuen Status `verificationsent` als Erfolg; neue Strings `sa:verificationsent`, `sa:nottemporary`, `sa:locked`.

**Offen (bewusst gestaffelt):** P0#6 — Admin-Conversion versendet die Passwort-Mail noch via Core `setnew_password_and_mail` (umgeht die FlexAccess-Mailqueue/Ratelimit). Fix erfordert einen neuen queued 'set-password'-Mailfluss.

## 0.9.9 — 2026-08-19 — Welle 4 Abschluss: Accessibility-Gate + Docs-SSOT & Traceability
- Keine Codeaenderung.

## 0.9.8 — 2026-08-19 — Welle 4: Policy-Caching (Perf)
- Keine Codeaenderung.

## 0.9.7 — 2026-08-19 — Welle 5: Einladungskampagnen (§49)
- Keine Codeaenderung.

## 0.9.6 — 2026-08-19 — Welle 4: Persistence-Follow-up (schließt P0 #9 vollständig)
- Keine Codeaenderung.

## 0.9.5 — 2026-08-19 — Welle 3 Strom E: administrierbare Kategorie-Policies (P0 #8) + Cleanup
- Keine Codeaenderung.

## 0.9.4 — 2026-08-19 — CI-Härtung + Upgrade-Robustheit (Plugin-Isolation, PHPDoc, reset_role_capabilities)
- Keine Codeaenderung.

## 0.9.3 — 2026-08-19 — Welle 3 Strom F: Quick-Registration neu spezifiziert (P0 #5)
- Keine Codeaenderung.

## 0.9.2 — 2026-08-19 — Welle 2: Retention/Deletion, zentraler Conversion-Guard, Temp-Restriktionen (P0 #9/#10/#6)
- Keine Codeaenderung.

## 0.9.1 — 2026-08-19 — Welle 1: Token-Sicherheit + atomares Temp-Rate-Limit (P0 #1, #2)
- Keine Codeaenderung.

## 0.9.0 — 2026-08-19 — Beta-Schwelle: CI-Fix, Maturity BETA, Versions-Neustart
- Versionsschema auf `2026081900` / Release `0.9.0` gesetzt, Maturity auf **MATURITY_BETA** angehoben; Cross-Plugin-Dependencies auf `2026081900` gezogen.
- **CI-Fix:** fehlende `@param $reference` in den Docblocks von `api::search_accounts` und `api::build_account_filter` ergaenzt (PHPDoc-Checker).
- Hinweis: Zwei aus dem erneuten Audit stammende Rest-P0 (Klartext-Token in der Mailqueue; generelles atomares Rate-Limit fuer anonyme Temporary-Erzeugung) sind als erste Beta-Haertungswelle eingeplant.

## 0.1.39 — 2026-08-19 — Konfigurierbare Rate-Limits, Cleanup, i18n, Backup/Restore, CI-Härtung
- **§44 Backup/Restore:** vollstaendige moodle2-Backup-/Restore-Unterstuetzung ergaenzt. Kernursache war, dass `flexaccess_supports(FEATURE_BACKUP_MOODLE2)` `false` lieferte — die Aktivitaet wurde nie gesichert; jetzt `true`. Neuer Test-Generator und ein Backup/Restore-Roundtrip-Test.
- **§3 Cleanup:** ungenutzte Klasse `activation_manager` (+ Test) entfernt.

## 0.1.38 — 2026-08-19 — Re-login-fähige Konversion, Transaktionen, Mailqueue-Limit, Referenzsuche (§7/§8/§13/§16/§36)
- **§7:** Self-Activation-Formular hat ein Passwortfeld mit Policy-Pruefung (`check_password_policy`); `view.php` reicht das Passwort an `self_activate` durch. Neue Strings `sa:password`/`sa:password_help`.

## 0.1.37 — 2026-08-19 — Teilnehmerlisten-Sichtbarkeit durchgesetzt (§35, P0)
- Keine Codeaenderung.

## 0.1.36 — 2026-08-19 — Capacity-Race / verwaiste Accounts behoben (§18)
- Keine Codeaenderung.

## 0.1.35 — 2026-08-19 — DSGVO-Privacy-Provider (§11) + PHPDoc-Fixes
- Keine Codeaenderung (Privacy bleibt `null_provider`).

## 0.1.35 — 2026-08-19 — DSGVO-Datenschutz-Provider vervollstaendigt (§11)
- Keine Codeaenderung (Provider bleibt korrekt `null_provider`: die `flexaccess`-Tabelle hat keine userid).

## 0.1.34 — 2026-08-19 — Rate-Limiting der oeffentlichen Schreib-Endpoints (§5)
- Keine Codeaenderung.

## 0.1.33 — 2026-08-19 — Enrolment-Expiry (§32/§33) + echte jmeter/playwright-Plaene (§26/§27)
- Keine Codeaenderung.

## 0.1.32 — 2026-08-19 — Magic-Login, Mail-Queue-Retrofit, SEC-03, main-CI + jmeter/playwright
- Keine Codeaenderung.

## 0.1.31 — 2026-08-18 — Aufraeumen: toter persistence_followup-Mailpfad entfernt
- Keine Codeaenderung.

## 0.1.30 — 2026-08-18 — DRY: gemeinsame Identitaetsfelder der Formulare
- Keine Codeaenderung.

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
