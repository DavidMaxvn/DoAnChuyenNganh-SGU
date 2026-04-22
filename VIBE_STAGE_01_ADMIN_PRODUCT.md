# Chang 1: Xay Nen Tang Admin Va Du Lieu San Pham

## 1. Muc tieu chang 1

Day la chang mo dau cua lo trinh vibe coding. Muc tieu khong phai la lam ra toan bo website ban nong san ngay lap tuc, ma la dung mot nen tang backend co the chay duoc va mo rong duoc o cac chang sau.

Pham vi chang 1:

- Co admin dang nhap bang guard rieng.
- Co endpoint backend de xem danh sach san pham.
- Co endpoint backend de tao du lieu san pham nen tang.
- Ho tro san pham goc va san pham con thong qua `parent_id`.
- Chua can lam cac phan cua chang sau nhu category, attribute, coupon, order, AI.

Phan giao dien duoc xem la co san, nen chang nay tap trung vao code backend va data model.

## 2. Phan tich he thong

### 2.1. Bai toan nghiep vu

Neu bat dau tu so 0, he thong can mot vai tro quan tri de co the nhap du lieu hang hoa truoc khi website ban cho nguoi dung cuoi.

Neu chua co chang 1, cac chang sau se bi ket:

- khong co admin de dang nhap va quan ly
- khong co du lieu san pham de hien thi
- khong co kho du lieu de mo rong thanh category, gio hang, don hang

### 2.2. Tac nhan

- Admin: dang nhap he thong va tao du lieu san pham.

### 2.3. Use case cot loi

1. Admin dang nhap.
2. Admin vao dashboard backend.
3. Admin xem danh sach san pham nen tang.
4. Admin tao san pham goc.
5. Admin tao san pham con gan voi san pham goc.

### 2.4. Dau vao va dau ra

Dau vao:

- Email, password cua admin.
- Thong tin san pham co ban: `name`, `price`, `quantity`, `parent_id`.

Dau ra:

- Session dang nhap admin.
- Ban ghi san pham trong database.
- Danh sach san pham backend o dang JSON de UI co the su dung.

### 2.5. Rang buoc chang 1

- UI khong phai trong tam.
- Khong duoc phu thuoc vao category va attribute vi do la chang sau.
- Code phai chay duoc o muc toi thieu, de sau nay gan them module ma khong pha nen.

## 3. Thiet ke kien truc

### 3.1. Kien truc module

Chang 1 duoc tach thanh 4 lop:

1. Route layer:
   - nhan request dang nhap va quan ly san pham
2. Request validation layer:
   - validate input truoc khi vao controller
3. Controller layer:
   - xu ly logic dang nhap, danh sach san pham, tao san pham
4. Data layer:
   - bang `admins`
   - bang `products`

### 3.2. Luong xu ly

#### Luong dang nhap admin

1. Client gui `POST /vibe/stage-01/admin/login`
2. `LoginRequest` validate email/password
3. `AuthController@login` su dung guard `admin`
4. Neu dung, tao session admin
5. Tra ve JSON xac nhan dang nhap thanh cong

#### Luong tao san pham

1. Client gui `POST /vibe/stage-01/admin/products`
2. Middleware `auth:admin` xac nhan da dang nhap
3. `ProductStoreRequest` validate du lieu
4. `ProductController@store` tao ban ghi `products`
5. Tra ve JSON cua san pham moi tao

### 3.3. So do file quan trong

Day la bo file quan trong nhat cho chang 1:

- `database/migrations/2023_02_11_141113_create_admins_table.php`
- `database/migrations/2023_04_15_064849_create_products_table.php`
- `app/Models/Admin.php`
- `app/Models/Product.php`
- `config/auth.php`
- `app/Providers/RouteServiceProvider.php`
- `routes/vibe_stage_01_admin.php`
- `app/Http/Controllers/Vibe/Stage01/Admin/AuthController.php`
- `app/Http/Controllers/Vibe/Stage01/Admin/ProductController.php`
- `app/Http/Requests/Vibe/Stage01/Admin/LoginRequest.php`
- `app/Http/Requests/Vibe/Stage01/Admin/ProductStoreRequest.php`
- `database/seeders/AdminSeeder.php`
- `database/seeders/ProductSeeder.php`

### 3.4. Vi sao day la bo file toi thieu

- Migration tao du lieu goc de he thong ton tai.
- Model giup thao tac du lieu.
- Auth config giup tach admin guard.
- Route + controller giup co luong chay thuc te.
- Request giup dam bao input an toan.
- Seeder giup demo chang 1 nhanh.

## 4. Cai dat trien khai

### 4.1. Prompt vibe coding cho chang 1

Prompt hop ly de tao chang 1 tu so 0:

```text
Hay xay cho toi chang 1 cua he thong ban nong san bang Laravel.
Chi can phan backend, UI cho la da co san.
Yeu cau:
- admin dang nhap bang guard rieng
- migration admins va products
- endpoint backend de dang nhap admin
- endpoint backend de xem danh sach san pham
- endpoint backend de tao san pham co ban
- san pham ho tro parent_id de mo rong ve sau
- viet code don gian, chay duoc, de lam nen tang cho cac chang sau
```

### 4.2. Cac file moi duoc them cho chang 1 trong codebase hien tai

- `routes/vibe_stage_01_admin.php`
- `app/Http/Controllers/Vibe/Stage01/Admin/AuthController.php`
- `app/Http/Controllers/Vibe/Stage01/Admin/ProductController.php`
- `app/Http/Requests/Vibe/Stage01/Admin/LoginRequest.php`
- `app/Http/Requests/Vibe/Stage01/Admin/ProductStoreRequest.php`
- `tests/CreatesApplication.php`
- `tests/TestCase.php`
- `tests/Concerns/CreatesStage01Schema.php`
- `tests/Feature/Vibe/Stage01/AdminAuthTest.php`
- `tests/Feature/Vibe/Stage01/ProductManagementTest.php`

### 4.3. Lenh khoi tao chang 1

Neu muon chay chang 1 tren project nay:

```bash
php artisan migrate --path=database/migrations/2023_02_11_141113_create_admins_table.php
php artisan migrate --path=database/migrations/2023_04_15_064849_create_products_table.php
php artisan db:seed --class=Database\\Seeders\\AdminSeeder
php artisan db:seed --class=Database\\Seeders\\ProductSeeder
php artisan serve
```

### 4.4. Endpoint chay duoc cho chang 1

- `GET /vibe/stage-01/admin/login`
- `POST /vibe/stage-01/admin/login`
- `GET /vibe/stage-01/admin/dashboard`
- `POST /vibe/stage-01/admin/logout`
- `GET /vibe/stage-01/admin/products`
- `POST /vibe/stage-01/admin/products`
- `GET /vibe/stage-01/admin/products/{product}`

### 4.5. Du lieu mau de test tay

Admin mau tu `AdminSeeder`:

- Email: `admin1@gmail.com`
- Password: `123`

San pham mau tu `ProductSeeder`:

- `product 1`
- `product 2`
- `product 3`
- `product 4`

## 5. Kiem thu phan mem

### 5.1. Muc tieu kiem thu chang 1

Can chung minh 3 dieu:

1. Dang nhap admin hoat dong.
2. Endpoint san pham duoc bao ve boi auth admin.
3. Admin tao duoc san pham goc va san pham con.

### 5.2. Kiem thu tu dong da them

Da them cac file test:

- `tests/Feature/Vibe/Stage01/AdminAuthTest.php`
- `tests/Feature/Vibe/Stage01/ProductManagementTest.php`

No bao phu cac tinh huong:

- validate dang nhap thieu du lieu
- dang nhap thanh cong
- dang nhap sai mat khau
- chan truy cap products khi chua dang nhap
- tao san pham goc
- tao san pham con
- doc danh sach san pham

### 5.3. Lenh chay test

```bash
php artisan test --filter=Stage01
```

### 5.4. Kiem thu tay de viet vao do an

1. Mo endpoint `GET /vibe/stage-01/admin/login` de xac nhan backend san sang.
2. Dang nhap admin bang tai khoan seed.
3. Goi `GET /vibe/stage-01/admin/products` xem danh sach san pham.
4. Goi `POST /vibe/stage-01/admin/products` voi san pham moi.
5. Thu tao san pham con voi `parent_id` cua san pham goc.
6. Kiem tra database co ghi du lieu dung.

## 6. Cach ke chang 1 trong bao cao do an

Ban co the viet chang 1 nhu sau:

> O chang 1, he thong duoc xay theo huong backend-first. Muc tieu la tao mot nen tang quan tri toi thieu de nhap du lieu san pham truoc khi phat trien website ban hang day du. He thong gom 2 thuc the trung tam la admin va product. Admin dang nhap bang guard rieng, sau do co the tao va quan ly du lieu san pham o muc co ban. Kien truc duoc tach thanh route, request validation, controller va data model. Chang nay da duoc kiem thu bang test tu dong va co the chay doc lap thong qua nhom endpoint `vibe/stage-01/admin`.

## 7. Gia tri cua chang 1 doi voi cac chang sau

Chang 1 la nen mong de di tiep:

- Chang 2 co the gan category, attribute, image vao product.
- Chang 3 co the mo storefront doc du lieu product.
- Chang 4 co the them user auth.
- Chang 5 va 6 co the dung product de lam gio hang va order.
- Chang 9 tro di moi co du lieu san pham de phan tich va AI.

Neu thieu chang 1, tat ca chang sau se mat diem tua chinh.
