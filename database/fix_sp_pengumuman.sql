-- Fix stored procedure sp_get_pengumuman_aktif
DROP PROCEDURE IF EXISTS sp_get_pengumuman_aktif;

DELIMITER $$

CREATE PROCEDURE sp_get_pengumuman_aktif(IN target_role VARCHAR(20))
BEGIN
  SELECT p.id_pengumuman,
         p.judul,
         p.isi_pengumuman,
         p.tgl_publikasi,
         p.hari,
         u.name AS author_name,
         u.role AS author_role
  FROM pengumuman p
  JOIN users u ON p.author_id = u.id
  WHERE (target_role IS NULL OR p.target_role = target_role OR p.target_role = 'Semua')
  ORDER BY p.tgl_publikasi DESC;
END$$

DELIMITER ;
