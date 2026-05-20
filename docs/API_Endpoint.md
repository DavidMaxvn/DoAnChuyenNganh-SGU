# API Endpoint and Route Contract

## 1. Muc dich

Tai lieu nay tong hop route/endpoint quan trong cua do an Laravel. Vi project hien tai dung nhieu route web Blade va cac route `vibe_stage_*` de chung minh tung chang, tai lieu chia endpoint theo nhom nghiep vu de de bao cao, test va thuyet trinh.

Quy uoc:

- Admin web route nam trong `routes/admin.php`.
- User web route nam trong `routes/web.php`.
- Stage/demo route nam trong `routes/vibe_stage_*.php`.
- API mac dinh nam trong `routes/api.php`, hien chi co route user voi Sanctum.
- Neu route tra view thi ghi "Blade".
- Neu route tra JSON cho test/stage thi ghi "JSON".

## 2. Admin authentication

| Method | Path | Route name | Handler | Output | Ghi chu |
| --- | --- | --- | --- | --- | --- |
| GET | `/admin/login` | `admin.login` | `Admin\AuthController@showFormLogin` | Blade | Form dang nhap admin |
| POST | `/admin/login` | `admin.login.post` | `Admin\AuthController@login` | Redirect/session | Xac thuc guard admin |
| GET | `/admin/logout` | `admin.logout` | `Admin\AuthController@logout` | Redirect | Dang xuat admin |
| GET | `/admin/forget-password` | `admin.forget.password.get` | `Admin\ForgotPasswordController@showForgetPasswordForm` | Blade | Form quen mat khau |
| POST | `/admin/forget-password` | `admin.forget.password.post` | `Admin\ForgotPasswordController@submitForgetPasswordForm` | Redirect/mail | Gui reset link |
| GET | `/admin/reset-password/{token}` | `admin.reset.password.get` | `Admin\ForgotPasswordController@showResetPasswordForm` | Blade | Form reset |
| POST | `/admin/reset-password` | `admin.reset.password.post` | `Admin\ForgotPasswordController@submitResetPasswordForm` | Redirect | Doi mat khau |

Security note:

- Cac route quan tri sau login phai nam sau middleware `auth:admin`.
- Khong hien stack trace hay thong tin cau hinh neu login/reset loi.

## 3. Admin management routes

| Resource | Route | Controller | Muc dich |
| --- | --- | --- | --- |
| Dashboard | `/admin/index`, `/admin/ajax-index` | `Admin\HomeController` | Tong quan doanh thu, don hang, san pham |
| Products | `/admin/products` resource | `Admin\ProductController` | CRUD product cha/con, anh, attribute |
| Admins | `/admin/admins` resource | `Admin\AdminController` | Quan ly tai khoan admin |
| Attributes | `/admin/attributes` resource | `Admin\AttributeController` | Quan ly thuoc tinh product |
| Categories | `/admin/categories` resource | `Admin\CategoryController` | Quan ly danh muc |
| Banners | `/admin/banners` resource | `Admin\BannerController` | Quan ly banner storefront |
| Orders | `/admin/orders` resource | `Admin\OrderController` | Quan ly don hang |
| Users | `/admin/users` resource | `Admin\UserController` | Quan ly nguoi dung |
| Coupons | `/admin/coupons` resource | `Admin\CouponController` | Quan ly ma giam gia |
| Cities | `/admin/cities` resource | `Admin\ShippingFeeController` | Phi van chuyen theo thanh pho |

Ajax/admin support:

| Method | Path | Route name | Muc dich |
| --- | --- | --- | --- |
| GET | `/admin/render-product-child-new-row` | `admin.products.render-product-child-new-row` | Render dong bien the product |
| GET | `/admin/render_image_review` | `admin.products.render_image_review` | Preview image |
| GET | `/admin/render-attribute` | `admin.renderAttribute` | Render attribute field |
| GET | `/admin/render-attribute-product-child` | `admin.renderAttributeProductChild` | Render attribute cho product con |

## 4. AI dashboard routes

| Method | Path | Route name | Handler | Muc dich |
| --- | --- | --- | --- | --- |
| GET | `/admin/ai-dashboard` | `admin.ai.dashboard` | `AIDashboardController@index` | Man hinh AI dashboard |
| POST | `/admin/ai-dashboard/dismiss` | `admin.ai.dismiss` | `AIDashboardController@dismissSuggestion` | An/bo qua goi y |
| GET | `/admin/ai-dashboard/analytics` | `admin.ai.analytics` | `AIDashboardController@getAnalyticsData` | Lay data chart/metric |

Response guideline:

```json
{
  "status": "success",
  "data": {},
  "message": "Loaded analytics data"
}
```

Neu loi:

```json
{
  "status": "error",
  "message": "Unable to load analytics data"
}
```

## 5. User storefront routes

| Method | Path | Route name | Handler | Muc dich |
| --- | --- | --- | --- | --- |
| GET | `/` | `index` | `Web\HomeController@index` | Trang chu |
| POST | `/recommend` | `recommend` | `Web\HomeController@recommend` | Goi y mon/san pham theo rule |
| POST | `/ai/recommend` | `ai.recommend` | `Web\HomeController@AiRecommend` | Goi y bang AI |
| GET | `/search-dish` | `search.dish` | `Web\HomeController@searchDish` | Tim mon |
| GET | `/get-recipe` | `get.recipe` | `Web\HomeController@getRecipe` | Lay cong thuc |
| GET | `/product/{id}` | `detail` | `Web\ProductController@detail` | Chi tiet product, co tracking |
| GET | `/category/{id}/detail` | `detail.category` | `Web\CategoryController@categoryDetail` | San pham theo danh muc |
| GET | `/search` | `search` | `Web\HomeController@search` | Search storefront |
| GET | `/about` | `about` | `Web\HomeController@about` | Gioi thieu |
| GET | `/contact` | `contact` | `Web\HomeController@contact` | Lien he |

Middleware note:

- `checkStatusUser` dung de chan tai khoan bi khoa.
- `track.product.view` ghi nhan hanh vi xem product.

## 6. User authentication/profile routes

| Method | Path | Route name | Handler | Muc dich |
| --- | --- | --- | --- | --- |
| GET | `/login` | `login` | `Web\AuthController@showFormLogin` | Form dang nhap |
| POST | `/login` | `login.post` | `Web\AuthController@login` | Dang nhap |
| GET | `/register` | `register` | `Web\AuthController@showFormRegister` | Form dang ky |
| POST | `/register` | `register.post` | `Web\AuthController@register` | Tao tai khoan |
| GET | `/logout` | `logout` | `Web\AuthController@logout` | Dang xuat |
| GET | `/forget-password` | `forget.password.get` | `Web\ForgotPasswordController@showForgetPasswordForm` | Form quen mat khau |
| POST | `/forget-password` | `forget.password.post` | `Web\ForgotPasswordController@submitForgetPasswordForm` | Gui reset link |
| GET | `/reset-password/{token}` | `reset.password.get` | `Web\ForgotPasswordController@showResetPasswordForm` | Form reset |
| POST | `/reset-password` | `reset.password.post` | `Web\ForgotPasswordController@submitResetPasswordForm` | Doi mat khau |
| GET | `/redirect/{social}` | `social.redirect` | `Web\SocialAuthController@redirect` | OAuth redirect |
| GET | `/callback/{social}` | `social.callback` | `Web\SocialAuthController@callback` | OAuth callback |
| GET | `/profile` | `profile` | `Web\ProfileController@showFormProfile` | Xem profile |
| POST | `/profile/{id}` | `profile.post` | `Web\ProfileController@profile` | Cap nhat profile |

## 7. Cart, checkout, order, payment

| Method | Path | Route name | Handler | Muc dich |
| --- | --- | --- | --- | --- |
| GET | `/add-cart` | `cart.add` | `Web\CartController@addCart` | Them vao gio, ajax/login guard |
| GET | `/cart` | `list.product.cart` | `Web\CartController@listProductInCart` | Xem gio hang |
| GET | `/delete` | `delete.product.cart` | `Web\CartController@deleteProductCart` | Xoa item gio hang |
| GET | `/checkout` | `checkout.order` | `Web\OrderController@checkOut` | Trang checkout |
| POST | `/create-order` | `create.order` | `Web\OrderController@createOrder` | Tao don hang |
| GET | `/order-success` | `success.order` | `Web\OrderController@success` | Dat hang thanh cong |
| GET | `/order-error` | `error.order` | `Web\OrderController@error` | Dat hang loi |
| GET | `/list-order` | `list_order_of_user` | `Web\OrderController@listOrderOfUser` | Don hang cua user |
| GET | `/order/{id}` | `order_detail` | `Web\OrderController@orderDetail` | Chi tiet don |
| POST | `/order/{id}` | `order_update_status` | `Web\OrderController@updateStatusOrder` | Cap nhat/huy don |
| GET | `/momo-return` | `momo_return` | `Web\OrderController@momoReturn` | Callback MoMo |
| GET | `/vnpay/create` | `vnpay.create` | `Web\VnpayController@create` | Tao thanh toan VNPAY |
| GET | `/vnpay/return` | `vnpay.return` | `Web\VnpayController@return` | Callback VNPAY |

Transaction guideline:

- Tao order + order_products + cap nhat cart + coupon/payment status phai nam trong transaction.
- Payment callback phai idempotent: goi lai khong duoc tao trung don.
- Khi cap nhat ton kho, can check quantity truoc khi tru.

## 8. Vibe stage routes

### Stage 01: Admin auth and product core

Base concept: admin login, dashboard, product root/child.

| Method | Path | Muc dich |
| --- | --- | --- |
| GET | `/vibe/stage-01/admin/login` | Thong tin login route |
| POST | `/vibe/stage-01/admin/login` | Login admin |
| GET | `/vibe/stage-01/admin/dashboard` | Dashboard JSON |
| POST | `/vibe/stage-01/admin/logout` | Logout admin |
| GET | `/vibe/stage-01/admin/products` | List products |
| POST | `/vibe/stage-01/admin/products` | Create product |
| GET | `/vibe/stage-01/admin/products/{product}` | Product detail |

### Stage 02: Product modeling

| Method | Path | Muc dich |
| --- | --- | --- |
| GET | `/vibe/stage-02/admin/overview` | Overview schema/product modeling |
| GET | `/vibe/stage-02/admin/attributes` | List attributes |
| POST | `/vibe/stage-02/admin/attributes` | Create attribute |
| PUT | `/vibe/stage-02/admin/products/{product}/model` | Configure simple/configurable product |
| GET | `/vibe/stage-02/admin/products/{product}/model` | View product model |
| POST | `/vibe/stage-02/admin/products/{product}/variants` | Create variant |

### Stage 03: Storefront catalog

| Method | Path | Muc dich |
| --- | --- | --- |
| GET | `/vibe/stage-03/storefront/overview` | Overview storefront |
| GET | `/vibe/stage-03/storefront/home` | Home feed |
| GET | `/vibe/stage-03/storefront/products` | Catalog |
| GET | `/vibe/stage-03/storefront/search` | Search |
| GET | `/vibe/stage-03/storefront/products/{product}` | Product detail |

### Stage 04: Account

| Method | Path | Muc dich |
| --- | --- | --- |
| GET | `/vibe/stage-04/account/overview` | Overview account |
| POST | `/vibe/stage-04/account/register` | Register |
| POST | `/vibe/stage-04/account/login` | Login |
| POST | `/vibe/stage-04/account/forgot-password` | Forgot password |
| POST | `/vibe/stage-04/account/reset-password` | Reset password |
| POST | `/vibe/stage-04/account/social/callback` | Social login callback |
| GET | `/vibe/stage-04/account/me` | Current user |
| PUT | `/vibe/stage-04/account/profile` | Update profile |
| POST | `/vibe/stage-04/account/logout` | Logout |

### Stage 07: Microservices boundary

| Method | Path | Muc dich |
| --- | --- | --- |
| GET | `/vibe/stage-07/microservices/overview` | Overview architecture |
| POST | `/vibe/stage-07/microservices/inventory/check` | Check inventory |
| POST | `/vibe/stage-07/microservices/pricing/quote` | Price quote |
| POST | `/vibe/stage-07/microservices/checkout/simulate` | Simulate checkout orchestration |
| GET | `/vibe/stage-07/microservices/outbox` | View outbox events |

## 9. Standard response contract

Success:

```json
{
  "status": "success",
  "message": "Operation completed",
  "data": {}
}
```

Validation error:

```json
{
  "status": "error",
  "message": "Validation failed",
  "errors": {
    "field": ["Message"]
  }
}
```

System error:

```json
{
  "status": "error",
  "message": "Something went wrong"
}
```

Rules:

- Khong tra stack trace cho client.
- Khong tra secret, token, config payment.
- Dung HTTP status phu hop: 200, 201, 400, 401, 403, 404, 422, 500.
- Loi validation phai de frontend hien gan field.

## 10. Endpoint checklist khi them route moi

- Route co middleware dung vai tro.
- Controller gom validation hoac FormRequest.
- Response/redirect nhat quan voi UI hien co.
- Ten route dung convention, khong trung.
- Co test neu route thuoc luong nghiep vu quan trong.
- Co logging voi hanh dong nhay cam: payment, order status, AI dismiss.
- Tai lieu nay duoc cap nhat sau khi them route.
