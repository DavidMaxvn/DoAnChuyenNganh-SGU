# Back-end MCP Usage

## 1. Muc dich

MCP viet tat cua Model Context Protocol. Trong bo agent cua do an nay, MCP duoc hieu la lop ket noi giua AI Agent va cac nguon context/cong cu lam viec co kiem soat.

File nay tra loi 3 cau hoi:

- AI duoc phep lay context tu dau.
- AI da dung nhung nhom cong cu nao khi lam viec trong repo.
- Neu sau nay gan MCP server that, Back-end Agent nen dung nhu the nao cho an toan.

MCP khong thay the `AGENT.md`, `Rules.md`, `Skill.md` hay `Workflows.md`. MCP chi la kenh dua context/tool vao agent. Quyet dinh dung tool nao van phai theo rules, workflow, security va acceptance criteria cua do an.

## 2. Trang thai MCP trong workspace hien tai

Tai thoi diem bo sung tai lieu nay, workspace chua khai bao MCP resource/template co dinh nao cho agent:

- `list_mcp_resources`: khong co resource.
- `list_mcp_resource_templates`: khong co template.

Vi vay trong qua trinh lam viec, AI chu yeu dung local tool layer cua moi truong coding:

- PowerShell de liet ke file, doc file va chay command.
- `rg` de tim nhanh route, controller, keyword va tai lieu.
- File patch tool de sua file theo diff co kiem soat.
- Laravel Artisan/PHPUnit khi can verify code.
- Local docs trong repo lam nguon context chinh.

Neu sau nay cau hinh them MCP server, file nay la quy uoc de AI biet nen dung MCP nao, dung khi nao va gioi han ra sao.

## 3. Nguon context AI da dung trong repo

Back-end Agent uu tien doc context theo thu tu:

1. Agent instruction:

- `agents/back-end-agent/AGENT.md`
- `agents/back-end-agent/Rules.md`
- `agents/back-end-agent/Skill.md`
- `agents/back-end-agent/Workflows.md`
- `agents/back-end-agent/Plan.md`
- `agents/back-end-agent/MCP.md`

2. Product requirement:

- `agents/back-end-agent/PRD/PRD.md`
- `agents/back-end-agent/PRD/UserStories.md`
- `agents/back-end-agent/PRD/Database_Schema.md`

3. Project contract:

- `docs/API_Endpoint.md`
- `docs/Check.md`
- `docs/AI_Agent_Training_Report.md`
- `SCRIPT_BAO_CAO_VA_SLIDE_THUYET_TRINH.md`

4. Source code:

- `routes/*.php`
- `app/Http/Controllers/**`
- `app/Http/Requests/**`
- `app/Services/**`
- `app/Models/**`
- `database/migrations/**`
- `database/seeders/**`
- `tests/Feature/**`

## 4. Nhom cong cu AI da dung

### 4.1 Filesystem/search

Muc dich:

- Doc cau truc repo.
- Tim route, controller, service, model va test lien quan.
- Tim noi da co keyword de khong viet trung/lac style.

Cong cu/lenh thuong dung:

```bash
rg -n "keyword" routes app docs agents
rg --files
Get-ChildItem -Force
Get-Content path/to/file.md
```

Quy tac:

- Uu tien `rg` truoc khi mo tung file lon.
- Doc file lien quan truoc khi sua.
- Khong sua file ngoai ownership neu task backend khong can.

### 4.2 File patch/edit

Muc dich:

- Sua code/tai lieu bang diff ro rang.
- Giu thay doi nho, de review.

Cong cu:

- `apply_patch` hoac co che patch tuong duong trong coding environment.

Quy tac:

- Khong ghi de bang cach sinh lai toan bo file neu chi can sua mot doan.
- Khong revert thay doi cua nguoi dung.
- Sau khi sua, doc lai diff hoac file quan trong neu thay doi co rui ro.

### 4.3 Laravel command/test

Muc dich:

- Verify feature theo tung stage.
- Kiem tra route/config/migration khi can.

Lenh goi y:

```bash
php artisan test --filter=Stage01
php artisan test --filter=Stage02
php artisan test --filter=Stage03
php artisan test --filter=Stage04
php artisan test --filter=Stage05
php artisan test --filter=Stage06
php artisan test --filter=Stage07
php artisan route:list
php artisan migrate:status
```

Quy tac:

- Chay test gan nhat voi thay doi truoc.
- Khong chay migration/drop/reset tren database production.
- Neu test khong chay duoc, ghi ro ly do va cach verify thu cong.

### 4.4 Local service/log

Muc dich:

- Kiem tra app/db khi project chay bang Docker hoac local server.
- Doc log khi backend loi.

Lenh goi y neu project dung Docker:

```bash
docker compose ps
docker compose up -d
docker compose logs --tail=100 api
docker compose logs --tail=100 db-init
```

Quy tac:

- Chi doc log can thiet.
- Khong dua password, token, secret vao tai lieu/bao cao.
- Khong restart service neu task chi can sua tai lieu.

### 4.5 Browser/manual verification

Muc dich:

- Kiem tra UI route admin/storefront neu behavior lien quan den Blade.
- Chup bang chung demo neu can cho bao cao.

Tool co the gan qua MCP sau nay:

- Browser MCP.
- Playwright MCP.

Quy tac:

- Backend Agent chi verify UI o muc route contract khi can.
- Loi layout/animation chuyen Front-end Agent neu khong thuoc backend.

### 4.6 Database/schema context

Muc dich:

- Doc bang/cot/relationship.
- Xac minh migration co khop voi PRD va model.

Nguon hien tai:

- `database/migrations/**`
- `app/Models/**`
- `agents/back-end-agent/PRD/Database_Schema.md`

MCP co the gan sau nay:

- Database MCP read-only cho MySQL/MariaDB/SQLite.

Quy tac:

- Mac dinh truy van read-only.
- Khong sua data that neu chua duoc yeu cau ro.
- Khi thay doi schema, cap nhat migration, model relationship va `Database_Schema.md`.

## 5. MCP server nen cau hinh neu mo rong

| MCP/server | Dung de lam gi | Gioi han an toan |
| --- | --- | --- |
| Filesystem MCP | Doc/sua file trong repo | Chi cho phep workspace, khong doc secret ngoai can thiet |
| Git/GitHub MCP | Xem diff, issue, PR, commit history | Khong push/merge neu chua co lenh ro |
| Database MCP | Doc schema, query test data | Read-only mac dinh, khong dung production |
| Browser/Playwright MCP | Verify route/UI, chup screenshot | Khong thao tac thanh toan that |
| Docs MCP | Tra cuu docs Laravel/OpenAI/payment | Uu tien official docs, ghi nguon neu trich dan |
| Terminal MCP | Chay test/build/artisan | Lenh destructive phai xin phep |

## 6. Mapping task backend voi MCP/tool

| Task | Context can doc | Tool/MCP nen dung | Output can co |
| --- | --- | --- | --- |
| Them route moi | `routes`, controller, API docs | Filesystem/search | Route name, middleware, request/response |
| Sua validation | FormRequest/controller/test | Search + file patch | Rule ro, loi 422 hoac redirect error |
| Sua checkout | Cart, order, payment service, migration | Search + test + database read-only | Transaction, idempotency, test/check |
| Sua payment callback | Config, controller, order schema | Search + log + test | Verify signature/amount/status |
| Sua AI dashboard | Activity log, suggestion, analytics route | Search + test + browser verify | Metric, fallback, dismiss action |
| Sua microservice stage 7 | Inventory, pricing, orchestrator, outbox | Search + Stage07 test | Boundary ro, event payload, rollback |
| Cap nhat tai lieu | Agent docs, API docs, Check | Filesystem/search + patch | Tai lieu khop behavior moi |

## 7. Workflow dung MCP cho Back-end Agent

1. Doc `AGENT.md`, `Rules.md`, `Skill.md`, `Workflows.md`, `MCP.md`.
2. Tim entry point bang `rg`: route, controller, service, test.
3. Doc PRD/schema/API endpoint neu task lien quan nghiep vu.
4. Xac dinh actor, input, output, bang/cot, transaction va loi can xu ly.
5. Sua file bang patch nho, giu dung convention Laravel.
6. Chay test gan nhat hoac route verify.
7. Cap nhat docs: API, Database Schema, UserStories, Check hoac agent docs.
8. Bao cao ngan gon: file da sua, verify da chay, rui ro con lai.

## 8. Quy tac bao mat khi dung MCP/tool

- Khong doc/ghi/commit `.env` neu khong co ly do ro.
- Khong dua secret vao prompt, log, tai lieu, slide.
- Khong goi API thanh toan production khi demo.
- Khong reset database, drop table, truncate data neu chua duoc yeu cau ro.
- Khong sua vendor, node_modules, file build sinh ra neu khong can.
- Neu tool cho ket qua khong chac, AI phai doc them code/test thay vi doan.

## 9. Bang chung dua vao bao cao/thuyet trinh

Khi giang vien hoi "AI da dung gi de lam project?", co the tra loi:

```text
Nhom khong chi prompt tu do. AI duoc dat trong bo agent co Rules, Skill, Workflow va MCP usage. AI doc PRD, schema, route, source code, test va docs trong repo; sau do dung tool co kiem soat nhu search, file patch, artisan test va log de sua/verify. MCP duoc xem la lop ket noi context/tool, con con nguoi van quyet dinh scope, review ket qua va chay demo.
```

Bang chung co the chi:

- `agents/back-end-agent/MCP.md`
- `agents/back-end-agent/AGENT.md`
- `agents/back-end-agent/Rules.md`
- `agents/back-end-agent/Skill.md`
- `agents/back-end-agent/Workflows.md`
- `docs/Check.md`
- Ket qua lenh `php artisan test --filter=StageXX`

## 10. Prompt mau cho AI khi can dung MCP/tool

```text
Ban la Back-end Agent cua do an Laravel. Truoc khi sua code, hay doc AGENT.md, Rules.md, Skill.md, Workflows.md va MCP.md. Dung rg de tim route/controller/service/test lien quan. Neu thay doi request/response/schema, cap nhat docs tuong ung. Chay test gan nhat neu co the. Khong doc secret, khong reset database, khong sua frontend neu khong can.
```

## 11. Checklist nhanh

Truoc khi ket thuc task backend:

- Da doc dung context agent.
- Da tim file bang `rg` hoac route/test lien quan.
- Da sua dung pham vi backend.
- Da validate input/error/transaction neu co.
- Da chay test/check gan nhat hoac ghi ly do khong chay.
- Da cap nhat docs lien quan.
- Da khong dua secret/log nhay cam vao output.

## 12. Ghi chu phien bo sung file MCP nay

Trong lan bo sung tai lieu MCP nay, AI da dung cac nhom tool/context sau:

- `list_mcp_resources` va `list_mcp_resource_templates` de kiem tra MCP resource/template hien co. Ket qua: chua co resource/template nao.
- PowerShell `Get-ChildItem` de xem cau truc `agents/back-end-agent`.
- PowerShell `Get-Content` de doc `Skill.md`, `Rules.md`, `Workflows.md`, `AGENT.md`, `Plan.md`, `docs/Check.md` va `docs/AI_Agent_Training_Report.md`.
- `rg` de tim keyword MCP/agent trong `agents`, `docs` va `README.md`.
- `apply_patch` de tao `MCP.md` va cap nhat cac file lien quan.
- `Select-String` de doc lai dung cac doan vua them.
- `git status --short` de xem trang thai file trong workspace.

Lan thay doi nay chi sua tai lieu nen khong can chay PHPUnit. Verify chinh la doc lai file, kiem tra Markdown va dam bao `MCP.md` duoc lien ket trong agent docs, checklist va report.
