# AI Agent Training Report

## 1. Tong quan

Do an nay duoc to chuc theo cach "AI Agent co kiem soat". Thay vi chi ghi prompt roi de AI sinh code tu do, project chia thanh cac agent theo vai tro:

- Back-end Agent: phu trach Laravel controller, request validation, service, model, migration, transaction, payment, AI data va microservice boundary.
- Front-end Agent: phu trach Blade view, layout admin/user, UI state, animation, AJAX, responsive va trai nghiem thao tac.
- Tester Agent: phu trach test case, checklist, regression, du lieu test va bang chung cho bao cao.

Cach chia nay giup bai thuyet trinh ro hon: moi agent co rules, skills, workflows va boundaries rieng, giong cach mot nhom phat trien phan mem that phan cong cong viec.

## 2. Cau truc thu muc da bo sung

```text
agents/
  back-end-agent/
    PRD/
      Database_Schema.md
      PRD.md
      UserStories.md
    AGENT.md
    MCP.md
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

## 3. Ly do can bo file training/skill

Trong thuc te, AI sinh code tot hon khi no co bo nho lam viec ro rang. Bo file nay dong vai tro nhu "instruction pack" cho tung agent:

- `AGENT.md` tra loi cau hoi agent la ai, phu trach gi, giao tiep voi ai.
- `Rules.md` la ranh gioi bat buoc, giup AI khong sua lung tung module khac.
- `Skill.md` mo ta ky nang, pattern code, cach xu ly tinh huong.
- `Workflows.md` ghi quy trinh tu nhan task den verify.
- `MCP.md` mo ta AI dung nguon context/tool nao, khi nao dung search/patch/test/log va gioi han an toan.
- `PRD.md` giu muc tieu san pham va scope.
- `UserStories.md` bien yeu cau thanh luong nguoi dung.
- `Database_Schema.md` giu model du lieu de AI khong tao bang/cot sai.
- `API_Endpoint.md` giu contract route giua frontend/backend/tester.
- `Check.md` la checklist de chot truoc khi demo.

## 4. Mapping voi 7 stage cua do an

| Stage | Muc tieu | Agent chinh | Tai lieu lien quan |
| --- | --- | --- | --- |
| 1 | Laravel foundation, admin auth, product core | Backend + Tester | Backend Rules, PRD, API Endpoint |
| 2 | Product attribute, variant, gallery | Backend + Frontend + Tester | Database Schema, User Stories |
| 3 | Storefront public catalog/search/detail | Frontend + Backend | Frontend Prompt Design, API Endpoint |
| 4 | User account/profile/password/social | Backend + Frontend + Tester | User Stories, Check |
| 5 | Cart, checkout, order, payment | Backend + Frontend + Tester | Backend Skill, QA Skill |
| 6 | Tracking, analytics, AI suggestion | Backend + Frontend + Tester | AI workflow, Check |
| 7 | Microservices boundary, inventory, pricing, outbox | Backend + Tester | Backend Workflow, Database Schema |

## 5. Mo hinh lam viec cua AI Agent

### Step 1: Doc context

Agent doc cac file:

- PRD de hieu muc tieu.
- Rules de biet gioi han.
- API Endpoint de biet route/contract.
- Database Schema de biet bang/cot.
- Check de biet verify.
- MCP de biet nguon context, tool duoc dung va gioi han an toan.

### Step 2: Lap plan nho

Agent chia task thanh cac buoc:

1. Xac dinh file se sua.
2. Xac dinh data contract.
3. Xac dinh validation/error/loading.
4. Viet code hoac tai lieu.
5. Chay test/check.
6. Cap nhat bao cao neu task lam thay doi behavior.

### Step 3: Thuc hien trong boundary

Backend Agent khong tu y sua view neu task khong yeu cau.
Frontend Agent khong tu y sua controller/migration.
Tester Agent khong sua logic production neu chua co bug ro.

### Step 4: Validate

Validation khong chi la chay test. Con phai check:

- route co middleware dung
- response dung format
- UI co loading/error
- data khong mat toan ven
- tai lieu da cap nhat

### Step 5: Bao cao ket qua

Moi task ket thuc can ghi:

- Da sua file nao.
- Luong nao duoc anh huong.
- Lenh test nao da chay.
- Rui ro con lai la gi.

## 6. Noi dung dua vao bao cao thuyet trinh

Co the them doan sau vao bao cao:

```text
Ben canh viec phat trien code, nhom xay dung them bo tai lieu AI Agent gom Backend Agent, Frontend Agent va Tester Agent. Moi agent co file AGENT, Rules, Skill va Workflows rieng. Muc dich la bien AI thanh cong cu lam viec co quy trinh, co pham vi va co kiem soat, thay vi chi prompt tu do.

Backend Agent dam bao controller, service, validation, transaction, payment, AI analytics va microservice boundary dung chuan Laravel. Frontend Agent dam bao giao dien Blade co loading, error, empty state, responsive va animation hop ly. Tester Agent dam bao moi chang co test, checklist va bang chung de bao ve do an.

Bo tai lieu nay giup quy trinh vibe coding tro nen co the giai thich duoc trong thuyet trinh: con nguoi chia bai toan, dat rule, kiem tra ket qua; AI ho tro sinh code, de xuat pattern va tang toc trien khai.

Ngoai rules/skills/workflows, nhom bo sung `MCP.md` de giai thich lop ket noi context/tool cua AI. Trong workspace hien tai chua co MCP resource co dinh, nen AI dung local tools nhu doc file, `rg`, patch, Laravel test va log theo gioi han an toan. Neu sau nay gan Filesystem/Git/Database/Browser MCP, agent van phai tuan theo cung mot quy trinh doc context, sua trong pham vi, verify va bao cao bang chung.
```

## 7. Diem manh khi trinh bay

- Co cau truc agent ro rang, khong phai viet code ngau nhien.
- Co PRD/User Stories/Rules/Skills/Workflow nhu quy trinh san pham that.
- Co mapping giua prompt, code, test va bao cao.
- Co 7 stage tang dan do phuc tap.
- Co AI feature that trong project: recommend, tracking, analytics dashboard.
- Co microservice boundary de the hien huong mo rong.

## 8. Rui ro va cach kiem soat

| Rui ro | Cach kiem soat |
| --- | --- |
| AI sua nham module | Dung Rules va boundary tung agent |
| Route/frontend khong khop | Cap nhat API_Endpoint.md |
| Schema bi lech voi migration | Cap nhat Database_Schema.md |
| UI dep nhung kho dung | Dung Frontend_Prompt_Design.md |
| Test thieu | Dung tester-agent va Check.md |
| Bao cao noi chung chung | Dua bang stage, file, command test, route, screenshot |
| AI dung tool khong kiem soat | Dung MCP.md de gioi han context, command, database va log |

## 9. Ket luan

Bo AI Agent Training giup do an co them lop quy trinh. Khi thuyet trinh, co the noi rang AI khong thay con nguoi lap trinh, ma duoc dat vao he thong vai tro, rule, skill va workflow. Nhom van giu quyen kiem soat: quyet dinh scope, review code, chay test va tong hop thanh san pham hoan chinh.
