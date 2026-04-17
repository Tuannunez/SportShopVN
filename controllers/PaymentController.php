<?php
require_once 'OrderModel.php';

class PaymentController {
    private $orderModel;

    public function __construct($pdo_connection) {
        $this->orderModel = new OrderModel($pdo_connection);
    }

    public function processCheckout() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!isset($_SESSION['user'])) {
                header('Location: login.php');
                exit;
            }

            $name    = $_POST['customer_name'] ?? '';
            $phone   = $_POST['customer_phone'] ?? '';
            $address = $_POST['shipping_address'] ?? '';
            $note    = $_POST['order_note'] ?? '';
            $paymentMethod = $_POST['payment_method'] ?? 'cod';
            
            $userId  = $_SESSION['user']['id']; 

            $cart = $_SESSION['cart'] ?? [];
            if (empty($cart)) {
                echo "Giỏ hàng của bạn đang trống!";
                return;
            }

            $totalAmount = 0;
            foreach ($cart as $item) {
                $totalAmount += $item['price'] * $item['quantity'];
            }
            
            $shippingFee = 30000;
            $finalTotal = $totalAmount + $shippingFee;

            $orderId = $this->orderModel->createFullOrder(
                $userId, 
                $name, 
                $phone, 
                $address, 
                $note, 
                $paymentMethod, 
                $finalTotal, 
                $cart
            );

            if ($orderId) {
                unset($_SESSION['cart']);
                
                header("Location: success.php?order_id=" . $orderId);
                exit;
            } else {
                echo "Có lỗi xảy ra trong quá trình lưu đơn hàng. Vui lòng thử lại!";
            }
        }
    }
}