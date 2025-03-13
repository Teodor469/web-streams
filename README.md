Project: Web Streams API
This project is a RESTful API for managing video streams, built using Laravel 8 with Sanctum authentication. It provides full CRUD functionality, user authentication, and an automated command for cleaning up expired streams.
Key Features & Implementation
Authentication (AuthController)
Implemented user registration, login, and logout with Laravel Sanctum.
Enforced ownership restrictions on streams.
Feature tests cover authentication scenarios.
Streams Management (StreamApiController)


CRUD operations on streams, with access control.
Filtering options for title, description, and type name in the index method.
Comprehensive feature tests to ensure API stability.
Middleware & Security


Custom Authenticate Middleware: Returns JSON response instead of redirecting when unauthenticated.
Route Protection: Applied auth:sanctum middleware where necessary.


Custom Artisan Command (streams:delete-expired) deletes expired streams.
Unit test ensures only expired streams are removed.
Testing


Feature tests for authentication, stream management, and authorization.
Unit test for expired stream deletion.
