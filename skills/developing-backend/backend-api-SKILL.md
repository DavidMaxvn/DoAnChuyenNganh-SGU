# Backend API Skill

## 1. Purpose

Skill nay chuyen ve thiet ke route/API contract trong Laravel project. Du route tra Blade hay JSON, van can contract ro de Front-end Agent va Tester Agent lam viec khong lech.

## 2. Route contract

Moi endpoint can ghi:

- Method.
- Path.
- Route name.
- Middleware.
- Controller method.
- Request fields.
- Response/redirect.
- Error cases.
- Test command/case.

## 3. JSON response standard

Success:

```json
{
  "status": "success",
  "message": "Operation completed",
  "data": {}
}
```

Error:

```json
{
  "status": "error",
  "message": "Something went wrong"
}
```

Validation:

```json
{
  "status": "error",
  "message": "Validation failed",
  "errors": {}
}
```

## 4. Middleware decision

- Guest admin: `guest:admin`.
- Protected admin: `auth:admin`.
- Guest user: `guest:web`.
- Protected user: `auth:web`.
- User status check: `checkStatusUser`.
- Product tracking: `track.product.view`.
- API auth neu can: `auth:sanctum`.

## 5. Payload design

Khong nhan:

- `user_id` tu client neu user da auth.
- `total` tu client ma khong tinh lai.
- `is_paid` tu client.
- Secret/payment status chua verify.

Nen nhan:

- Product id/quantity.
- Shipping/contact fields.
- Coupon code.
- Payment method.
- Search/filter query.

## 6. API documentation update

Sau khi them/sua route, update:

- `docs/API_Endpoint.md`.
- `docs/Check.md` neu co verify moi.
- `agents/back-end-agent/PRD/UserStories.md` neu flow moi.

## 7. Example prompt

```text
Hay them endpoint JSON cho admin dismiss AI suggestion.
Route nam trong routes/admin.php, middleware auth:admin.
Request gom suggestion_id.
Response dung {status,message,data}.
Validate suggestion ton tai.
Khong lo stack trace.
Viet feature test cho success va suggestion khong ton tai.
Cap nhat docs/API_Endpoint.md.
```
