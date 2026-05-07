# Back-end Rules

## Phan 1. Security context

1. Khong hard-code secret

- Khong viet truc tiep database password, mail password, MoMo key, VNPAY secret, API key vao code.
- Dung `.env` va document bang `.env.example` neu can.
- Khong log token, password, payment secret.

2. Authentication and authorization

- Admin route bat buoc qua `auth:admin`.
- User route can dang nhap bat buoc qua `auth:web`.
- Route guest phai nam trong middleware `guest`.
- User bi khoa phai bi chan boi middleware `checkStatusUser`.
- Khong tin vao `user_id` client gui len neu da co session auth.

3. Upload and file safety

- Validate file type, size va path.
- Khong cho path traversal.
- Xoa/ghi file phai kiem tra folder hop le.
- Ten file nen duoc normalize/unique.

## Phan 2. Validation and sanitization

1. Khong tin data client

- Moi input tu request phai validate.
- Dung FormRequest neu logic validation phuc tap.
- Dung rule ro: required, string, numeric, integer, min, max, exists, unique, in, nullable.

2. Product validation

- `name` khong rong khi publish.
- `price` khong am.
- `quantity` khong am.
- `category_id` phai ton tai neu bat buoc.
- Product child/variant phai tham chieu product parent hop le.
- Duplicate variant combination phai bi chan.

3. Account validation

- Email unique khi register.
- Password co confirm/length.
- Profile phone/address validate do dai.
- Reset token phai hop le va chua het han.

4. Order/payment validation

- Cart khong rong truoc checkout.
- Quantity khong vuot ton kho.
- Coupon hop le, chua het han, dung dieu kien.
- Payment amount phai khop order total.
- Callback payment phai verify signature neu co.

## Phan 3. Error handling

1. Centralized behavior

- Controller khong de exception tho lo ra client.
- Production khong hien stack trace.
- Loi khong mong muon tra message trung tinh.

2. JSON format cho route API/stage

Thanh cong:

```json
{ "status": "success" }
```

Loi:

```json
{ "status": "error" }
```

3. Validation error

- Dung HTTP 422.
- Tra `errors` theo field.
- Frontend co the hien gan input.

## Phan 4. Data integrity

1. Transaction bat buoc khi thao tac nhieu bang

Dung transaction cho:

- Tao order + order_products + cart cleanup.
- Apply coupon + update order total.
- Payment callback + update payment status + order status.
- Inventory reservation + outbox event.
- Product configurable + variants + attribute config neu update cung luc.

2. Idempotency

- Payment callback co the duoc goi lai.
- Dismiss AI suggestion khong loi neu item da dismissed.
- Checkout simulation khong duoc tao duplicate outbox event neu cung request id.

3. Foreign keys and references

- Khong luu id khong ton tai.
- Khi xoa entity, xem xet entity con: product images, cart items, order products.
- Khong xoa product da co order neu khong co soft delete/archival policy.

## Phan 5. Production standards

1. Gateway/security headers

- API production nen co CORS cau hinh ro.
- Nen dung Helmet/security headers neu co node gateway; voi Laravel thi cau hinh middleware/header tuong duong neu can.
- Rate limit route login, reset password, AI call.

2. Fallback and retry

- Goi API ngoai nhu payment/AI/recommendation phai co Plan B.
- Retry toi da 3 lan cho loi tam thoi, co timeout.
- Neu AI loi thi tra fallback recommendation theo rule/local data.

3. Logging

- Log order/payment/AI errors voi context an toan.
- Log khong chua password/token/secret.
- Can co request id neu debug luong checkout/payment.

## Phan 6. Boundaries

- Khong tu y sua file frontend khi task backend khong yeu cau.
- Khong thay doi schema lon khi chua cap nhat PRD/Database_Schema.
- Khong doi ten route da co neu frontend/test dang dung.
- Khong xoa migration cu.
- Khong sua vendor/node_modules.
- Khong reset database production.

## Phan 7. Code style

- Follow Laravel convention.
- Controller mong, service chua logic nghiep vu phuc tap.
- FormRequest chua validation.
- Model relationship ro rang.
- Ten method dien dat hanh dong nghiep vu.
- Khong tao abstraction khi chua can.
- Comment ngan khi logic kho doc.

## Phan 8. Review checklist

Truoc khi chot:

- Route co middleware dung.
- Request duoc validate.
- Loi validation va exception duoc xu ly.
- Transaction dung noi can.
- Query khong N+1 nghiem trong.
- Secret nam trong env.
- Test lien quan pass.
- Docs lien quan da update.
