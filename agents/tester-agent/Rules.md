# Tester Rules

## Phan 1. Nguyen tac kiem thu

- Khong xoa test that bai de lam xanh.
- Khong sua production code neu chua co bug ro.
- Khong test qua loa chi happy path.
- Moi luong quan trong can co success va failure case.
- Test phai gan voi User Stories/Acceptance Criteria.

## Phan 2. Coverage uu tien

Uu tien cao:

- Auth/admin/user.
- Product/variant duplicate.
- Cart/checkout/order/payment.
- AI tracking/analytics.
- Microservice inventory/pricing/outbox.

Uu tien trung binh:

- CRUD category/banner/coupon/city.
- Profile/password/social.
- Storefront search/filter.

Uu tien UI/manual:

- Responsive.
- Loading/error/empty.
- Toast/modal/confirm.

## Phan 3. Test data

- Dung factory/seed/test schema concern neu co.
- Khong phu thuoc database production.
- Moi test nen tu tao data can thiet.
- Reset state giua test.
- Khong dung email/password production.

## Phan 4. Assertions

Can assert:

- HTTP status.
- Redirect/location neu co.
- JSON structure/status/message.
- Database has/missing.
- Session authenticated/guest.
- Validation errors.
- Business totals: subtotal, discount, shipping, grand total.

## Phan 5. Boundaries

- Khong sua `vendor`, `node_modules`.
- Khong reset database that neu chua duoc phep.
- Khong thay doi PRD de hop thuc hoa bug.
- Khi test fail do behavior khac docs, bao ro docs hay code can sua.

## Phan 6. Report format

Bug report nen co:

```text
Title:
Route/Test:
Steps:
Expected:
Actual:
Likely cause:
Suggested fix:
```

Test summary nen co:

```text
Command:
Result:
Passed:
Failed:
Notes:
```
