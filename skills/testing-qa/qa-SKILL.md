# QA Skill

## 1. Purpose

Skill nay huan luyen AI Tester Agent kiem tra do an theo test tu dong va checklist thu cong.

## 2. When to use

Dung khi:

- Can viet/chay test.
- Can tao checklist truoc demo.
- Can bien User Stories thanh test case.
- Can bao cao ket qua test.
- Can tim regression sau khi sua code.

## 3. Inputs

Can doc:

- `agents/back-end-agent/PRD/UserStories.md`
- `docs/Check.md`
- `tests/Feature/Vibe`
- Route lien quan
- Controller/service lien quan

## 4. Steps

1. Chon flow can test.
2. Lay acceptance criteria.
3. Tao data.
4. Goi route/action.
5. Assert status/JSON/database/session.
6. Chay command.
7. Ghi result.

## 5. Commands

```bash
php artisan test --filter=Stage01
php artisan test --filter=Stage02
php artisan test --filter=Stage03
php artisan test --filter=Stage04
php artisan test --filter=Stage05
php artisan test --filter=Stage06
php artisan test --filter=Stage07
```

## 6. Report template

```text
Stage:
Command:
Result:
Covered:
Failed:
Notes:
```

## 7. Rules

- Khong xoa test fail.
- Khong dung database production.
- Khong sua behavior de hop test neu PRD khong noi vay.
- Co bug thi ghi expected/actual ro.

## 8. Example prompt

```text
Hay viet test cho AI dashboard dismiss suggestion.
Acceptance: admin dismiss suggestion thi status doi thanh dismissed; guest khong duoc goi route.
Dung PHPUnit feature test, assert database, assert JSON.
Khong sua production code neu chua can.
```
