<?php
class OrderModel {
    private $db;

    public function __construct($pdo_connection) {
        $this->db = $pdo_connection;
    }
    public function createFullOrder($userId, $name, $phone, $address, $note, $paymentMethod, $totalAmount, $cartItems) {
        try {
            $this->db->beginTransaction();

            $sqlOrder = "INSERT INTO orders (user_id, customer_name, customer_phone, shipping_address, order_note, payment_method, total_amount, status, created_at) 
                         VALUES (:user_id, :name, :phone, :address, :note, :payment_method, :total, 'pending', NOW())";
            
            $stmtOrder = $this->db->prepare($sqlOrder);
            $stmtOrder->execute([
                ':user_id'        => $userId,
                ':name'           => $name,
                ':phone'          => $phone,
                ':address'        => $address,
                ':note'           => $note,
                ':payment_method' => $paymentMethod,
                ':total'          => $totalAmount
            ]);

            $orderId = $this->db->lastInsertId();

            $sqlDetail = "INSERT INTO order_details (order_id, product_id, quantity, price) 
                          VALUES (:order_id, :product_id, :quantity, :price)";
            $stmtDetail = $this->db->prepare($sqlDetail);

            foreach ($cartItems as $item) {
                $stmtDetail->execute([
                    ':order_id'   => $orderId,
                    ':product_id' => $item['id'], 
                    ':quantity'   => $item['quantity'],
                    ':price'      => $item['price']
                ]);
            }

            $this->db->commit();
            
            return $orderId; 

        } catch (PDOException $e) {
            $this->db->rollBack();
            return false;
        }
    }

    public function getAllOrders() {
        $sql = "SELECT * FROM orders ORDER BY created_at DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function getOrdersByUserId($userId) {
        $sql = "SELECT * FROM orders WHERE user_id = :user_id ORDER BY created_at DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':user_id' => $userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getOrderDetails($orderId) {
        $sql = "SELECT * FROM order_details WHERE order_id = :order_id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':order_id' => $orderId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>