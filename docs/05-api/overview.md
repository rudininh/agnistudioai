# API Conventions

All public endpoints will be versioned below `/api/v1`. Laravel Form Requests validate input and API Resources serialize output.

Collection endpoints use `page` and `per_page` pagination. Filtering uses `filter[field]=value`, sorting uses `sort=field` or `sort=-field`, and free-text lookup uses `search=value`. Responses return data under `data` and pagination metadata under `meta`.

Mutating endpoints use standard REST semantics: `POST` creates, `PATCH` updates, and `DELETE` removes. Domain and validation errors use Laravel's JSON error response format, with stable machine-readable error codes added for business-rule failures.
