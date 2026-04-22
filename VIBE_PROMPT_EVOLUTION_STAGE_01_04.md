# Tien Hoa Prompt Vibe Coding Cho Chang 1 Den Chang 4

## 1. Muc dich cua tai lieu

Tai lieu nay bo sung cho `VIBE_CODING_PROCESS.md` va cac file `VIBE_STAGE_*`.

Muc tieu khong phai la viet 1 prompt dai tu dau va "doan trung" toan bo code. Nguoc lai, tai lieu nay dung de chung minh mot cach vibe coding thuc te hon:

- Nguoi dung prompt tu muc tieu nho.
- AI sinh ra mot phien ban chay duoc nhung chua day du.
- Sau khi test, nguoi dung nhin ra cho thieu.
- Prompt sau tro nen cu the hon, sat hon, co rang buoc hon.
- Cu the hoa dan cho den muc co the sinh ra code PHP/Laravel chay duoc that.

Noi ngan gon: prompt phai "tien hoa theo bug va theo khoang trong nghiep vu", khong phai "thay boi tu dau".

## 2. Cong thuc prompt thuc te cho do an nay

Voi de tai "sieu thi ban do an co AI goi y", prompt cua tung chang nen tien hoa theo cong thuc sau:

1. Prompt muc tieu MVP:
   - chi xin 1 phan nho nhat co the chay
2. Kiem thu:
   - goi endpoint
   - seed du lieu
   - xem database
   - xac dinh luong nao chua co
3. Prompt bo sung theo ket qua test:
   - noi ro dang thieu endpoint nao
   - thieu validation nao
   - thieu relation nao
   - thieu guard nao
   - thieu schema nao
4. Prompt hyper-specific:
   - chi ro framework Laravel
   - chi ro bang DB
   - chi ro ten route
   - chi ro ten field
   - chi ro guard, middleware, JSON shape
   - chi ro pham vi khong lam

Neu prompt den buoc 4, kha nang AI sinh ra code PHP chay duoc se cao hon rat nhieu.

## 3. Chang 1: Xay nen tang admin va du lieu san pham

### 3.1. Tinh huong thuc te

Ban vua tao project Laravel, vao web thay app rong. Luc nay ban chua the prompt qua dai, vi ban chua biet can bao nhieu bang, bao nhieu module. Thu tu thuc te hop ly la lam admin nho nhat co the chay duoc.

### 3.2. Prompt vong 1: con rat mo

```text
Toi dang lam do an website sieu thi ban do an bang Laravel.
Hay tao cho toi mot trang admin de quan ly san pham.
```

### 3.3. Ket qua co the sinh ra

- Co 1 controller admin.
- Co route CRUD san pham.
- Co the co view Blade.

### 3.4. Van de nhin ra sau khi test

Sau khi chay thu, ban se thay:

- chua co bang `admins`
- chua tach auth admin rieng voi user
- chua co bang `products`
- chua co route login admin
- chua ro phai tra HTML hay JSON
- chua ro product can toi thieu field nao

Nghia la prompt vong 1 rat kho sinh code PHP chay duoc on.

### 3.5. Prompt vong 2: da biet ro hon sau kiem thu

```text
Toi can phan admin backend dau tien cho do an Laravel ban do an.
Hay tao:
- bang admins de dang nhap backend
- bang products de luu san pham co ban
- auth guard rieng cho admin
- login admin
- danh sach san pham
- tao moi san pham
Chua can frontend dep, tra JSON cung duoc.
```

### 3.6. Ket qua co the co, nhung van chua du

Luc nay AI co the sinh duoc:

- migration `admins`, `products`
- auth guard `admin`
- route login
- route products

Nhung sau khi test tiep, ban se thay:

- chua noi ro field nao cua product
- chua biet co can `parent_id` khong
- chua co validation
- chua ro endpoint nao bat buoc
- chua noi middleware nao bao ve route

### 3.7. Prompt vong 3: hyper-specific de ra code chay duoc

```text
Hay xay chang 1 cho project Laravel 9 cua toi ve sieu thi ban do an.
Chi lam backend, UI coi nhu da co san.

Yeu cau ky thuat:
- tao migration `admins` gom: id, name, email unique, password, remember_token, timestamps
- tao migration `products` gom: id, name nullable, price default 0, quantity default 0, parent_id nullable, timestamps
- cau hinh `config/auth.php` de co guard `admin` dung provider `admins`
- tao route prefix `/vibe/stage-01/admin`
- tao `POST /login`
- tao `GET /products`
- tao `POST /products`
- route products phai duoc bao ve boi `auth:admin`
- login thanh cong tao session admin
- product store validate `name`, `price`, `quantity`, `parent_id`
- san pham phai ho tro `parent_id` de chang sau mo rong bien the
- controller tra JSON, khong can Blade
- viet them feature test cho login admin, chan guest, tao root product, tao child product

Pham vi khong lam:
- chua lam category
- chua lam attribute
- chua lam user auth
- chua lam cart va order
```

### 3.8. Cach ke lai trong bao cao

Ban khong noi "em prompt 1 phat ra luon chang 1". Ban noi:

> Ban dau em chi yeu cau admin quan ly san pham. Sau khi chay thu, em nhan ra can tach auth admin, can migration chuan va can parent_id de mo rong nghiep vu san pham. Luc do em prompt lai cu the hon vao schema, guard, route va test, tu do moi ra duoc mot chang 1 chay on dinh.

## 4. Chang 2: Mo hinh hoa san pham, thuoc tinh, bien the, hinh anh

### 4.1. Tinh huong thuc te

Sau chang 1, admin tao duoc san pham co ban. Nhung khi nhap du lieu thuc te cho sieu thi do an, ban se gap ngay van de:

- 1 mon hang co nhieu quy cach
- can thuoc tinh nhu khoi luong, size, loai, do cay
- can nhieu anh
- can san pham cha/con

Day la ket qua rat tu nhien sau khi demo admin product co ban.

### 4.2. Prompt vong 1: moi nghi den "thuoc tinh"

```text
San pham hien tai qua don gian.
Hay them thuoc tinh cho san pham va ho tro nhieu anh.
```

### 4.3. Van de nhin ra sau khi test

Prompt nay qua chung, AI co the sinh:

- them 1 bang attributes
- them 1 relation nao do

Nhung van chua ro:

- gia tri thuoc tinh luu o dau
- cai nao la thuoc tinh chung, cai nao la thuoc tinh bien the
- bien the sinh ra thanh record product con hay bang rieng
- gallery luu nhu text hay bang rieng
- simple va configurable khac nhau nhu the nao

### 4.4. Prompt vong 2: da ro hon sau khi nhap thu du lieu

```text
Toi can nang cap model product cho website ban do an.
San pham co the co thuoc tinh chung, thuoc tinh bien the, anh dai dien va gallery.
Hay them migration va relation de admin co the cau hinh duoc product phuc tap hon.
```

### 4.5. Van de tiep tuc lo ra

Sau khi AI sinh ra mot ban nua va ban thu cau hinh san pham, ban se thay:

- chua co cach phan biet `simple` va `configurable`
- chua co endpoint tao bien the
- chua co logic chan trung to hop bien the
- chua co snapshot tong hop de frontend/admin do du lieu

### 4.6. Prompt vong 3: hyper-specific

```text
Hay xay chang 2 cho project Laravel 9 sieu thi ban do an, build tren chang 1.
Chi lam backend JSON, khong can giao dien.

Toi can mo hinh san pham day du hon:
- bang `attributes`: id, name, timestamps
- bang `values`: product_id, attribute_id, text_value; dung de luu gia tri thuoc tinh cua tung product
- bang `product_attr_config`: product_id, attribute_id, is_private; dung de biet thuoc tinh nao la thuoc tinh bien the
- bang `product_images`: id, image, product_id, timestamps
- them cot `image` va `type` vao `products`

Nghiep vu:
- product co 2 loai: `simple` va `configurable`
- product goc co the gan shared attributes
- product configurable co variant attributes
- variant la ban ghi trong `products` voi `parent_id` tro den product goc
- moi variant co `name`, `price`, `quantity`, `image`, `attribute_values`
- khong cho phep trung to hop gia tri bien the

Endpoint:
- `POST /vibe/stage-02/admin/attributes`
- `PUT /vibe/stage-02/admin/products/{product}/model`
- `GET /vibe/stage-02/admin/products/{product}/model`
- `POST /vibe/stage-02/admin/products/{product}/variants`

Validation:
- `shared_attribute_ids` va `variant_attribute_ids` khong duoc giao nhau
- `configurable` phai co it nhat 1 variant attribute
- variant phai cung cap du gia tri cho toan bo variant attributes

Hay viet service rieng de xu ly modeling va them feature test cho:
- tao attribute
- cau hinh product configurable
- luu shared values
- luu gallery
- tao variant
- chan variant duplicate
```

### 4.7. Cach ke lai trong bao cao

> Sau khi demo chang 1, em nhan ra du lieu san pham qua ngheo, chua mo ta duoc quy cach thuc te cua do an. Em bat dau bang prompt nhe chi nhac toi thuoc tinh va nhieu anh. Sau khi chay thu, em thay can tach ro shared attributes, variant attributes, gallery va simple/configurable. Luc do prompt moi du cu the de dinh hinh schema va endpoint cua chang 2.

## 5. Chang 3: Xay storefront cho nguoi dung

### 5.1. Tinh huong thuc te

Sau chang 2, ban co mot admin kha manh, nhung mo demo ra thi thay van chua co gi cho nguoi mua xem. Day la luc prompt storefront xuat hien rat tu nhien sau khi test.

### 5.2. Prompt vong 1: mong muon rat tu nhien

```text
Toi da co du lieu san pham trong admin.
Hay lam cho toi phan trang nguoi dung de xem san pham.
```

### 5.3. Van de nhin ra sau khi test

Prompt nay qua rong. AI co the sinh HTML, Blade, route linh tinh, nhung van chua ro:

- co can home feed khong
- co can search khong
- product detail can nhung du lieu nao
- co hien bien the khong
- co cho xem variant id truc tiep khong
- co tra JSON hay HTML

### 5.4. Prompt vong 2: sau khi da demo san pham va gap thieu

```text
Toi can mot storefront toi thieu cho website ban do an.
Nguoi dung phai xem duoc danh sach san pham, tim kiem va xem chi tiet san pham.
Du lieu lay tu chang 2.
```

### 5.5. Van de tiep tuc lo ra

Sau khi chay thu, ban van se thay:

- home va catalog chua biet lay root product hay ca variant
- search chua biet co match ten variant khong
- detail chua ro co tra gallery va shared attributes khong
- khi mo bang id variant thi UX rat ro rang la phai tra ve root product

### 5.6. Prompt vong 3: hyper-specific

```text
Hay xay chang 3 cho project Laravel 9 sieu thi ban do an, build tren chang 2.
UI coi nhu da co san, backend tra JSON.

Toi can storefront public toi thieu gom:
- `GET /vibe/stage-03/storefront/overview`
- `GET /vibe/stage-03/storefront/home`
- `GET /vibe/stage-03/storefront/products`
- `GET /vibe/stage-03/storefront/search`
- `GET /vibe/stage-03/storefront/products/{product}`

Yeu cau nghiep vu:
- home va catalog chi hien root products (`parent_id = null`)
- search phai tim duoc theo ten root product hoac ten variant
- product detail phai tra ve:
  - product info
  - main_image
  - gallery
  - shared_attributes
  - variants
  - price_from, price_to
- neu truyen vao id cua variant, he thong phai resolve ve root product

Kien truc:
- tach request validation cho query `q`, `type`, `limit`
- tach service rieng de build product card va product detail snapshot
- khong lam auth, cart, order o chang nay

Hay viet feature test cho:
- home chi lay root products
- search match duoc variant name
- detail co gallery, shared attributes, variants
- detail resolve parent khi goi bang variant id
```

### 5.7. Cach ke lai trong bao cao

> Em khong prompt ngay mot storefront day du. Ban dau em chi can nguoi dung xem duoc san pham. Sau khi test, em nhan ra phai tach home, catalog, search, detail va phai quy dinh ro root product/variant thi frontend moi on dinh. Tu do prompt moi tang do chinh xac va sinh ra duoc backend stage 3 chay duoc.

## 6. Chang 4: Them auth cho user, profile, quen mat khau, social login

### 6.1. Tinh huong thuc te

Sau chang 3, website da cho xem san pham nhung chua co tai khoan nguoi dung. Khi demo tiep, ban se tu nhien nghi:

- ai la nguoi bo gio hang
- ai la nguoi tao don
- user quay lai dang nhap the nao
- quen mat khau xu ly ra sao

Day la cach nghi rat hop ly sau khi test storefront.

### 6.2. Prompt vong 1: muc tieu rat tu nhien

```text
Website da xem duoc san pham roi.
Bay gio toi can dang ky dang nhap cho nguoi dung.
```

### 6.3. Van de nhin ra sau khi test

Prompt nay chua du de ra code chay on:

- chua tach guard `web` va `admin`
- chua biet co profile khong
- chua biet co forgot password khong
- chua biet social login se lam theo provider that hay mock
- chua biet route nao guest, route nao auth

### 6.4. Prompt vong 2: sau khi gap nhu cau that

```text
Toi can them user auth cho storefront Laravel:
- register
- login
- logout
- profile
- quen mat khau
Phan admin auth da ton tai rieng.
```

### 6.5. Van de tiep tuc lo ra

Sau khi test tiep, ban se gap:

- can bang `users`, `password_resets`, `social_accounts`
- can profile update doi ten, email, phone, address, password
- can route guest va route protected
- social login that kho test trong do an neu phu thuoc provider ngoai

### 6.6. Prompt vong 3: hyper-specific

```text
Hay xay chang 4 cho project Laravel 9 sieu thi ban do an, build tren chang 3.
UI coi nhu da co san, backend tra JSON.

Toi can them tang tai khoan user dung guard `web`, tach biet voi guard `admin`.

Bang du lieu:
- `users`: id, name, email unique, password, address nullable, phone nullable, status default 1, remember_token, timestamps
- `password_resets`: id, email index, token, timestamps
- `social_accounts`: user_id, provider_user_id, provider, timestamps

Endpoint:
- `GET /vibe/stage-04/account/overview`
- `POST /vibe/stage-04/account/register`
- `POST /vibe/stage-04/account/login`
- `POST /vibe/stage-04/account/logout`
- `GET /vibe/stage-04/account/me`
- `PUT /vibe/stage-04/account/profile`
- `POST /vibe/stage-04/account/forgot-password`
- `POST /vibe/stage-04/account/reset-password`
- `POST /vibe/stage-04/account/social/callback`

Nghiep vu:
- register tao user moi nhung khong can auto login
- login dung session guard `web`
- neu `status = 0` thi chan login
- `GET /me` tra profile hien tai
- `PUT /profile` cho sua name, email, phone, address va doi password neu co
- forgot password tao token reset trong `password_resets`
- reset password doi mat khau va xoa token cu
- social login duoc mo phong bang payload `provider`, `provider_user_id`, `email`, `name`
- neu email da ton tai thi link vao user cu, khong tao duplicate

Kien truc:
- tach request validation rieng cho register, login, profile update, forgot password, reset password, social callback
- tach service rieng cho auth va account flow
- route public dung `guest:web`
- route profile/logout dung `auth:web`

Hay viet feature test cho:
- register
- login thanh cong
- login user bi khoa
- auth required cho profile
- profile update
- forgot/reset password
- social callback tao moi hoac link vao user cu
```

### 6.7. Cach ke lai trong bao cao

> Sau chang 3, khi demo storefront em nhan ra website van chua co "nguoi dung that". Em bat dau bang prompt dang ky dang nhap co ban. Sau khi kiem thu, em bo sung dan profile, password reset, trang thai khoa tai khoan va social login mo phong. Prompt cua chang 4 vi vay tro nen rat cu the o muc bang du lieu, guard, middleware, endpoint va test.

## 7. Mau prompt tien hoa chung de ban tai su dung cho cac chang sau

Ban co the ap mot form chung cho chang 5 tro di:

### 7.1. Prompt mo dau

```text
Toi da co [chang truoc].
Bay gio toi can [1 bai toan nghiep vu tiep theo].
Chi can backend chay duoc, UI coi nhu co san.
```

### 7.2. Sau kiem thu, prompt bo sung

```text
Ban code truoc da chay nhung con thieu:
- [endpoint]
- [validation]
- [relation]
- [schema]
- [middleware]
Hay bo sung dung cac phan nay, khong mo rong sang chang khac.
```

### 7.3. Prompt hyper-specific cuoi cung

```text
Hay xay chang X cho project Laravel 9 [ten de tai], build tren chang Y.
Chi lam backend JSON.

Bang du lieu:
- ...

Endpoint:
- ...

Validation:
- ...

Nghiep vu:
- ...

Pham vi khong lam:
- ...

Hay them feature test cho:
- ...
```

## 8. Cach noi 1 cau cho thuyet phuc

Neu hoi "sao prompt lai chinh xac den vay", ban co the tra loi:

> Vi prompt khong duoc viet mot lan tu dau. Moi prompt sau la ket qua cua lan chay thu truoc do. Em demo xong moi thay he thong dang thieu guard nao, bang nao, endpoint nao, validation nao, roi em moi prompt bo sung vao dung cho do. Nen prompt sau cung moi dat do chinh xac cao va sinh ra code PHP chay duoc.
