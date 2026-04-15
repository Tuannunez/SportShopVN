<?php
class OrderModel {
    private $db;

    public function __construct($pdo_connection) {
        $this->db = $pdo_connection;
    }

    public function createOrder($name, $phone, $address, $total) {
        $sql = "INSERT INTO orders (customer_name, customer_phone, shipping_address, total_amount) 
                VALUES (:name, :phone, :address, :total)";
        $stmt = $this->db->prepare($sql);
        
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':phone', $phone);
        $stmt->bindParam(':address', $address);
        $stmt->bindParam(':total', $total);
        
        if ($stmt->execute()) {
            return $this->db->lastInsertId(); 
        }
        return false;
    }

    public function getAllOrders() {
        $sql = "SELECT * FROM orders ORDER BY created_at DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>