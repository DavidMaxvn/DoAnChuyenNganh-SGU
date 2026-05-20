# Back-end Workflows

## 1. Workflow tong quat

Moi task backend di qua 9 buoc:

1. Doc yeu cau.
2. Xac dinh actor va flow.
3. Doc route/controller/service/model lien quan.
4. Doc `MCP.md` neu task can tool, test, log, database, browser verify hoac bang chung.
5. Xac dinh schema va transaction.
6. Sua code.
7. Chay test hoac chay route verify.
8. Cap nhat docs.
9. Bao cao file da sua va ket qua.

## 2. Workflow tao feature moi

### Step 1: Define

Tra loi:

- Ai dung feature?
- Route nao?
- Request gom field nao?
- Response/view nao?
- Bang nao bi anh huong?
- Loi nao can xu ly?

### Step 2: Design

Chon:

- Controller method.
- FormRequest.
- Service method.
- Model relationship.
- Migration neu can.
- Test scenario.

### Step 3: Implement

Thu tu de tranh loi:

1. Migration/model relationship neu can.
2. FormRequest validation.
3. Service logic.
4. Controller.
5. Route.
6. View/response contract neu backend phai cung cap.
7. Test.
8. Docs.

### Step 4: Verify

Chay test gan nhat:

```bash
php artisan test --filter=StageXX
```

Neu feature thuoc admin/web cu, verify bang browser route va ghi checklist.

## 3. Workflow sua bug

1. Reproduce: tim route/test tai hien.
2. Isolate: xac dinh bug o validation, service, query, middleware hay view contract.
3. Patch: sua nho nhat.
4. Regression: them test neu bug co nguy co lap lai.
5. Document: cap nhat Check neu them case moi.

Khong sua lan sang refactor lon khi chi can fix bug nho.

## 4. Workflow product modeling

1. Xac dinh product root/child.
2. Load attributes/values.
3. Check type simple/configurable.
4. Validate shared attributes.
5. Validate variant attributes.
6. Check duplicate variant.
7. Save gallery/image.
8. Return snapshot cho admin/storefront.
9. Test duplicate, missing field, success.

## 5. Workflow storefront backend

1. Chi lay product active/status hop le.
2. Neu catalog, paginate/sort/filter.
3. Neu search, validate query va empty state.
4. Neu detail, resolve variant ve parent neu can.
5. Track view neu route yeu cau.
6. Tra data du cho Blade: product, category, related, variants, images.

## 6. Workflow account

1. Register: validate, hash password, create user, login/redirect.
2. Login: check credentials, check status, regenerate session.
3. Logout: invalidate session.
4. Profile: authorize user, validate, update.
5. Forgot password: tao token, gui mail.
6. Reset password: verify token, hash password, clear token.
7. Social login: validate provider payload, link/create account.

## 7. Workflow checkout

1. Require `auth:web`.
2. Load cart by user.
3. Validate cart not empty.
4. Validate shipping info/city.
5. Recalculate price server-side.
6. Apply coupon server-side.
7. Start transaction.
8. Create order.
9. Create order products.
10. Update stock/reservation if implemented.
11. Clear/update cart.
12. Commit.
13. Trigger payment redirect or success.
14. Rollback on failure.

## 8. Workflow payment callback

1. Read provider payload.
2. Verify signature/config.
3. Find order.
4. Check amount/currency/order id.
5. If already paid, return idempotent success.
6. Update payment status.
7. Update order status.
8. Log payment event.
9. Redirect success/error.

## 9. Workflow AI dashboard

1. Load activity/order/product metrics.
2. Aggregate by time range.
3. Build suggestions.
4. Return analytics JSON.
5. Dismiss suggestion by id.
6. Avoid exposing raw sensitive data.
7. Add fallback if no data.

## 10. Workflow microservices stage 7

Inventory check:

1. Validate items.
2. Check product exists.
3. Check quantity.
4. Return availability result.

Pricing quote:

1. Validate items/coupon/city.
2. Recalculate subtotal.
3. Apply coupon.
4. Add shipping.
5. Return quote breakdown.

Checkout orchestrator:

1. Call inventory check.
2. If fail, return error.
3. Call pricing quote.
4. If fail, return error.
5. Create outbox event.
6. Return simulation result.

## 11. Workflow documentation update

Sau moi task:

- Route moi: update `docs/API_Endpoint.md`.
- Schema moi: update `Database_Schema.md`.
- Flow moi: update `UserStories.md`.
- Check moi: update `docs/Check.md`.
- Prompt/agent lesson moi: update `Skill.md` hoac `Workflows.md`.
- Tool/MCP lesson moi: update `MCP.md`.
