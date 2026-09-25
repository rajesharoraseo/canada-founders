# CanadaFounders

This repository contains the source for the CanadaFounders WordPress theme and demo-import plugin.

## Structure

- `canada founder/canadafounders/wp-content/themes/canadafounders/` — active theme source
- `canada founder/canadafounders/wp-content/plugins/canadafounders-demo-import/` — active demo-import plugin source
- `canada founder/canadafounders/scripts/build-wordpress-zips.ps1` — builds installable zip packages
- `canada founder/canadafounders/dist/` — generated ZIP packages (ignored by Git)

## Notes

- Generated ZIP archives and verification copies are intentionally excluded from Git tracking via `.gitignore`.
- The project keeps a single canonical source tree and does not rely on duplicated packaged copies in the repository.
