# Epic 9: GitLab/Slack Integration

## Story 9.1: Slack Message Monitor

As a system,
I want to monitor Slack for work tasks,
so that nothing gets forgotten.

### Acceptance Criteria

1: Slack API token configuration
2: Monitor configured channels for messages
3: Extract tasks from direct messages and mentions
4: Store Slack tasks with source link
5: Mark tasks as processed

## Story 9.2: GitLab Issue Sync

As a system,
I want to compare Slack tasks with GitLab,
so that I can identify ungrecorded work.

### Acceptance Criteria

1: GitLab API token configuration
2: Fetch issues from configured projects
3: Match Slack tasks to GitLab issues
4: Identify unmatched tasks
5: Daily sync with change detection

## Story 9.3: Task Gap Interface

As a user,
I want to see tasks not in GitLab,
so that I can record them properly.

### Acceptance Criteria

1: List of unmatched Slack/email tasks
2: One-click create GitLab issue
3: Bulk issue creation
4: Mark as "not a task" option
5: Task age and source display
