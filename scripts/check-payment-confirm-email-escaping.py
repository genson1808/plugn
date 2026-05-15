#!/usr/bin/env python3
from pathlib import Path
import re


ROOT = Path(__file__).resolve().parents[1]
TEMPLATE = ROOT / "common/mail/payment-confirm-html.php"
source = TEMPLATE.read_text()

expected_fragments = [
    "$customerName = Html::encode($order->customer_name);",
    "$customerPhoneNumber = Html::encode($order->customer_phone_number);",
    "$orderUuid = Html::encode($order->order_uuid);",
    "$trackingHref = preg_match('/^https?:\\/\\//i', $rawRestaurantDomain)",
    "$paymentMethodName = Html::encode($order->getPaymentMethodName());",
    "$paymentStatus = $order->payment ? Html::encode($order->payment->payment_current_status) : '';",
    "$paymentGatewayOrderId = $order->payment ? Html::encode($order->payment->payment_gateway_order_id) : '';",
    "$paymentGatewayTransactionId = $order->payment ? Html::encode($order->payment->payment_gateway_transaction_id) : '';",
    "$deliveryAddress = Html::encode(implode(', ', array_filter($deliveryAddressParts, static function ($part) {",
    "<?= $customerName ?>",
    "<?= $customerPhoneNumber ?>",
    "href=\"<?= $trackingHref ?>\"",
    "Order #<?= $orderUuid ?>",
    "<?= Html::encode($orderItem->qty) ?>x",
    "<?= Html::encode($orderItem->item_name . ' ' . $orderItem->item_name_ar) ?>",
    "<?= Html::encode($extraOption->extra_option_name . ' ' . $extraOption->extra_option_name_ar) ?>",
    "<?= $deliveryAddress ?>",
    "<?= $paymentMethodName ?>",
    "Result: <?= $paymentStatus ?>",
    "Ref: <?= $paymentGatewayOrderId ?>",
    "Charge: <?= $paymentGatewayTransactionId ?>",
]

for fragment in expected_fragments:
    if fragment not in source:
        raise SystemExit(f"missing expected escaped fragment: {fragment}")

raw_patterns = [
    r"<\?=\s*\$order->customer_name\s*\?>",
    r"<\?=\s*\$order->customer_phone_number\s*\?>",
    r"<\?=\s*\$order->order_uuid\s*\?>",
    r"href=\"<\?=\s*\$order->restaurant->restaurant_domain\s*\.",
    r"<\?=\s*\$orderItem->qty\s*\?>",
    r"<\?=\s*\$orderItem->item_name\s*\.",
    r"<\?=\s*\$extraOption->extra_option_name\s*\.",
    r"echo\s+\$order->(block|street|avenue|floor|apartment|office|house_number|area_name|address_1|address_2|postalcode|city)",
    r"<\?php\s+echo\s+\$order->getPaymentMethodName\(\);\s*\?>",
    r"<\?=\s*\$order->payment->payment_current_status\s*\?>",
    r"<\?=\s*\$order->payment->payment_gateway_order_id\s*\?>",
    r"<\?=\s*\$order->payment->payment_gateway_transaction_id\s*\?>",
]

for pattern in raw_patterns:
    if re.search(pattern, source):
        raise SystemExit(f"found raw payment confirmation email output: {pattern}")

print("payment confirmation email escaping checks passed")
