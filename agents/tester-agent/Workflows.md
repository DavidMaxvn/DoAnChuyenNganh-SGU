# Tester Workflows

## 1. Workflow tong quat

1. Doc User Stories.
2. Chon acceptance criteria can test.
3. Tim route/controller/service lien quan.
4. Chon test level: feature/manual.
5. Tao data.
6. Chay action.
7. Assert behavior.
8. Ghi result vao checklist/report.

## 2. Workflow test stage

1. Xac dinh stage.
2. Chay command filter stage.
3. Neu pass, ghi evidence.
4. Neu fail, doc loi dau tien.
5. Re-run test fail neu can.
6. Bao bug theo format.

## 3. Workflow test auth

1. Guest access protected route.
2. Invalid login.
3. Valid login.
4. Logout.
5. Locked user/admin neu co.
6. Assert guard/session.

## 4. Workflow test product

1. Tao admin.
2. Login/actingAs admin.
3. Tao root product.
4. Tao attribute.
5. Configure product.
6. Tao variant.
7. Thu duplicate variant.
8. Assert database.

## 5. Workflow test storefront

1. Tao category/product active.
2. Mo home/catalog.
3. Search keyword.
4. Mo detail.
5. Assert product data.
6. Assert tracking neu route co middleware.

## 6. Workflow test checkout

1. Tao user.
2. Tao product co quantity.
3. Them cart.
4. Checkout valid.
5. Assert order/order_products.
6. Assert cart state.
7. Test empty cart.
8. Test invalid coupon/stock.

## 7. Workflow test payment

1. Tao order unpaid.
2. Goi callback success.
3. Assert `is_paid`.
4. Goi callback lai.
5. Assert khong duplicate side effect.
6. Goi callback fail.
7. Assert order status hop ly.

## 8. Workflow test AI

1. Tao activity logs.
2. Tao products/orders neu can.
3. Goi analytics endpoint.
4. Assert JSON.
5. Tao suggestion.
6. Dismiss suggestion.
7. Assert status/dismissed_at.
8. Test fallback khi no data.

## 9. Workflow test microservices

1. Tao product quantity.
2. Goi inventory check available.
3. Goi inventory check unavailable.
4. Goi pricing quote.
5. Goi checkout simulate success.
6. Assert outbox event.
7. Goi checkout simulate failure.
8. Assert khong tao success event.

## 10. Workflow report

Sau khi test:

```text
Stage:
Command:
Result:
Business flow covered:
Files/tests:
Remaining risk:
```
