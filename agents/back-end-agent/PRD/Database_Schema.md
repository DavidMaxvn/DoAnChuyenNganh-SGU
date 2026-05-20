# Database Schema

## 1. Muc dich

Tai lieu nay tom tat schema quan trong cua do an dua tren cac migration hien co. Muc tieu la giup Back-end Agent va Tester Agent hieu dung bang/cot truoc khi sua code, viet test hoac giai thich trong bao cao.

## 2. Migration inventory

Migration hien co gom cac nhom:

- Admin/auth: `admins`, `password_resets`, `admin_password_resets`.
- Product modeling: `products`, `attributes`, `values`, `product_images`, `product_attr_config`, `categories`.
- Storefront content: `banners`.
- User/account: `users`, `social_accounts`.
- Commerce: `carts`, `orders`, `order_products`, `coupons`, `city`.
- AI/analytics: `activity_logs`, `ai_suggestions`.
- Microservices: `inventory_reservations`, `microservice_outbox_events`.

## 3. Core tables

### admins

Purpose: tai khoan quan tri.

Likely fields:

- `id`
- `name`
- `email`
- `password`
- `remember_token`
- `image`
- `created_at`
- `updated_at`

Rules:

- Email unique.
- Password hash.
- Admin route dung guard `admin`.

### users

Purpose: tai khoan nguoi mua.

Likely fields:

- `id`
- `name`
- `email`
- `password`
- `phone`
- `address`
- `status`
- `remember_token`
- `created_at`
- `updated_at`

Rules:

- Email unique.
- User bi khoa khong duoc thao tac mua hang.
- Profile update chi cho chinh user.

### social_accounts

Purpose: lien ket user voi provider social login.

Likely fields:

- `id`
- `user_id`
- `provider_name`
- `provider_id`
- `created_at`
- `updated_at`

Rules:

- `user_id` tham chieu `users`.
- Provider + provider_id nen unique.

## 4. Product tables

### products

Purpose: san pham cha/con, simple/configurable, gia, ton kho.

Fields tu migration:

- `id`
- `name`
- `price`
- `quantity`
- `parent_id`
- `image`
- `status`
- `description`
- `category_id`
- `type`
- `is_same_price`
- `created_at`
- `updated_at`

Relationships:

- Product belongs to category.
- Product parent has many child products/variants.
- Product has many product images.
- Product has many attribute values.

Rules:

- `price >= 0`.
- `quantity >= 0`.
- `parent_id` nullable.
- Root product: `parent_id = null`.
- Variant/child: `parent_id` points to root product.
- Storefront listing nen uu tien root product.

### categories

Purpose: danh muc san pham.

Likely fields:

- `id`
- `name`
- `status`
- `created_at`
- `updated_at`

Rules:

- Product `category_id` phai ton tai neu duoc gan.
- Chi hien category active tren storefront.

### attributes

Purpose: dinh nghia thuoc tinh san pham.

Likely fields:

- `id`
- `name`
- `created_at`
- `updated_at`

Rules:

- Ten attribute khong nen trung.
- Attribute duoc dung trong `values` va `product_attr_config`.

### values

Purpose: gia tri thuoc tinh gan vao product.

Likely fields:

- `id`
- `product_id`
- `attribute_id`
- `value`
- `created_at`
- `updated_at`

Rules:

- `product_id` tham chieu products.
- `attribute_id` tham chieu attributes.
- Variant combination khong duoc duplicate.

### product_images

Purpose: gallery anh product.

Likely fields:

- `id`
- `product_id`
- `image`
- `created_at`
- `updated_at`

Rules:

- `product_id` tham chieu products.
- File image phai validate type/size.

### product_attr_config

Purpose: cau hinh attribute nao la shared, attribute nao tao variant.

Likely fields:

- `id`
- `product_id`
- `attribute_id`
- `type` or equivalent config marker
- `created_at`
- `updated_at`

Rules:

- Khong de cung attribute vua shared vua variant trong cung product neu logic khong cho phep.

## 5. Storefront content

### banners

Purpose: banner trang chu.

Likely fields:

- `id`
- `name`
- `image`
- `status`
- `created_at`
- `updated_at`

Rules:

- Chi banner active moi hien storefront.
- Image phai co fallback.

## 6. Commerce tables

### carts

Purpose: gio hang cua user.

Likely fields:

- `id`
- `user_id`
- `product_id`
- `quantity`
- `created_at`
- `updated_at`

Rules:

- `user_id` tham chieu users.
- `product_id` tham chieu products.
- Quantity > 0.
- Khi product da ton tai trong cart, update quantity thay vi tao duplicate neu rule yeu cau.

### orders

Purpose: don hang.

Fields duoc bo sung qua migration:

- `id`
- `user_id`
- `name`
- `email`
- `phone`
- `address`
- `total`
- `status`
- `is_paid`
- `ship_code`
- `coupon_id`
- `city_id`
- `created_at`
- `updated_at`

Rules:

- Tao order nen trong transaction.
- `is_paid` update qua payment callback.
- `coupon_id` nullable.
- `city_id` nullable/required tuy checkout rule.

### order_products

Purpose: item snapshot cua order.

Likely fields:

- `id`
- `order_id`
- `product_id`
- `quantity`
- `price`
- `created_at`
- `updated_at`

Rules:

- Gia trong order product nen la snapshot tai thoi diem mua.
- Khong tinh lai theo price hien tai khi xem order cu.

### coupons

Purpose: ma giam gia.

Likely fields:

- `id`
- `code`
- `discount`
- `type`
- `status`
- `start_date`
- `end_date`
- `created_at`
- `updated_at`

Rules:

- Coupon active va trong han moi ap dung.
- Discount khong duoc lam total am.

### city

Purpose: thanh pho/phi van chuyen.

Likely fields:

- `id`
- `name`
- `ship_fee`
- `created_at`
- `updated_at`

Rules:

- Checkout dung city de tinh shipping.

## 7. AI and analytics tables

### activity_logs

Purpose: ghi hanh vi user/guest, vi du product view.

Likely fields:

- `id`
- `user_id`
- `session_id`
- `event_type`
- `subject_type`
- `subject_id`
- `metadata`
- `created_at`
- `updated_at`

Rules:

- Metadata khong chua secret.
- Tracking fail khong duoc lam fail product detail.

### ai_suggestions

Purpose: goi y hanh dong cho admin.

Likely fields:

- `id`
- `title`
- `description`
- `priority`
- `action`
- `status`
- `metadata`
- `dismissed_at`
- `created_at`
- `updated_at`

Rules:

- Suggestion co the dismissed.
- Priority nen co set ro: low, medium, high.

## 8. Microservice tables

### inventory_reservations

Purpose: mo phong giu ton kho khi checkout.

Likely fields:

- `id`
- `product_id`
- `quantity`
- `status`
- `expires_at`
- `created_at`
- `updated_at`

Rules:

- Reservation khong duoc vuot ton kho.
- Expired reservation can co cleanup neu production.

### microservice_outbox_events

Purpose: luu event de publish sau.

Likely fields:

- `id`
- `event_type`
- `payload`
- `status`
- `published_at`
- `created_at`
- `updated_at`

Rules:

- Payload nen la JSON.
- Status: pending, published, failed.
- Checkout success event chi tao sau khi orchestration thanh cong.

## 9. Relationship summary

```text
admins

users
  has many carts
  has many orders
  has many social_accounts
  has many activity_logs

categories
  has many products

products
  belongs to category
  belongs to parent product nullable
  has many child products
  has many product_images
  has many values
  has many carts
  has many order_products

attributes
  has many values
  has many product_attr_config

orders
  belongs to user
  has many order_products
  belongs to coupon nullable
  belongs to city nullable
```

## 10. Schema checklist khi sua code

- Kiem tra migration co cot can dung.
- Kiem tra model `$fillable`.
- Kiem tra relationship.
- Kiem tra nullable/default.
- Kiem tra test schema concern neu stage test dung SQLite/in-memory schema.
- Cap nhat file nay neu them bang/cot moi.
