Local demo fallback for certificate verification
=============================================

If you want the public verification page to show a sample valid certificate while your database is empty (local development only), enable the demo fallback.

How it works
- The feature is implemented in `CertificateVerificationController::index()` and `scan()`.
- If a certificate with the requested number does not exist and your app is running in the `local` environment and the `CERT_DEMO_ENABLED` flag is set, the controller will create an in-memory demo certificate for rendering. No records are persisted to the database.

Enable demo (local only)
1. Open your `.env` and make sure you are running in local env:

   APP_ENV=local

2. Add the flag to enable demo behavior:

   CERT_DEMO_ENABLED=true

3. The verification page will now return a non-persistent demo certificate result for any certificate number (useful while the DB is empty).

Example test URLs (after enabling flag)
- /verifikasi-sertifikat?q=CERT-ALG-2025-001234
- /verifikasi-sertifikat/scan/CERT-ALG-2025-001234

Important
- Demo fallback is meant for local development and testing only. Do NOT enable on production.
