# Product Requirements Document

## 1. Product name

Website sieu thi ban do an/nong san co AI goi y.

## 2. Vision

He thong giup nguoi dung tim, xem, mua san pham do an/nong san truc tuyen; dong thoi giup admin quan ly san pham, don hang, khuyen mai, nguoi dung va theo doi insight bang AI dashboard.

## 3. Problem

Nguoi mua can:

- Tim san pham nhanh.
- Xem chi tiet, gia, anh, danh muc.
- Them gio hang va thanh toan.
- Theo doi don hang.
- Nhan goi y phu hop.

Admin can:

- Quan ly product co bien the/thuoc tinh.
- Quan ly danh muc, banner, coupon, shipping, user, order.
- Xem analytics va goi y hanh dong.
- Dam bao don hang/payment khong sai du lieu.

## 4. Goals

- Xay duoc e-commerce workflow end-to-end.
- Co admin backend day du CRUD.
- Co storefront de nguoi dung mua hang.
- Co account, cart, checkout, order, payment.
- Co tracking, analytics va AI suggestion.
- Co microservice boundary cho inventory/pricing/checkout.
- Co test theo tung stage de bao ve do an.
- Co bo AI Agent docs de thuyet trinh quy trinh vibe coding co kiem soat.

## 5. Non-goals

- Khong bat buoc tach microservices thanh nhieu server vat ly.
- Khong bat buoc co mobile app native.
- Khong bat buoc co AI model tu train tu dau.
- Khong bat buoc co payment production live neu moi truong demo khong co key that.
- Khong viet lai toan bo frontend sang React/NextJS neu Blade hien tai dap ung demo.

## 6. Actors

| Actor | Mo ta |
| --- | --- |
| Guest | Nguoi chua dang nhap, xem san pham, search, dang ky/dang nhap |
| User | Nguoi mua da dang nhap, co cart/order/profile/payment |
| Admin | Nguoi quan tri, quan ly san pham, don hang, user, coupon, AI dashboard |
| AI System | He thong goi y dua tren activity/order/product data |
| Payment Provider | MoMo/VNPAY callback/redirect |
| Microservice Boundary | Inventory, pricing, checkout orchestrator, outbox |

## 7. Core business flows

### Flow 1: Admin product management

Admin login, tao product, cau hinh attribute, tao variant, upload anh, gan category/banner/coupon lien quan.

Success:

- Product hien tren storefront.
- Variant khong trung combination.
- Anh va gia/ton kho hien dung.

### Flow 2: Storefront discovery

Guest/user vao home, xem category, search, xem product detail, nhan goi y.

Success:

- Product root hien tren listing.
- Search tim duoc product hop le.
- Product detail co du anh/gia/attribute.
- View duoc track cho analytics.

### Flow 3: Cart, checkout, payment

User them gio, xem cart, checkout, ap dung coupon/shipping, tao order, thanh toan MoMo/VNPAY hoac COD, xem lich su don.

Success:

- Order duoc tao dung tong tien.
- Cart duoc cap nhat.
- Payment callback cap nhat trang thai dung.
- User xem duoc order detail.

### Flow 4: AI analytics and recommendation

System ghi activity, tong hop analytics, tao AI suggestions va recommendation.

Success:

- Admin thay dashboard.
- Suggestion co action ro.
- User nhan goi y san pham/mon an.
- Fallback neu AI loi.

### Flow 5: Microservice simulation

Checkout orchestrator goi inventory, pricing, ghi outbox event.

Success:

- Neu het hang, checkout simulation fail ro.
- Neu pricing hop le, tra quote breakdown.
- Neu orchestration thanh cong, outbox co event.

## 8. Functional requirements

### Admin

- Admin co the login/logout.
- Admin co the CRUD product/admin/attribute/category/banner/order/user/coupon/city.
- Admin co the xem AI dashboard.
- Admin co the dismiss AI suggestion.

### User

- User co the register/login/logout.
- User co the reset password.
- User co the login social.
- User co the cap nhat profile.
- User co the xem order history/detail.

### Product

- Product co name, price, quantity, category, status, image, description.
- Product co the co parent/child.
- Product co attribute/value.
- Product co gallery.
- Product co simple/configurable type.

### Commerce

- User them/xoa cart item.
- Checkout tinh tong tien server-side.
- Coupon/shipping duoc xu ly dung.
- Order luu item snapshot.
- Payment callback cap nhat order.

### AI

- Product view duoc track.
- AI recommend co route rieng.
- AI dashboard co analytics data.
- Suggestion co dismiss.

## 9. Non-functional requirements

Security:

- Dung auth guard.
- Validate input.
- Khong hard-code secret.
- Khong lo stack trace production.

Performance:

- Query list product/order can paginate.
- Avoid N+1 cho relation product/category/images.
- AI analytics nen aggregate hop ly.

Reliability:

- Checkout/payment dung transaction.
- Payment callback idempotent.
- AI/recommendation co fallback.

Maintainability:

- Controller mong.
- Service chua logic phuc tap.
- Docs cap nhat theo route/schema.
- Test theo stage.

## 10. Success metrics

- Full stage tests pass.
- Admin co the tao product va quan ly order.
- User co the mua hang tu home den order success.
- AI dashboard hien data/suggestion.
- Stage07 simulation tra ket qua hop le.
- Bo tai lieu AI Agent day du de giai thich quy trinh.

## 11. Release checklist

- `.env` dung.
- Migrate/seed demo data.
- Admin account demo.
- Payment sandbox config neu demo.
- Product/category/banner demo co anh.
- Test Stage01-Stage07 pass.
- Docs/slide/report cap nhat.
