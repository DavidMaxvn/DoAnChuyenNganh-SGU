# Chang 7: Them Microservices Cho He Thong Hoan Chinh

## 1. Muc tieu chang 7

Chang 7 bo sung lop microservices vao he thong da co san pham, storefront, auth, cart, order, payment, tracking, analytics va AI.

Muc tieu cua chang nay khong phai la tach ca project thanh nhieu repo rieng ngay lap tuc. Muc tieu thuc te hon la tao cac service boundary ro rang ngay trong Laravel monolith:

- Inventory Service
- Pricing Service
- Checkout Orchestrator
- Outbox Service

Voi cach nay, code hien tai van chay duoc, nhung cac logic quan trong da duoc tach thanh tung khoi doc lap, co contract JSON va co the tach thanh microservice rieng trong tuong lai.

## 2. Phan tich he thong

### 2.1. Bai toan nghiep vu

Sau chang 6, he thong da la mot website ban hang co AI. Tuy nhien, cac luong nhu ton kho, tinh tien va checkout van nam trong monolith. Khi he thong lon hon, cac phan nay can tach bien ro hon vi:

- ton kho can duoc kiem tra va reserve truoc khi tao order
- pricing can tinh subtotal, shipping fee, coupon va grand total nhat quan
- checkout can dieu phoi nhieu buoc va co correlation id de trace
- outbox event can duoc luu de sau nay dispatch async sang queue hoac service khac

### 2.2. Tac nhan

- Frontend hoac API client goi endpoint microservices demo.
- Inventory Service kiem tra va reserve ton kho.
- Pricing Service tinh quote.
- Checkout Orchestrator dieu phoi cac service.
- Outbox Service luu event.

### 2.3. Use case cot loi

1. Kiem tra ton kho kha dung cho danh sach san pham.
2. Tinh quote checkout gom subtotal, shipping fee, coupon discount va grand total.
3. Mo phong checkout bang cach goi pricing va inventory.
4. Ghi outbox event de trace va chuan bi cho async processing.
5. Tu choi checkout neu ton kho khong du.

## 3. Thiet ke kien truc

### 3.1. Service boundary

Chang 7 chia logic thanh 4 service:

- `InventoryMicroservice`: check available stock va tao inventory reservation.
- `PricingMicroservice`: tinh quote dua tren product, city va coupon.
- `CheckoutOrchestratorService`: tao correlation id va dieu phoi pricing + inventory + outbox.
- `MicroserviceOutboxService`: luu event vao `microservice_outbox_events`.

### 3.2. Data model moi

Bang `inventory_reservations`:

- luu reservation group
- luu reservation code tung product
- luu quantity, status, expires_at
- luu correlation_id de trace

Bang `microservice_outbox_events`:

- luu service_name
- luu event_type
- luu payload JSON
- luu status `PENDING`
- luu correlation_id

### 3.3. Endpoint chay duoc

- `GET /vibe/stage-07/microservices/overview`
- `POST /vibe/stage-07/microservices/inventory/check`
- `POST /vibe/stage-07/microservices/pricing/quote`
- `POST /vibe/stage-07/microservices/checkout/simulate`
- `GET /vibe/stage-07/microservices/outbox`

## 4. Cai dat trien khai

### 4.1. Prompt hyper-specific cho chang 7

```text
Hay xay chang 7 cho project Laravel 9 sieu thi ban do an, build tren chang 6.
Toi can them microservices co tinh thuc tien, khong chi viet ly thuyet.

Pham vi:
- van giu project Laravel hien tai chay duoc
- them service boundary theo kieu microservices
- UI coi nhu da co san
- backend tra JSON

Microservices can co:
- Inventory Service: kiem tra ton kho kha dung va reserve hang truoc checkout
- Pricing Service: tinh subtotal, shipping fee, coupon discount va grand total
- Checkout Orchestrator: goi Pricing Service, Inventory Service va ghi event
- Outbox Service: luu event de sau nay co the dispatch async sang service khac

Bang du lieu moi:
- `inventory_reservations`
- `microservice_outbox_events`

Endpoint:
- `GET /vibe/stage-07/microservices/overview`
- `POST /vibe/stage-07/microservices/inventory/check`
- `POST /vibe/stage-07/microservices/pricing/quote`
- `POST /vibe/stage-07/microservices/checkout/simulate`
- `GET /vibe/stage-07/microservices/outbox`

Nghiep vu:
- inventory check phai tru ton kho da bi reserve nhung chua het han
- reserve tao reservation group, reservation code va het han sau 15 phut
- pricing phai lay gia product, phi ship city va coupon hien co
- checkout simulation phai tao correlation_id de trace qua nhieu service
- moi service quan trong phai ghi event vao outbox
- neu ton kho khong du thi checkout simulation tra loi 422

Kien truc:
- route rieng cho Stage07
- controller mong, chi nhan request va tra response
- request validation rieng cho inventory, pricing va checkout simulation
- service rieng cho inventory, pricing, checkout orchestrator va outbox
- feature test cho cac luong chinh
```

### 4.2. File moi cua chang 7

- `routes/vibe_stage_07_microservices.php`
- `database/migrations/2026_04_22_000001_create_inventory_reservations_table.php`
- `database/migrations/2026_04_22_000002_create_microservice_outbox_events_table.php`
- `app/Http/Controllers/Vibe/Stage07/MicroserviceController.php`
- `app/Http/Requests/Vibe/Stage07/InventoryCheckRequest.php`
- `app/Http/Requests/Vibe/Stage07/PricingQuoteRequest.php`
- `app/Http/Requests/Vibe/Stage07/CheckoutSimulationRequest.php`
- `app/Services/Vibe/Stage07/InventoryMicroservice.php`
- `app/Services/Vibe/Stage07/PricingMicroservice.php`
- `app/Services/Vibe/Stage07/CheckoutOrchestratorService.php`
- `app/Services/Vibe/Stage07/MicroserviceOutboxService.php`
- `tests/Concerns/CreatesStage07Schema.php`
- `tests/Feature/Vibe/Stage07/MicroserviceArchitectureTest.php`

### 4.3. Lenh migrate

```bash
php artisan migrate --path=database/migrations/2026_04_22_000001_create_inventory_reservations_table.php
php artisan migrate --path=database/migrations/2026_04_22_000002_create_microservice_outbox_events_table.php
```

## 5. Kiem thu phan mem

### 5.1. Muc tieu kiem thu

Can chung minh:

1. Microservice overview mo ta dung boundary.
2. Inventory Service tru reservation dang active khoi available stock.
3. Pricing Service tinh dung subtotal, shipping fee, coupon discount va grand total.
4. Pricing Service ghi event vao outbox.
5. Checkout Orchestrator goi pricing, inventory va outbox bang correlation id.
6. Checkout bi tu choi khi ton kho khong du.

### 5.2. Lenh kiem thu

```bash
php artisan test --filter=Stage07
```

### 5.3. Ket qua

- `5 passed`

## 6. Gia tri cua chang 7

Chang 7 giup do an co them yeu to kien truc thuc te:

- cho thay he thong khong chi la CRUD monolith
- co service boundary ro rang
- co outbox pattern de chuan bi cho event-driven architecture
- co correlation id de trace luong checkout
- co test rieng de chung minh microservices layer chay duoc

Neu can nang cap tiep, chang 7 co the phat trien thanh:

- API gateway
- service token authentication
- queue worker dispatch outbox event
- tach inventory/pricing thanh service rieng
- docker compose cho nhieu service doc lap
