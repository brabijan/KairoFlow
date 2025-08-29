# Brainstorming Session Results

**Session Date:** 2025-08-29
**Facilitator:** Business Analyst Mary
**Participant:** User

## Executive Summary

**Topic:** Osobní AI asistent pro životní oblasti (KairoFlow)

**Session Goals:** Sepsat existující představy o systému, rozvinout myšlenky o data flow a features, zaměření na finance a organizaci práce

**Techniques Used:** Mind Mapping, Morphological Analysis, First Principles Thinking

**Total Ideas Generated:** 45+

### Key Themes Identified:
- Automatizace finančního managementu pro překonání finanční negramotnosti
- ADHD-friendly design patterns a notifikační strategie  
- Integrace různých komunikačních kanálů do jednotného systému
- Postupné budování finančního bufferu a oddlužení
- Automatické follow-up tasky na základě Git commits

## Technique Sessions

### Mind Mapping - 25 minut

**Description:** Zmapování všech komponent systému a jejich propojení

#### Ideas Generated:
1. Dedikovaná emailová schránka jako centrální hub
2. Cron-based email fetching a automatické třídění
3. LLM fallback pro nerozpoznané dokumenty
4. QR kódy pro okamžité placení
5. Napojení na Clockify pro predikci příjmů
6. Monitoring Slack/Email pro nezapsané úkoly
7. GitLab commit analýza pro follow-up tasky
8. Týdenní automatická fakturace
9. Kategorizace plateb (jednorázové/opakované, firemní/osobní)
10. Cash flow management s týdenním bufferem

#### Insights Discovered:
- Email může sloužit jako univerzální vstup pro různé typy dat
- Automatizace musí mít ruční fallback pro edge cases
- Systém musí být "emotions-free" pro překonání ADHD prokrastinace

#### Notable Connections:
- Clockify data → příjmy → finanční plánování
- Git commits → automatické checklists → follow-up tasky
- Týdenní fakturace → sledování úhrad → cash flow

### Morphological Analysis - 20 minut

**Description:** Prozkoumání kombinací features a ADHD bypass strategií

#### Ideas Generated:
1. API integrace kde možné, email jako backup
2. Semi-automatické schvalování plateb (QR kódy, ne přímé platby)
3. Prediktivní cash flow analýza
4. "Ranní vydírání" - blokace GitLabu dokud nepotvrdíte finance
5. Vizuální terorismus - wallpaper mění barvu podle urgence
6. Gamifikace - buffer jako health bar
7. Fyzická realita - e-ink display u kávy
8. Sociální tlak - společný dashboard s manželkou
9. Pozitivní reinforcement - odemykání odměn
10. Anti-prokrastinace design - one-click akce

#### Insights Discovered:
- ADHD mozek potřebuje více simultánních strategií
- Integrace do existujících návyků je klíčová
- Vizuální a fyzické připomínky fungují lépe než digitální notifikace

#### Notable Connections:
- Ranní rutina + finanční check = udržitelný návyk
- Sociální tlak + gamifikace = dlouhodobá motivace
- Fyzické elementy + digitální systém = nemožné ignorovat

### First Principles Thinking - 15 minut

**Description:** Rozklad na základní potřeby a problémy

#### Ideas Generated:
1. Oddlužení engine s optimalizací splátek
2. Investiční modul po splacení dluhů
3. Projekce budoucnosti - "když X tak za Y měsíců..."
4. Denní návyk s 2-minutovým checkem
5. Chytré follow-up šablony podle typu deploye
6. Automatické generování follow-ups z commit message
7. Financial Trainer mód - postupné učení
8. Deadline Pressure Visualizer
9. Time-to-money converter pro úkoly
10. Smart batching plateb a úkolů

#### Insights Discovered:
- Systém musí řešit příčinu (finanční negramotnost), ne jen symptomy
- Automatizace + vzdělávání = dlouhodobá změna
- Projekce budoucnosti motivuje k akci

#### Notable Connections:
- Oddlužení → uvolněné prostředky → investice
- Úkoly → Clockify → příjmy → finanční plán
- Denní návyk → postupné zlepšení → dlouhodobý úspěch

## Idea Categorization

### Immediate Opportunities
*Ideas ready to implement now*

1. **Email Hub Setup**
   - Description: Založit dedikovanou schránku, nastavit cron pro fetching
   - Why immediate: Základní infrastruktura, bez které nic nepůjde
   - Resources needed: Email účet, základní Nette aplikace s cronem

2. **Základní finanční modul**
   - Description: Parsování bankovních výpisů, zobrazení QR kódů k platbě
   - Why immediate: Řeší okamžitý problém s placením po splatnosti
   - Resources needed: QR generátor, parser pro bankovní maily

3. **GitLab/Slack monitoring**
   - Description: Základní sledování nezapsaných úkolů
   - Why immediate: Zabrání zapomínání úkolů
   - Resources needed: API přístupy, jednoduchý diff systém

### Future Innovations
*Ideas requiring development/research*

1. **LLM Integration**
   - Description: Inteligentní kategorizace neznámých dokumentů
   - Development needed: Výběr LLM, prompt engineering, fallback strategie
   - Timeline estimate: 2-3 měsíce

2. **Prediktivní cash flow**
   - Description: AI predikce budoucích příjmů a výdajů
   - Development needed: Historická data, ML model, vizualizace
   - Timeline estimate: 4-6 měsíců

3. **ADHD Bypass System**
   - Description: Multi-channel notifikační systém s gamifikací
   - Development needed: Různé integrační body, A/B testing strategií
   - Timeline estimate: 3-4 měsíce iterativně

### Moonshots
*Ambitious, transformative concepts*

1. **Kompletní finanční autopilot**
   - Description: Systém který plně automatizuje finanční rozhodování
   - Transformative potential: Úplné vyřešení finanční negramotnosti
   - Challenges to overcome: Právní omezení, bezpečnost, důvěra

2. **AI Life Coach**
   - Description: Rozšíření na všechny životní oblasti s personalizovaným koučinkem
   - Transformative potential: Komplexní životní management
   - Challenges to overcome: Složitost, udržení fokus, privacy

### Insights & Learnings
*Key realizations from the session*

- **ADHD vyžaduje multi-pronged approach**: Jedna strategie nestačí, potřeba kombinovat vizuální, fyzické, sociální a gamifikační prvky
- **Automatizace musí být lidská**: Semi-auto s QR kódy místo plné automatizace zachovává kontrolu a buduje návyk
- **Řešit příčiny, ne symptomy**: Systém musí učit finanční gramotnost, ne jen připomínat platby
- **Integrace > Izolace**: Napojení na existující nástroje (GitLab, Clockify) místo budování všeho od nuly

## Action Planning

### Top 3 Priority Ideas

#### #1 Priority: Email Hub + Základní parser
- Rationale: Bez vstupu dat nemůže systém fungovat
- Next steps: Založit email, napsat cron job, implementovat základní parser
- Resources needed: Nette, Contributte/Scheduler, email knihovna
- Timeline: 1 týden

#### #2 Priority: Finanční modul s QR platbami
- Rationale: Okamžitě řeší problém plateb po splatnosti
- Next steps: QR generátor, UI pro zobrazení, kategorizace plateb
- Resources needed: QR knihovna, Doctrine entity, základní UI
- Timeline: 2 týdny

#### #3 Priority: Work monitoring (GitLab/Slack)
- Rationale: Zabrání zapomínání úkolů a follow-ups
- Next steps: API integrace, diff systém, notifikace
- Resources needed: GitLab API, Slack API, cron jobs
- Timeline: 2 týdny

## Reflection & Follow-up

### What Worked Well
- Strukturovaný přístup pomohl zmapovat celý systém
- Kombinace technik odhalila nové souvislosti
- Zaměření na ADHD problematiku přineslo kreativní řešení

### Areas for Further Exploration
- Konkrétní ADHD strategie: Které budou nejefektivnější?
- Technická architektura: Jak strukturovat moduly?
- Bezpečnost: Jak zabezpečit finanční data?
- UX design: Jak udělat rozhraní co nejjednodušší?

### Recommended Follow-up Techniques
- SCAMPER: Pro vylepšení jednotlivých features
- Six Thinking Hats: Pro vyhodnocení rizik
- Assumption Reversal: Pro nalezení inovativních řešení

### Questions That Emerged
- Jak měřit úspěšnost ADHD bypass strategií?
- Jaký LLM bude nejlepší pro kategorizaci?
- Jak řešit právní aspekty automatické fakturace?
- Jak zajistit, aby systém fungoval i při výpadku externích služeb?

### Next Session Planning
- **Suggested topics:** Detailní technická architektura, ADHD strategie výběr, Security design
- **Recommended timeframe:** Po implementaci MVP (4-6 týdnů)
- **Preparation needed:** Základní prototyp, feedback z používání, seznam problémů

---

## ADHD Bypass Strategies (Separate Section)

### Vizuální strategie
- Červený widget na ploše
- Progress bar "týdenní buffer"  
- Wallpaper Engine - měnící se pozadí
- Stream Deck červené tlačítko
- Browser New Tab hijack

### Zvukové strategie
- Alexa/Google hlášení
- Achievement sounds
- Podcast-style briefing

### Sociální strategie
- Společný dashboard s manželkou
- Weekly review rituály
- Accountability bot reporty

### Fyzické strategie
- E-ink display u kávy
- NFC tagy pro rychlé akce
- Tištěné QR kódy
- Smart hodinky vibrace

### Rutinní strategie
- Blokace GitLabu dokud nezkontrolujete finance
- Ranní káva = check finance
- Auto-open při zapnutí PC
- První commit = dashboard

### Gamifikační strategie
- Health bar pro buffer
- Combo streaks
- Boss fights pro velké platby
- Achievements a odemykání

### Pozitivní reinforcement
- Odemykání guilty pleasure účtu
- Netflix/Steam rewards
- Automatické převody na zábavu

---

*Session facilitated using the BMAD-METHOD™ brainstorming framework*