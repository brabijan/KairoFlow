# Project Brief: KairoFlow

## Executive Summary

KairoFlow je osobní AI asistent navržený specificky pro naši domácnost k překonání finanční negramotnosti a ADHD-related prokrastinace v oblastech finance a organizace práce. Systém automatizuje sběr dat z různých komunikačních kanálů (email, Slack, GitLab) a poskytuje actionable insights s ADHD-friendly notifikacemi a gamifikací pro budování zdravých finančních návyků.

**Primární problém:** Opakované platby po splatnosti s pokutami, zapomínání pracovních úkolů, neschopnost budovat finanční rezervy kvůli ADHD a finanční negramotnosti.

**Cíloví uživatelé:** Dva uživatelé - já (freelance developer s ADHD) a manželka, oba s plným přístupem bez rozlišení rolí.

**Klíčová hodnota:** Emotivně neutrální systém šitý na míru našim potřebám, který obchází ADHD prokrastinaci pomocí multi-channel strategií a postupně buduje finanční gramotnost.

## Problem Statement

### Současný stav a bolestivé body

Jako freelance developer s ADHD čelím dvěma propojeným problémům, které významně ovlivňují kvalitu života naší domácnosti:

**Finanční chaos:**
- Pravidelně platím faktury po splatnosti, což vede k pokutám a penále (stovky až tisíce Kč měsíčně)
- Přestože týdně fakturuji vysoké částky, na konci týdne mi nezůstává téměř nic
- Nemám přehled o tom, co všechno musím zaplatit a kdy
- Neschopnost budovat finanční rezervy vede k neustálému stresu
- Existující dluhy se nedaří umořovat systematicky

**Pracovní dezorganizace:**
- Klienti zadávají úkoly přes Slack, email a telefon
- Často zapomínám přepsat úkoly do GitLabu, kde je trackuji
- Chybí mi follow-up tasky ("za 3 dny zkontroluj, jestli deployment funguje")
- Co není zapsané, to se neudělá - ADHD mozek to prostě vypustí

### Kvantifikovaný dopad

- **Finanční ztráty:** 500-2000+ Kč měsíčně na pokutách a penále
- **Časové ztráty:** 5-10 hodin týdně řešením urgentních situací místo plánované práce
- **Psychologický dopad:** Konstantní stres z financí, napětí v domácnosti
- **Missed opportunities:** Neschopnost investovat nebo budovat dlouhodobé finanční zdraví

### Proč existující řešení selhávají

**Tradiční finanční aplikace:** Vyžadují disciplínu a pravidelný input - přesně to, co ADHD mozek nedokáže dlouhodobě udržet

**Todo aplikace:** Fungují první týden, pak je přestanu používat. Notifikace ignoruji, úkoly nezapisuji.

**Bankovní aplikace:** Poskytují data, ale ne actionable insights. Neřeší problém prokrastinace.

**Externí účetní:** Řeší daně, ne denní cash flow management a osobní finance.

### Urgence řešení

Situace se každým měsícem zhoršuje - dluhy rostou, pokuty se kumulují. Bez systémového řešení hrozí vážné finanční problémy. Navíc, současný stres negativně ovlivňuje produktivitu a kvalitu života.

## Proposed Solution

### Základní koncept

KairoFlow je personalizovaný AI-powered systém, který funguje jako "finanční a pracovní autopilot" specificky navržený pro ADHD mozek. Místo spoléhání na disciplínu a paměť systém automaticky sbírá, třídí a prezentuje informace způsobem, který je nemožné ignorovat.

### Klíčový přístup

**Centrální email hub:** Dedikovaná emailová schránka jako univerzální vstupní bod - vše se přeposílá sem (bankovní výpisy, faktury, upomínky, Slack notifikace). Systém každou minutu kontroluje nové zprávy.

**Inteligentní zpracování:** Automatické parsování známých formátů (bankovní výpisy), LLM pro nerozpoznané dokumenty, ruční kategorizace jako fallback.

**ADHD Bypass Engine:** Multi-channel strategie které obchází prokrastinaci:
- QR kódy pro okamžité placení (odstranění friction)
- Vizuální indikátory urgence (nemožné přehlédnout)
- Blokování pracovních nástrojů dokud nepotvrdím finanční status
- Gamifikace s týdenním bufferem jako "health bar"
- Fyzické elementy (e-ink display, tištěné QR kódy)

### Klíčové diferenciátory

**Emoce-free rozhodování:** Systém prezentuje fakta a akce bez moralizování nebo vyvolávání viny - klíčové pro ADHD mozek, který se vyhýbá nepříjemným tématům.

**Zero-friction akce:** Od notifikace k zaplacení = 2 kliky. Žádné přihlašování do bank, žádné opisování čísel účtů.

**Postupné budování návyků:** Místo velkých změn systém postupně buduje mikronavyky pomocí daily streaks a malých výher.

**Prediktivní plánování:** "Tento týden musíš vydělat ještě X Kč aby příští týden byl buffer Y Kč" - konkrétní, dosažitelné cíle.

### Proč uspěje tam, kde ostatní selhali

1. **Navrženo specificky pro nás** - žádné kompromisy, 100% personalizace
2. **Obchází ADHD místo boje s ním** - využívá psychologické hacky místo spoléhání na disciplínu  
3. **Automatizace všeho možného** - minimální maintenance overhead
4. **Integrace do existujících workflows** - GitLab, Slack, Clockify už používáme
5. **Postupná evoluce** - začneme s MVP, postupně přidáváme features podle skutečných potřeb

### Dlouhodobá vize

Systém který nejen řeší akutní problémy, ale postupně buduje finanční gramotnost a zdravé návyky. Po splacení dluhů automaticky přepne na investiční mód. Z fire-fighting nástroje se stane wealth-building assistant.

## Target Users

### Primary User Segment: Já (Freelance Developer s ADHD)

**Profil:**
- Freelance/contractor developer pracující pro více klientů současně
- Diagnostikované ADHD ovlivňující exekutivní funkce
- Vysoké technické schopnosti, nízké administrativní schopnosti
- Výborná znalost PHP/Nette/Doctrine stacku

**Současné chování a workflows:**
- Komunikace s klienty přes Slack, email, telefon - chaoticky
- Tracking úkolů v GitLabu - když si vzpomenu
- Časový tracking v Clockify - konzistentně (nutné pro fakturaci)
- Fakturace týdně - mechanicky funguje
- Placení účtů - když přijde upomínka nebo náhodně

**Specifické potřeby a bolesti:**
- Potřebuje systém, který funguje BEZ mé aktivní účasti
- Musí překonat prokrastinaci kolem financí
- Potřebuje vizuální a fyzické připomínky (digitální ignoruji)
- Chce rychlé akce s minimálním friction (2 kliky max)
- Potřebuje follow-up připomínky po deploymentech

**Cíle:**
- Nikdy neplatit pokuty a penále
- Vybudovat finanční buffer a postupně splatit dluhy
- Nezapomínat na pracovní úkoly a follow-ups
- Snížit stres z financí na minimum

### Secondary User Segment: Manželka

**Profil:**
- Spolupracující partner v domácnosti
- Sdílený přístup k rodinným financím
- Motivace vidět zlepšení finanční situace

**Současné chování:**
- Příležitostně připomíná důležité platby
- Frustrovaná z opakujících se finančních problémů
- Ochotná participovat na řešení

**Specifické potřeby:**
- Přehled o finanční situaci domácnosti
- Jednoduchost použití - není technik
- Možnost vidět progress a zlepšení
- Společné cíle a motivace

**Cíle:**
- Mít klid od finančních starostí
- Vidět postupné zlepšování situace
- Společně budovat finanční jistotu

## Goals & Success Metrics

### Business Objectives

- **Eliminovat pokuty a penále do 2 měsíců** - z současných 500-2000+ Kč/měsíc na 0 Kč
- **Vybudovat týdenní finanční buffer do 6 měsíců** - začít s 500 Kč, každý týden +10%
- **Snížit dobu mezi příjmem úkolu a zapsáním do GitLabu** - z dnů/týdnů na max 24 hodin
- **Automatizovat 100% follow-up tasků** - žádné zapomenuté post-deploy kontroly
- **Začít systematické splácení dluhů do 3 měsíců** - minimálně 5% měsíčního příjmu na splátky

### User Success Metrics

- **Denní interakce se systémem** - alespoň 1x denně ranní check (měřeno jako streak)
- **Rychlost placení faktur** - od notifikace k zaplacení < 48 hodin
- **Pocit kontroly nad financemi** - subjektivní hodnocení 1-10 (týdně)
- **Redukce finančního stresu** - méně konfliktů v domácnosti kvůli penězům
- **Úspěšnost follow-up kontrol** - 100% deploymentů zkontrolováno podle plánu

### Key Performance Indicators (KPIs)

- **Pokuta Rate**: 0 pokut/měsíc (současně 2-5)
- **Buffer Growth**: +10% týdně až do cílové částky
- **Task Capture Rate**: 95%+ úkolů zachyceno do 24h
- **Daily Active Usage**: 7-day streak minimálně 80% času
- **QR Payment Usage**: 80%+ plateb přes QR (vs. manuální platby)
- **Follow-up Completion**: 100% follow-up tasků dokončeno včas
- **Debt Reduction Rate**: Minimálně -5% dlužné částky měsíčně
- **System Uptime**: 99%+ dostupnost (kritické pro daily habit)

## MVP Scope

### Core Features (Must Have)

- **Email Hub Infrastructure:** Dedikovaná schránka, cron každou minutu, základní parser pro bankovní výpisy a známé formáty
- **Finanční Dashboard:** Přehled všech plateb k úhradě, zobrazení deadlinů, aktuální stav účtů
- **QR Kód Generátor:** Okamžité generování QR kódů pro všechny platby k úhradě, batch zobrazení
- **Základní Kategorizace:** Jednorázové/opakované, firemní/osobní platby, automatické + ruční fallback
- **GitLab/Slack Monitor:** Kontrola nových úkolů, diff proti GitLab issues, seznam nezapsaných položek
- **Follow-up Engine:** Automatické generování follow-up tasků z commit messages (fix/feat/BREAKING)
- **Týdenní Fakturace:** Automatické generování faktur z Clockify, email klientům
- **ADHD Bypass v1:** Minimálně 2 strategie - ranní blokace GitLabu + vizuální dashboard
- **Cash Flow Tracker:** Kolik přijde (Clockify), kolik musí odejít, kolik zbývá na buffer
- **Detekce duplicit:** Kontrola před vytvořením QR, označení podezřelých k ruční kontrole
- **Základní audit trail:** Log důležitých operací pro debugging

### Out of Scope for MVP

- LLM integrace pro inteligentní parsování
- Pokročilé ADHD strategie (e-ink display, wallpaper engine, atd.)
- Oddlužení optimizer
- Investiční modul
- Prediktivní analytics
- Mobilní aplikace (jen responzivní web)
- Integrace s bankovními API (jen email výpisy)
- Multi-language support
- Kompletní audit trail s verzováním
- Automatické platby (jen QR kódy)
- OCR pro screenshoty faktur

### MVP Success Criteria

Systém je úspěšný, pokud během prvního měsíce:
1. Zachytí 90%+ plateb k úhradě
2. Eliminuje alespoň 50% pokut
3. Použiju ho minimálně 5 dní v týdnu
4. Všechny nové úkoly jsou v GitLabu do 24h
5. Manželka pochopí a použije dashboard bez mé pomoci

## Post-MVP Vision

### Phase 2 Features (3-6 měsíců)

**Inteligentní zpracování:**
- LLM integrace (OpenAI/Claude API) pro parsování neznámých dokumentů
- Automatická extrakce dat z PDF faktur a upomínek
- Chytré kategorizace na základě historie
- OCR pro screenshoty faktur

**ADHD Bypass Expansion:**
- E-ink display s Raspberry Pi - fyzický dashboard u kávovaru
- Browser extension - new tab hijack s financemi
- Wallpaper engine integrace - měnící se pozadí podle urgence
- Smart watch notifikace s vibrací

**Pokročilé finanční features:**
- Oddlužení optimizer - avalanche vs. snowball strategie
- Predikce cash flow na základě historie
- "What-if" scénáře - "Kdybys tento měsíc ušetřil X..."
- Automatické upozornění na podezřelé transakce

### Long-term Vision (1-2 roky)

**Kompletní životní autopilot:**
- Rozšíření na další oblasti (trénink, jídlo, návyky)
- AI-powered finanční poradce učící postupně finanční gramotnost
- Automatické vyjednávání s věřiteli (šablony, tracking komunikace)
- Investiční modul - po splacení dluhů automatický přechod na wealth building

**Integrace a automatizace:**
- Přímé napojení na bankovní API
- Two-way sync s GitLabem
- Automatické generování reportů pro účetní
- Voice assistant integrace (Alexa/Google Home)

**Sociální a gamifikační prvky:**
- Společné finanční cíle s vizualizací progressu
- Achievement system s reálnými odměnami
- Rodinný finanční "health score"

### Expansion Opportunities

**Technické rozšíření:**
- API pro integraci s dalšími nástroji
- Webhook systém pro real-time události
- Backup a disaster recovery strategie
- Self-hosted LLM (Ollama) pro privacy

**Feature rozšíření:**
- Daňová optimalizace pro OSVČ
- Automatické hlídání slev a lepších tarifů
- Meal planning napojený na finance
- Habit tracker s finančními motivacemi

**Potenciální komercionalizace (pokud by fungovalo):**
- SaaS pro ADHD komunitu
- White-label řešení pro finanční poradce
- Integrace do terapeutických programů

## Technical Considerations

### Platform Requirements

- **Target Platforms:** Web aplikace (desktop-first, responsive pro mobil)
- **Browser/OS Support:** Chrome/Firefox poslední verze, Safari 15+, bez IE/Edge Legacy
- **Performance Requirements:** 
  - Dashboard load < 2 sekundy
  - Cron processing < 30 sekund per run
  - QR generování < 500ms
  - 99% uptime (kritické pro daily habits)

### Technology Preferences

- **Frontend:** 
  - Nette Forms + Latte templates (žádný SPA v MVP)
  - Alpine.js pro interaktivitu
  - Tailwind CSS pro styling
  - Chart.js pro vizualizace
  
- **Backend:**
  - PHP 8.4+ s Nette Framework (nejnovější verze)
  - Doctrine ORM přes Contributte/Doctrine
  - Contributte/Scheduler pro cron management
  - Contributte/Mailing pro emaily
  - Contributte/PSR pro PSR standards
  - Contributte/API-Router pro API endpoints
  
- **Database:**
  - PostgreSQL 14+ (hlavní databáze)
  - Redis pro cache a queues (přes Contributte/Redis)
  
- **Hosting/Infrastructure:**
  - Kubernetes cluster (Rancher 2)
  - Docker containers pro všechny služby
  - Helm charts pro deployment
  - Ingress controller pro routing
  - Cert-manager pro SSL

### Architecture Considerations

- **Repository Structure:**
  - Monorepo - vše v jednom repository
  - Standard Nette struktura (app/, www/, temp/, log/)
  - Modular organizace (app/Modules/Finance/, app/Modules/Work/)
  - Kubernetes manifests v k8s/ složce
  
- **Service Architecture:**
  - Monolith s moduly (ne microservices)
  - Event-driven komunikace mezi moduly
  - Repository pattern pro data access
  - Service layer pro business logiku
  - Health checks pro K8s liveness/readiness probes
  
- **Integration Requirements:**
  - IMAP/POP3 pro email fetching (přes Contributte packages)
  - GitLab API (REST)
  - Slack API (Web API)
  - Clockify API (REST)
  - LLM API (OpenAI/Claude) - switchable provider
  - QR payment standard (SPAYD/EPC)
  
- **Security/Compliance:**
  - 2FA autentizace
  - Šifrování citlivých dat (bankovní info)
  - HTTPS only (terminace na Ingress)
  - Rate limiting pro API
  - K8s secrets pro citlivé konfigurace
  - Backup strategie (persistent volumes + snapshots)
  - GDPR není nutné (osobní použití)

## Constraints & Assumptions

### Constraints

- **Budget:** Neomezený čas na development (osobní projekt), hosting už mám (Rancher cluster)
- **Timeline:** Flexibilní, ale MVP ideálně do 2 měsíců kvůli rostoucím pokutám
- **Resources:** Jeden developer (já), příležitostné testování manželkou
- **Technical:** 
  - Omezení na technologie které dobře znám (PHP/Nette/Doctrine)
  - Žádný přístup k bankovním API (pouze email parsing)
  - Závislost na třetích stranách (GitLab, Slack, Clockify API)

### Key Assumptions

- Email jako univerzální integrace bude fungovat pro 90%+ případů
- Bankovní výpisy budou chodit v parsovatelném formátu
- GitLab a Slack API zůstanou stabilní a zpětně kompatibilní
- Clockify data budou vždy aktuální a správná (základ pro fakturaci)
- Kombinace ADHD bypass strategií bude účinnější než jednotlivé přístupy
- Manželka bude ochotná používat systém pravidelně
- QR platební standard bude fungovat se všemi českými bankami
- Jeden cron běžící každou minutu zvládne vše potřebné zpracovat
- K8s cluster bude stabilní a nepotřebuje konstantní maintenance
- Ruční fallback pro kategorizaci bude potřeba max v 20% případů
- Gamifikační prvky udrží motivaci dlouhodobě
- Vizuální indikátory urgence budou dostatečně "otravné" aby fungovaly

## Risks & Open Questions

### Key Risks

- **Email parsing fragilita:** Banky mohou změnit formát emailů bez varování, což rozbije automatické zpracování
- **ADHD bypass strategie nemusí fungovat:** Teoretické koncepty nemusí v praxi překonat prokrastinaci
- **API rate limits:** GitLab/Slack/Clockify mohou omezit počet requestů, zejména při častém pollingu
- **Overwhelm z notifikací:** Příliš agresivní ADHD strategie mohou vést k úplnému vypnutí systému
- **Single point of failure:** Pokud email schránka nebude dostupná, celý systém se zastaví
- **Manželka přestane používat:** Pokud UI nebude intuitivní, ztratíme sociální tlak komponentu
- **K8s komplexita:** Problémy s clusterem mohou zabrat čas místo vývoje features
- **Scope creep:** Tendence přidávat features místo řešení core problémů
- **Duplicitní platby:** Stejná platba může přijít vícekrát (předpis + upomínka) - řešení: detekce duplicit před vytvořením QR, označení k ruční kontrole

### Open Questions

- Který LLM provider bude nejlepší pro parsování českých dokumentů?
- Jak přesně měřit úspěšnost ADHD bypass strategií?
- Kolik historických dat potřebujeme pro smysluplné predikce?
- Jak nastavit ideální agresivitu notifikací - aby fungovaly ale neobtěžovaly?
- Jak řešit případ, kdy Clockify data nesedí s realitou?
- Jaká pravidla pro detekci duplicitních plateb? (částka + variabilní symbol? částka + účet + ±7 dní?)

### Areas Needing Further Research

- **SPAYD/EPC QR formáty** - kompatibilita napříč českými bankami
- **Email parsing libraries** - nejvhodnější pro český banking
- **ADHD productivity patterns** - akademické studie o efektivních intervencích
- **Kubernetes CronJob vs. application scheduler** - co je spolehlivější
- **LLM prompt engineering** - specificky pro finanční dokumenty v češtině (Phase 2)
- **Algoritmy detekce duplicit** - jak chytře identifikovat stejné platby
- **Progressive Web App možnosti** - pro budoucí mobilní notifikace
- **Právní aspekty** - automatická fakturace, archivace finančních dat

## Appendices

### A. Research Summary

**Z brainstorming session (dnes):**
- 45+ konkrétních nápadů pro features a implementaci
- Identifikované ADHD bypass strategie (20+ různých přístupů)
- Technická architektura a data flow
- Prioritizace features do Immediate/Future/Moonshot kategorií

**Klíčové insights:**
- ADHD vyžaduje multi-pronged approach - jedna strategie nestačí
- Email jako univerzální hub je nejjednodušší integrace
- Automatizace musí mít lidský fallback
- Gamifikace a vizuální feedback jsou kritické pro udržení návyku

### B. Stakeholder Input

**Není aplikovatelné** - osobní projekt bez externích stakeholderů

### C. References

**Technické zdroje:**
- [Contributte packages](https://contributte.org/) - Nette integrace
- [SPAYD specifikace](https://qr-platba.cz/pro-vyvojare/) - QR platby standard
- [GitLab API docs](https://docs.gitlab.com/ee/api/)
- [Slack Web API](https://api.slack.com/web)
- [Clockify API docs](https://docs.clockify.me/)

**Existující dokumenty projektu:**
- `docs/brainstorming-session-results.md` - Kompletní výstup z brainstorming session
- `.bmad-core/workflows/greenfield-fullstack.yaml` - Workflow template

## Next Steps

### Immediate Actions

1. Dokončit tento Project Brief a uložit jako `docs/project-brief.md`
2. Přejít na PM agenta pro vytvoření PRD na základě tohoto briefu
3. Založit dedikovanou email schránku pro systém
4. Inicializovat Git repository a základní Nette projekt struktura
5. Nastavit K8s namespace a základní Helm chart

### PM Handoff

This Project Brief provides the full context for **KairoFlow** - osobní AI asistent pro finanční a pracovní management specificky navržený pro ADHD. 

Please start in 'PRD Generation Mode', review the brief thoroughly to work with the user to create the PRD section by section as the template indicates, asking for any necessary clarification or suggesting improvements.

**Key focus areas for PRD:**
- User stories reflecting ADHD-specific needs
- Technical implementation details for email parsing
- ADHD bypass strategies implementation
- Integration points (GitLab, Slack, Clockify)
- Clear acceptance criteria for each feature