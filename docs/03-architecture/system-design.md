# System Design

## Application boundaries

`apps/web` owns the creator-facing React experience. `apps/api` owns authentication, business rules, persistence, queues, and external AI integrations. The web app communicates with the API through versioned REST endpoints.

Agni Platform primitive yang harus tetap product-neutral: identity, workspace tenancy, authorization, audit trail, AI execution, asset references, notifications, and API access. Studio owns creator-specific content domain logic.

## Shared packages

- `@agni/types`: transport-safe TypeScript contracts shared by the web app and SDK.
- `@agni/ui`: reusable visual primitives with no product-domain logic.
- `@agni/prompts`: versioned prompt templates and metadata.
- `@agni/sdk`: typed API client generated or maintained from the API contract.

Laravel PHP classes remain internal to `apps/api`; domain logic must not be duplicated in shared JavaScript packages.

## Backend direction

Organize new Laravel code by feature under `app/Domain/<Feature>`. Keep controllers thin, validate with Form Requests, centralize use cases in actions/services, and expose stable API responses through Resources. Use queued jobs for long-running AI and publishing tasks.

## Infrastructure

Infrastructure definitions are colocated by dependency. Docker Compose and environment templates will compose PostgreSQL, Redis, API workers, and the web app from `infrastructure/docker`.
Added content


## Content Intelligence Engine

The Content Intelligence Engine is the core AI-powered system that drives content recommendations and strategic guidance for creators. It operates through a continuous five-phase framework:

1. **Observe**: Collects data from multiple sources (Google Trends, social platforms, analytics, competitors)
2. **Understand**: Analyzes data in context of creator's channel, audience, and historical performance
3. **Recommend**: Generates content opportunities with Opportunity Scores (0-100) and clear rationale
4. **Execute**: Provides production assistance, content briefs, and workflow guidance
5. **Learn**: Continuously improves recommendations based on actual performance and feedback

The engine powers the Content Command Center dashboard and Opportunity Score system, ensuring all recommendations are data-driven, personalized, and action-oriented.


## Opportunity Score

A proprietary scoring algorithm (0-100) that quantifies the potential value of a content idea for a specific creator. The score helps prioritize content ideas by predicting which are most likely to succeed based on six weighted factors:

- Trend Strength (25%): How hot is the topic right now?
- Audience Relevance (20%): How well does it match your specific audience?
- Competitive Gap (20%): How underserved is this opportunity in your niche?
- Seasonal Timing (15%): How timely is this for upcoming events/seasons?
- Content Format Fit (10%): How suitable is this for your preferred content style?
- Historical Performance Prediction (10%): How did similar content perform in your past?

Scores are interpreted as:
- 85-100: ?? EXCELLENT OPPORTUNITY (Priority: HIGH)
- 70-84: ?? GOOD OPPORTUNITY (Priority: MEDIUM-HIGH)
- 55-69: ?? MODERATE OPPORTUNITY (Priority: MEDIUM)
- 40-54: ?? LOW OPPORTUNITY (Priority: LOW-MEDIUM)
- 0-39: ?? AVOID (Priority: LOW)



## Content Command Center

The central dashboard interface that transforms insights from the Content Intelligence Engine into actionable daily guidance. Rather than showing historical metrics, it focuses on what creators should do TODAY to move their channels forward.

Key components include:
- Personalized morning briefing with greeting and overview
- Today's Mission: Prioritized actionable content tasks with specific timing and performance predictions
- Opportunity Feed: Ranked content ideas by Opportunity Score
- Trend Radar: Real-time visualization of emerging relevant trends
- Content Calendar & Workflow View: Integrated scheduling and production tracking
- Performance Intelligence: Interpreted metrics with actionable insights
- Action Center: One-click actions based on insights
- Learning & Optimization Tips: Daily personalized recommendations

Designed with principles of action over information, contextual relevance, progressive disclosure, temporal awareness, and reduced cognitive load. The Command Center ensures creators spend less time deciding what to do and more time creating impactful content.
