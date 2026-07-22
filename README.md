# Agni Studio AI

Agni Studio AI is an AI Content Operating System for taking creators from an idea to published content and performance insight.

## Repository layout

- `apps/api` - Laravel API and asynchronous application services.
- `apps/web` - React and TypeScript creator workspace.
- `apps/docs` - Product, architecture, and API documentation.
- `packages` - Shared UI, prompts, types, and SDK contracts.
- `infrastructure` - Local and deployable service configuration.
- `workflows` - Automation definitions for n8n and Hermes.

## Local development

The API remains a standalone Laravel application:

```powershell
cd apps/api
php artisan serve
```

Install JavaScript dependencies at repository root, then run the web workspace:

```powershell
npm install
npm run web:dev
```

Start PostgreSQL and Redis for local development with:

```powershell
docker compose --env-file infrastructure/docker/.env.example -f infrastructure/docker/compose.dev.yml up -d
```

Read [the architecture overview](apps/docs/architecture.md), [API conventions](apps/docs/api.md), and [engineering roadmap](apps/docs/roadmap.md) before adding a new cross-application capability.
