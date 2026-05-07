# Check and Verification Plan

## 1. Muc dich

File nay la checklist kiem tra do an truoc khi nop bao cao, quay demo hoac thuyet trinh. Checklist gom 4 lop:

1. Check cau truc tai lieu AI Agent.
2. Check source code Laravel theo tung stage.
3. Check UI/UX admin va storefront.
4. Check bao cao/slide co du bang chung.

## 2. Check cau truc tai lieu AI Agent

Bat buoc co cac folder/file:

- `agents/back-end-agent/AGENT.md`
- `agents/back-end-agent/Rules.md`
- `agents/back-end-agent/Skill.md`
- `agents/back-end-agent/Workflows.md`
- `agents/back-end-agent/MCP.md`
- `agents/back-end-agent/Plan.md`
- `agents/back-end-agent/PRD/PRD.md`
- `agents/back-end-agent/PRD/UserStories.md`
- `agents/back-end-agent/PRD/Database_Schema.md`
- `agents/front-end-agent/AGENT.md`
- `agents/front-end-agent/Rules.md`
- `agents/front-end-agent/Skill.md`
- `agents/front-end-agent/Workflows.md`
- `agents/tester-agent/AGENT.md`
- `agents/tester-agent/Rules.md`
- `agents/tester-agent/Skill.md`
- `agents/tester-agent/Workflows.md`
- `skills/developing-backend/developing-backend.md`
- `skills/developing-backend/backend-api-SKILL.md`
- `skills/developing-backend/create-skills.md`
- `skills/developing-frontend/frontend-ui-SKILL.md`
- `skills/testing-qa/qa-SKILL.md`
- `docs/API_Endpoint.md`
- `docs/Frontend_Prompt_Design.md`
- `docs/AI_Agent_Training_Report.md`

Tieu chi dat:

- Moi file co muc dich ro.
- Rules tach rieng frontend/backend/tester.
- PRD co actor, scope, out-of-scope, success metrics.
- UserStories dung format "As a..., I want..., so that...".
- Acceptance Criteria dung mau "Whenever... then...".
- Database_Schema lien ket voi migration hien co.
- API_Endpoint co route admin, user, vibe stage, microservices.
- MCP.md co noi ro nguon context, tool AI dung, gioi han an toan va verify command.

## 3. Check stage code

| Stage | Command goi y | Expected |
| --- | --- | --- |
| Stage01 | `php artisan test --filter=Stage01` | Admin auth + product core pass |
| Stage02 | `php artisan test --filter=Stage02` | Product modeling pass |
| Stage03 | `php artisan test --filter=Stage03` | Storefront catalog pass |
| Stage04 | `php artisan test --filter=Stage04` | User account pass |
| Stage05 | `php artisan test --filter=Stage05` | Cart/checkout/order/payment pass |
| Stage06 | `php artisan test --filter=Stage06` | Tracking/analytics/AI pass |
| Stage07 | `php artisan test --filter=Stage07` | Microservices boundary pass |

Neu chay full:

```bash
php artisan test
```

Truoc khi chay:

- `.env` co database dung.
- `APP_KEY` da generate.
- Composer dependencies da co trong `vendor`.
- Database test khong dung nham database production.

## 4. Check backend production

Authentication:

- Admin login dung guard `admin`.
- User login dung guard `web`.
- Guest khong vao duoc route admin.
- User bi khoa bi chan boi middleware `checkStatusUser`.

Validation:

- Product store/update validate name, price, quantity, category, image.
- Attribute/variant validate duplicate combination.
- Register/login/profile/reset password validate input.
- Coupon/order/payment validate amount, status, city/shipping.

Security:

- Secret payment, mail, database nam trong `.env`.
- Khong commit `.env`.
- Error khong lo stack trace khi production.
- Upload image check extension/size.
- Admin route nam sau middleware `auth:admin`.

Data integrity:

- Tao order gom order + order_products + cart cleanup nen dung transaction.
- Payment callback khong tao trung don.
- Coupon chi ap dung dung dieu kien.
- Inventory check truoc khi checkout.
- Outbox event ghi du thong tin neu checkout simulation thanh cong.

## 5. Check frontend/admin UI

Admin:

- Login form co error ro.
- Dashboard load khong loi.
- Product index scan duoc, action edit/delete ro.
- Product create/edit co preview image.
- Attribute/category/banner/coupon/city CRUD thao tac duoc.
- Order index va order edit hien trang thai dung.
- AI dashboard co metric, chart/analytics, suggestion, dismiss.

Storefront:

- Home hien banner/category/product.
- Product detail hien anh, gia, attribute/variant neu co.
- Search tra dung ket qua va empty state.
- Add cart co login guard.
- Cart cap nhat/xoa item ro.
- Checkout co thong tin user, city/shipping, coupon/payment.
- Order success/error co thong tin tiep theo.

Responsive:

- Mobile 360px: menu, product card, cart khong tran.
- Tablet 768px: grid khong vo.
- Desktop 1366px: admin table va storefront layout can doi.

## 6. Check AI features

Tracking:

- Product detail route co middleware `track.product.view`.
- Activity log luu du user/session/product/event.
- Khong ghi data nhay cam vao activity log.

Recommendation:

- `/recommend` co ket qua khi input hop le.
- `/ai/recommend` co fallback khi AI service loi.
- UI khong bi treo khi AI response cham.

AI dashboard:

- Analytics endpoint tra JSON hop le.
- Suggestion co priority/action/status.
- Dismiss suggestion cap nhat UI va database.
- Empty state neu chua co activity log.

## 7. Check report and slide

Bao cao can co:

- Ly do chon quy trinh vibe coding co kiem soat.
- Cau truc AI Agent: backend, frontend, tester.
- Bang 7 stage phat trien.
- Prompt mau cho tung stage.
- Danh sach file code quan trong.
- Ket qua test theo stage.
- Anh/chung cu demo man hinh admin, storefront, AI dashboard.
- Ranh gioi: AI ho tro, con nguoi kiem soat.

Slide can co:

1. Ten de tai va muc tieu.
2. Bai toan nghiep vu.
3. Kien truc Laravel + AI + microservices boundary.
4. Quy trinh AI Agent.
5. 7 stage phat trien.
6. Demo admin/product.
7. Demo storefront/cart/order/payment.
8. Demo tracking/AI dashboard.
9. Kiem thu.
10. Ket luan va huong phat trien.

## 8. Definition of done

Mot hang muc duoc xem la xong khi:

- Code chay duoc trong project Laravel.
- Co validation va error handling.
- Co UI state phu hop neu la man hinh.
- Co test hoac cach verify ro.
- Tai lieu lien quan da cap nhat.
- Khong sua file ngoai pham vi agent neu khong co ly do.
