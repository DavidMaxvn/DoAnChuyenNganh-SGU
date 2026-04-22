# Chang 3: Xay Storefront Cho Nguoi Dung

## 1. Muc tieu chang 3

Chang 3 dua du lieu san pham da duoc mo hinh hoa o chang 2 ra mot tang public de nguoi dung co the xem, tim va chon san pham. Day la luc he thong bat dau co "mat tien" huong nguoi dung, du giao dien van duoc xem la co san.

Pham vi chang 3:

- Tao home feed cho storefront.
- Tao catalog listing public.
- Tao product detail public.
- Tao keyword search public.
- Tai su dung du lieu bien the, gallery, shared attributes tu chang 2.

Phan UI duoc xem la co san, nen chang 3 tap trung vao backend public tra JSON de giao dien co the gan vao.

## 2. Phan tich he thong

### 2.1. Bai toan nghiep vu

Sau chang 2, he thong da co san pham, thuoc tinh, gallery va bien the. Tuy nhien, nhung du lieu nay van nam o tang admin/noi bo. Neu chua co chang 3, nguoi dung cuoi van khong co cach:

- xem san pham dang ban
- tim san pham theo tu khoa
- xem chi tiet san pham
- xem bien the va anh san pham

Vi vay, chang 3 duoc dung de xay mot storefront toi thieu nhung chay duoc that, noi tu database san pham ra public API.

### 2.2. Tac nhan

- Khach truy cap public storefront.

### 2.3. Use case cot loi

1. Khach vao home feed.
2. Khach xem danh sach san pham.
3. Khach tim kiem san pham bang tu khoa.
4. Khach xem chi tiet san pham.
5. Khach xem gallery, thuoc tinh chung va bien the cua san pham.

### 2.4. Dau vao va dau ra

Dau vao:

- query `q`
- query `type`
- query `limit`
- `product_id`

Dau ra:

- danh sach card san pham cho home/catalog
- ket qua tim kiem
- snapshot chi tiet san pham
- danh sach bien the va gallery

### 2.5. Rang buoc chang 3

- Chang 3 build tren chang 2.
- Chua xu ly gio hang, auth user, order.
- Chua dua category/banner vao scope toi thieu nay de giu ranh gioi phase ro rang.
- Chi public san pham goc (`parent_id = null`) trong home va catalog.
- Neu truy cap detail bang id bien the, he thong se resolve ve san pham goc.

## 3. Thiet ke kien truc

### 3.1. Kien truc module

Chang 3 duoc tach thanh 4 lop:

1. Route layer:
   - public routes cho home/catalog/detail/search
2. Request validation layer:
   - validate query filter cho catalog/search
3. Controller layer:
   - tiep nhan request storefront
4. Service layer:
   - tao "view model" cho product card va product detail

### 3.2. Luong xu ly

#### Luong 1: Home feed

1. Client goi `GET /vibe/stage-03/storefront/home`
2. `StorefrontCatalogService@homeFeed` lay danh sach san pham goc moi nhat
3. Service build card data: ten, anh, gia, type, has_variants
4. Tra ve JSON de UI render home

#### Luong 2: Catalog listing va search

1. Client goi `GET /vibe/stage-03/storefront/products` hoac `GET /search`
2. `CatalogRequest` validate `q`, `type`, `limit`
3. `StorefrontCatalogService@catalog` loc san pham goc
4. Search co the match ten san pham goc hoac ten bien the
5. Tra ve danh sach card san pham

#### Luong 3: Product detail

1. Client goi `GET /vibe/stage-03/storefront/products/{product}`
2. Neu id la bien the, service resolve ve san pham goc
3. Service tong hop:
   - thong tin san pham
   - gallery
   - shared attributes
   - variants
   - price range
4. Tra ve snapshot detail cho UI

### 3.3. File quan trong nhat cua chang 3

File backend stage 3:

- `routes/vibe_stage_03_storefront.php`
- `app/Http/Controllers/Vibe/Stage03/StorefrontController.php`
- `app/Http/Requests/Vibe/Stage03/CatalogRequest.php`
- `app/Services/Vibe/Stage03/StorefrontCatalogService.php`

File data/model duoc tai su dung:

- `app/Models/Product.php`
- `database/migrations/2023_04_15_064849_create_products_table.php`
- `database/migrations/2023_04_15_064916_create_attributes_table.php`
- `database/migrations/2023_04_15_064937_create_values_table.php`
- `database/migrations/2023_04_16_075245_add_image_to_products_table.php`
- `database/migrations/2023_05_25_022324_create_product_images_table.php`
- `database/migrations/2023_05_26_031401_create_product_attr_config_table.php`
- `database/migrations/2023_05_26_061449_add_type_to_products_table.php`

### 3.4. Vi sao day la bo file toi thieu

- Route/controller tao public entrypoint.
- Request giu input catalog/search gon va an toan.
- Service la noi ket tinh toan storefront-specific, tranh de logic view lan vao controller.
- Product + cac bang stage 2 la du lieu nen de storefront hoat dong.

## 4. Cai dat trien khai

### 4.1. Prompt vibe coding cho chang 3

```text
Hay xay chang 3 cua he thong ban nong san.
Toi da co san pham, thuoc tinh, bien the va gallery o chang 2.
Bay gio toi can mot storefront public backend:
- home feed
- catalog listing
- search theo tu khoa
- product detail
- detail phai hien duoc gallery, shared attributes, variants
UI coi nhu da co san, backend tra JSON la duoc.
```

### 4.2. Cac file moi duoc them cho chang 3 trong codebase hien tai

- `routes/vibe_stage_03_storefront.php`
- `app/Http/Controllers/Vibe/Stage03/StorefrontController.php`
- `app/Http/Requests/Vibe/Stage03/CatalogRequest.php`
- `app/Services/Vibe/Stage03/StorefrontCatalogService.php`
- `tests/Feature/Vibe/Stage03/StorefrontCatalogTest.php`

### 4.3. Lenh khoi tao chang 3

Chang 3 build tren chang 2, nen khi setup tu dau can co schema stage 1 + 2 truoc:

```bash
php artisan migrate --path=database/migrations/2023_02_11_141113_create_admins_table.php
php artisan migrate --path=database/migrations/2023_04_15_064849_create_products_table.php
php artisan migrate --path=database/migrations/2023_04_15_064916_create_attributes_table.php
php artisan migrate --path=database/migrations/2023_04_15_064937_create_values_table.php
php artisan migrate --path=database/migrations/2023_04_16_075245_add_image_to_products_table.php
php artisan migrate --path=database/migrations/2023_05_25_022324_create_product_images_table.php
php artisan migrate --path=database/migrations/2023_05_26_031401_create_product_attr_config_table.php
php artisan migrate --path=database/migrations/2023_05_26_061449_add_type_to_products_table.php
php artisan db:seed --class=Database\\Seeders\\ProductSeeder
php artisan serve
```

### 4.4. Endpoint chay duoc cho chang 3

- `GET /vibe/stage-03/storefront/overview`
- `GET /vibe/stage-03/storefront/home`
- `GET /vibe/stage-03/storefront/products`
- `GET /vibe/stage-03/storefront/search`
- `GET /vibe/stage-03/storefront/products/{product}`

### 4.5. Cach demo tay chang 3

1. Tao du lieu san pham o chang 1 va 2.
2. Mo `GET /vibe/stage-03/storefront/home` de xem home feed.
3. Mo `GET /vibe/stage-03/storefront/products` de xem catalog.
4. Dung `GET /search?q=...` de test tim kiem.
5. Mo `GET /products/{id}` de xem detail.
6. Thu truyen vao id cua bien the va xac nhan he thong resolve ve product goc.

## 5. Kiem thu phan mem

### 5.1. Muc tieu kiem thu chang 3

Can chung minh 4 dieu:

1. Home chi hien san pham goc.
2. Search tim duoc ca theo ten goc va ten bien the.
3. Detail hien du gallery, shared attributes va variants.
4. Detail co the resolve tu variant id ve parent product.

### 5.2. Test tu dong da them

Da them file:

- `tests/Feature/Vibe/Stage03/StorefrontCatalogTest.php`

No bao phu cac tinh huong:

- home chi lay root products
- search match root hoac variant name
- detail tong hop gallery + shared attributes + variants
- detail resolve parent khi truyen variant id

### 5.3. Lenh chay test

```bash
php artisan test --filter=Stage03
```

### 5.4. Kiem thu tay de dua vao do an

1. Test home feed tra ve card san pham.
2. Test catalog list co loc theo tu khoa.
3. Test detail tra ve:
   - product info
   - gallery
   - shared attributes
   - variants
4. Test truy cap bang variant id van ra root detail.

## 6. Cach ke chang 3 trong bao cao do an

Ban co the viet chang 3 nhu sau:

> O chang 3, he thong duoc mo rong tu tang quan tri noi bo sang tang public storefront. Muc tieu la dua du lieu san pham da mo hinh hoa o chang 2 ra cho nguoi dung cuoi co the xem va tim kiem. Kien truc chang 3 gom route public, request validation cho filter tim kiem, controller storefront va service tong hop du lieu detail. Storefront hien thi home feed, catalog, ket qua search va product detail. Neu nguoi dung truy cap bang id cua bien the, he thong tu dong quy doi ve san pham goc de dam bao trai nghiem xem san pham nhat quan.

## 7. Gia tri cua chang 3 doi voi cac chang sau

Chang 3 la diem chuyen tu "he thong quan ly du lieu" sang "san pham nguoi dung co the su dung":

- Chang 4 moi them auth user vao storefront.
- Chang 5 moi them gio hang.
- Chang 6 moi them checkout/order.
- Cac chang sau moi phan tich hanh vi nguoi dung tren storefront.

Neu thieu chang 3, he thong chi la mot bo admin backend, chua phai website ban hang huong nguoi dung.
