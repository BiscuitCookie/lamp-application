<?php

require_once PROJECT_ROOT_PATH . "/Model/Database.php";

class UserModel extends Database
{
  public function getUsers($ids)
  {
    $ids = implode(',', $ids);
    return $this->select("
      SELECT concat_ws(' ', u.first_name, u.second_name) AS full_name, p.title, p.price
      FROM users u
      LEFT JOIN user_order uo
      ON u.id = uo.user_id
      LEFT JOIN products p
      ON uo.product_id = p.id
      WHERE u.id IN ({$ids})
      ORDER BY full_name, p.title DESC, p.price DESC;");
  }
}
