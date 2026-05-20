# Developing Backend Skill Pack

## 1. Muc dich

Skill pack nay huan luyen AI phat trien backend Laravel trong do an website sieu thi ban do an/nong san co AI goi y.

No dung cho:

- Back-end Agent.
- Tester Agent khi can hieu backend flow.
- Bao cao khi giai thich AI duoc "train" bang rules/skills/workflows.

## 2. Khi nao dung skill nay

Dung khi task lien quan:

- Route/controller/request/service/model.
- Migration/schema.
- Admin CRUD.
- User auth/profile.
- Product/attribute/variant.
- Cart/checkout/order/payment.
- AI analytics/recommendation.
- Microservice boundary.

## 3. Quy trinh bat buoc

1. Doc `agents/back-end-agent/Rules.md`.
2. Doc `agents/back-end-agent/PRD/Database_Schema.md`.
3. Doc `docs/API_Endpoint.md`.
4. Xac dinh file se sua.
5. Viet code theo Laravel convention.
6. Chay test lien quan.
7. Cap nhat docs neu contract/schema doi.

## 4. Backend coding rules

- Controller khong qua day.
- Service chua logic phuc tap.
- FormRequest chua validation.
- Transaction cho thao tac nhieu bang.
- Secret nam trong `.env`.
- Error JSON co `status`.
- Khong lo stack trace production.
- Khong sua frontend khi khong can.

## 5. Stage memory

- Stage01: admin auth + product core.
- Stage02: product modeling.
- Stage03: storefront.
- Stage04: user account.
- Stage05: cart/checkout/order/payment.
- Stage06: tracking/analytics/AI.
- Stage07: microservice boundary.

## 6. Done checklist

- Route/middleware dung.
- Validation dung.
- Service/transaction dung.
- Test pass.
- Docs update.
- Bao cao co the giai thich duoc thay doi.
