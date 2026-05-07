# Front-end Skill

## 1. Skill overview

Front-end Skill giup AI tao/cai tien giao dien Laravel Blade co tinh dung duoc, khong chi dep. Skill nay tap trung vao:

- Blade layout/partial.
- Admin CRUD screens.
- Storefront product discovery.
- Cart/checkout UI.
- AI dashboard UI.
- AJAX interaction.
- Responsive and animation.

## 2. Blade component skill

Khi gap UI lap lai, tach partial:

- Product card: `resources/views/web/include/item_product.blade.php`.
- Search item: `resources/views/web/include/item_product_search.blade.php`.
- Attribute field: `resources/views/admin/attribute/_list_field.blade.php`.
- Product field: `resources/views/admin/product/_list_field.blade.php`.
- Cart row/order row neu can.

Nguyen tac:

- Partial nhan bien ro.
- Khong query database trong Blade.
- Khong lap logic phuc tap trong view.
- Dung route name thay vi hard-code URL neu co.

## 3. Admin UI skill

Admin page nen co:

- Header/title.
- Action chinh: create/save/export neu co.
- Filter/search neu danh sach dai.
- Table co cot quan trong truoc.
- Badge status.
- Action edit/delete/view gon.
- Pagination neu co.
- Empty state.

Form admin nen chia nhom:

- Thong tin chung.
- Gia va ton kho.
- Anh/preview.
- Attribute/variant.
- Status/visibility.

## 4. Storefront UI skill

Trang home/catalog:

- Banner/danh muc/san pham ro.
- Product card co anh, ten, gia, CTA.
- Fallback image.
- Search/filter de thay.

Product detail:

- Gallery/anh chinh.
- Ten, gia, status, mo ta.
- Attribute/variant options.
- Add cart ro.
- Related/recommendation neu co.

Cart:

- Item, quantity, price, subtotal.
- Remove action co confirm.
- Tong tien ro.
- CTA checkout.

Checkout:

- Thong tin nguoi nhan.
- Dia chi/city/shipping.
- Coupon.
- Payment method.
- Order summary sticky hoac ro.

## 5. AJAX skill

Pattern:

1. Bind event.
2. Prevent duplicate click.
3. Show loading.
4. Send Axios/jQuery request.
5. On success: update UI/toast/redirect.
6. On validation error: show field messages.
7. On system error: show fallback message.
8. Always restore state.

Pseudo:

```js
button.prop('disabled', true);
showLoading();

axios.post(url, payload)
  .then(function (response) {
    showSuccess(response.data.message || 'Thanh cong');
  })
  .catch(function (error) {
    showError(resolveMessage(error));
  })
  .finally(function () {
    button.prop('disabled', false);
    hideLoading();
  });
```

## 6. Animation skill

Dung animation de:

- Xac nhan action.
- Lam ro modal/dropdown.
- Giam cam giac cho khi loading.
- Huong mat vao loi validation.

Khong dung animation de:

- Lam admin table bi cham.
- Che noi dung.
- Tao layout shift.
- Bien checkout thanh trang gioi thieu.

## 7. AI dashboard UI skill

Moi AI suggestion card nen co:

- Title.
- Priority badge.
- Evidence/metric.
- Suggested action.
- Dismiss button.
- Status.

Analytics:

- Loading skeleton.
- Empty state.
- Chart labels.
- Time range filter neu co.
- Don vi ro: views, orders, revenue, conversion.

## 8. Responsive skill

Checklist:

- Product grid: 1 col mobile, 2 col tablet, 3-4 col desktop.
- Admin table: horizontal scroll mobile.
- Form: 1 col mobile, 2 col desktop.
- Checkout summary: below form mobile, side column desktop.
- Button: full-width mobile khi can.

## 9. Prompt skill

Khi viet prompt frontend, luon gom:

- File duoc sua.
- Route demo.
- Actor.
- State can co.
- Boundary khong sua backend.
- Verification.

Mau:

```text
Ban la Front-end Agent. Hay cai tien [page] trong Laravel Blade.
Chi sua [files].
Du lieu den tu [route/controller/bien Blade].
Can co loading, error, empty, success state.
Khong sua controller/model/migration.
Kiem tra tren mobile 360px va desktop 1366px.
```
