# Create Skills Guide

## 1. Muc dich

File nay mo ta cach tao them skill file cho AI Agent trong do an. Skill file la tai lieu ngan gon nhung ro, giup AI nho cach lam mot nhom viec lap lai.

## 2. Cau truc skill tot

Mot skill tot nen co:

- Purpose: skill dung de lam gi.
- When to use: khi nao dung.
- Inputs: can doc file/du lieu nao.
- Steps: quy trinh tung buoc.
- Rules: dieu cam/ranh gioi.
- Output: ket qua mong doi.
- Verification: cach check.
- Example prompt: mau prompt.

## 3. Mau skill file

````md
# [Ten] Skill

## Purpose

[Mo ta ngan]

## When to use

- [Tinh huong 1]
- [Tinh huong 2]

## Steps

1. [Buoc 1]
2. [Buoc 2]
3. [Buoc 3]

## Rules

- [Rule 1]
- [Rule 2]

## Verification

- [Cach check]

## Example prompt

```text
[Prompt mau]
```
````

## 4. Skill nen tao them neu project mo rong

- `payment-SKILL.md`: MoMo/VNPAY/payments.
- `ai-recommendation-SKILL.md`: AI fallback, prompt, analytics.
- `database-migration-SKILL.md`: migration/rollback/schema.
- `admin-crud-SKILL.md`: CRUD admin pattern.
- `storefront-seo-SKILL.md`: SEO/meta/product detail.
- `deployment-SKILL.md`: deploy Laravel production.

## 5. Quy tac cap nhat skill

- Neu lap lai bug 2 lan, them rule vao skill.
- Neu them module moi, them workflow vao skill.
- Neu route contract doi, cap nhat API skill.
- Neu test fail vi thieu acceptance, cap nhat QA skill.
