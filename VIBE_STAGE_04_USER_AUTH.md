# Chang 4: Them Auth Cho User, Profile, Quen Mat Khau, Social Login

## 1. Muc tieu chang 4

Sau chang 3, he thong da co storefront cho nguoi dung xem san pham. Tuy nhien, nguoi dung van chua co danh tinh trong he thong, nen chua the di tiep toi cac chang gio hang, checkout va theo doi don hang. Vi vay, chang 4 duoc dung de them lop tai khoan nguoi dung.

Pham vi chang 4:

- Dang ky tai khoan user.
- Dang nhap va dang xuat theo guard `web`.
- Xem va cap nhat profile.
- Quen mat khau va dat lai mat khau.
- Social login theo mo hinh callback payload de chay duoc offline.

Phan giao dien duoc xem la co san, nen chang 4 tap trung vao backend JSON va session auth.

## 2. Phan tich he thong

### 2.1. Bai toan nghiep vu

Khi storefront da ton tai, nguoi dung can co tai khoan de:

- luu thong tin ca nhan
- quay lai dang nhap o cac lan mua sau
- doi mat khau khi quen
- dang nhap nhanh bang tai khoan mang xa hoi

Neu bo qua chang 4, he thong se bi dut mach giua "xem san pham" va "mua hang co dinh danh nguoi dung".

### 2.2. Tac nhan

- Khach chua co tai khoan.
- User da dang nhap.
- He thong xac thuc session `web`.
- Nha cung cap social login o muc mo phong callback.

### 2.3. Use case cot loi

1. Khach dang ky tai khoan moi.
2. Khach dang nhap vao storefront.
3. User dang xuat.
4. User xem profile hien tai.
5. User cap nhat thong tin profile.
6. Khach quen mat khau va yeu cau reset.
7. Khach dat lai mat khau bang token.
8. Khach dang nhap bang social provider.

### 2.4. Dau vao va dau ra

Dau vao:

- `name`, `email`, `password`, `password_confirm`
- `phone`, `address`
- `remember`
- `token`
- `provider`, `provider_user_id`

Dau ra:

- thong tin user sau khi dang ky/dang nhap
- session auth `web`
- token reset luu trong `password_resets`
- lien ket social luu trong `social_accounts`

### 2.5. Rang buoc chang 4

- Chang 4 build tren tang storefront cua chang 3.
- UI va mail template duoc xem la co san.
- De phase chay doc lap va test duoc, social login duoc mo phong bang `POST /social/callback` thay vi redirect toi nha cung cap that.
- Logic van bam sat san pham hien tai vi tai su dung bang `users`, `password_resets`, `social_accounts` va guard `web`.

## 3. Thiet ke kien truc

### 3.1. Kien truc module

Chang 4 duoc tach thanh 4 lop:

1. Route layer:
   - gom route public cho register/login/forgot/reset/social callback
   - gom route protected cho profile va logout
2. Request validation layer:
   - validate tung form auth theo tung use case
3. Controller layer:
   - nhan request va tra JSON
4. Service layer:
   - chua nghiep vu auth, profile, reset password va social login

### 3.2. Luong xu ly

#### Luong 1: Dang ky

1. Client goi `POST /vibe/stage-04/account/register`
2. `RegisterRequest` validate `name`, `email`, `password`, `password_confirm`
3. `UserAccountService@register` tao user moi trong bang `users`
4. He thong tra ve user snapshot va chi dan buoc tiep theo la dang nhap

#### Luong 2: Dang nhap va dang xuat

1. Client goi `POST /login`
2. `LoginRequest` validate input
3. `UserAccountService@login` dung `Auth::guard('web')->attempt(...)`
4. Neu tai khoan bi khoa, he thong huy session va bao loi
5. Neu thanh cong, regenerate session va tra ve user snapshot
6. Khi dang xuat, route `POST /logout` huy session va token CSRF hien tai

#### Luong 3: Profile

1. User da dang nhap goi `GET /me`
2. Backend tra ve thong tin profile hien tai
3. User goi `PUT /profile`
4. `ProfileUpdateRequest` validate name/email/password moi
5. `UserAccountService@updateProfile` cap nhat ten, email, phone, address va mat khau neu co

#### Luong 4: Quen mat khau

1. Client goi `POST /forgot-password`
2. `ForgotPasswordRequest` xac nhan email ton tai
3. `UserAccountService@createPasswordResetToken` tao token trong bang `password_resets`
4. Trong phase nay, backend tra token preview cho UI/mail layer de demo va test
5. Client goi `POST /reset-password` voi `email`, `token`, `password`
6. `UserAccountService@resetPassword` doi mat khau moi va xoa token cu

#### Luong 5: Social login

1. Client nhan payload tu provider hoac mock provider
2. Client goi `POST /social/callback`
3. `SocialCallbackRequest` validate `provider`, `provider_user_id`, `email`
4. `UserAccountService@socialLogin` tim lien ket cu trong `social_accounts`
5. Neu chua co lien ket:
   - tim user theo email
   - neu chua co user thi tao user moi
   - tao lien ket social moi
6. He thong dang nhap user vao guard `web` va tra ve ket qua

### 3.3. File quan trong nhat cua chang 4

File backend stage 4:

- `routes/vibe_stage_04_account.php`
- `app/Http/Controllers/Vibe/Stage04/AuthenticationController.php`
- `app/Http/Controllers/Vibe/Stage04/ProfileController.php`
- `app/Http/Controllers/Vibe/Stage04/PasswordResetController.php`
- `app/Http/Controllers/Vibe/Stage04/SocialLoginController.php`
- `app/Services/Vibe/Stage04/UserAccountService.php`

File validation:

- `app/Http/Requests/Vibe/Stage04/RegisterRequest.php`
- `app/Http/Requests/Vibe/Stage04/LoginRequest.php`
- `app/Http/Requests/Vibe/Stage04/ProfileUpdateRequest.php`
- `app/Http/Requests/Vibe/Stage04/ForgotPasswordRequest.php`
- `app/Http/Requests/Vibe/Stage04/ResetPasswordRequest.php`
- `app/Http/Requests/Vibe/Stage04/SocialCallbackRequest.php`

File data/model duoc tai su dung:

- `app/Models/User.php`
- `app/Models/SocialAccount.php`
- `config/auth.php`
- `database/migrations/2023_05_08_145112_create_users_table.php`
- `database/migrations/2023_05_04_044437_create_password_resets_table.php`
- `database/migrations/2023_05_24_093541_create_social_accounts_table.php`

File kiem thu:

- `tests/Concerns/CreatesStage04Schema.php`
- `tests/Feature/Vibe/Stage04/UserAccountTest.php`

### 3.4. Vi sao day la bo file toi thieu

- Route/controller la entrypoint bat buoc de giao dien co the goi auth.
- Request tach rieng de moi use case co validation ro rang.
- Service tap trung nghiep vu session auth, profile va social account, tranh de logic bi tan man trong controller.
- `users`, `password_resets`, `social_accounts` la 3 bang du lieu cot loi cua chang 4.
- Test schema rieng giup chung minh chang 4 co the chay doc lap trong hanh trinh vibe coding.

## 4. Cai dat trien khai

### 4.1. Prompt vibe coding cho chang 4

```text
Hay xay chang 4 cua he thong ban nong san.
Toi da co storefront public o chang 3.
Bay gio toi can them tang tai khoan nguoi dung gom:
- register
- login/logout bang guard web
- xem va sua profile
- quen mat khau va reset mat khau
- social login
UI coi nhu da co san, backend tra JSON va phai chay test duoc.
Neu social login kho demo voi provider that thi hay dung callback payload de mo phong nhung van bam sat bang social_accounts hien co.
```

### 4.2. Cac file moi duoc them cho chang 4 trong codebase hien tai

- `routes/vibe_stage_04_account.php`
- `app/Http/Controllers/Vibe/Stage04/AuthenticationController.php`
- `app/Http/Controllers/Vibe/Stage04/ProfileController.php`
- `app/Http/Controllers/Vibe/Stage04/PasswordResetController.php`
- `app/Http/Controllers/Vibe/Stage04/SocialLoginController.php`
- `app/Http/Requests/Vibe/Stage04/RegisterRequest.php`
- `app/Http/Requests/Vibe/Stage04/LoginRequest.php`
- `app/Http/Requests/Vibe/Stage04/ProfileUpdateRequest.php`
- `app/Http/Requests/Vibe/Stage04/ForgotPasswordRequest.php`
- `app/Http/Requests/Vibe/Stage04/ResetPasswordRequest.php`
- `app/Http/Requests/Vibe/Stage04/SocialCallbackRequest.php`
- `app/Services/Vibe/Stage04/UserAccountService.php`
- `tests/Concerns/CreatesStage04Schema.php`
- `tests/Feature/Vibe/Stage04/UserAccountTest.php`

### 4.3. Lenh khoi tao chang 4

Chang 4 co the dung doc lap voi bo bang tai khoan toi thieu:

```bash
php artisan migrate --path=database/migrations/2023_05_08_145112_create_users_table.php
php artisan migrate --path=database/migrations/2023_05_04_044437_create_password_resets_table.php
php artisan migrate --path=database/migrations/2023_05_24_093541_create_social_accounts_table.php
php artisan serve
```

### 4.4. Endpoint chay duoc cho chang 4

- `GET /vibe/stage-04/account/overview`
- `POST /vibe/stage-04/account/register`
- `POST /vibe/stage-04/account/login`
- `POST /vibe/stage-04/account/logout`
- `GET /vibe/stage-04/account/me`
- `PUT /vibe/stage-04/account/profile`
- `POST /vibe/stage-04/account/forgot-password`
- `POST /vibe/stage-04/account/reset-password`
- `POST /vibe/stage-04/account/social/callback`

### 4.5. Cach demo tay chang 4

1. Goi `POST /register` de tao user moi.
2. Goi `POST /login` de tao session `web`.
3. Goi `GET /me` de xem profile.
4. Goi `PUT /profile` de cap nhat ten, so dien thoai, dia chi.
5. Goi `POST /forgot-password` de lay `reset_payload`.
6. Goi `POST /reset-password` voi token vua tao.
7. Goi `POST /social/callback` voi payload provider demo de mo phong dang nhap bang mang xa hoi.

## 5. Kiem thu phan mem

### 5.1. Muc tieu kiem thu chang 4

Can chung minh 6 nhom hanh vi:

1. Register tao duoc user moi.
2. Login tao duoc session cho user hop le.
3. Tai khoan bi khoa khong the dang nhap.
4. Profile can auth va cap nhat duoc.
5. Forgot/reset password tao token va doi duoc mat khau.
6. Social login tao duoc user moi hoac gan vao user cu ma khong tao duplicate.

### 5.2. Test tu dong da them

Da them file:

- `tests/Feature/Vibe/Stage04/UserAccountTest.php`

No bao phu cac tinh huong:

- overview mo ta dung scope chang 4
- register tao user moi
- login thanh cong voi user active
- login that bai voi user bi khoa
- profile yeu cau auth va update duoc
- forgot password tao token
- reset password doi duoc mat khau va xoa token
- social login tao moi hoac lien ket vao user cu

### 5.3. Lenh chay test

```bash
php artisan test --filter=Stage04
```

### 5.4. Kiem thu tay de dua vao do an

1. Dang ky mot tai khoan moi va xac nhan bang `users` co them ban ghi.
2. Dang nhap va xac nhan route `GET /me` tra ve thong tin user dang nhap.
3. Thu khoa tai khoan `status = 0` va xac nhan login bi tu choi.
4. Thu cap nhat profile va doi mat khau ngay trong profile.
5. Thu quen mat khau, lay token preview va reset password.
6. Thu social callback voi:
   - email chua ton tai
   - email da ton tai

## 6. Cach ke chang 4 trong bao cao do an

Ban co the viet chang 4 nhu sau:

> O chang 4, he thong duoc bo sung tang tai khoan nguoi dung de ket noi storefront public voi cac nghiep vu ca nhan hoa. Kien truc chang 4 gom route auth, request validation cho tung form, controller tiep nhan request va service xu ly session login, profile, password reset, social account. He thong tai su dung guard `web` cung cac bang `users`, `password_resets`, `social_accounts`. De dam bao phase co the chay duoc trong moi truong do an ma khong phu thuoc nha cung cap OAuth that, social login duoc trien khai o muc callback payload mo phong nhung van giu dung cau truc lien ket tai khoan xa hoi cua san pham hien tai.

## 7. Gia tri cua chang 4 doi voi cac chang sau

Chang 4 mo khoa cac phase tiep theo:

- Chang 5 moi co the gan gio hang vao user.
- Chang 6 moi co the tao don hang gop voi thong tin user dang nhap.
- Chang 7 tro di moi co the theo doi lich su mua va hanh vi nguoi dung.

Neu thieu chang 4, storefront chi dung lai o muc xem san pham, chua tao duoc trai nghiem mua hang co dinh danh nguoi dung.
