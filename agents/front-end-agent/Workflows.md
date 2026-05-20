# Front-end Workflows

## 1. Workflow tong quat

1. Doc yeu cau va man hinh.
2. Tim route/view/layout lien quan.
3. Xac dinh data Blade/API.
4. Phan tich state: loading, empty, error, success.
5. Sua view/JS/CSS.
6. Kiem tra responsive.
7. Kiem tra console/interaction.
8. Cap nhat docs neu co pattern moi.

## 2. Workflow admin page

1. Xac dinh resource: product, order, user, coupon, AI dashboard.
2. Doc view index/create/edit.
3. Giu layout `master_admin`.
4. Kiem tra route name.
5. Them/cai tien table/form state.
6. Them confirm cho delete/cancel/dismiss.
7. Kiem tra empty state.
8. Kiem tra mobile horizontal scroll neu table rong.

## 3. Workflow product form

1. Nhom field theo thong tin chung/gia/ton kho/anh/attribute.
2. Giu old input.
3. Hien validation error gan field.
4. Preview image.
5. Xu ly product child/variant row.
6. Dung Select2 neu project da dung.
7. Disable submit khi dang gui.
8. Sau success redirect/hien toast theo pattern san co.

## 4. Workflow storefront page

1. Xac dinh goal: discover/search/detail/buy.
2. Dat product image la tin hieu chinh.
3. Dam bao price/status/CTA ro.
4. Dung fallback image.
5. Them empty state neu danh sach rong.
6. Kiem tra product card khong vo tren mobile.
7. Kiem tra search/filter khong lam reload kho hieu.

## 5. Workflow cart/checkout

Cart:

1. Hien item list.
2. Hien quantity, price, subtotal.
3. Confirm remove.
4. Hien empty cart.
5. CTA checkout ro.

Checkout:

1. Hien user/shipping form.
2. Hien coupon.
3. Hien payment method.
4. Hien order summary.
5. Disable submit khi dang tao order/payment.
6. Loi payment co trang error ro.

## 6. Workflow AI dashboard

1. Hien metric cards.
2. Hien chart/analytics.
3. Hien suggestion cards.
4. Dismiss suggestion bang AJAX.
5. Xu ly loading/error/empty.
6. Co priority badge.
7. Co suggested action.

## 7. Workflow responsive pass

Kiem tra theo thu tu:

1. Mobile 360px.
2. Mobile 414px.
3. Tablet 768px.
4. Desktop 1366px.

Can check:

- Text co tran nut/card khong.
- Menu co dung khong.
- Table co scroll khong.
- Image co meo khong.
- Modal co bi cat khong.
- Checkout summary co de doc khong.

## 8. Workflow AJAX

1. Tim form/button selector hien co.
2. Kiem tra CSRF token.
3. Them loading/disabled.
4. Goi API/route.
5. Xu ly response success.
6. Xu ly validation/system error.
7. Restore state.
8. Cap nhat UI khong reload neu phu hop.

## 9. Workflow handoff cho Tester Agent

Khi xong UI, ghi lai:

- Route demo.
- User role can login.
- Data can co.
- State da xu ly.
- Case can test.

Vi du:

```text
Route demo: /admin/ai-dashboard
Role: admin
Can co: activity_logs va ai_suggestions
Check: load metrics, analytics endpoint, dismiss suggestion, empty state
```
