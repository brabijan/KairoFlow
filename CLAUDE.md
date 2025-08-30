# KairoFlow Development Workflow

## Hybrid BMAD + Archon Knowledge Integration

This project uses a hybrid approach combining:
- **BMAD Method** for story-driven development with local files (docs/stories/, docs/prd.md, docs/architecture.md)
- **Archon MCP Server** for knowledge queries and code examples (NOT for task management)
- **TodoWrite** for local task tracking during implementation

### CRITICAL: Workflow Priority
1. Read BMAD stories from local filesystem (docs/stories/)
2. Use TodoWrite for task tracking during development
3. Use Archon ONLY for knowledge queries and research

## Archon Knowledge Integration

### What Archon IS used for:
- `perform_rag_query()` - Query best practices, patterns, and documentation
- `search_code_examples()` - Find implementation examples and patterns
- `get_available_sources()` - List available knowledge sources

### What Archon is NOT used for:
- ❌ Task management (use TodoWrite instead)
- ❌ Story storage (use local docs/stories/)
- ❌ Project tracking (use BMAD workflow)

### Knowledge Query Examples:
```bash
# Before implementing any feature - research patterns
archon:perform_rag_query("React hooks best practices", match_count=5)
archon:search_code_examples("JWT authentication Express.js", match_count=3)

# Architecture decisions
archon:perform_rag_query("microservices vs monolith tradeoffs", match_count=5)

# Debugging help
archon:perform_rag_query("TypeScript generic type inference error", match_count=3)
```

## Story-Driven Development Workflow

### 1. Story Pickup (BMAD)
```bash
# List available stories
ls docs/stories/*.md | grep -v completed

# Read selected story
cat docs/stories/{epic}.{story}.md
```

### 2. Create Local Task List
```bash
# Parse story and create TodoWrite tasks
TodoWrite([
  {content: "Analyze story requirements", status: "in_progress"},
  {content: "Research implementation patterns", status: "pending"},
  {content: "Implement core functionality", status: "pending"},
  {content: "Write unit tests", status: "pending"},
  {content: "Write integration tests", status: "pending"},
  {content: "Run QA review", status: "pending"},
  {content: "Update documentation", status: "pending"}
])
```

### 3. Research Phase (Archon Knowledge)
```bash
# Use Archon ONLY for knowledge queries
archon:perform_rag_query("{technology} architecture patterns", match_count=5)
archon:search_code_examples("{specific feature} implementation", match_count=3)

# Example for authentication story:
archon:perform_rag_query("JWT refresh token security best practices", match_count=5)
archon:search_code_examples("Express.js JWT middleware", match_count=3)
```

### 4. Implementation (Local Development)
- Follow BMAD story acceptance criteria
- Update TodoWrite status as you progress
- Keep all code, tests, and documentation local
- Reference patterns found via Archon queries

### 5. QA Review (BMAD)
```bash
# Run BMAD QA commands
@qa *review {story}

# Results saved locally to docs/qa/
# Gate files in docs/qa/gates/
# Assessments in docs/qa/assessments/
```

## Daily Development Routine

### Start of Day:
1. **Check local stories:**
   ```bash
   ls -la docs/stories/*.md | grep -E "(todo|in-progress)"
   ```

2. **Pick story to work on:**
   ```bash
   # Read the story content
   cat docs/stories/{selected-story}.md
   ```

3. **Create TodoWrite list from story:**
   - Parse acceptance criteria
   - Create implementation tasks
   - Add testing tasks
   - Include documentation updates

### During Development:
1. **Research via Archon knowledge:**
   ```bash
   archon:perform_rag_query("implementation pattern for {feature}")
   archon:search_code_examples("{technology} {pattern}")
   ```

2. **Implement following local story requirements**
   - Use patterns discovered via Archon
   - Follow BMAD coding standards (docs/architecture/coding-standards.md)
   - Update TodoWrite status after each subtask

3. **Test incrementally:**
   - Write tests as specified in story
   - Use Archon to find testing patterns if needed

### End of Day:
1. **Update TodoWrite items:**
   - Mark completed tasks as done
   - Update in-progress items
   - Note any blockers

2. **Update story status locally:**
   ```bash
   # Edit story file to update status
   # Status: Draft → Approved → In Progress → Review → Complete
   ```

3. **Commit changes:**
   ```bash
   git add .
   git commit -m "feat: {story-title} - {progress-description}"
   ```

## Optional: Lightweight Story References in Archon

If you want to track which stories exist (metadata only, not content):
```bash
# Create reference document (optional)
archon:create_document(
  document_type="story-reference",
  title="{epic}.{story}",
  content={
    "local_path": "docs/stories/{epic}.{story}.md",
    "status": "in-progress",
    "epic": "{epic}",
    "priority": "P0"
  }
)
```
**Note:** This is ONLY for reference/discovery. All actual work uses local files.

## BMAD Method Integration

### Story Structure (Local Files)
Stories remain in `docs/stories/` with standard BMAD format:
- Epic assignment
- User story
- Acceptance criteria
- Implementation notes
- Priority (P0, P1, P2)
- Status tracking

### Document Hierarchy
```
docs/
├── prd.md                 # Product Requirements (BMAD)
├── architecture.md        # Technical Architecture (BMAD)
├── stories/              # User stories (BMAD)
│   ├── {epic}.{story}.md
│   └── ...
├── epics/               # Epic definitions (BMAD)
├── qa/                  # QA results (BMAD)
│   ├── assessments/
│   └── gates/
└── architecture/        # Sharded architecture docs
    ├── coding-standards.md
    ├── tech-stack.md
    └── source-tree.md
```

### Research-Driven Development Standards

#### Before Any Implementation:
**Research checklist using Archon:**
- [ ] Query existing patterns: `perform_rag_query("{pattern} best practices")`
- [ ] Find code examples: `search_code_examples("{feature} implementation")`  
- [ ] Check security implications: `perform_rag_query("{feature} security risks")`
- [ ] Look for antipatterns: `perform_rag_query("{pattern} common mistakes")`

#### Knowledge Query Strategy:
1. Start with broad architectural queries
2. Narrow down to specific implementation details
3. Cross-reference multiple sources for validation
4. Keep match_count low (3-5) for focused results

### Quality Assurance Integration

#### BMAD QA Commands:
```bash
# Risk assessment (before development)
@qa *risk {story}

# Test design (before development)
@qa *design {story}

# Requirements tracing (during development)
@qa *trace {story}

# NFR validation (before review)
@qa *nfr {story}

# Full review (after development)
@qa *review {story}

# Update gate status (after fixes)
@qa *gate {story}
```

All QA results are stored locally in `docs/qa/`

## Important Reminders

### Do's:
✅ Use local BMAD stories for requirements
✅ Use TodoWrite for task tracking
✅ Use Archon for knowledge queries only
✅ Keep all project files local
✅ Follow BMAD development cycle

### Don'ts:
❌ Don't use Archon for task management
❌ Don't store stories in Archon (only references if needed)
❌ Don't skip local QA reviews
❌ Don't bypass BMAD workflow
❌ Don't commit without updating story status

## Summary

This hybrid approach gives you:
1. **Story-driven development** via local BMAD files
2. **Knowledge-powered implementation** via Archon queries
3. **Local task tracking** via TodoWrite
4. **Quality assurance** via BMAD QA tools
5. **Full traceability** via local documentation

The key is: **BMAD for process, Archon for knowledge, TodoWrite for tracking**.