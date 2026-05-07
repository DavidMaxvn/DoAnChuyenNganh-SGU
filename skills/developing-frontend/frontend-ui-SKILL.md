# Frontend UI Skill

## 1. Purpose

Skill nay huan luyen AI khi sua giao dien Laravel Blade cua project. Muc tieu la tao UI dung nghiep vu, co state ro va khong pha backend contract.

## 2. When to use

Dung khi task lien quan:

- Admin Blade page.
- Storefront Blade page.
- Product card/detail.
- Cart/checkout UI.
- AI dashboard UI.
- AJAX interaction.
- Responsive pass.
- Animation/transition.

## 3. Inputs

Truoc khi sua can doc:

- `agents/front-end-agent/Rules.md`
- `docs/Frontend_Prompt_Design.md`
- Route trong `routes/web.php` hoac `routes/admin.php`
- View/partial hien co trong `resources/views`
- JS hien co neu co interaction

## 4. Steps

1. Xac dinh actor va goal.
2. Xac dinh route/view.
3. Xac dinh data Blade/API.
4. Thiet ke state: loading, empty, error, success.
5. Sua view/JS/CSS.
6. Kiem tra responsive.
7. Kiem tra console/interaction.
8. Ghi lai route demo.

## 5. Rules

- Khong sua backend khi task frontend.
- Khong doi route name.
- Khong hard-code data neu co bien Blade.
- Khong them framework lon tuy tien.
- Khong animate qua muc.
- Khong de text tran container.

## 6. Verification

- Mo route demo.
- Thu mobile 360px, tablet 768px, desktop 1366px.
- Thu form success/fail.
- Thu empty data neu co the.
- Kiem tra console.

## 7. Example prompt

```text
Hay cai tien trang cart Laravel Blade.
Chi sua resources/views/web/cart/list.blade.php va JS lien quan neu can.
Can co empty state, confirm khi xoa item, tong tien ro, CTA checkout.
Khong sua controller/model/route.
Kiem tra mobile 360px va desktop 1366px.
```
