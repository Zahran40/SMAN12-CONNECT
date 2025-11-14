<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::unprepared("CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_check_login_attempts`(
  IN p_email VARCHAR(200),
  IN p_ip_address VARCHAR(45),
  OUT p_is_locked BOOLEAN
)
BEGIN
  DECLARE attempt_count INT;
  
  SELECT COUNT(*) INTO attempt_count
  FROM login_attempts
  WHERE email = p_email
    AND ip_address = p_ip_address
    AND success = 0
    AND attempt_time > DATE_SUB(NOW(), INTERVAL 15 MINUTE);
  
  IF attempt_count >= 5 THEN
    SET p_is_locked = TRUE;
  ELSE
    SET p_is_locked = FALSE;
  END IF;
END");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared("DROP PROCEDURE IF EXISTS sp_check_login_attempts");
    }
};
