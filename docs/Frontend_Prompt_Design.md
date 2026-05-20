# Frontend Prompt Design

## 1. Muc dich tai lieu

Tai lieu nay dung de huan luyen Front-end Agent khi lam giao dien cho do an website sieu thi ban do an/nong san co AI goi y. Muc tieu khong phai chi lam giao dien "dep", ma la lam giao dien de nguoi dung mua hang nhanh hon, admin thao tac it loi hon va cac luong AI/analytics duoc trinh bay de hieu.

Project hien tai dung Laravel Blade, Laravel Mix, CSS/JS truyen thong, Select2, SweetAlert2, Axios va cac asset trong `public/theme`. Neu sau nay nang cap sang React/NextJS/Framer Motion thi van giu cung nguyen tac animation va UX trong file nay.

## 2. Nguyen tac prompt frontend

Moi prompt giao cho AI Front-end Agent phai co du 7 thanh phan:

1. Context: man hinh nao, user nao su dung, du lieu den tu controller/service nao.
2. Goal: nguoi dung can hoan thanh viec gi tren man hinh.
3. Scope: chi duoc sua nhung file view/asset nao.
4. Data contract: bien Blade, route name, JSON response, validation message.
5. UX state: loading, empty, error, success, disabled, confirmed action.
6. Accessibility: label, focus state, contrast, keyboard navigation neu co form.
7. Verification: cach test bang browser, route, feature test hoac screenshot.

Vi du prompt tot:

```text
Hay cai tien giao dien trang admin product create trong Laravel Blade.
Pham vi chi sua resources/views/admin/product/create.blade.php va partial lien quan trong resources/views/admin/product.
Khong sua controller, migration, model.
Muc tieu: admin tao product cha/con, them attribute, upload anh va thay preview ro rang.
Can co state loading khi submit, SweetAlert2 khi loi validate, giu lai old input.
Khong lam thay doi route name hien co.
Sau khi sua, kiem tra bang route admin.products.create va chay test Stage02 neu lien quan.
```

## 3. Animation principles

Neu co dung Framer Motion trong phien ban frontend moi, animation phai tuan thu:

- Animation phai lam ro trang thai UI, khong chi de trang tri.
- Toc do nhanh, nhe, it gay cham thao tac.
- Khong over-animation voi form admin, table, checkout.
- Khong lam layout shift khi danh sach product/cart thay doi.
- Motion phai co fallback khi thiet bi yeu hoac nguoi dung bat reduce motion.

Neu van dung Laravel Blade/jQuery:

- Dung CSS transition ngan cho hover/focus/menu.
- Dung fade/slide nhe cho dropdown, modal, filter panel.
- Dung loading overlay chi khi can chan thao tac lap.
- Khong animate table row qua muc lam admin kho scan du lieu.

## 4. Required animations

### Page and layout

- Page transition: fade + slight slide down/up, opacity tu 0 den 1, translateY tu 8px ve 0.
- Route-like transition trong Blade: fade-in vung noi dung chinh khi load trang.
- Admin dashboard/card metric: stagger nhe, moi item tre 40-60ms.
- Storefront product cards: hover nang nhe, shadow ro hon, anh khong phong to qua manh.

### Sidebar/admin navigation

- Sidebar expand/collapse muot, khong giat width.
- Active menu phai co state ro: background, border-left hoac icon color.
- Submenu mo/dong dung max-height + opacity, thoi gian duoi 180ms.
- Mobile admin menu phai co overlay va nut dong de tranh mac ket.

### Forms

- Input focus: border/color ring ro nhung khong gay choi.
- Validation error: hien message gan field, co transition nhe.
- Submit button: loading spinner nho, disabled trong luc request.
- Upload image: preview fade-in, nut xoa anh co confirm.

### Storefront

- Product card hover: nhe, co y nghia goi click.
- Add-to-cart: nut chuyen sang loading, thanh cong hien toast.
- Search suggestion: dropdown fade/slide nhe, co empty state.
- Cart quantity change: cap nhat tong tien khong nhay layout.

### Checkout/payment

- Step indicator: ro buoc hien tai, khong dung animation gay roi.
- Payment redirect MoMo/VNPAY: loading state va text trang thai.
- Order success/error: icon/state ro, co call-to-action quay ve don hang.

### AI dashboard

- Metric card: animate number nhe khi load.
- Suggestion list: item moi fade-in.
- Dismiss suggestion: collapse item sau khi API thanh cong.
- Chart/filter: dung skeleton trong luc lay analytics data.

## 5. UI style direction

### Admin

- Uu tien ro rang, de scan, khong lam marketing layout.
- Bang du lieu can co sticky header neu dai, action icon gon.
- Form tao/sua can chia nhom: thong tin chung, gia/ton kho, anh, attribute, SEO neu co.
- Mau sac nen trung tinh, nhan bang 1-2 mau chinh cho action.

### Storefront

- Anh san pham phai la tin hieu chinh.
- Gia, ten, ton kho, trang thai khuyen mai phai nhin thay nhanh.
- Search/filter phai dat gan danh sach product.
- Cart/checkout can giam nhiem vu cua user: it field, ro loi, ro tong tien.

### AI/Analytics

- Khong bien AI dashboard thanh trang tri.
- Moi suggestion phai tra loi: van de gi, muc do uu tien, hanh dong de xuat, tac dong mong doi.
- Bieu do can co label, don vi, thoi gian, empty state.

## 6. Prompt templates

### Template cho Blade page

```text
Ban la Front-end Agent cho Laravel Blade.
Man hinh: [ten man hinh].
Nguoi dung: [admin/user].
Muc tieu nghiep vu: [ket qua].
Du lieu hien co: [bien Blade/route/API].
Pham vi file duoc sua: [duong dan].
Yeu cau UI: [layout/components/states].
Yeu cau UX: [loading/error/empty/success].
Khong duoc sua: [controller/model/migration/module khac].
Kiem tra: [route/test/cach verify].
```

### Template cho AJAX interaction

```text
Hay them/cai tien AJAX interaction cho [hanh dong].
Dung Axios/jQuery pattern hien co cua project.
Can co loading state, disabled repeat click, xu ly loi validate va toast thanh cong.
Neu API tra loi fail thi hien message tu backend, khong hard-code noi dung sai voi server.
Chi sua [file JS/Blade].
```

### Template cho responsive pass

```text
Hay kiem tra va sua responsive cho [page].
Breakpoint can ho tro: mobile 360px, tablet 768px, desktop 1366px.
Khong de text tran nut/card/table.
Table admin neu qua rong thi dung horizontal scroll co header ro.
Storefront grid can tu 1 cot len 2/3/4 cot tuy man hinh.
```

## 7. Do and don't

Do:

- Tai su dung layout `master_admin`, `master_user` va partial san co.
- Giu route name hien tai.
- Giu validation message tu backend neu co.
- Toi uu image preview va loading state.
- Viet UI de thuyet trinh duoc: ro truoc/sau, ro loi ich.

Don't:

- Khong sua logic backend trong task frontend.
- Khong doi ten route hoac bien Blade tuy tien.
- Khong them framework lon neu khong co yeu cau.
- Khong tao landing page giai thich thay cho man hinh chuc nang that.
- Khong lam animation lam cham admin/product/checkout.

## 8. Checklist truoc khi chot frontend

- Man hinh co loading, empty, error, success state.
- Form co label, validate, old value, disabled submit.
- Table/list scan duoc tren desktop va mobile.
- Anh san pham/banner khong meo, co fallback.
- Action nguy hiem co confirm.
- Toast/modal khong che noi dung quan trong.
- Text khong tran container.
- Khong sua ngoai pham vi frontend agent.
- Neu co JS moi, khong pha script hien co.
