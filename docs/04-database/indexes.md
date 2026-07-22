# Index Strategy

Index semua foreign key. Composite index utama: `(workspace_id, status)`, `(workspace_id, scheduled_at)`, `(publication_id, captured_at)`, dan `(workspace_id, created_at)`. Unique constraint mencakup tenant, misalnya `(workspace_id, slug)`. Evaluasi query nyata sebelum menambahkan index baru.
