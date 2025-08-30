# Epic 10: Follow-up Automation

## Story 10.1: Commit Message Scanner

As a system,
I want to scan commit messages,
so that I can generate follow-up tasks.

### Acceptance Criteria

1: Monitor GitLab commits via webhook or polling
2: Detect keywords: fix, feat, BREAKING, deploy
3: Calculate follow-up date based on keyword
4: Create follow-up task record
5: Link to original commit

## Story 10.2: Follow-up Task Generator

As a system,
I want to create follow-up reminders,
so that deployments are verified.

### Acceptance Criteria

1: Generate task 3 days after "deploy" commits
2: Include commit link and description
3: Assign to committer
4: Set appropriate priority
5: Integrate with notification system

## Story 10.3: Follow-up Dashboard

As a user,
I want to see pending follow-ups,
so that I don't forget post-deployment checks.

### Acceptance Criteria

1: List of pending follow-up tasks
2: Mark as complete functionality
3: Snooze for 24 hours option
4: Link to original work
5: Overdue follow-up alerts
