# Engineering Roadmap

## Foundation

- [x] Establish monorepo application boundaries.
- [x] Prepare React TypeScript workspace and Laravel API location.
- [x] Add local PostgreSQL and Redis service composition.
- [ ] Align the Laravel runtime configuration with the local Compose environment.
- [ ] Add CI for web typecheck/build and Laravel tests.

## Core Platform

- [ ] Define API v1 conventions, error schema, pagination, filtering, and sorting.
- [ ] Implement user identity, workspace tenancy, roles, and audit events.
- [ ] Add content idea, project, asset, and publishing domain models.
- [ ] Add job orchestration and observability for AI generation tasks.

## Creator Experience

- [ ] Establish the shared UI system with Tailwind and shadcn-compatible primitives.
- [ ] Build authenticated workspace, content pipeline, and analytics views.
- [ ] Publish a typed SDK from the versioned API contract.

## Reliability

- [ ] Add feature factories, API integration coverage, and queue job tests.
- [ ] Configure Horizon, Redis monitoring, backups, rate limits, and structured logs.
