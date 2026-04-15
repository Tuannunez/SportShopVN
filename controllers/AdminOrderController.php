<?php
require_once __DIR__ . '/../models/OrderModel.php';
require_once __DIR__ . '/../configs/env.php';

class AdminOrderController {
    private $orderModel;

    public function __construct($pdo_connection) {
        $this->orderModel = new OrderModel($pdo_connection);
    }

    public function index() {
        $orders = $this->orderModel->getAllOrders();
        require PATH_VIEW . 'admin/admin_orders.php';
    }
}
