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
        DB::unprepared("CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_check_user_permission`(
  IN p_user_id BIGINT,
  IN p_module VARCHAR(50),
  IN p_action VARCHAR(20),
  OUT p_has_permission BOOLEAN
)
BEGIN
  DECLARE user_role VARCHAR(20);
  DECLARE permission_value TINYINT;
  
  SELECT role INTO user_role
  FROM users
  WHERE id = p_user_id;
  
  IF p_action = 'create' THEN
    SELECT can_create INTO permission_value
    FROM role_permissions
    WHERE role = user_role AND module = p_module;
  ELSEIF p_action = 'read' THEN
    SELECT can_read INTO permission_value
    FROM role_permissions
    WHERE role = user_role AND module = p_module;
  ELSEIF p_action = 'update' THEN
    SELECT can_update INTO permission_value
    FROM role_permissions
    WHERE role = user_role AND module = p_module;
  ELSEIF p_action = 'delete' THEN
    SELECT can_delete INTO permission_value
    FROM role_permissions
    WHERE role = user_role AND module = p_module;
  END IF;
  
  SET p_has_permission = IFNULL(permission_value, 0);
END");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared("DROP PROCEDURE IF EXISTS sp_check_user_permission");
    }
};
