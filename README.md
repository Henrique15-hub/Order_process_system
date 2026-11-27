# Order Processing API

A RESTful API for managing an order processing workflow, including user authentication, merchandise management, shopping carts, order creation, inventory management, and online payments through Stripe.

The project was developed with **Laravel**, focusing on separation of responsibilities, maintainable code, automated testing, and integration with external services.

## Features

### Authentication and User Management
- User account registration.
- Authentication using Laravel Sanctum.
- Token-based authentication.
- Account deletion.
- Automatic shopping cart creation when a new user registers.
- Role-based access control with `Admin` and `Client` roles.
- Administrative user management, including listing, viewing, updating, and deleting users.

### Merchandise Management
- Merchandise registration, updating, viewing, and deletion.
- Unique public identifiers generated using ULIDs.
- Stock quantity management.
- Validation to prevent duplicate merchandise entries.
- Administrative permissions for merchandise management.

### Shopping Cart
- Automatic cart creation for registered users.
- Add merchandise to the cart.
- Increase or decrease item quantities.
- Remove merchandise from the cart.
- Automatic calculation of the cart's total value.
- Stock availability validation when adding items.
- Cart status management (`Open` and `Closed`).

### Order Processing
- Order creation based on the authenticated user's cart.
- Validation to prevent orders with empty carts.
- Stock availability verification before payment creation.
- Order persistence with merchandise details and total value.
- Automatic cart closure during the checkout process.
- Payment status tracking.

### Payment Integration — Stripe

The project integrates with the **Stripe API** to process online payments.

Instead of relying on products previously registered in the Stripe Dashboard, the application dynamically builds checkout session line items using its own merchandise data.

The integration includes:

- Dynamic Checkout Session creation.
- Product names, prices, and quantities sent to Stripe.
- BRL currency configuration.
- Order and user identifiers sent through Stripe metadata.
- Payment URL generation and delivery to the client.
- Webhook endpoint for receiving Stripe events.
- Webhook signature verification.
- Handling of `checkout.session.completed` and `checkout.session.expired` events.
- Payment and order status updates based on payment events.

The Stripe integration is isolated behind a contract (`StripeClientInterface`), allowing the application to depend on an abstraction rather than directly coupling its business logic to the Stripe SDK.

## Architecture

The application follows a layered architecture, separating HTTP responsibilities, business rules, and data persistence.

### Dependency Inversion

The Stripe integration uses `StripeClientInterface` as an abstraction.

The concrete implementation is registered in Laravel's service container through `AppServiceProvider`, allowing the application to replace the external client implementation without changing the use cases that depend on it.

This also makes it possible to replace the real Stripe client with a mock during automated tests.

## Automated Tests

The project uses **PHPUnit** and Laravel's testing utilities to validate application behavior.

The test suite includes Feature Tests covering important application workflows.

### Tested Scenarios

- Users can create accounts.
- Users can delete their own accounts.
- User registration and account deletion.
- Admin authorization for user management operations.
- Prevention of regular users from listing, viewing, updating, or deleting other users.
- Admin users can list, view, update, and delete users.
- Automatic cart creation when a user is registered.
- Automatic cart deletion when its owner is deleted.
- Users can view and manage their own cart items.
- Adding, removing, and decreasing merchandise quantities.
- Prevention of adding quantities greater than the available stock.
- Prevention of removing more items than the cart contains.
- Correct calculation of cart totals when items are added or removed.
- Admin users cannot access or modify shopping carts.
- Admin users can create, update, and delete merchandise.
- Regular users cannot create, update, or delete merchandise.
- Merchandise can be viewed by users.
- Users can create orders from their shopping carts.
- Admin users cannot create orders.
- Shopping carts are closed after order creation.
- Payment records are created alongside orders.
- Payment links are generated during the order creation process.

### External API Mocking

The Stripe integration is mocked through `StripeClientInterface` using Mockery.

This allows tests to validate the application's integration behavior without making real requests to Stripe or requiring actual payment transactions.

The tests can verify the generated checkout payload, including line items and metadata, as well as the expected payment URL response.

### Running Tests

Run the complete test suite with:

```bash
php artisan test
```

The PHPUnit configuration uses an in-memory SQLite database, helping isolate tests from the application's regular database.

## Technologies

- **PHP 8.3+**
- **Laravel 13**
- **Laravel Sanctum**
- **Stripe PHP SDK**
- **SQLite**
- **PHPUnit**
- **Mockery**
- **Composer**

## How to use

### Requirements

- PHP 8.3 or higher.
- Composer.
- SQLite or another database supported by Laravel.
- A Stripe account and API credentials for real payment integration.

### 1. Clone the repository

```bash
git clone <repository-url>
cd <project-directory>
```

### 2. Install dependencies

```bash
composer install
```

### 3. Configure environment variables

Copy the example environment file:

```bash
cp .env.example .env
```

Generate the application key:

```bash
php artisan key:generate
```

Configure the database and Stripe credentials in your `.env` file.

Relevant Stripe variables:

```env
STRIPE_SECRET_KEY=
STRIPE_WEBHOOK_SECRET=

ACTIVE_PAYMENT_PROVIDER=stripe
```

`STRIPE_TEST_SESSION` is used by the automated tests as a test payment URL.

### 4. Run database migrations

```bash
php artisan migrate
```

### 5. Start the development server

```bash
php artisan serve
```

The API will be available at:

```text
http://127.0.0.1:8000
```

## Stripe Webhooks

For local development, Stripe webhook events must be forwarded to the application's webhook endpoint.

Endpoint:

```text
POST /api/stripe/webhook
```

Configure the webhook signing secret in `STRIPE_WEBHOOK_SECRET`.

The application verifies the `Stripe-Signature` header before processing incoming events.


