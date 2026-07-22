# Database Schema Overview

This document defines the complete database schema for Agni Studio AI's Content Intelligence Engine, supporting the Observe-Understand-Recommend-Execute-Learn framework.

## Core Entities

### 1. Users (authentication)
Stores creator account information.

`sql
Table: users
Columns:
- id (UUID, PK)
- email (VARCHAR, unique)
- password_hash (VARCHAR)
- first_name (VARCHAR)
- last_name (VARCHAR)
- is_active (BOOLEAN)
- is_verified (BOOLEAN)
- email_verified_at (TIMESTAMP, nullable)
- last_login_at (TIMESTAMP, nullable)
- created_at (TIMESTAMP)
- updated_at (TIMESTAMP)
Indexes:
- PRIMARY KEY (id)
- UNIQUE KEY email_unique (email)
- INDEX idx_users_active (is_active)
`

### 2. Workspaces
Collaborative spaces where creators organize their content.

`sql
Table: workspaces
Columns:
- id (UUID, PK)
- owner_id (UUID, FK to users.id)
- name (VARCHAR)
- slug (VARCHAR, unique)
- description (TEXT, nullable)
- is_active (BOOLEAN)
- settings (JSONB) - timezone, dateFormat, notificationPreference, defaultView
- created_at (TIMESTAMP)
- updated_at (TIMESTAMP)
Indexes:
- PRIMARY KEY (id)
- UNIQUE KEY workspaces_slug_unique (slug)
- INDEX idx_workspaces_owner (owner_id)
- INDEX idx_workspaces_active (is_active)
`

### 3. Workspace Members
Tracks who belongs to which workspace and their role.

`sql
Table: workspace_members
Columns:
- id (UUID, PK)
- workspace_id (UUID, FK to workspaces.id)
- user_id (UUID, FK to users.id)
- role (ENUM: 'owner', 'admin', 'member', 'viewer')
- is_active (BOOLEAN)
- joined_at (TIMESTAMP)
- left_at (TIMESTAMP, nullable)
- created_at (TIMESTAMP)
- updated_at (TIMESTAMP)
Indexes:
- PRIMARY KEY (id)
- UNIQUE KEY workspace_members_user_workspace_unique (workspace_id, user_id)
- INDEX idx_wm_workspace (workspace_id)
- INDEX idx_wm_user (user_id)
- INDEX idx_wm_role (role)
- INDEX idx_wm_active (is_active, left_at IS NULL)
`

### 4. Projects
Content campaigns or series within a workspace.

`sql
Table: projects
Columns:
- id (UUID, PK)
- workspace_id (UUID, FK to workspaces.id)
- name (VARCHAR)
- slug (VARCHAR)
- description (TEXT, nullable)
- status (ENUM: 'active', 'archived', 'completed')
- metadata (JSONB) - color, icon, createdBy
- created_at (TIMESTAMP)
- updated_at (TIMESTAMP)
Indexes:
- PRIMARY KEY (id)
- UNIQUE KEY projects_workspace_slug_unique (workspace_id, slug)
- INDEX idx_projects_workspace (workspace_id)
- INDEX idx_projects_status (status)
`

### 5. Content Ideas
Raw concepts for content that go through the Intelligence Engine.

`sql
Table: content_ideas
Columns:
- id (UUID, PK)
- project_id (UUID, FK to projects.id)
- title (VARCHAR)
- description (TEXT)
- content_type (VARCHAR) - youtube_video, blog_post, tweet_thread, etc.
- status (ENUM: 'new', 'reviewed', 'in_progress', 'approved', 'rejected')
- priority (INTEGER 1-5, where 1 is highest)
- tags (TEXT ARRAY)
- opportunity_score (INTEGER 0-100, nullable)
- opportunity_confidence (ENUM: 'high', 'medium', 'low', nullable)
- metadata (JSONB) - estimatedEffort, targetAudience, keywords, sources
- created_at (TIMESTAMP)
- updated_at (TIMESTAMP)
- scheduled_for (TIMESTAMP, nullable)
Indexes:
- PRIMARY KEY (id)
- INDEX idx_ideas_project (project_id)
- INDEX idx_ideas_status (status)
- INDEX idx_ideas_priority (priority)
- INDEX idx_ideas_opportunity_score (opportunity_score) WHERE opportunity_score IS NOT NULL
- INDEX idx_ideas_type (content_type)
`

### 6. Content Items
Developed content in various states (draft, scheduled, published, etc.).

`sql
Table: content_items
Columns:
- id (UUID, PK)
- idea_id (UUID, FK to content_ideas.id)
- workspace_id (UUID, FK to workspaces.id)
- title (VARCHAR)
- content (TEXT)
- format (ENUM: 'article', 'video', 'short_video', 'podcast', 'post', 'story', 'thread', 'email', 'newsletter')
- status (ENUM: 'draft', 'scheduled', 'published', 'failed', 'archived')
- published_at (TIMESTAMP, nullable)
- metadata (JSONB) - wordCount, readingTime, tags, platforms, seo (title, description, keywords)
- created_at (TIMESTAMP)
- updated_at (TIMESTAMP)
Indexes:
- PRIMARY KEY (id)
- INDEX idx_items_idea (idea_id)
- INDEX idx_items_workspace (workspace_id)
- INDEX idx_items_status (status)
- INDEX idx_items_format (format)
- INDEX idx_items_published (published_at) WHERE published_at IS NOT NULL
`

### 7. Opportunity Scores
Detailed breakdown of how each Opportunity Score was calculated.

`sql
Table: opportunity_scores
Columns:
- id (UUID, PK)
- idea_id (UUID, FK to content_ideas.id)
- score (INTEGER 0-100)
- trend_strength (INTEGER 0-25)
- audience_relevance (INTEGER 0-20)
- competitive_gap (INTEGER 0-20)
- seasonal_timing (INTEGER 0-15)
- content_format_fit (INTEGER 0-10)
- historical_prediction (INTEGER 0-10)
- confidence_level (ENUM: 'high', 'medium', 'low')
- supporting_evidence (JSONB) - sources, data points, rationale
- calculated_at (TIMESTAMP)
- valid_until (TIMESTAMP) - when score should be recalculated
Indexes:
- PRIMARY KEY (id)
- UNIQUE KEY opportunity_scores_idea_unique (idea_id)
- INDEX idx_opportunity_score (score)
- INDEX idx_opportunity_confidence (confidence_level)
- INDEX idx_opportunity_valid (valid_until)
`

### 8. Analytics Events
Performance data from connected platforms.

`sql
Table: analytics_events
Columns:
- id (UUID, PK)
- content_item_id (UUID, FK to content_items.id)
- platform (ENUM: 'youtube', 'tiktok', 'instagram', 'facebook', 'twitter')
- event_type (ENUM: 'view', 'like', 'comment', 'share', 'save', 'click', 'impression')
- event_value (INTEGER) - count of events
- event_timestamp (TIMESTAMP)
- metadata (JSONB) - device, geography, traffic_source, etc.
- recorded_at (TIMESTAMP)
Indexes:
- PRIMARY KEY (id)
- INDEX idx_analytics_content (content_item_id)
- INDEX idx_analytics_platform (platform)
- INDEX idx_analytics_type (event_type)
- INDEX idx_analytics_time (event_timestamp)
- INDEX idx_analytics_recorded (recorded_at)
`

### 9. AI Interactions
Tracks AI usage for learning and improvement.

`sql
Table: ai_interactions
Columns:
- id (UUID, PK)
- user_id (UUID, FK to users.id)
- interaction_type (ENUM: 'idea_generation', 'research', 'writing', 'thumbnail', 'analysis')
- prompt_text (TEXT)
- response_text (TEXT)
- model_used (VARCHAR)
- tokens_used (INTEGER)
- cost_usd (DECIMAL)
- user_feedback (ENUM: 'helpful', 'neutral', 'unhelpful', nullable)
- created_at (TIMESTAMP)
Indexes:
- PRIMARY KEY (id)
- INDEX idx_ai_user (user_id)
- INDEX idx_ai_type (interaction_type)
- INDEX idx_ai_created (created_at)
- INDEX idx_ai_feedback (user_feedback) WHERE user_feedback IS NOT NULL
`

### 10. Trends
Tracks trending topics over time for the Observe phase.

`sql
Table: trends
Columns:
- id (UUID, PK)
- platform (ENUM: 'google', 'youtube', 'tiktok', 'instagram', 'facebook', 'twitter')
- keyword (VARCHAR)
- category (VARCHAR, nullable)
- score (FLOAT) - platform-specific trend value
- timestamp (TIMESTAMP)
- metadata (JSONB) - related_queries, geographic_data, demographic_data
Indexes:
- PRIMARY KEY (id)
- UNIQUE KEY trends_platform_keyword_timestamp_unique (platform, keyword, timestamp)
- INDEX idx_trends_platform (platform)
- INDEX idx_trends_keyword (keyword)
- INDEX idx_trends_time (timestamp)
- INDEX idx_trends_score (score)
`

### 11. Recommendations
Tracks what was recommended to users and the outcomes.

`sql
Table: recommendations
Columns:
- id (UUID, PK)
- user_id (UUID, FK to users.id)
- recommendation_type (ENUM: 'content_idea', 'optimal_time', 'thumbnail', 'title', 'format')
- entity_id (UUID) - references content_ideas.id, content_items.id, etc. depending on type
- recommendation_data (JSONB) - the actual recommendation
- was_accepted (BOOLEAN, nullable)
- outcome_metrics (JSONB) - actual performance if acted upon
- created_at (TIMESTAMP)
- acted_at (TIMESTAMP, nullable)
Indexes:
- PRIMARY KEY (id)
- INDEX idx_recommendations_user (user_id)
- INDEX idx_recommendations_type (recommendation_type)
- INDEX idx_recommendations_entity (entity_id)
- INDEX idx_recommendations_accepted (was_accepted) WHERE was_accepted IS NOT NULL
- INDEX idx_recommendations_created (created_at)
`

## Relationships Summary

1. Users 1:M Workspace Members (owner)
2. Users 1:M Workspace Members (members)
3. Workspaces 1:M Workspace Members
4. Workspaces 1:M Projects
5. Projects 1:M Content Ideas
6. Content Ideas 1:M Content Items
7. Content Ideas 1:1 Opportunity Scores (optional)
8. Content Items 1:M Analytics Events
9. Users 1:M AI Interactions
10. Users 1:M Recommendations
11. Content Ideas M:1 Projects (through project_id)
12. Content Items M:1 Content Ideas (through idea_id)
13. Content Items M:1 Workspaces (through workspace_id)

## Partitioning Strategy (for scale)

For high-volume tables:
- **analytics_events**: Partition by month (range partitioning on event_timestamp)
- **ai_interactions**: Partition by month (range partitioning on created_at)
- **trends**: Partition by month (range partitioning on timestamp)

## Security Considerations

1. Row-Level Security (RLS) policies should enforce:
   - Users can only access their own data
   - Workspace members can only access workspace data based on role
   - Owners/admins have full access to workspace data

2. Encryption at rest for sensitive fields:
   - email
   - payment information (if added later)

3. Audit trails for:
   - Permission changes
   - Data exports
   - Critical configuration changes

This schema supports the full Content Intelligence Engine lifecycle while maintaining flexibility for future extensions.