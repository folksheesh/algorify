ALTER TABLE transaksi DROP CONSTRAINT IF EXISTS transaksi_metode_pembayaran_check;
ALTER TABLE transaksi ADD CONSTRAINT transaksi_metode_pembayaran_check CHECK (metode_pembayaran IN ('bank_transfer', 'e_wallet', 'credit_card', 'cash', 'midtrans'));
