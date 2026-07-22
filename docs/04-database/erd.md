# ERD: Initial Concept

`users` <- membership -> `workspaces`.

`workspaces` -> `content_items` -> `content_versions`, `ai_executions`, `publications` -> `metric_snapshots` -> `recommendations`.

Semua record domain bertenant memiliki `workspace_id`; relasi lintas workspace dilarang oleh database constraint dan policy aplikasi.
