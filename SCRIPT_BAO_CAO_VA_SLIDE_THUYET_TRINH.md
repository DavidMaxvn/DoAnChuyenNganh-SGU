# Script bao cao va PowerPoint ve quy trinh vibe coding

File nay gom 3 phan:

1. Script viet vao bao cao: ban chat quy trinh, diem nhan da lam, vi sao lam.
2. Noi dung PowerPoint: goi y tung slide kem loi thuyet trinh.
3. Bang "nhom con nguoi prompt gi, lam gi" trong suot quy trinh.

## Phan 1. Script cho bao cao

### 1. Ban chat quy trinh la gi

Ban chat cua quy trinh trong do an nay la "vibe coding co kiem soat". Nghia la nhom khong viet toan bo du an theo cach truyen thong tu dau den cuoi, cung khong phu thuoc vao mot prompt duy nhat. Nhom chia bai toan website ban nong san thanh nhieu chang nho, moi chang co muc tieu rieng, co prompt rieng, co code sinh ra, co buoc chay thu, sua loi, bo sung validation va test.

Diem quan trong la AI chi dong vai tro tang toc viec sinh code va goi y cau truc. Con nguoi van la ben quyet dinh:

- chon thu tu phat trien tinh nang;
- gioi han pham vi tung chang;
- viet prompt ngay cang cu the sau khi test;
- doc va dieu chinh code AI sinh ra;
- them test de chung minh tinh nang chay duoc;
- tong hop thanh tai lieu bao cao va slide.

Vi vay, quy trinh khong phai la "prompt mot cau ra ca du an", ma la mot vong lap:

1. Xac dinh muc tieu chang.
2. Viet prompt MVP cho phan nho nhat co the chay.
3. AI sinh code ban dau.
4. Chay thu tren project Laravel that.
5. Nhin ra thieu schema, route, validation, guard, service hay test.
6. Prompt lai cu the hon.
7. Chot chang bang code, endpoint va feature test.
8. Moi sang chang tiep theo.

Do an duoc ke lai thanh 7 chang lon:

| Chang | Ban chat | Ket qua |
| --- | --- | --- |
| 1 | Khoi tao nen tang Laravel, admin auth, product core | Co backend toi thieu de admin dang nhap va tao san pham |
| 2 | Mo hinh hoa san pham | Co attribute, variant, gallery, simple/configurable product |
| 3 | Storefront public | Nguoi dung xem, tim kiem, xem chi tiet san pham |
| 4 | User account | Co register, login, profile, reset password, social callback |
| 5 | Giao dich mua hang | Co cart, checkout, order, coupon, shipping, MoMo, VNPAY |
| 6 | Tracking, analytics va AI | Co activity log, chi so kinh doanh, AI dashboard, AI goi y |
| 7 | Microservices boundary | Co inventory service, pricing service, checkout orchestrator, outbox |

Ket luan ngan gon: quy trinh nay di tu nen tang e-commerce truoc, sau do moi nang cap thanh he thong e-commerce co du lieu, AI va dinh huong microservices.

### 2. Diem nhan 1: Lam admin va product core truoc

O chang 1, nhom khong bat dau bang giao dien dep hay AI ngay. Nhom bat dau tu phan loi: Laravel chay duoc, admin dang nhap duoc va co bang product de nhap du lieu.

Da lam:

- Tao guard rieng cho admin.
- Tao route rieng cho stage 1: `routes/vibe_stage_01_admin.php`.
- Tao controller auth admin va product admin.
- Tao request validation cho login va product store.
- Tao product co `parent_id` de chuan bi cho bien the o chang sau.
- Viet test Stage01 de kiem tra login, chan guest, tao root product va child product.

Vi sao lam nhu vay:

- Website ban hang can du lieu san pham truoc khi co storefront.
- Neu chua co admin, nguoi quan tri khong co noi nhap hang hoa.
- Neu product khong co `parent_id`, cac chang sau se kho mo rong thanh san pham cha/con.

Chi ra trong code:

- `routes/vibe_stage_01_admin.php`: tach route login va product management.
- `app/Http/Controllers/Vibe/Stage01/Admin/AuthController.php`: xu ly login/logout admin.
- `app/Http/Controllers/Vibe/Stage01/Admin/ProductController.php`: tao va xem product.
- `app/Http/Requests/Vibe/Stage01/Admin/ProductStoreRequest.php`: validate product.
- `tests/Feature/Vibe/Stage01/*`: test luong admin va product.

Ung dung:

- Lam nen cho module quan tri.
- Tao du lieu dau vao cho storefront, cart, order, analytics va AI.

### 3. Diem nhan 2: Nang product tu du lieu don gian thanh model co nghiep vu

O chang 2, nhom thay rang san pham chi co ten, gia, so luong la chua du. He thong ban nong san/do an can mo ta quy cach, thuoc tinh, anh dai dien, gallery va cac phien ban san pham.

Da lam:

- Tao bang `attributes`.
- Tao bang `values` de luu gia tri thuoc tinh cua product.
- Tao bang `product_attr_config` de phan biet thuoc tinh chung va thuoc tinh bien the.
- Tao bang `product_images` cho gallery.
- Them `image` va `type` vao `products`.
- Tao `ProductModelingService` de cau hinh product simple/configurable va tao variant.
- Chan shared attribute va variant attribute bi trung.
- Chan duplicate variant combination.

Vi sao thay doi:

- Product thuc te khong chi co mot gia tri co dinh.
- Mot san pham co the co nhieu quy cach: khoi luong, kich co, loai, do tuoi, cach dong goi.
- Neu khong tach shared attribute va variant attribute, UI va order sau nay se roi.
- Neu khong chan duplicate variant, database co the co hai bien the giong nhau.

Chi ra trong code:

- `app/Services/Vibe/Stage02/ProductModelingService.php`: logic chinh cua stage 2.
- `routes/vibe_stage_02_admin.php`: endpoint tao attribute, cau hinh product, tao variant.
- `app/Http/Requests/Vibe/Stage02/Admin/*`: validate tung request.
- `tests/Feature/Vibe/Stage02/ProductModelingTest.php`: test attribute, configurable product, gallery, variant, duplicate variant.

Ung dung:

- Admin quan ly san pham linh hoat hon.
- Storefront co du du lieu de hien product detail.
- Cart/order co the lam viec voi tung variant cu the.
- AI sau nay co du metadata san pham de phan tich.

### 4. Diem nhan 3: Dua du lieu tu admin ra storefront public

O chang 3, he thong chuyen tu "quan ly noi bo" sang "nguoi dung co the xem san pham". Day la moc bien backend product thanh trai nghiem public.

Da lam:

- Tao home feed.
- Tao catalog listing.
- Tao search public.
- Tao product detail.
- Chi hien root product trong home/catalog.
- Search co the match ten product goc hoac ten variant.
- Neu truy cap detail bang variant id, he thong resolve ve product goc.
- Tach `StorefrontCatalogService` de build product card va detail snapshot.

Vi sao thay doi:

- Nguoi dung khong nen thay tat ca variant nhu nhung product roi rac tren home.
- Search phai tim duoc san pham theo ca ten goc va ten bien the vi nguoi dung co the go tu khoa khac nhau.
- Detail bang variant id van can quay ve product goc de UX nhat quan.

Chi ra trong code:

- `routes/vibe_stage_03_storefront.php`: route public cho overview, home, products, search, detail.
- `app/Services/Vibe/Stage03/StorefrontCatalogService.php`: build card/detail snapshot.
- `app/Http/Requests/Vibe/Stage03/CatalogRequest.php`: validate query.
- `tests/Feature/Vibe/Stage03/StorefrontCatalogTest.php`: test root product, search variant, detail snapshot, resolve parent.

Ung dung:

- Tao mat tien cho website.
- La tien de de them auth, cart, order va tracking hanh vi nguoi dung.

### 5. Diem nhan 4: Them user account de co nguoi mua that

O chang 4, nhom them tang tai khoan nguoi dung. Day la buoc noi giua viec xem san pham va viec mua hang co dinh danh.

Da lam:

- Register.
- Login/logout bang guard `web`.
- Chan user bi khoa.
- Profile va update profile.
- Forgot/reset password.
- Social login theo callback payload mo phong.
- Tach `UserAccountService` de gom logic account.
- Tach request validation cho register, login, profile, reset password, social callback.

Vi sao thay doi:

- Cart va order can biet user nao dang mua.
- Profile giup checkout co thong tin ten, email, phone, address.
- Password reset la chuc nang co ban cua website that.
- Social login duoc mo phong de test duoc trong moi truong do an, khong phu thuoc provider OAuth that.

Chi ra trong code:

- `routes/vibe_stage_04_account.php`: route guest va auth:web.
- `app/Services/Vibe/Stage04/UserAccountService.php`: register, login, logout, profile, reset password, social login.
- `app/Http/Controllers/Vibe/Stage04/*`: controller cho auth/profile/password/social.
- `tests/Feature/Vibe/Stage04/UserAccountTest.php`: 9 test bao phu account flow.

Ung dung:

- Mo khoa cart, checkout, order history.
- Tao nen du lieu user cho tracking va AI ca nhan hoa sau nay.

### 6. Diem nhan 5: Hoan chinh luong mua hang va thanh toan

O chang 5, website khong con chi la catalog. Nhom bo sung luong giao dich day du: them gio, checkout, tao order, coupon, shipping, thanh toan online.

Da lam:

- Them cart cho user dang nhap.
- Check ton kho va gioi han so luong trong cart.
- Xoa/cap nhat gio hang.
- Checkout tinh tong tien, coupon va phi ship theo city.
- Tao order va order_products.
- Xoa cart sau khi dat hang.
- User xem danh sach va chi tiet don hang.
- Tich hop MoMo return.
- Tich hop VNPAY create/return.
- Tracking purchase khi tao don.

Vi sao thay doi:

- E-commerce phai co transaction flow tu xem hang den dat hang.
- Coupon va shipping fee lam checkout gan voi nghiep vu thuc te.
- Thanh toan online giup he thong khong chi dung o muc COD.
- Order history giup user theo doi don.

Chi ra trong code:

- `routes/web.php`: route `add-cart`, `cart`, `checkout`, `create-order`, `list-order`, `momo-return`, `vnpay/create`, `vnpay/return`.
- `app/Http/Controllers/Web/CartController.php`: add/remove cart va tracking add/remove.
- `app/Http/Controllers/Web/OrderController.php`: checkout, create order, MoMo return, tracking purchase.
- `app/Http/Controllers/Web/VnPayController.php`: tao URL VNPAY va xu ly return.
- `tests/Feature/Vibe/Stage05/CartCheckoutOrderPaymentTest.php`: test cart, checkout, COD, VNPAY, MoMo, scope order theo user.

Ung dung:

- Demo duoc hanh trinh mua hang dau cuoi.
- Tao du lieu order/purchase cho analytics va AI.

### 7. Diem nhan 6: Tu giao dich sang he thong co du lieu va AI

O chang 6, nhom nang website tu "ban hang duoc" thanh "hieu duoc hanh vi nguoi dung". Day la ly do them tracking, analytics va AI.

Da lam:

- Tao `activity_logs` de luu view, search, add_to_cart, remove_from_cart, purchase.
- Tao `ai_suggestions` de luu goi y AI.
- Them timestamps cho carts de phan tich bo gio.
- Tao `ActivityTracker`.
- Gan tracking vao HomeController, CartController, OrderController va middleware xem san pham.
- Tao `AnalyticsService` de tinh conversion rate, cart abandonment, trending products, frequently bought together, slow moving products, pricing analysis.
- Tao `DataCollectorService` de tong hop du lieu thanh prompt cho AI.
- Tao `AIAnalyticsService` de goi OpenAI, ep JSON, parse va validate goi y.
- Tao `AISuggestionService` de luu va dismiss suggestion.
- Tao admin AI dashboard.
- Tao command `ai:generate-suggestions`.
- Them AI goi y san pham theo mon an cho user.

Vi sao thay doi:

- Khi da co order va cart, du an moi co du lieu that de phan tich.
- AI khong nen them qua som, vi luc do chua co data dau vao.
- Dashboard AI giup admin nhin thay san pham nao can giam gia, day ton kho, tao combo, day san pham hot.
- AI goi y theo mon an giup nguoi dung mua nhanh hon theo nhu cau nau an.

Chi ra trong code:

- `app/Services/ActivityTracker.php`: `trackView`, `trackSearch`, `trackAddToCart`, `trackRemoveFromCart`, `trackPurchase`.
- `app/Services/AnalyticsService.php`: cac ham phan tich chi so.
- `app/Services/DataCollectorService.php`: gom data va format prompt.
- `app/Services/AIAnalyticsService.php`: build prompt, goi OpenAI, parse JSON.
- `app/Services/AISuggestionService.php`: luu/dismiss goi y.
- `app/Http/Controllers/Admin/AIDashboardController.php`: dashboard admin.
- `app/Console/Commands/GenerateAISuggestions.php`: command generate suggestions.
- `routes/admin.php`: route `ai-dashboard`, `ai-dashboard/dismiss`, `ai-dashboard/analytics`.
- `routes/web.php`: route `/search-dish`, `/get-recipe`, `/ai/recommend`.
- `tests/Feature/Vibe/Stage06/TrackingAnalyticsAiTest.php`: test tracking, analytics, AI suggestion va command.

Ung dung:

- Admin co the ra quyet dinh dua tren du lieu thay vi cam tinh.
- Marketing co the dua theo ngay/gio cao diem.
- He thong co nen de mo rong ca nhan hoa, goi y san pham, combo va flash sale.

### 8. Diem nhan 7: Them microservices theo huong thuc te, khong tach repo vo vang

O chang 7, nhom them microservices boundary ngay trong Laravel monolith. Muc tieu khong phai tach project thanh nhieu repo ngay, ma la tach logic nghiep vu quan trong thanh cac service co contract ro rang.

Da lam:

- Inventory Service: check ton kho kha dung va reserve hang.
- Pricing Service: tinh subtotal, shipping fee, coupon discount, grand total.
- Checkout Orchestrator: dieu phoi pricing, inventory va outbox.
- Outbox Service: luu event de sau nay dispatch async.
- Tao bang `inventory_reservations`.
- Tao bang `microservice_outbox_events`.
- Them `correlation_id` de trace mot checkout qua nhieu service.
- Endpoint rieng cho inventory check, pricing quote, checkout simulate, outbox.

Vi sao thay doi:

- Ton kho va tinh tien la hai logic co kha nang phuc tap khi he thong lon.
- Reservation tranh tinh trang hai user cung dat vuot ton kho.
- Correlation id giup trace toan bo luong checkout.
- Outbox pattern la buoc dem de chuyen sang event-driven architecture.
- Giu trong monolith giup do an van chay duoc, nhung code da san sang tach service sau.

Chi ra trong code:

- `routes/vibe_stage_07_microservices.php`: route cho microservices demo.
- `app/Services/Vibe/Stage07/InventoryMicroservice.php`: check va reserve stock.
- `app/Services/Vibe/Stage07/PricingMicroservice.php`: tinh quote va publish event `pricing.quoted`.
- `app/Services/Vibe/Stage07/CheckoutOrchestratorService.php`: goi pricing, inventory, outbox bang correlation id.
- `app/Services/Vibe/Stage07/MicroserviceOutboxService.php`: luu outbox event.
- `tests/Feature/Vibe/Stage07/MicroserviceArchitectureTest.php`: test reservation, quote, outbox, orchestrator, reject khi stock khong du.

Ung dung:

- Tang tinh kien truc cho do an.
- Chuan bi cho queue, API gateway, service token, tach inventory/pricing thanh service rieng.
- Chung minh du an khong chi CRUD ma co xu ly checkout theo huong gan production.

### 9. Doan ket luan cho bao cao

Du an duoc phat trien theo quy trinh vibe coding co kiem soat. Nhom khong dung AI de thay the toan bo lap trinh vien, ma dung AI nhu cong cu tang toc trong tung chang ro rang. Moi chang bat dau tu mot muc tieu nho, sau do prompt, sinh code, chay thu, sua loi, bo sung validation, tach service va viet test. Thu tu phat trien di tu nen tang admin va product, sang storefront, account, cart/order/payment, tracking/analytics/AI va cuoi cung la microservices boundary. Cach lam nay giup project vua co san pham chay duoc, vua co dau vet quy trinh, vua co co so de bao ve ve mat ky thuat.

## Phan 2. Noi dung PowerPoint goi y

### Slide 1. Ten de tai

Noi dung tren slide:

- Website ban nong san ung dung AI phan tich va goi y
- Quy trinh phat trien bang vibe coding co kiem soat
- Laravel 9, MySQL, Blade, OpenAI, microservices boundary

Loi thuyet trinh:

> De tai cua nhom em la xay dung website ban nong san co day du luong e-commerce: quan tri san pham, storefront, tai khoan nguoi dung, gio hang, don hang, thanh toan, tracking hanh vi, analytics, AI dashboard va them lop microservices cho checkout. Diem chinh cua bai trinh bay khong chi la san pham cuoi, ma la cach nhom em dung vibe coding co kiem soat de di tung chang va de lai dau vet trong code, test va tai lieu.

### Slide 2. Bai toan va muc tieu

Noi dung tren slide:

- Bai toan: website ban nong san can vua ban hang, vua quan tri, vua hieu du lieu nguoi dung.
- Muc tieu 1: xay e-commerce chay duoc tu admin den checkout.
- Muc tieu 2: thu thap hanh vi va phan tich chi so.
- Muc tieu 3: dung AI tao goi y hanh dong cho admin va goi y mua sam cho user.
- Muc tieu 4: them service boundary cho inventory, pricing, checkout.

Loi thuyet trinh:

> Nhom em khong chi lam mot trang CRUD san pham. Bai toan duoc mo rong theo huong mot he thong ban hang that: admin nhap du lieu, nguoi dung xem va mua, he thong ghi nhan hanh vi, sau do AI phan tich de goi y hanh dong. Cuoi cung, nhom them microservices boundary de cac logic nhu ton kho, tinh tien va checkout co cau truc ro rang hon.

### Slide 3. Ban chat quy trinh vibe coding

Noi dung tren slide:

- Khong prompt mot lan ra ca du an.
- Chia bai toan thanh 7 chang.
- Moi chang co: muc tieu, prompt, code, test, tai lieu.
- Con nguoi giu vai tro chia scope, kiem thu, prompt bo sung, quyet dinh kien truc.
- AI giup sinh code nhanh va goi y cau truc.

Loi thuyet trinh:

> Ban chat quy trinh la vibe coding co kiem soat. Moi chang deu bat dau tu mot bai toan nho. Sau khi AI sinh code, nhom chay thu tren project that, phat hien thieu route, thieu validation, thieu schema hoac sai nghiep vu, roi prompt tiep cho cu the hon. Vi vay prompt cuoi cung chinh xac khong phai vi doan truoc dap an, ma vi no la ket qua cua nhieu lan test va sua.

### Slide 4. Lo trinh 7 chang

Noi dung tren slide:

| Chang | Noi dung | Test |
| --- | --- | --- |
| 1 | Laravel + admin auth + product core | Stage01: 7 passed |
| 2 | Product modeling | Stage02: 6 passed |
| 3 | Storefront | Stage03: 4 passed |
| 4 | User account | Stage04: 9 passed |
| 5 | Cart, checkout, order, payment | Stage05: 7 passed |
| 6 | Tracking, analytics, AI | Stage06: 8 passed |
| 7 | Microservices boundary | Stage07: 5 passed |

Loi thuyet trinh:

> Nhom rut gon qua trinh thanh 7 chang lon. Bon chang dau co endpoint vibe rieng va test rieng. Chang 5 hoan thien luong mua hang. Chang 6 them tracking, analytics va AI. Chang 7 them microservices boundary. Tong the nay giup bao cao gon nhung van chung minh duoc qua trinh phat trien co thu tu.

### Slide 5. Chang 1: Nen tang admin va product

Noi dung tren slide:

- Lam admin auth truoc.
- Tao product core co `parent_id`.
- Route rieng: `routes/vibe_stage_01_admin.php`.
- Test: login admin, chan guest, tao root/child product.

Viec thay doi va ly do:

- Thay vi lam UI truoc, lam backend-first de co du lieu.
- Them `parent_id` ngay tu dau de mo rong variant sau nay.

Ung dung:

- Admin nhap du lieu hang hoa.
- Lam nen cho category, attribute, cart, order, AI.

Loi thuyet trinh:

> O chang 1, nhom em uu tien nen tang. Website ban hang can co admin va product truoc. Diem nhan la product duoc thiet ke co `parent_id`, nen chang sau co the mo rong thanh san pham cha/con ma khong can dap lai schema tu dau.

### Slide 6. Chang 2: Product modeling

Noi dung tren slide:

- Them attribute, value, product_attr_config, product_images.
- Ho tro simple/configurable product.
- Tao variant theo to hop attribute.
- Chan overlap attribute va duplicate variant.
- Code chinh: `ProductModelingService`.

Viec thay doi va ly do:

- Product don gian khong du mo ta san pham thuc te.
- Can tach thuoc tinh chung va thuoc tinh bien the.
- Can gallery de storefront hien thi tot hon.

Ung dung:

- Quan ly nhieu quy cach san pham.
- Storefront va order xu ly dung variant.

Loi thuyet trinh:

> Day la chang quan trong nhat ve data model. Sau khi co product co ban, nhom nhan ra can mo ta san pham theo quy cach thuc te. Vi vay code duoc tach vao service rieng, co transaction, co validate va co test chan bien the trung lap.

### Slide 7. Chang 3: Storefront

Noi dung tren slide:

- Home feed.
- Catalog.
- Search.
- Product detail.
- Chi hien root product tren home/catalog.
- Detail resolve variant id ve parent product.

Viec thay doi va ly do:

- Du lieu admin can duoc public cho nguoi dung.
- Root/variant phai xu ly ro de UX khong roi.

Ung dung:

- Nguoi dung co the xem, tim va chon san pham.
- Mo duong cho auth, cart va tracking.

Loi thuyet trinh:

> Chang 3 bien he thong tu backend quan tri thanh website co mat tien. Diem nhan la khong day tat ca bien the ra home nhu san pham rieng, ma chi hien root product. Neu nguoi dung mo detail bang variant id, service tu resolve ve product goc de trai nghiem nhat quan.

### Slide 8. Chang 4: Tai khoan nguoi dung

Noi dung tren slide:

- Register, login, logout.
- Profile va doi thong tin.
- Forgot/reset password.
- Social login bang callback payload mo phong.
- Code chinh: `UserAccountService`.

Viec thay doi va ly do:

- Storefront can co user de cart/order gan dung chu so huu.
- Social login mo phong giup test duoc trong moi truong do an.

Ung dung:

- Luu profile.
- Gan cart/order theo user.
- Tao nen cho tracking theo user.

Loi thuyet trinh:

> Chang 4 them danh tinh cho nguoi dung. Nhom tach guard `web` voi guard `admin`, nen tai khoan mua hang khong lan voi tai khoan quan tri. Phan social login duoc lam theo callback payload de van chung minh duoc logic link account ma khong can phu thuoc provider ben ngoai.

### Slide 9. Chang 5: Cart, checkout, order, payment

Noi dung tren slide:

- Add/remove cart.
- Check ton kho.
- Checkout tinh coupon va shipping fee.
- Tao order va order_products.
- MoMo return va VNPAY create/return.
- Order history theo user.

Viec thay doi va ly do:

- Website can luong mua hang dau cuoi.
- Coupon, city shipping va payment lam nghiep vu gan thuc te.
- Scope order theo user de bao ve du lieu.

Ung dung:

- Demo duoc hanh trinh mua hang.
- Tao du lieu giao dich cho analytics va AI.

Loi thuyet trinh:

> Chang 5 la moc bien storefront thanh he thong ban hang hoan chinh. Nguoi dung them gio, checkout, tao don, thanh toan bang COD, MoMo hoac VNPAY. Test Stage05 kiem tra ca luong thanh toan va viec don hang chi hien voi user so huu.

### Slide 10. Chang 6: Tracking va analytics

Noi dung tren slide:

- Track view, search, add_to_cart, remove_from_cart, purchase.
- Tinh conversion rate.
- Tinh cart abandonment.
- Tim trending products.
- Tim frequently bought together.
- Phan tich theo ngay/gio cao diem.

Viec thay doi va ly do:

- Sau khi co cart/order moi co du lieu de phan tich.
- Tracking giup admin hieu hanh vi thay vi chi xem don hang.

Ung dung:

- Toi uu san pham, gia, ton kho, combo, marketing.

Loi thuyet trinh:

> Chang 6 bat dau tu tracking. Neu khong co activity logs thi AI khong co nguyen lieu. Nhom track cac hanh vi quan trong: xem, tim, them gio, xoa gio va mua. Tu do analytics tinh duoc ti le chuyen doi, bo gio, san pham trending va cac cap san pham hay mua cung nhau.

### Slide 11. Chang 6: AI dashboard va AI goi y

Noi dung tren slide:

- `DataCollectorService`: gom metrics.
- `AIAnalyticsService`: build prompt, goi OpenAI, parse JSON.
- `AISuggestionService`: luu va dismiss suggestion.
- `AIDashboardController`: hien action cards.
- Command: `ai:generate-suggestions`.
- User AI: search dish, get recipe, recommend products.

Viec thay doi va ly do:

- AI duoc them sau khi he thong da co data.
- Ep AI tra JSON de luu DB va hien dashboard.
- Co fallback/mock de he thong van chay khi API loi.

Ung dung:

- Goi y giam gia, day hang hot, xu ly ton kho, tao combo.
- Goi y san pham theo mon an cho nguoi mua.

Loi thuyet trinh:

> AI trong do an khong dung de trang tri. Du lieu tu tracking va order duoc tong hop thanh prompt, AI tra ve cac goi y co type, product_id, title, description, action, priority va reasoning. Sau do goi y duoc luu vao database va hien thi tren dashboard admin nhu cac action card.

### Slide 12. Chang 7: Microservices boundary

Noi dung tren slide:

- Inventory Service.
- Pricing Service.
- Checkout Orchestrator.
- Outbox Service.
- `inventory_reservations`.
- `microservice_outbox_events`.
- `correlation_id` de trace.

Viec thay doi va ly do:

- Khong tach repo vo vang, tach boundary trong monolith truoc.
- Reserve ton kho truoc checkout.
- Outbox chuan bi cho event-driven architecture.

Ung dung:

- De nang cap sang queue worker, API gateway, service doc lap.
- Checkout co trace va de debug hon.

Loi thuyet trinh:

> Chang 7 them yeu to kien truc thuc te. Nhom khong tach thanh nhieu service vat ly ngay vi nhu vay se phuc tap va kho demo. Thay vao do, nhom tach service boundary trong Laravel: inventory, pricing, checkout orchestrator va outbox. Cach nay van chay trong monolith nhung logic da san sang de tach rieng khi can.

### Slide 13. Diem nhan code

Noi dung tren slide:

- Route tach theo stage: `vibe_stage_01`, `vibe_stage_02`, `vibe_stage_03`, `vibe_stage_04`, `vibe_stage_07`.
- Service layer cho logic phuc tap: product modeling, account, analytics, AI, microservices.
- Request validation tach rieng.
- Feature test theo stage.
- Outbox va correlation id o Stage07.

Loi thuyet trinh:

> Diem nhan trong code la nhom khong de logic lon nam het trong controller. Nhung phan phuc tap nhu product modeling, user account, analytics, AI va microservices deu co service rieng. Request validation tach rieng giup input ro rang. Feature test theo stage giup moi chang co bang chung chay duoc.

### Slide 14. Prompt tien hoa nhu the nao

Noi dung tren slide:

- Prompt vong 1: mo, chi noi muc tieu.
- Test lan 1: phat hien thieu schema/route/guard/validation.
- Prompt vong 2: cu the hon theo loi va khoang trong.
- Prompt vong 3: hyper-specific, co bang, endpoint, validation, test, pham vi khong lam.

Loi thuyet trinh:

> Tai lieu `VIBE_PROMPT_EVOLUTION_STAGE_01_04.md` cho thay prompt khong duoc viet mot lan tu dau. Moi prompt sau la ket qua cua lan test truoc. Vi du, ban dau chi noi "lam admin quan ly san pham", sau khi test moi bo sung guard admin, migration admins/products, route prefix, validation va feature test. Do la cach prompt tien hoa theo bug va theo khoang trong nghiep vu.

### Slide 15. Nhom con nguoi da lam gi

Noi dung tren slide:

- Chia chang va xac dinh muc tieu.
- Viet prompt theo tung chang.
- Chay app, goi endpoint, xem DB.
- Doc code AI sinh ra.
- Sua scope va prompt lai.
- Them validation, service, test.
- Tong hop README, stage docs, slide va script bao cao.

Loi thuyet trinh:

> Vai tro cua nhom con nguoi la dieu khien quy trinh. AI khong tu biet du an can di theo 7 chang. Nhom phai quyet dinh lam admin truoc, product modeling sau, roi storefront, auth, cart/order, analytics/AI va microservices. Sau moi lan AI sinh code, nhom kiem thu, nhin ra cho thieu va prompt tiep. Do la phan cong viec quan trong nhat cua con nguoi trong vibe coding.

### Slide 16. Demo flow

Noi dung tren slide:

1. Admin login va tao/cau hinh product.
2. User xem storefront va search.
3. User register/login.
4. User them cart va checkout.
5. Thanh toan VNPAY/MoMo hoac COD.
6. Tracking ghi activity.
7. Admin xem AI dashboard.
8. Stage07 mo phong pricing/inventory/checkout orchestration.

Loi thuyet trinh:

> Khi demo, nhom co the di theo dung hanh trinh nguoi dung. Dau tien admin tao du lieu. Sau do nguoi dung xem, tim, mua. Khi co hanh vi va don hang, he thong ghi log. Cuoi cung admin mo AI dashboard de xem goi y, va Stage07 cho thay checkout co the duoc dieu phoi qua inventory, pricing va outbox.

### Slide 17. Ket qua kiem thu

Noi dung tren slide:

- Stage01: 7 passed.
- Stage02: 6 passed.
- Stage03: 4 passed.
- Stage04: 9 passed.
- Stage05: 7 passed.
- Stage06: 8 passed.
- Stage07: 5 passed.

Loi thuyet trinh:

> Kiem thu la bang chung cho qua trinh. Moi chang deu co test rieng theo scope. Stage01 den Stage04 test cac endpoint vibe rieng. Stage05 test luong mua hang va payment. Stage06 test tracking, analytics va AI. Stage07 test microservices boundary, pricing, inventory reservation, outbox va checkout reject khi ton kho khong du.

### Slide 18. Ket luan va huong phat trien

Noi dung tren slide:

- Da xay duoc e-commerce co day du luong mua hang.
- Da them tracking, analytics va AI co du lieu dau vao.
- Da co service boundary cho checkout.
- Vibe coding duoc kiem soat bang scope, prompt, test va tai lieu.

Huong phat trien:

- Tach inventory/pricing thanh service doc lap.
- Them queue worker dispatch outbox.
- Them API gateway va service token.
- Nang cap AI recommendation theo lich su user.

Loi thuyet trinh:

> Ket qua chinh la nhom da xay duoc mot he thong co hanh trinh tu admin den nguoi mua, tu giao dich den phan tich, tu monolith den service boundary. Diem quan trong la quy trinh vibe coding duoc kiem soat, co prompt theo chang, co test va co tai lieu de chung minh qua trinh.

## Phan 3. Nhom con nguoi prompt gi, lam gi trong suot quy trinh

### 1. Vai tro cua nhom con nguoi

Trong quy trinh nay, nhom con nguoi khong chi "bam nut cho AI viet code". Cong viec cua nhom gom:

- Phan tich bai toan: website ban nong san can nhung module nao.
- Chia phase: khong lam AI, payment, microservices qua som.
- Viet prompt: bat dau tu prompt mo, sau do tang do cu the.
- Kiem thu: chay endpoint, xem database, chay feature test.
- Dieu chinh: sua code, bo sung validation, tach service, dat lai scope.
- Danh gia: tinh nang nao dung demo, tinh nang nao can chua lam.
- Tai lieu hoa: README, file stage, prompt evolution, slide.

### 2. Mau prompt chung cua nhom

Mau prompt duoc dung lap lai:

```text
Hay xay chang X cho project Laravel 9 website ban nong san, build tren chang Y.
Pham vi chang nay:
- ...

Bang du lieu can co:
- ...

Endpoint can co:
- ...

Nghiep vu:
- ...

Validation:
- ...

Kien truc:
- controller mong
- request validation rieng
- service rieng neu logic phuc tap
- feature test cho cac luong chinh

Pham vi khong lam:
- ...
```

Ly do prompt phai co "pham vi khong lam": neu khong gioi han, AI de mo rong qua rong, sinh them code ngoai y muon va lam roi project.

### 3. Bang prompt va viec con nguoi lam theo tung chang

| Chang | Prompt nhom dua cho AI | Nhom con nguoi lam sau khi AI tra code | Ket qua |
| --- | --- | --- | --- |
| 1 | "Hay tao chang 1 Laravel backend: admin guard rieng, admins/products migration, route login, route products, product co parent_id, tra JSON, viet feature test." | Chay login, test guest bi chan, kiem tra DB products, bo sung validation va test tao root/child product. | Co nen admin/product chay duoc. |
| 2 | "Mo rong product: attributes, values, product_attr_config, product_images, image/type, simple/configurable, variant, chan duplicate." | Thu cau hinh product, xem cac bang trung gian, sua prompt de tach shared vs variant attribute, them service transaction va duplicate check. | Product model du mo ta nghiep vu. |
| 3 | "Tao storefront public: overview, home, catalog, search, detail, chi hien root product, search match variant, detail resolve variant ve parent." | Goi endpoint home/search/detail, kiem tra output card/detail, sua prompt de bo auth/cart/order khoi scope. | Storefront public co JSON on dinh. |
| 4 | "Them user account guard web: register, login, logout, me, profile, forgot/reset password, social callback payload, request validation, service, test." | Test user active/blocked, test reset token, test social login email moi/email cu, giu social o muc mock de khong phu thuoc OAuth. | User account san sang cho cart/order. |
| 5 | "Them cart, checkout, order, coupon, city shipping, MoMo, VNPAY, order history, user scope, tracking purchase." | Thu add cart, checkout, COD, VNPAY return, MoMo return, kiem tra order_products va cart bi xoa, test order chi cua user dang nhap. | E-commerce flow dau cuoi. |
| 6 | "Them tracking, analytics va AI: activity_logs, ai_suggestions, ActivityTracker, AnalyticsService, DataCollector, OpenAIService, AI dashboard, command, AI recommend mon an." | Gan tracking vao controller, test view/search/cart/purchase, ep AI tra JSON, them parse/validate, them dashboard va dismiss suggestion. | He thong co du lieu va goi y AI. |
| 7 | "Them microservices boundary: Inventory, Pricing, Checkout Orchestrator, Outbox, reservation, correlation_id, endpoint JSON, feature test." | Kiem tra reservation tru stock, pricing tinh coupon/shipping, checkout reject khi stock thieu, outbox co event va correlation id. | Kien truc san sang mo rong thanh microservices. |

### 4. Prompt mau chi tiet co the dua vao phu luc

#### Prompt chang 1

```text
Toi dang lam do an Laravel 9 cho website ban nong san.
Hay xay chang 1 chi o muc backend, UI coi nhu da co san.
Yeu cau:
- tao migration admins va products
- cau hinh guard admin rieng
- tao route prefix /vibe/stage-01/admin
- tao POST /login, GET /products, POST /products
- products phai co name, price, quantity, parent_id
- route products phai duoc bao ve boi auth:admin
- controller tra JSON
- viet feature test login admin, chan guest, tao root product va child product
Pham vi khong lam:
- chua lam category, attribute, user auth, cart, order, AI
```

#### Prompt chang 2

```text
Hay xay chang 2 build tren chang 1.
Toi can mo hinh san pham day du:
- attributes
- values
- product_attr_config
- product_images
- image va type tren products
Nghiep vu:
- product simple/configurable
- shared attributes va variant attributes khong duoc giao nhau
- variant la product con co parent_id
- khong cho trung to hop bien the
Hay tach ProductModelingService va viet feature test.
```

#### Prompt chang 3

```text
Hay xay chang 3 build tren chang 2.
Toi can storefront public:
- home
- catalog
- search
- product detail
Yeu cau:
- home/catalog chi hien root product
- search match root hoac variant name
- detail tra gallery, shared attributes, variants, price range
- neu product id la variant thi resolve ve parent
Khong lam auth, cart, order trong chang nay.
```

#### Prompt chang 4

```text
Hay xay chang 4 build tren chang 3.
Toi can user account dung guard web:
- register
- login/logout
- me
- profile update
- forgot/reset password
- social callback mock
Tach request validation va UserAccountService.
Viet test cho register, login, blocked user, profile, reset password, social link.
```

#### Prompt chang 5

```text
Hay xay chang 5 build tren chang 4.
Toi can luong mua hang day du:
- add/remove cart
- check ton kho
- checkout
- coupon
- shipping fee theo city
- create order va order_products
- xoa cart sau khi dat
- order history theo user
- MoMo return
- VNPAY create/return
- tracking purchase
Viet test cho cart, checkout, COD, VNPAY, MoMo va order scope.
```

#### Prompt chang 6

```text
Hay xay chang 6 build tren chang 5.
Toi can tracking, analytics va AI:
- activity_logs
- ai_suggestions
- ActivityTracker
- AnalyticsService
- DataCollectorService format prompt
- AIAnalyticsService goi OpenAI va parse JSON
- AISuggestionService luu/dismiss suggestion
- admin AI dashboard
- command ai:generate-suggestions
- user AI recommend theo mon an
Neu OpenAI loi phai co fallback/mock de he thong van chay.
```

#### Prompt chang 7

```text
Hay xay chang 7 build tren chang 6.
Toi can microservices boundary trong Laravel monolith:
- Inventory Service check va reserve stock
- Pricing Service tinh subtotal, shipping, coupon, grand total
- Checkout Orchestrator goi pricing + inventory + outbox
- Outbox Service luu event
- inventory_reservations
- microservice_outbox_events
- correlation_id de trace
Endpoint:
- inventory/check
- pricing/quote
- checkout/simulate
- outbox
Neu ton kho khong du thi checkout simulate tra 422.
Viet feature test cho cac luong chinh.
```

### 5. Cach noi phan "con nguoi prompt gi, lam gi" khi bi hoi

Co the tra loi:

> Nhom em khong prompt mot lan de AI viet ca du an. Moi chang nhom em xac dinh truoc muc tieu, sau do prompt AI sinh phan backend nho nhat co the chay. Khi chay thu, nhom xem endpoint, database va test de phat hien thieu guard, thieu validation, thieu schema hoac sai nghiep vu. Sau do nhom prompt lai cu the hon, co ten bang, ten route, ten field, middleware, service va test. AI giup sinh code nhanh, nhung nhom con nguoi la ben chia chang, kiem thu, chot scope va quyet dinh thay doi nao duoc giu lai.

## Phan 4. Ban noi ngan cho thuyet trinh 3-5 phut

> Trong do an nay, nhom em xay dung website ban nong san theo quy trinh vibe coding co kiem soat. Nhom khong dung mot prompt duy nhat de tao ca du an, ma chia thanh 7 chang ro rang. Chang 1 tao nen tang Laravel, admin auth va product core. Chang 2 nang product thanh model co attribute, variant va gallery. Chang 3 dua du lieu ra storefront public. Chang 4 them tai khoan nguoi dung. Chang 5 hoan thien luong mua hang voi cart, checkout, order va thanh toan. Chang 6 them tracking, analytics va AI dashboard. Chang 7 them microservices boundary cho inventory, pricing, checkout orchestrator va outbox.
>
> Diem nhan cua quy trinh la moi chang deu co prompt rieng, code rieng va test rieng. AI duoc dung de tang toc sinh code, con nhom con nguoi van la ben chia scope, viet prompt, chay thu, sua loi, bo sung validation va viet test. Vi du, o chang 2, sau khi thay product co ban khong du mo ta san pham thuc te, nhom prompt them attributes, product_attr_config, product_images va variant. O chang 6, nhom chi them AI sau khi da co cart, order va tracking, vi AI can du lieu that de phan tich. O chang 7, nhom khong tach service thanh nhieu repo ngay, ma tach boundary trong monolith de project van chay duoc nhung san sang mo rong.
>
> Ket qua la he thong khong chi dung o CRUD, ma co day du hanh trinh e-commerce, co du lieu hanh vi, co AI goi y cho admin va user, dong thoi co kien truc service boundary cho cac phan quan trong nhu ton kho, tinh gia va checkout.

## Phan 5. Cau tra loi nhanh khi bi hoi

### Hoi: Ban chat cua quy trinh la gi?

Tra loi:

> Ban chat la vibe coding co kiem soat: chia nho bai toan, prompt theo tung chang, chay thu, sua loi, bo sung validation/test, roi moi mo rong. AI sinh code nhanh, con nguoi dieu khien scope va kiem chung ket qua.

### Hoi: Vi sao khong lam AI ngay tu dau?

Tra loi:

> Vi AI can du lieu. Neu chua co product, cart, order va activity logs thi AI khong co co so phan tich. Nhom lam e-commerce flow truoc, tracking sau, roi moi them AI dashboard.

### Hoi: Diem nhan code quan trong nhat la gi?

Tra loi:

> Co 4 diem: product modeling co simple/configurable va variant; tracking/analytics tao du lieu cho AI; AI pipeline tu DataCollector den AIAnalytics va AISuggestion; Stage07 tach inventory, pricing, checkout orchestration va outbox bang correlation id.

### Hoi: Con nguoi lam gi, AI lam gi?

Tra loi:

> Con nguoi chia chang, viet prompt, test, review code, sua scope va quyet dinh giu/bo tinh nang. AI ho tro sinh code, goi y cau truc va tang toc lap trinh trong tung pham vi da duoc con nguoi dinh nghia.

### Hoi: Vi sao them microservices trong monolith?

Tra loi:

> Vi tach boundary trong monolith la cach thuc te cho do an: he thong van de demo va test, nhung logic inventory, pricing, checkout da doc lap hon. Sau nay co the tach thanh service rieng, them queue va API gateway.

### Hoi: Bang chung nao cho thay tung chang chay duoc?

Tra loi:

> Moi chang co feature test rieng: Stage01 7 passed, Stage02 6 passed, Stage03 4 passed, Stage04 9 passed, Stage05 7 passed, Stage06 8 passed va Stage07 5 passed. Ngoai ra code co route, service va controller rieng theo tung chang.

## Phan 6. Bo sung: He thong AI Agent va file training/skill

### 1. Ly do bo sung AI Agent

Sau khi hoan thien 7 chang code, nhom bo sung them mot lop tai lieu AI Agent de quy trinh vibe coding co the trinh bay ro rang hon. Neu chi noi "nhom dung AI de code" thi hoi chung chung. Vi vay, nhom to chuc AI thanh cac vai tro giong mot team phat trien phan mem:

- Back-end Agent: phu trach Laravel backend, route, controller, service, validation, transaction, payment, analytics va microservice boundary.
- Front-end Agent: phu trach Blade UI, admin page, storefront, cart/checkout, AI dashboard, responsive va animation.
- Tester Agent: phu trach test case, checklist, regression va bang chung kiem thu.

Moi agent co file rieng:

- `AGENT.md`: agent la ai, phu trach gi.
- `Rules.md`: quy tac va ranh gioi bat buoc.
- `Skill.md`: ky nang, pattern, cach xu ly task lap lai.
- `Workflows.md`: quy trinh lam viec tung buoc.

Rieng Back-end Agent co them:

- `PRD/PRD.md`: muc tieu san pham.
- `PRD/UserStories.md`: user stories va acceptance criteria.
- `PRD/Database_Schema.md`: tom tat schema database.
- `Plan.md`: plan 7 chang backend.

### 2. Cau truc thu muc AI Agent da them

```text
agents/
  back-end-agent/
    PRD/
      Database_Schema.md
      PRD.md
      UserStories.md
    AGENT.md
    Plan.md
    Rules.md
    Skill.md
    Workflows.md
  front-end-agent/
    AGENT.md
    Rules.md
    Skill.md
    Workflows.md
  tester-agent/
    AGENT.md
    Rules.md
    Skill.md
    Workflows.md
skills/
  developing-backend/
    developing-backend.md
    backend-api-SKILL.md
    create-skills.md
  developing-frontend/
    frontend-ui-SKILL.md
  testing-qa/
    qa-SKILL.md
docs/
  API_Endpoint.md
  Check.md
  Frontend_Prompt_Design.md
  AI_Agent_Training_Report.md
Outline/
Timeline/
```

### 3. Noi dung cua tung nhom file

#### Nhom `agents/back-end-agent`

Nhom nay dinh nghia cach AI lam backend. Trong do:

- `AGENT.md` noi ro Back-end Agent phu trach controller, request, service, model, migration, route va business logic.
- `Rules.md` dat cac quy tac production: khong hard-code secret, validate input, transaction khi thao tac nhieu bang, JSON error format `{ "status": "error" }`, khong lo stack trace.
- `Skill.md` ghi ky nang lam product modeling, cart/checkout, payment, AI analytics va microservice boundary.
- `Workflows.md` ghi quy trinh tao feature, sua bug, checkout, payment callback, AI dashboard va Stage07.
- `Plan.md` map 7 chang voi file code va command test.
- `PRD/PRD.md` mo ta muc tieu san pham, actor, core flow, functional/non-functional requirements.
- `PRD/UserStories.md` viet theo format "As a..., I want..., so that..." va acceptance criteria "Whenever..., then...".
- `PRD/Database_Schema.md` tom tat cac bang: admins, users, products, categories, attributes, values, carts, orders, order_products, coupons, activity_logs, ai_suggestions, inventory_reservations, outbox events.

#### Nhom `agents/front-end-agent`

Nhom nay dinh nghia cach AI lam giao dien:

- `AGENT.md` xac dinh stack frontend la Laravel Blade, Laravel Mix, jQuery/Axios/SweetAlert2/Select2.
- `Rules.md` dat quy tac UI/UX: premium, dynamic, component doc lap, loading/error/empty/success state, responsive.
- `Skill.md` ghi cach tach Blade partial, lam admin UI, storefront UI, cart/checkout UI, AI dashboard UI.
- `Workflows.md` ghi quy trinh sua admin page, product form, storefront page, cart/checkout, AI dashboard va AJAX.

#### Nhom `agents/tester-agent`

Nhom nay dinh nghia cach AI kiem thu:

- `AGENT.md` noi Tester Agent phu trach test, checklist va evidence.
- `Rules.md` quy dinh khong xoa test fail, khong dung database production, khong sua behavior de hop thuc hoa bug.
- `Skill.md` ghi cach viet PHPUnit feature test, assert HTTP/JSON/database/session.
- `Workflows.md` ghi quy trinh test auth, product, storefront, checkout, payment, AI va microservices.

#### Nhom `skills`

Nhom nay la bo "training skill" nho cho AI:

- `developing-backend.md`: skill tong quat lam backend Laravel.
- `backend-api-SKILL.md`: skill thiet ke route/API contract.
- `create-skills.md`: mau tao skill moi khi project mo rong.
- `frontend-ui-SKILL.md`: skill lam UI Blade.
- `qa-SKILL.md`: skill kiem thu va bao cao test.

#### Nhom `docs`

Nhom nay dung cho bao cao va handoff:

- `Frontend_Prompt_Design.md`: nguyen tac prompt frontend, animation, UI state, responsive.
- `API_Endpoint.md`: tong hop route admin, user, cart, checkout, AI dashboard va vibe stage.
- `Check.md`: checklist cau truc file, code stage, UI/UX, AI, report/slide.
- `AI_Agent_Training_Report.md`: bao cao rieng ve he thong AI Agent.

### 4. Gia tri khi dua vao thuyet trinh

Phan AI Agent giup bai thuyet trinh co them 4 diem manh:

1. Co cau truc lam viec ro rang: AI duoc chia vai tro, khong code tu do.
2. Co ranh gioi: backend khong sua frontend tuy tien, frontend khong sua database, tester khong xoa test fail.
3. Co bang chung: docs, route, test, schema va checklist deu nam trong repo.
4. Co the giai thich duoc: con nguoi tao quy trinh, AI thuc hien trong pham vi, con nguoi review va kiem thu.

### 5. Doan co the noi trong slide

> Ngoai 7 chang code, nhom em con xay dung mot bo AI Agent Training. Bo nay chia AI thanh Back-end Agent, Front-end Agent va Tester Agent. Moi agent co AGENT, Rules, Skill va Workflows rieng. Back-end Agent tap trung validation, transaction, route va service. Front-end Agent tap trung Blade UI, loading, error, responsive va animation. Tester Agent tap trung test case, regression va bang chung. Nho vay, AI khong hoat dong tuy tien ma lam viec trong pham vi do con nguoi dat ra.

### 6. Cau tra loi nhanh neu giang vien hoi ve file training/skill

Hoi: Tai sao can file Skill va Rules?

> Vi AI can context va ranh gioi. File Rules giup AI biet dieu bat buoc va dieu khong duoc lam. File Skill giup AI nho pattern lap lai, vi du checkout phai tinh total server-side, payment callback phai idempotent, frontend phai co loading/error state.

Hoi: AI Agent khac gi prompt thuong?

> Prompt thuong chi la mot lenh don le. AI Agent co bo nho lam viec gom PRD, User Stories, Rules, Skill, Workflow, API Endpoint va Checklist. Nho vay moi task deu duoc dat trong context cua toan he thong.

Hoi: Con nguoi con vai tro gi?

> Con nguoi chia stage, viet prompt, dat rule, kiem tra code, chay test, quyet dinh scope va tong hop bao cao. AI chi la cong cu tang toc trong pham vi da duoc kiem soat.

Hoi: Lam sao biet cac file nay khong chi de trang tri?

> Vi cac file nay gan truc tiep voi repo: API_Endpoint lay tu route thuc te, Database_Schema lay tu migration, Check gan voi command test Stage01 den Stage07, UserStories gan voi cac flow admin, storefront, checkout, AI va microservices.
