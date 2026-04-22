# Chang 2: Mo Hinh Hoa San Pham, Thuoc Tinh, Bien The, Hinh Anh

## 1. Muc tieu chang 2

Chang 2 mo rong nen tang cua chang 1. Neu chang 1 tra loi cau hoi "admin co dang nhap va tao du lieu san pham toi thieu duoc chua?", thi chang 2 tra loi cau hoi "san pham da du manh de mo ta nghiep vu thuc te chua?".

Pham vi chang 2:

- Tao danh muc thuoc tinh san pham.
- Cau hinh thuoc tinh chung va thuoc tinh bien the cho tung san pham.
- Ho tro san pham `simple` va `configurable`.
- Ho tro san pham con thong qua `parent_id`.
- Ho tro anh dai dien va gallery anh.
- Ho tro tao bien the tu cac to hop gia tri thuoc tinh.

UI duoc xem la da co san, nen chang nay tiep tuc tap trung vao backend va data model.

## 2. Phan tich he thong

### 2.1. Bai toan nghiep vu

Neu san pham chi co `name`, `price`, `quantity`, thi moi phu hop voi du lieu rat don gian. Trong he thong ban nong san hien tai, san pham can duoc mo ta chi tiet hon:

- mot san pham co the co nhieu thuoc tinh
- co thuoc tinh dung chung cho ca san pham
- co thuoc tinh chi dung de sinh bien the
- co anh dai dien va nhieu anh gallery
- co nhieu phien ban con cua san pham goc

Vi vay, chang 2 duoc dung de nang cap tu "du lieu san pham co ban" thanh "mo hinh san pham co the mo rong".

### 2.2. Tac nhan

- Admin: tao thuoc tinh, cau hinh mo hinh san pham, them gallery, tao bien the.

### 2.3. Use case cot loi

1. Admin tao thuoc tinh moi.
2. Admin cau hinh 1 san pham thanh `simple` hoac `configurable`.
3. Admin gan thuoc tinh chung cho san pham.
4. Admin gan thuoc tinh bien the cho san pham.
5. Admin nhap gia tri thuoc tinh chung cho san pham goc.
6. Admin them anh dai dien va gallery anh.
7. Admin tao san pham con theo to hop thuoc tinh bien the.

### 2.4. Dau vao va dau ra

Dau vao:

- Danh sach `attribute`
- `shared_attribute_ids`
- `variant_attribute_ids`
- `shared_attribute_values`
- `main_image`
- `gallery`
- du lieu cua bien the: `name`, `price`, `quantity`, `image`, `attribute_values`

Dau ra:

- cau truc san pham da duoc mo hinh hoa
- gallery anh cua san pham
- danh sach bien the da tao
- snapshot tong hop de UI co the render

### 2.5. Rang buoc chang 2

- Chang 2 xay tren chang 1, nen da co `admins` va `products`.
- Chua can `category`, `order`, `coupon`, `AI`.
- Thuoc tinh chung va thuoc tinh bien the khong duoc trung nhau.
- San pham `configurable` phai co it nhat 1 thuoc tinh bien the.
- Bien the khong duoc trung to hop gia tri thuoc tinh.

## 3. Thiet ke kien truc

### 3.1. Kien truc module

Chang 2 duoc tach thanh 5 lop:

1. Route layer:
   - nhan request tao thuoc tinh, cau hinh product, tao variant
2. Request validation layer:
   - validate input o muc request
3. Controller layer:
   - dieu phoi request/response cho stage 2
4. Service layer:
   - xu ly nghiep vu modeling san pham
5. Data layer:
   - `attributes`
   - `values`
   - `product_attr_config`
   - `product_images`
   - bo sung `image` va `type` cho `products`

### 3.2. Data model chang 2

- `attributes`: danh sach thuoc tinh co the tai su dung
- `product_attr_config`: thuoc tinh nao duoc gan cho san pham, va co phai thuoc tinh bien the hay khong
- `values`: gia tri thuoc tinh gan cho 1 san pham cu the
- `product_images`: gallery anh phu cua san pham
- `products.image`: anh dai dien
- `products.type`: `simple` hoac `configurable`

### 3.3. Luong xu ly

#### Luong 1: Tao thuoc tinh

1. Admin gui `POST /vibe/stage-02/admin/attributes`
2. `AttributeStoreRequest` validate ten thuoc tinh
3. `AttributeController@store` tao ban ghi `attributes`
4. Tra ve JSON thuoc tinh moi

#### Luong 2: Cau hinh mo hinh san pham

1. Admin gui `PUT /vibe/stage-02/admin/products/{product}/model`
2. `ProductModelRequest` validate du lieu
3. `ProductModelingService@configureProduct`:
   - cap nhat `type`
   - cap nhat `main_image`
   - dong bo `product_attr_config`
   - dong bo gia tri thuoc tinh chung vao `values`
   - dong bo gallery vao `product_images`
4. Tra ve snapshot tong hop cua san pham

#### Luong 3: Tao bien the

1. Admin gui `POST /vibe/stage-02/admin/products/{product}/variants`
2. `VariantStoreRequest` validate du lieu
3. `ProductModelingService@createVariant`:
   - kiem tra product goc la `configurable`
   - kiem tra da truyen day du thuoc tinh bien the
   - kiem tra to hop bien the chua ton tai
   - tao san pham con
   - luu gia tri thuoc tinh bien the vao `values`
4. Tra ve snapshot moi cua san pham goc

### 3.4. File quan trong nhat cua chang 2

File du lieu goc:

- `database/migrations/2023_04_15_064916_create_attributes_table.php`
- `database/migrations/2023_04_15_064937_create_values_table.php`
- `database/migrations/2023_04_16_075245_add_image_to_products_table.php`
- `database/migrations/2023_05_25_022324_create_product_images_table.php`
- `database/migrations/2023_05_26_031401_create_product_attr_config_table.php`
- `database/migrations/2023_05_26_061449_add_type_to_products_table.php`

File backend stage 2:

- `routes/vibe_stage_02_admin.php`
- `app/Http/Controllers/Vibe/Stage02/Admin/AttributeController.php`
- `app/Http/Controllers/Vibe/Stage02/Admin/ProductModelingController.php`
- `app/Http/Requests/Vibe/Stage02/Admin/AttributeStoreRequest.php`
- `app/Http/Requests/Vibe/Stage02/Admin/ProductModelRequest.php`
- `app/Http/Requests/Vibe/Stage02/Admin/VariantStoreRequest.php`
- `app/Services/Vibe/Stage02/ProductModelingService.php`

File model/relation duoc tai su dung:

- `app/Models/Product.php`
- `app/Models/Attribute.php`
- `app/Models/ProductImage.php`

### 3.5. Vi sao day la bo file toi thieu

- Migration phan anh dung data model cua san pham phuc tap.
- Controller va request giup co luong chay ro rang.
- Service giu logic nghiep vu tap trung, de UI va chang sau de mo rong.
- Product/Attribute/ProductImage la 3 model quan trong nhat de noi backend vao database.

## 4. Cai dat trien khai

### 4.1. Prompt vibe coding cho chang 2

```text
Hay mo rong chang 1 cua he thong ban nong san thanh chang 2.
Toi can:
- bang attributes
- bang values de luu gia tri thuoc tinh
- bang product_attr_config de biet thuoc tinh nao la chung, thuoc tinh nao la bien the
- bang product_images cho gallery
- them image va type cho products
- endpoint backend de tao thuoc tinh
- endpoint backend de cau hinh san pham simple/configurable
- endpoint backend de tao bien the
UI coi nhu da co san, backend tra JSON la duoc.
```

### 4.2. Cac file moi duoc them cho chang 2 trong codebase hien tai

- `routes/vibe_stage_02_admin.php`
- `app/Http/Controllers/Vibe/Stage02/Admin/AttributeController.php`
- `app/Http/Controllers/Vibe/Stage02/Admin/ProductModelingController.php`
- `app/Http/Requests/Vibe/Stage02/Admin/AttributeStoreRequest.php`
- `app/Http/Requests/Vibe/Stage02/Admin/ProductModelRequest.php`
- `app/Http/Requests/Vibe/Stage02/Admin/VariantStoreRequest.php`
- `app/Services/Vibe/Stage02/ProductModelingService.php`
- `tests/Concerns/CreatesStage02Schema.php`
- `tests/Feature/Vibe/Stage02/ProductModelingTest.php`

### 4.3. Lenh khoi tao chang 2

Chang 2 build tren chang 1, nen khi setup tu dau can migrate ca hai chang:

```bash
php artisan migrate --path=database/migrations/2023_02_11_141113_create_admins_table.php
php artisan migrate --path=database/migrations/2023_04_15_064849_create_products_table.php
php artisan migrate --path=database/migrations/2023_04_15_064916_create_attributes_table.php
php artisan migrate --path=database/migrations/2023_04_15_064937_create_values_table.php
php artisan migrate --path=database/migrations/2023_04_16_075245_add_image_to_products_table.php
php artisan migrate --path=database/migrations/2023_05_25_022324_create_product_images_table.php
php artisan migrate --path=database/migrations/2023_05_26_031401_create_product_attr_config_table.php
php artisan migrate --path=database/migrations/2023_05_26_061449_add_type_to_products_table.php
php artisan db:seed --class=Database\\Seeders\\AdminSeeder
php artisan db:seed --class=Database\\Seeders\\ProductSeeder
php artisan serve
```

### 4.4. Endpoint chay duoc cho chang 2

- `GET /vibe/stage-02/admin/overview`
- `GET /vibe/stage-02/admin/attributes`
- `POST /vibe/stage-02/admin/attributes`
- `PUT /vibe/stage-02/admin/products/{product}/model`
- `GET /vibe/stage-02/admin/products/{product}/model`
- `POST /vibe/stage-02/admin/products/{product}/variants`

### 4.5. Cach demo tay chang 2

1. Dang nhap admin bang tai khoan da co tu chang 1.
2. Tao 2-3 thuoc tinh nhu `Trong luong`, `Mau sac`, `Kich co`.
3. Chon 1 san pham goc tu chang 1.
4. Goi endpoint cau hinh san pham thanh `configurable`.
5. Gan `shared_attribute_ids` va `variant_attribute_ids`.
6. Them `main_image` va `gallery`.
7. Tao 1-2 bien the bang endpoint variant.
8. Goi endpoint `GET /model` de xem snapshot tong hop.

## 5. Kiem thu phan mem

### 5.1. Muc tieu kiem thu chang 2

Can chung minh 5 dieu:

1. Endpoint stage 2 duoc bao ve boi auth admin.
2. Admin tao duoc thuoc tinh.
3. San pham configurable duoc cau hinh dung.
4. Admin tao duoc bien the.
5. He thong chan duoc to hop bien the trung lap.

### 5.2. Test tu dong da them

Da them file:

- `tests/Feature/Vibe/Stage02/ProductModelingTest.php`

No bao phu cac tinh huong:

- chan truy cap khi chua dang nhap
- tao attribute
- chan thuoc tinh chung va thuoc tinh bien the bi trung
- cau hinh configurable product + gallery + shared values
- tao variant
- chan variant duplicate

### 5.3. Lenh chay test

```bash
php artisan test --filter=Stage02
```

### 5.4. Kiem thu tay de dua vao do an

1. Test khong auth thi khong vao duoc endpoint stage 2.
2. Test tao thuoc tinh thanh cong.
3. Test cau hinh `type=configurable` va xem du lieu da ghi vao:
   - `products`
   - `product_attr_config`
   - `values`
   - `product_images`
4. Test tao bien the.
5. Test tao trung bien the thi bi chan.
6. Test endpoint `GET /model` tra ve dung snapshot.

## 6. Cach ke chang 2 trong bao cao do an

Ban co the viet chang 2 nhu sau:

> Sau khi hoan thanh chang 1 voi admin auth va du lieu san pham co ban, he thong duoc mo rong o chang 2 de ho tro mo hinh san pham phuc tap. Nghiep vu duoc phan tach thanh thuoc tinh chung, thuoc tinh bien the, gia tri thuoc tinh, anh dai dien, gallery va san pham con. Kien truc chang 2 duoc chia thanh route, request validation, controller, service va data layer. Mot san pham co the duoc cau hinh thanh simple hoac configurable. Neu la configurable, admin co the sinh cac bien the va he thong kiem tra trung lap to hop gia tri thuoc tinh. Toan bo chang 2 da duoc kiem thu tu dong va co endpoint JSON de UI tich hop.

## 7. Gia tri cua chang 2 doi voi cac chang sau

Chang 2 la buoc quyet dinh de san pham tro nen "co nghiep vu that":

- Chang 3 moi co san pham day du de dua len storefront.
- Chang 5, 6 moi co bien the de dua vao gio hang va order.
- Chang AI sau nay moi co du lieu phong phu de phan tich san pham.

Khong co chang 2 thi du an chi dung o muc demo san pham don gian, chua dat muc he thong ban hang thuc te.
