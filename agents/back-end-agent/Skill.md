# Back-end Skill

## 1. Skill overview

Back-end Skill la bo ky nang cho AI khi phat trien Laravel backend trong do an. Skill nay tap trung vao:

- Laravel route/controller/request/service/model.
- Admin auth va user auth.
- Product modeling: simple/configurable/variant.
- Cart, checkout, order, payment.
- Tracking, analytics, AI suggestion.
- Microservice boundary: inventory, pricing, checkout orchestrator, outbox.
- MCP/tool usage: doc context, search code, patch file, verify va bao cao bang chung.

## 2. Laravel skill map

### Route design

Khi tao route:

- Xac dinh actor: guest, user, admin, system.
- Chon file route dung: `admin.php`, `web.php`, `api.php`, `vibe_stage_*.php`.
- Dat middleware dung.
- Dat route name on dinh.
- Tra view neu la luong UI Blade, tra JSON neu la stage/API.

Pattern:

```php
Route::middleware('auth:admin')->group(function () {
    Route::get('ai-dashboard', [AIDashboardController::class, 'index'])->name('ai.dashboard');
});
```

### Controller design

Controller nen:

- Nhan FormRequest neu co input.
- Goi service cho logic phuc tap.
- Tra response/redirect gon.
- Khong de query/transaction dai nam het trong controller.

### FormRequest skill

Dung FormRequest khi:

- Co nhieu field validate.
- Co rule phu thuoc nhau.
- Co authorize logic.
- Loi validation can message ro.

### Service skill

Dung service khi:

- Logic co nhieu buoc.
- Nhieu controller cung dung.
- Can transaction.
- Can de test rieng.

Vi du service hien co:

- `ProductModelingService`
- `StorefrontCatalogService`
- `UserAccountService`
- `InventoryMicroservice`
- `PricingMicroservice`
- `CheckoutOrchestratorService`
- `MicroserviceOutboxService`

## 3. Product modeling skill

Product trong project co the gom:

- Root product.
- Child product/variant thong qua `parent_id`.
- Attribute va value.
- Product gallery.
- Type simple/configurable.
- Config attribute cho variant.

Khi sua product:

1. Xem product la root hay child.
2. Check `parent_id`.
3. Check `type`.
4. Check price/quantity.
5. Check image/gallery.
6. Check duplicate variant.
7. Check storefront chi hien root product khi can.

Acceptance hints:

- Whenever admin creates configurable product, then system shall save shared attributes and variant attributes separately.
- Whenever duplicate variant combination is submitted, then system shall reject it with validation error.
- Whenever storefront detail receives variant id, then system shall resolve parent product if required.

## 4. Cart and checkout skill

Luong checkout an toan:

1. Lay user tu session auth.
2. Lay cart hien tai.
3. Validate cart khong rong.
4. Check product/variant con ton tai.
5. Check quantity.
6. Tinh gia, coupon, shipping.
7. Tao order trong transaction.
8. Tao order_products.
9. Cap nhat cart/payment state.
10. Gui mail/log event neu can.

Rui ro can chan:

- Cart item tham chieu product da xoa.
- Gia bi client sua.
- Coupon het han.
- Payment callback sai amount.
- User bam submit nhieu lan.

## 5. Payment skill

Voi MoMo/VNPAY:

- Config nam trong `.env`.
- Callback verify signature neu production.
- Order status chi update khi amount/status hop le.
- Callback nen idempotent.
- Loi payment dua user ve trang error co thong tin de thu lai.

Khong nen:

- Tin amount tu query client neu chua verify.
- Hard-code secret.
- Tao order moi trong return callback neu order da co.

## 6. AI and analytics skill

Data nguon:

- Activity logs.
- Product views.
- Orders.
- Cart actions.
- Search/recommend input.
- AI suggestions.

Khi tao AI suggestion:

- Co title/action/priority/status.
- Co evidence hoac metric lien quan.
- Co fallback khi khong du data.
- Co dismiss action.

Recommendation fallback:

1. Neu AI service co ket qua hop le, dung ket qua AI.
2. Neu AI timeout/loi, dung rule-based recommendation.
3. Neu khong co du data, tra product pho bien/ban moi.

## 7. Microservice boundary skill

Stage 7 mo phong kien truc:

- Inventory service check ton kho/reservation.
- Pricing service tinh quote, coupon, shipping.
- Checkout orchestrator goi inventory + pricing + outbox.
- Outbox service ghi event de publish sau.

Rule:

- Service boundary khong can tach server that trong do an, nhung code phai the hien hop dong ro.
- Outbox event phai co event type, payload, status.
- Checkout simulation phai rollback/tra loi ro khi inventory hoac pricing fail.

## 8. Debug skill

Khi loi backend:

1. Doc stack trace/local log neu co.
2. Xac dinh route va middleware.
3. Xem request payload.
4. Xem validation.
5. Xem service logic.
6. Xem query/database.
7. Viet hoac chay test tai hien loi.
8. Sua nho nhat co the.

## 9. Documentation skill

Moi thay doi backend lon can cap nhat:

- `docs/API_Endpoint.md` neu route/request/response doi.
- `agents/back-end-agent/PRD/Database_Schema.md` neu bang/cot doi.
- `agents/back-end-agent/PRD/UserStories.md` neu acceptance criteria doi.
- `docs/Check.md` neu them verify step.
- `agents/back-end-agent/MCP.md` neu them/sua cach AI dung tool, MCP server, verify command hoac nguon context.

## 10. MCP/tool skill

Khi can dung tool hoac MCP:

1. Doc `MCP.md` de biet nguon context va gioi han.
2. Dung `rg` de tim route/controller/service/test truoc khi sua.
3. Doc file lien quan thay vi doan theo ten file.
4. Sua bang patch nho, khong revert thay doi cua nguoi dung.
5. Chay test/check gan nhat neu co the.
6. Ghi ro bang chung verify trong bao cao ket qua.

Rule:

- MCP/tool chi la kenh ho tro, khong vuot qua `Rules.md`.
- Khong doc/ghi secret neu khong co ly do ro.
- Khong chay lenh destructive nhu reset/drop/truncate neu chua duoc yeu cau.
- Neu MCP resource khong co, dung local repo docs va terminal/search lam fallback.

## 11. Prompt memory

Back-end Agent nen tu nhac:

```text
Toi la Back-end Agent. Toi phai giu validation, transaction, security, route contract, MCP/tool boundary va test. Toi khong sua frontend neu khong can. Moi behavior moi phai co cach verify va tai lieu contract.
```
