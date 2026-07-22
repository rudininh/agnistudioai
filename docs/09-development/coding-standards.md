# Coding Standards

## General
- Follow the language-specific style guides.
- Write clear, maintainable, and testable code.
- Use meaningful names for variables, functions, and classes.
- Keep functions small and focused on a single responsibility.
- Comment complex logic, but strive for self-documenting code.

## Backend (PHP/Laravel)
- Follow PSR-12 coding standard.
- Use Laravel's Eloquent ORM for database interactions.
- Keep controllers thin; move business logic to service classes or use cases.
- Use Form Requests for validation.
- Leverage Laravel's built-in features (events, queues, etc.) appropriately.
- Write unit and feature tests; aim for high coverage.

## Frontend (JavaScript/TypeScript, React)
- Use TypeScript for type safety.
- Follow Airbnb or standard JavaScript/TypeScript style guide with Prettier.
- Functional components with hooks; avoid class components unless necessary.
- Keep components small and reusable.
- Use state management (e.g., Redux, Context API) appropriately.
- Write unit and integration tests for components.

## Database
- Use migrations for schema changes.
- Seeders for test data.
- Index columns used in WHERE, JOIN, ORDER BY clauses.
- Use foreign keys to maintain referential integrity.
- Consider partitioning for large tables.

## API
- Follow RESTful conventions.
- Version APIs using URL versioning (e.g., /api/v1/).
- Use consistent error response format.
- Implement rate limiting.
- Secure endpoints with authentication and authorization.
- Document APIs with OpenAPI/Swagger.

## Security
- Validate and sanitize all inputs.
- Use prepared statements or ORM to prevent SQL injection.
- Escape outputs to prevent XSS.
- Implement proper authentication and authorization.
- Keep dependencies updated.

## CI/CD
- Run tests on every pull request.
- Use staging environment for pre-production testing.
- Automate deployment to production after manual approval.
- Monitor application and infrastructure.

## Documentation
- Comment public APIs and complex functions.
- Keep README up to date.
- Document architectural decisions.
