# Front-end Rules

## Phan 1. Giao dien va tham my

1. Premium and dynamic

- Giao dien can hien dai, gon, co chuyen dong nhe khi co y nghia.
- Khong dung layout qua cung nhac neu lam nguoi dung kho scan.
- Khong trang tri lam mo muc tieu chinh: mua hang/admin thao tac.

2. Component doc lap

- Blade partial nen tach cho item lap lai: product card, cart row, form group, alert, action button.
- Du lieu truyen vao partial ro rang.
- Khong copy/paste nhieu block HTML lon neu co the tach partial.

3. CSS/asset consistency

- Uu tien style hien co cua theme.
- Khong them framework moi neu khong co yeu cau.
- Neu co TailwindCSS o phien ban sau, phai dung token ro.
- Khong pha class/selector dang duoc JS hien co su dung.

4. Visual hierarchy

- Admin: title, filter, table, action phai ro.
- Storefront: anh, ten, gia, CTA phai ro.
- Checkout: tong tien, phi ship, discount, payment phai ro.
- AI dashboard: metric, chart, suggestion phai ro.

## Phan 2. UX states

Bat buoc xu ly:

- Loading state.
- Empty state.
- Error state.
- Success state.
- Disabled state khi dang submit.
- Confirmation state cho delete/cancel/dismiss/payment.

Form:

- Label gan input.
- Required field co dau hieu ro.
- Error message gan field.
- Old input duoc giu neu validate fail.
- Submit khong click lap nhieu lan.

AJAX:

- Button loading.
- Catch network error.
- Hien message tu backend neu co.
- Cap nhat UI sau success.

## Phan 3. Animation

- Animation phai nhanh, duoi 200ms cho UI thong thuong.
- Dung fade/slide nhe cho modal/dropdown/filter.
- Product card hover nhe.
- Sidebar expand/collapse muot.
- Khong animate table qua nhieu.
- Khong gay layout shift.

Neu dung Framer Motion trong phien ban moi:

- Page transition: opacity + translateY nhe.
- Route changes: soft transition.
- Sidebar: smooth expand/collapse.
- Respect reduced motion.

## Phan 4. Responsive

- Mobile 360px khong tran text.
- Tablet 768px grid khong vo.
- Desktop 1366px layout can doi.
- Table admin qua rong thi co horizontal scroll.
- Button text dai phai wrap hoac rut gon hop ly.
- Image co aspect ratio/fallback.

## Phan 5. Boundaries

- Khong sua controller/service/model/migration neu task frontend.
- Khong doi route name.
- Khong doi request payload neu chua thong nhat voi Backend Agent.
- Khong hard-code data neu controller da truyen bien.
- Khong sua `vendor`, `node_modules`.
- Khong xoa asset dang dung neu chua tim usage.

## Phan 6. Accessibility

- Input co label.
- Button co text hoac title ro.
- Link/action focus duoc.
- Mau error/success co contrast du.
- Icon khong la thong tin duy nhat neu action quan trong.
- Modal co nut dong ro.

## Phan 7. Performance

- Khong load anh full-size neu chi can thumbnail.
- Khong goi AJAX lap lien tuc khi search neu khong debounce.
- Khong them animation/script nang cho admin table.
- Khong duplicate library da co trong layout.

## Phan 8. Review checklist

Truoc khi chot:

- Man hinh chay duoc voi data that.
- Empty/error/loading duoc xu ly.
- Responsive ok.
- Console khong co loi JS.
- Form khong mat old input.
- Button khong submit duplicate.
- Route name va selector hien co khong bi pha.
