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
        DB::unprepared("CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_log_login_attempt`(
  IN p_email VARCHAR(200),
  IN p_ip_address VARCHAR(45),
  IN p_success BOOLEAN
)
BEGIN
  INSERT INTO login_attempts (email, ip_address, success)
  VALUES (p_email, p_ip_address, p_success);
  
  DELETE FROM login_attempts
  WHERE attempt_time < DATE_SUB(NOW(), INTERVAL 24 HOUR);
END");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared("DROP PROCEDURE IF EXISTS sp_log_login_attempt");
    }
};
