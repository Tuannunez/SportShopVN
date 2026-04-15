<?php
require_once 'OrderModel.php';

class PaymentController {
    private $orderModel;

    public function __construct($pdo_connection) {
        $this->orderModel = new OrderModel($pdo_connection);
    }

    public function processCheckout() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = $_POST['customer_name'] ?? '';
            $phone = $_POST['customer_phone'] ?? '';
            $address = $_POST['shipping_address'] ?? '';
            
            $totalAmount = 500000; 

            $orderId = $this->orderModel->createOrder($name, $phone, $address, $totalAmount);

            if ($orderId) {
                
                header("Location: success.php?order_id=" . $orderId);
                exit;
            } else {
                echo "Có lỗi xảy ra trong quá trình đặt hàng!";
            }
        }
    }
}
?>