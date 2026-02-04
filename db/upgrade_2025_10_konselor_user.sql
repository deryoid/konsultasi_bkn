-- Mapping akun user ke entitas konselor
CREATE TABLE IF NOT EXISTS `konselor_user` (
  `id_konselor` int NOT NULL,
  `id_user` int NOT NULL,
  PRIMARY KEY (`id_konselor`),
  UNIQUE KEY `uniq_konselor_user_user` (`id_user`),
  KEY `idx_konselor_user_konselor` (`id_konselor`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Opsional: tambahkan foreign key jika diperlukan (pastikan engine/tipe sesuai)
-- ALTER TABLE `konselor_user`
--   ADD CONSTRAINT `fk_ku_konselor` FOREIGN KEY (`id_konselor`) REFERENCES `konselor` (`id_konselor`) ON DELETE CASCADE,
--   ADD CONSTRAINT `fk_ku_user` FOREIGN KEY (`id_user`) REFERENCES `user` (`id_user`) ON DELETE CASCADE;

-- Contoh mapping (hapus setelah disesuaikan):
-- INSERT INTO konselor_user (id_konselor, id_user) VALUES (1, 2);
