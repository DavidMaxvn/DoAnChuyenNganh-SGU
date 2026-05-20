# User Stories

## 1. Format

Moi story dung format:

```text
As a [Role], I want to [Action], so that [Benefit].
```

Acceptance Criteria dung mau:

```text
Whenever [condition/event], then [expected behavior].
```

## 2. Admin authentication

### ADM-01 Login admin

As an Admin, I want to log in with my admin account, so that I can manage products, orders and system data.

Acceptance Criteria:

- Whenever admin submits valid email and password, then the system shall authenticate with guard `admin` and redirect to admin dashboard.
- Whenever admin submits invalid credentials, then the system shall reject login and show an error message.
- Whenever an authenticated admin opens `/admin/index`, then the system shall show the dashboard.
- Whenever a guest opens a protected admin route, then the system shall redirect to admin login.

### ADM-02 Reset admin password

As an Admin, I want to reset my password, so that I can recover access when I forget it.

Acceptance Criteria:

- Whenever admin submits a registered email, then the system shall create a reset token and send reset instruction.
- Whenever reset token is invalid or expired, then the system shall reject the reset request.
- Whenever password reset succeeds, then the system shall update the hashed password and clear the token.

## 3. Product management

### PRD-01 Create root product

As an Admin, I want to create a root product, so that customers can see and buy it on the storefront.

Acceptance Criteria:

- Whenever admin submits valid product data, then the system shall store the product with name, price, quantity, category, image and status.
- Whenever product price is negative, then the system shall reject the request.
- Whenever product quantity is negative, then the system shall reject the request.
- Whenever product is active, then the storefront can show it in catalog.

### PRD-02 Create configurable product

As an Admin, I want to configure a product with attributes and variants, so that one product can represent multiple options.

Acceptance Criteria:

- Whenever admin configures shared attributes, then the system shall store them as product-level data.
- Whenever admin configures variant attributes, then the system shall use them to create child products.
- Whenever admin submits a duplicate variant combination, then the system shall reject the request.
- Whenever a variant is created successfully, then it shall reference the root product through `parent_id`.

### PRD-03 Manage product images

As an Admin, I want to upload product images, so that customers can inspect the product before buying.

Acceptance Criteria:

- Whenever admin uploads a valid image, then the system shall save it in product image storage and show preview.
- Whenever image type or size is invalid, then the system shall reject the upload.
- Whenever product has gallery images, then product detail shall display them.

## 4. Storefront discovery

### STO-01 View home page

As a Guest, I want to view the home page, so that I can discover categories, banners and featured products.

Acceptance Criteria:

- Whenever a guest opens the home page, then the system shall show active banners/categories/products.
- Whenever no product exists, then the system shall show an empty state instead of an error.
- Whenever product images are missing, then the system shall show a fallback image.

### STO-02 Search products

As a Customer, I want to search products by keyword, so that I can find the item I need quickly.

Acceptance Criteria:

- Whenever customer submits a keyword, then the system shall return matching products.
- Whenever keyword matches a variant name, then the system shall still return the related root product when appropriate.
- Whenever no result is found, then the system shall show a clear empty message.

### STO-03 View product detail

As a Customer, I want to view product detail, so that I can evaluate price, image, description and options.

Acceptance Criteria:

- Whenever customer opens a valid product detail page, then the system shall show product information and images.
- Whenever the route receives a child product id, then the system shall resolve the parent product if detail should focus on root product.
- Whenever product detail is viewed, then the system shall track product view for analytics.

## 5. User account

### ACC-01 Register

As a Guest, I want to create an account, so that I can buy products and track my orders.

Acceptance Criteria:

- Whenever guest submits valid registration data, then the system shall create a user account.
- Whenever email already exists, then the system shall reject the request.
- Whenever password confirmation does not match, then the system shall show validation error.

### ACC-02 Login user

As a User, I want to log in, so that I can access cart, checkout and order history.

Acceptance Criteria:

- Whenever user submits valid credentials, then the system shall authenticate with guard `web`.
- Whenever user account is locked, then the system shall block access.
- Whenever credentials are invalid, then the system shall show an error message.

### ACC-03 Update profile

As a User, I want to update my profile, so that checkout can use my latest name, phone and address.

Acceptance Criteria:

- Whenever authenticated user submits valid profile data, then the system shall update the profile.
- Whenever guest opens profile route, then the system shall redirect to login.
- Whenever phone/address is invalid, then the system shall show validation error.

## 6. Cart and checkout

### CRT-01 Add product to cart

As a User, I want to add a product to cart, so that I can buy it later.

Acceptance Criteria:

- Whenever authenticated user adds an available product, then the system shall create or update the cart item.
- Whenever guest tries to add cart through AJAX, then the system shall return login-required behavior.
- Whenever requested quantity exceeds stock, then the system shall reject the request.

### CRT-02 Review cart

As a User, I want to review cart items, so that I can confirm products and quantity before checkout.

Acceptance Criteria:

- Whenever user opens cart page, then the system shall show current cart items and totals.
- Whenever user removes an item, then the system shall update cart total.
- Whenever cart is empty, then the system shall show empty cart state.

### ORD-01 Checkout

As a User, I want to checkout my cart, so that I can create an order.

Acceptance Criteria:

- Whenever user submits checkout with valid data, then the system shall create order and order products in a transaction.
- Whenever cart is empty, then the system shall reject checkout.
- Whenever coupon is invalid, then the system shall show an error and not apply discount.
- Whenever shipping city is selected, then the system shall include shipping fee in total.

### PAY-01 Pay order

As a User, I want to pay with MoMo or VNPAY, so that I can complete my purchase online.

Acceptance Criteria:

- Whenever payment provider returns success, then the system shall mark the order as paid.
- Whenever payment provider returns failure, then the system shall keep order unpaid and show error page.
- Whenever callback is repeated, then the system shall not duplicate payment/order effects.

## 7. AI and analytics

### AI-01 Track product view

As the System, I want to track product views, so that analytics can identify customer interest.

Acceptance Criteria:

- Whenever customer views a product detail page, then the system shall write an activity log.
- Whenever the viewer is a guest, then the system shall still track using session/request context if available.
- Whenever tracking fails, then product detail should still load normally.

### AI-02 Recommend products

As a Customer, I want to receive product recommendations, so that I can discover relevant products faster.

Acceptance Criteria:

- Whenever customer requests AI recommendation, then the system shall return relevant products or suggestions.
- Whenever AI service fails, then the system shall use fallback recommendation.
- Whenever input is invalid, then the system shall return validation error.

### AI-03 AI dashboard

As an Admin, I want to view AI analytics and suggestions, so that I can improve product, promotion and inventory decisions.

Acceptance Criteria:

- Whenever admin opens AI dashboard, then the system shall show metrics and suggestions.
- Whenever admin requests analytics data, then the system shall return JSON chart data.
- Whenever admin dismisses a suggestion, then the system shall mark it dismissed and update the UI.

## 8. Microservices boundary

### MS-01 Inventory check

As the Checkout Orchestrator, I want to check inventory, so that checkout does not sell unavailable items.

Acceptance Criteria:

- Whenever item quantity is available, then inventory service shall return available status.
- Whenever item quantity exceeds stock, then inventory service shall return unavailable status and reason.
- Whenever product does not exist, then inventory service shall return error.

### MS-02 Pricing quote

As the Checkout Orchestrator, I want to request a pricing quote, so that checkout total is calculated consistently.

Acceptance Criteria:

- Whenever valid items are submitted, then pricing service shall return subtotal, discount, shipping and grand total.
- Whenever coupon is invalid, then pricing service shall return a clear validation result.
- Whenever city/shipping data is missing, then pricing service shall use default or return validation error based on rule.

### MS-03 Outbox event

As the System, I want to write checkout events to outbox, so that integration events can be published reliably later.

Acceptance Criteria:

- Whenever checkout simulation succeeds, then the system shall create an outbox event.
- Whenever orchestration fails before success, then the system shall not create success outbox event.
- Whenever outbox is listed, then events shall include type, payload and status.
