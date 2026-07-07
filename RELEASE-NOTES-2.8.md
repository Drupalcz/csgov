# CSGOV 2.8.0 — poznámky k vydání (podklad)

CSGOV 2.8 přechází na gov_cz **4.6.0** s design systémem gov.cz **4.6.4**
(z DS 4.0.4) a přidává plnou podporu tmavého režimu.

## Co se mění pro návštěvníky

- **DS 4.6 je rebrand**: barvy se posunou i ve světlém režimu (primární
  modrá `#2362a2` → `#00469B`), mění se odstíny, typografická škála,
  focus stavy. Nejde o regresi, ale o záměr aktualizace design systému.
- **Tmavý režim**: po upgradu je web **light-only** — žádná změna chování.
  Tmavý režim se zapíná umístěním nového bloku **„Theme switch (dark
  mode)"** do regionu *Header Extra* (Správa → Struktura → Rozvržení
  bloků). Jakmile je blok umístěn:
  - web se řídí nastavením OS návštěvníka (auto),
  - návštěvník si může přepínačem vynutit světlý/tmavý režim
    (ukládá se do localStorage) nebo se vrátit k „Podle systému".
- Nové instalace mají blok přepínače umístěný automaticky.

## Co se mění pro správce webů (upgrade z 2.7.2)

- `composer update` — žádné změny konfigurace nejsou potřeba, žádné
  update hooky se nespouští. Po nasazení stačí `drush cr`.
- „Secondary" barva DS 4.6 je nově **žlutá**; všechna místa, kde CSGOV
  dřív používal starou šedou „secondary", jsou přemapována na neutrální
  šedé tokeny — weby nezežloutnou. Žlutá se může objevit jen tam, kde ji
  DS používá záměrně (např. warning tlačítka).
- Vlastní přepisy starých `--gov-*` tokenů v custom stylech webů se na
  nové DS komponenty nepropíší (tokeny byly v DS přejmenovány). Knihovna
  `gov_cz/legacy-tokens` staré hodnoty dál servíruje pro legacy komponenty
  (statsbar, tiles). Vlastní styly webů doporučujeme převést na sémantické
  tokeny DS 4.6 (`--background-*`, `--text-*`, `--border-*`, …) — jen ty
  reagují na tmavý režim.
- Weby se striktní CSP: inline skript v `<head>` (aplikace uložené
  preference před prvním vykreslením) je statický — povolte ho hashem.

## Verze

| Balíček | 2.7.2 | 2.8.0 |
|---|---|---|
| drupal/gov_cz | 4.1.3 | 4.6.0 (parita s DS 4.6.4) |
| drupalcz/csgov_theme | 4.4.1 | 4.5.0 |
