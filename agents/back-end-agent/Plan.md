# Back-end Development Plan

## 1. Muc tieu plan

Plan nay giup thuyet trinh cach backend duoc phat trien theo 7 stage. Moi stage co muc tieu, output, file quan trong, test va rui ro can kiem soat.

## 2. Stage 01 - Laravel foundation, admin auth, product core

Muc tieu:

- Laravel boot duoc.
- Admin guard hoat dong.
- Admin login/logout.
- Product core co root/child qua `parent_id`.

Output:

- Admin auth routes.
- Product CRUD core cho stage.
- Feature tests Stage01.

File quan trong:

- `config/auth.php`
- `routes/vibe_stage_01_admin.php`
- `app/Http/Controllers/Vibe/Stage01/Admin/AuthController.php`
- `app/Http/Controllers/Vibe/Stage01/Admin/ProductController.php`
- `tests/Feature/Vibe/Stage01/*`

Verify:

```bash
php artisan test --filter=Stage01
```

## 3. Stage 02 - Product modeling

Muc tieu:

- Mo rong product thanh model co attribute, value, image, variant.
- Ho tro simple/configurable product.
- Chan duplicate variant combination.

Output:

- `ProductModelingService`.
- Attribute endpoints.
- Product model configure endpoint.
- Variant create endpoint.

File quan trong:

- `routes/vibe_stage_02_admin.php`
- `app/Services/Vibe/Stage02/ProductModelingService.php`
- `app/Http/Controllers/Vibe/Stage02/Admin/*`
- `tests/Feature/Vibe/Stage02/ProductModelingTest.php`

Verify:

```bash
php artisan test --filter=Stage02
```

## 4. Stage 03 - Storefront catalog

Muc tieu:

- Dua product ra public home/catalog/search/detail.
- Chi hien root product tren listing.
- Search match ca root va variant.

Output:

- `StorefrontCatalogService`.
- Public storefront JSON stage routes.
- Tests Stage03.

File quan trong:

- `routes/vibe_stage_03_storefront.php`
- `app/Services/Vibe/Stage03/StorefrontCatalogService.php`
- `app/Http/Controllers/Vibe/Stage03/StorefrontController.php`
- `tests/Feature/Vibe/Stage03/StorefrontCatalogTest.php`

Verify:

```bash
php artisan test --filter=Stage03
```

## 5. Stage 04 - User account

Muc tieu:

- Register/login/logout.
- Profile.
- Forgot/reset password.
- Social callback mo phong.

Output:

- `UserAccountService`.
- Account routes.
- Tests Stage04.

File quan trong:

- `routes/vibe_stage_04_account.php`
- `app/Services/Vibe/Stage04/UserAccountService.php`
- `app/Http/Controllers/Vibe/Stage04/*`
- `tests/Feature/Vibe/Stage04/UserAccountTest.php`

Verify:

```bash
php artisan test --filter=Stage04
```

## 6. Stage 05 - Cart, checkout, order, payment

Muc tieu:

- User them product vao cart.
- Checkout tao order.
- Xu ly coupon/shipping/payment.
- Theo doi order cua user.

Output:

- Cart routes/views.
- Checkout route/view.
- Order create/list/detail/update.
- Payment MoMo/VNPAY return.
- Tests Stage05.

File quan trong:

- `routes/web.php`
- `app/Http/Controllers/Web/CartController.php`
- `app/Http/Controllers/Web/OrderController.php`
- `app/Http/Controllers/Web/VnpayController.php`
- `tests/Feature/Vibe/Stage05/CartCheckoutOrderPaymentTest.php`

Verify:

```bash
php artisan test --filter=Stage05
```

## 7. Stage 06 - Tracking, analytics, AI

Muc tieu:

- Ghi activity log.
- Theo doi product view.
- Tao AI recommendation.
- Tao AI dashboard cho admin.

Output:

- Activity logs.
- AI suggestions.
- AI dashboard route/view.
- Analytics JSON endpoint.
- Tests Stage06.

File quan trong:

- `routes/admin.php`
- `routes/web.php`
- `app/Http/Controllers/Admin/AIDashboardController.php`
- `app/Http/Middleware/TrackProductView.php`
- `tests/Feature/Vibe/Stage06/TrackingAnalyticsAiTest.php`

Verify:

```bash
php artisan test --filter=Stage06
```

## 8. Stage 07 - Microservices boundary

Muc tieu:

- Tach logic ton kho, tinh gia, checkout orchestration thanh service boundary.
- Mo phong outbox event.

Output:

- Inventory microservice.
- Pricing microservice.
- Checkout orchestrator.
- Outbox service.
- Stage07 routes/tests.

File quan trong:

- `routes/vibe_stage_07_microservices.php`
- `app/Services/Vibe/Stage07/InventoryMicroservice.php`
- `app/Services/Vibe/Stage07/PricingMicroservice.php`
- `app/Services/Vibe/Stage07/CheckoutOrchestratorService.php`
- `app/Services/Vibe/Stage07/MicroserviceOutboxService.php`
- `tests/Feature/Vibe/Stage07/MicroserviceArchitectureTest.php`

Verify:

```bash
php artisan test --filter=Stage07
```

## 9. Backlog uu tien neu con thoi gian

1. Chuan hoa JSON response helper.
2. Them FormRequest cho cac controller web cu.
3. Them transaction ro cho create order/payment callback.
4. Them rate limit login/reset/AI recommend.
5. Them `.env.example` mo ta payment/AI keys.
6. Them API docs cho payload request/response chi tiet.
7. Them seed demo data cho bao cao.
8. Them screenshot checklist trong `docs/Check.md`.

## 10. Bao cao tien do

Khi bao cao voi giang vien:

- Neu hoi "AI lam gi?", tra loi: AI duoc chia thanh agent co rules/skills/workflows, ho tro sinh code, test, tai lieu.
- Neu hoi "AI dung MCP/tool gi?", tra loi: AI doc context trong repo, dung search/file patch/test/log co kiem soat theo `MCP.md`; hien workspace chua co MCP resource co dinh nen local tools va docs la nguon chinh.
- Neu hoi "con nguoi lam gi?", tra loi: con nguoi chia stage, dat prompt, kiem tra code, chay test, quyet dinh scope.
- Neu hoi "bang chung?", dua test command, route, screenshot va file tai lieu.
