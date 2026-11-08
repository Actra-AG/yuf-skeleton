# Changelog

Changes of the yuf skeleton, newest first. A new project copies the skeleton once and is not upgraded from it: follow
yuf's [UPGRADE.md](https://github.com/Actra-AG/yuf/blob/main/UPGRADE.md) in existing projects instead. Older
versions: see the Git tags.

## v1.18.6 (2026-11-08)

- `setup.sh` refuses to run inside a DDEV project.
- Requires `actra/yuf` ^8.13 (login page on another host with a server-side hand-over, shared login, test helpers
  for views). `docs/views.md` shows `LoginTarget::remote(url:, loginHandOver:, handOverPath:)`.
- Requires `actra/coding-standard` ^2.2 (no named variadic arguments of PHP built-ins).

## v1.18.5 (2026-11-08)

- Requires `actra/yuf` ^8.11 (typed login target `LoginTarget`, login page on another host, HTML body and attachments
  of received mails). `docs/views.md` shows `withLoginTarget()` instead of `withLoginPath()`.
- Requires `actra/coding-standard` ^2.1 (`UPDATE` and `DELETE` only by primary or unique key).
- `AGENTS.md` of new projects is shorter: no rules that the coding standard, `composer.json` or `README.md` already
  state; new `TODO:` for the frontend review.

## v1.18.4 (2026-10-31)

- Requires `actra/yuf` ^8.7.1 (routes with the language of the request, `SentMailListener`, new `App` shortcuts) and
  `actra/coding-standard` ^2.0 (named arguments in the order of the parameters).
- `.php-cs-fixer.dist.php` declares the type of the header helper, as the template of the coding standard.

## v1.18.3 (2026-10-29)

- GitHub release for every version tag, with the section of this file as release notes. New projects are unchanged.

## v1.18.2 (2026-10-29)

- Requires `actra/yuf` ^8.4.0 (new IMAP mailbox reader `actra\yuf\mailbox`, no changes in the skeleton).
