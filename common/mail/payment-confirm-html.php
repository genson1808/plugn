<?php

use yii\helpers\Html;
use common\models\Order;
use common\models\Voucher;
use common\models\BankDiscount;

/* @var $this yii\web\View */
/* @var $order common\models\Order */

$customerName = Html::encode($order->customer_name);
$customerPhoneNumber = Html::encode($order->customer_phone_number);
$orderUuid = Html::encode($order->order_uuid);
$rawRestaurantDomain = trim((string) $order->restaurant->restaurant_domain);
$trackingHref = preg_match('/^https?:\/\//i', $rawRestaurantDomain)
    ? Html::encode(rtrim($rawRestaurantDomain, '/') . '/order-status/' . rawurlencode((string) $order->order_uuid))
    : null;
$paymentMethodName = Html::encode($order->getPaymentMethodName());
$paymentStatus = $order->payment ? Html::encode($order->payment->payment_current_status) : '';
$paymentGatewayOrderId = $order->payment ? Html::encode($order->payment->payment_gateway_order_id) : '';
$paymentGatewayTransactionId = $order->payment ? Html::encode($order->payment->payment_gateway_transaction_id) : '';
$deliveryAddressParts = [];

if ($order->order_mode == Order::ORDER_MODE_DELIVERY) {
    if ($order->area_id) {
        $deliveryAddressParts[] = 'Block ' . $order->block;
        $deliveryAddressParts[] = 'Street ' . $order->street;
        if ($order->avenue != null) {
            $deliveryAddressParts[] = 'Avenue ' . $order->avenue;
        }
        if (strtolower($order->unit_type) != Order::UNIT_TYPE_HOUSE && $order->floor != null) {
            $deliveryAddressParts[] = 'Floor ' . $order->floor;
        }
        if (strtolower($order->unit_type) == Order::UNIT_TYPE_APARTMENT && $order->apartment != null) {
            $deliveryAddressParts[] = 'Apartment ' . $order->apartment;
        }
        if (strtolower($order->unit_type) == Order::UNIT_TYPE_OFFICE && $order->office != null) {
            $deliveryAddressParts[] = 'Office No. ' . $order->office;
        }
        $deliveryAddressParts[] = strtolower($order->unit_type) == Order::UNIT_TYPE_HOUSE
            ? 'House No. ' . $order->house_number
            : 'Building ' . $order->house_number;
        $deliveryAddressParts[] = $order->area_name;
        $deliveryAddressParts[] = $order->area->city->city_name;
        $deliveryAddressParts[] = $order->area->country->country_name;
    } else if ($order->shipping_country_id) {
        $deliveryAddressParts[] = $order->address_1;
        $deliveryAddressParts[] = $order->address_2;
        $deliveryAddressParts[] = $order->postalcode;
        $deliveryAddressParts[] = $order->city;
        $deliveryAddressParts[] = $order->country->country_name;
    }
}

$deliveryAddress = Html::encode(implode(', ', array_filter($deliveryAddressParts, static function ($part) {
    return $part !== null && $part !== '';
})));
?>


<!doctype html>
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml" xmlns:o="urn:schemas-microsoft-com:office:office">

    <head>
        <title>Payment Confirmation</title>
        <!--[if !mso]><!-- -->
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
            <!--<![endif]-->
            <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
                <meta name="viewport" content="width=device-width,initial-scale=1">
                    <style type="text/css">
                        #outlook a {
                            padding: 0;
                        }

                        body {
                            margin: 0;
                            padding: 0;
                            -webkit-text-size-adjust: 100%;
                            -ms-text-size-adjust: 100%;
                        }

                        table,
                        td {
                            border-collapse: collapse;
                            mso-table-lspace: 0pt;
                            mso-table-rspace: 0pt;
                        }

                        img {
                            border: 0;
                            height: auto;
                            line-height: 100%;
                            outline: none;
                            text-decoration: none;
                            -ms-interpolation-mode: bicubic;
                        }

                        p {
                            display: block;
                            margin: 13px 0;
                        }
                    </style>

                    <style type="text/css">
                        @media only screen and (min-width:480px) {
                            .mj-column-per-100 {
                                width: 100% !important;
                                max-width: 100%;
                            }
                            .mj-column-per-50 {
                                width: 50% !important;
                                max-width: 50%;
                            }
                        }
                    </style>
                    <style type="text/css">
                        @media only screen and (max-width:480px) {
                            table.mj-full-width-mobile {
                                width: 100% !important;
                            }
                            td.mj-full-width-mobile {
                                width: auto !important;
                            }
                        }
                    </style>
                    </head>

                    <body style="background-color:#ffffff;">
                        <div style="background-color:#ffffff;">
                            <!--[if mso | IE]><table align="center" border="0" cellpadding="0" cellspacing="0" class="" style="width:600px;" width="600" ><tr><td style="line-height:0px;font-size:0px;mso-line-height-rule:exactly;"><![endif]-->
                            <div style="margin:0px auto;max-width:600px;">
                                <table align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="width:100%;">
                                    <tbody>
                                        <tr>
                                            <td style="direction:ltr;font-size:0px;padding:0px;padding-bottom:10px;padding-top:10px;text-align:center;">
                                                <!--[if mso | IE]><table role="presentation" border="0" cellpadding="0" cellspacing="0"><tr><td class="" style="vertical-align:top;width:600px;" ><![endif]-->
                                                <div class="mj-column-per-100 mj-outlook-group-fix" style="font-size:0px;text-align:left;direction:ltr;display:inline-block;vertical-align:top;width:100%;">
                                                    <table border="0" cellpadding="0" cellspacing="0" role="presentation" style="vertical-align:top;" width="100%">
                                                        <tr>
                                                            <td align="center" style="font-size:0px;padding:10px 25px;word-break:break-word;">
                                                                <table border="0" cellpadding="0" cellspacing="0" role="presentation" style="border-collapse:collapse;border-spacing:0px;">
                                                                    <tbody>
                                                                        <tr>
                                                                            <td style="width:100px;"><img height="auto" src='<?= $order->restaurant->getRestaurantLogoUrl() ?>' style="border:0;display:block;outline:none;text-decoration:none;height:auto;width:100%;font-size:13px;" width="100"></td>
                                                                        </tr>
                                                                    </tbody>
                                                                </table>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </div>
                                                <!--[if mso | IE]></td></tr></table><![endif]-->
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <!--[if mso | IE]></td></tr></table><table align="center" border="0" cellpadding="0" cellspacing="0" class="" style="width:600px;" width="600" ><tr><td style="line-height:0px;font-size:0px;mso-line-height-rule:exactly;"><![endif]-->
                            <div style="margin:0px auto;max-width:600px;">
                                <table align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="width:100%;">
                                    <tbody>
                                        <tr>
                                            <td style="border:1px solid #d8e2e7;direction:ltr;font-size:0px;padding:5px;text-align:center;">
                                                <!--[if mso | IE]><table role="presentation" border="0" cellpadding="0" cellspacing="0"><tr><td class="" width="600px" ><table align="center" border="0" cellpadding="0" cellspacing="0" class="" style="width:588px;" width="588" ><tr><td style="line-height:0px;font-size:0px;mso-line-height-rule:exactly;"><![endif]-->
                                                <div style="margin:0px auto;max-width:588px;">
                                                    <table align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="width:100%;">
                                                        <tbody>
                                                            <tr>
                                                                <td style="direction:ltr;font-size:0px;padding:0px;text-align:center;">
                                                                    <!--[if mso | IE]><table role="presentation" border="0" cellpadding="0" cellspacing="0"><tr><td class="" style="vertical-align:top;width:588px;" ><![endif]-->
                                                                    <div class="mj-column-per-100 mj-outlook-group-fix" style="font-size:0px;text-align:left;direction:ltr;display:inline-block;vertical-align:top;width:100%;">
                                                                        <table border="0" cellpadding="0" cellspacing="0" role="presentation" style="vertical-align:top;" width="100%">
                                                                            <tr>
                                                                                <td align="center" style="font-size:0px;padding:10px 25px;padding-top:25px;padding-bottom:10px;word-break:break-word;">
                                                                                    <div style="font-family:Proxima Nova, Arial, Arial, Helvetica, sans-serif;font-size:14px;line-height:24px;text-align:center;color:#000000;">Hello
                                                                                        <?= $customerName ?>,</div>
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td align="center" style="font-size:0px;padding:10px 25px;padding-top:0px;padding-bottom:6px;word-break:break-word;">
                                                                                    <div style="font-family:Proxima Nova, Arial, Arial, Helvetica, sans-serif;font-size:21px;font-weight:bold;line-height:24px;text-align:center;color:#000000;">We've received your order</div>
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td align="center" vertical-align="middle" style="font-size:0px;padding:10px 25px;padding-top:8px;padding-right:5px;padding-bottom:23px;padding-left:0px;word-break:break-word;">
                                                                                    <table border="0" cellpadding="0" cellspacing="0" role="presentation" style="border-collapse:separate;line-height:100%;">
                                                                                        <tr>
                                                                                            <td align="center" bgcolor="#ffffff" role="presentation" style="border:1px solid black;border-radius:5px;cursor:auto;mso-padding-alt:10px 25px;background:#ffffff;" valign="middle"><?php if ($trackingHref): ?><a href="<?= $trackingHref ?>" style="display:inline-block;background:#ffffff;color:#000000;font-family:Proxima Nova, Arial, Arial, Helvetica, sans-serif;font-size:15px;font-weight:bold;line-height:120%;margin:0;text-decoration:none;text-transform:none;padding:10px 25px;mso-padding-alt:0px;border-radius:5px;" target="_blank">Track your order</a><?php else: ?><span style="display:inline-block;background:#ffffff;color:#000000;font-family:Proxima Nova, Arial, Arial, Helvetica, sans-serif;font-size:15px;font-weight:bold;line-height:120%;margin:0;text-decoration:none;text-transform:none;padding:10px 25px;mso-padding-alt:0px;border-radius:5px;">Track your order</span><?php endif; ?></td>
                                                                                        </tr>
                                                                                    </table>
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td style="font-size:0px;padding:10px 25px;word-break:break-word;">
                                                                                    <p style="border-top:solid 1px #d8e2e7;font-size:1;margin:0px auto;width:100%;"></p>
                                                                                    <!--[if mso | IE]><table align="center" border="0" cellpadding="0" cellspacing="0" style="border-top:solid 1px #d8e2e7;font-size:1;margin:0px auto;width:538px;" role="presentation" width="538px" ><tr><td style="height:0;line-height:0;"> &nbsp;
                                        </td></tr></table><![endif]-->
                                                                                </td>
                                                                            </tr>
                                                                        </table>
                                                                    </div>
                                                                    <!--[if mso | IE]></td></tr></table><![endif]-->
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                                <!--[if mso | IE]></td></tr></table></td></tr><tr><td class="" width="600px" ><table align="center" border="0" cellpadding="0" cellspacing="0" class="" style="width:588px;" width="588" ><tr><td style="line-height:0px;font-size:0px;mso-line-height-rule:exactly;"><![endif]-->
                                                <div style="margin:0px auto;max-width:588px;">
                                                    <table align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="width:100%;">
                                                        <tbody>
                                                            <tr>
                                                                <td style="direction:ltr;font-size:0px;padding:0px;text-align:center;">
                                                                    <!--[if mso | IE]><table role="presentation" border="0" cellpadding="0" cellspacing="0"><tr><td class="" style="vertical-align:top;width:588px;" ><![endif]-->
                                                                    <div class="mj-column-per-100 mj-outlook-group-fix" style="font-size:0px;text-align:left;direction:ltr;display:inline-block;vertical-align:top;width:100%;">
                                                                        <table border="0" cellpadding="0" cellspacing="0" role="presentation" style="vertical-align:top;" width="100%">
                                                                            <tr>
                                                                                <td align="center" style="font-size:0px;padding:10px 25px;padding-top:15px;padding-bottom:6px;word-break:break-word;">
                                                                                    <div style="font-family:Proxima Nova, Arial, Arial, Helvetica, sans-serif;font-size:18px;line-height:24px;text-align:center;color:#000000;">Order #<?= $orderUuid ?>
                                                                                    </div>
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td align="left" style="font-size:0px;padding:10px 25px;word-break:break-word;">
                                                                                    <table cellpadding="0" cellspacing="0" width="100%" border="0" style="color:#000000;font-family:Proxima Nova, Arial, Arial, Helvetica, sans-serif;font-size:15px;line-height:22px;table-layout:auto;width:100%;border:none;">
                                                                                        <!-- #item -->

                                                                                        <?php
                                                                                        foreach ($order->getOrderItems()->all() as $orderItem) {
                                                                                            ?>
                                                                                            <tr>
                                                                                                <td style="padding: 0 15px; padding-top:10px; width: 34px; vertical-align:top;">
                                                                                                    <?= Html::encode($orderItem->qty) ?>x
                                                                                                </td>
                                                                                                <td style="width: 70%; padding: 0 15px; padding-top:10px; vertical-align:top;">
                                                                                                    <p style="margin:0;padding:0;">
                                                                                                        <?= Html::encode($orderItem->item_name . ' ' . $orderItem->item_name_ar) ?>
                                                                                                    </p>
                                                                                                    <?php foreach ($orderItem->getOrderItemExtraOptions()->all() as $extraOption) { ?>
                                                                                                        <p style="margin:0;padding:0; color:#828585;">
                                                                                                            <?= Html::encode($extraOption->extra_option_name . ' ' . $extraOption->extra_option_name_ar) ?>
                                                                                                        </p>
                                                                                                    <?php } ?>
                                                                                                </td>
                                                                                                <td style="color:#828585; padding-top:10px; text-align: right; vertical-align:top;width: 80px;">
                                                                                                    <?= \Yii::$app->formatter->asCurrency($orderItem->calculateOrderItemPrice(), $orderItem->currency->code, [\NumberFormatter::MAX_SIGNIFICANT_DIGITS => $orderItem->currency->decimal_place]); ?>
                                                                                                </td>
                                                                                            </tr>
                                                                                        <?php } ?>

                                                                                        <!-- #item -->
                                                                                        <!-- End items -->
                                                                                        <!-- Subtotal-->
                                                                                        <tr>
                                                                                            <td colspan="2" style="padding: 0 15px; padding-top:40px; vertical-align:top;">
                                                                                                <p style="margin:0;padding:0;">Subtotal</p>
                                                                                            </td>
                                                                                            <td style="color:#828585;padding-top:40px; text-align: right; vertical-align:top;">
                                                                                                <?= \Yii::$app->formatter->asCurrency($order->subtotal, $order->currency->code, [\NumberFormatter::MAX_SIGNIFICANT_DIGITS => $order->currency->decimal_place]) ?>
                                                                                            </td>
                                                                                        </tr>

                                                                                        <?php
                                                                                        if ($order->voucher_id != null && $order->voucher_id && $order->voucher->discount_type !== Voucher::DISCOUNT_TYPE_FREE_DELIVERY) {
                                                                                            $voucherDiscount = $order->voucher->discount_type == Voucher::DISCOUNT_TYPE_PERCENTAGE ? ($order->subtotal * ($order->voucher->discount_amount / 100)) : $order->voucher->discount_amount;
                                                                                            $subtotalAfterDiscount = $order->subtotal - $voucherDiscount;
                                                                                            ?>
                                                                                            <tr>
                                                                                                <td colspan="2" style="padding: 0 15px;  vertical-align:top;">
                                                                                                    <p style="margin:0;padding:0;">Voucher Discount</p>
                                                                                                </td>
                                                                                                <td style="color:#828585; text-align: right; vertical-align:top;">
                                                                                                    -<?= Yii::$app->formatter->asCurrency($voucherDiscount, $order->currency->code, [\NumberFormatter::MIN_FRACTION_DIGITS => $order->currency->decimal_place, \NumberFormatter::MAX_FRACTION_DIGITS => $order->currency->decimal_place]) ?>
                                                                                                </td>
                                                                                            </tr>


                                                                                            <tr>
                                                                                                <td colspan="2" style="padding: 0 15px;  vertical-align:top;">
                                                                                                    <p style="margin:0;padding:0;">Subtotal After Voucher</p>
                                                                                                </td>

                                                                                                <td style="color:#828585; text-align: right; vertical-align:top;">
                                                                                                    <?= Yii::$app->formatter->asCurrency($subtotalAfterDiscount, $order->currency->code, [\NumberFormatter::MIN_FRACTION_DIGITS => $order->currency->decimal_place, \NumberFormatter::MAX_FRACTION_DIGITS => $order->currency->decimal_place]) ?>
                                                                                                </td>
                                                                                            </tr>

                                                                                            <?php
                                                                                        } else if ($order->bank_discount_id != null && $order->bank_discount_id) {
                                                                                            $bankDiscount = $order->bankDiscount->discount_type == BankDiscount::DISCOUNT_TYPE_PERCENTAGE ? ($order->subtotal * ($order->bankDiscount->discount_amount / 100)) : $order->bankDiscount->discount_amount;
                                                                                            $subtotalAfterDiscount = $order->subtotal - $bankDiscount;
                                                                                            ?>

                                                                                            <tr>
                                                                                                <td colspan="2" style="padding: 0 15px;  vertical-align:top;">
                                                                                                    <p style="margin:0;padding:0;">Bank Discount</p>
                                                                                                </td>
                                                                                                <td style="color:#828585; text-align: right; vertical-align:top;">
                                                                                                    -<?= Yii::$app->formatter->asCurrency($bankDiscount, $order->currency->code, [
                                                                                                            \NumberFormatter::MIN_FRACTION_DIGITS => $order->currency->decimal_place,
                                                                                                            \NumberFormatter::MAX_FRACTION_DIGITS => $order->currency->decimal_place]) ?>
                                                                                                </td>
                                                                                            </tr>



                                                                                            <tr>
                                                                                                <td colspan="2" style="padding: 0 15px;  vertical-align:top;">
                                                                                                    <p style="margin:0;padding:0;">Subtotal After Bank Discount</p>
                                                                                                </td>

                                                                                                <td style="color:#828585; text-align: right; vertical-align:top;">
                                                                                                    <?= Yii::$app->formatter->asCurrency($subtotalAfterDiscount, $order->currency->code, [
                                                                                                            \NumberFormatter::MIN_FRACTION_DIGITS => $order->currency->decimal_place,
                                                                                                            \NumberFormatter::MAX_FRACTION_DIGITS => $order->currency->decimal_place
                                                                                                    ]) ?>
                                                                                                </td>
                                                                                            </tr>
                                                                                        <?php }
                                                                                        ?>


                                                                                        <!-- Delivery fee -->
                                                                                        <?php if ($order->order_mode == Order::ORDER_MODE_DELIVERY) { ?>
                                                                                            <tr>
                                                                                                <td colspan="2" style="padding: 0 15px; padding-top:0px; vertical-align:top;">
                                                                                                    <p style="margin:0;padding:0;">Delivery fee</p>
                                                                                                </td>
                                                                                                <td style="color:#828585;padding-top:0px; text-align: right; vertical-align:top;">
                                                                                                    <?= Yii::$app->formatter->asCurrency($order->delivery_fee, $order->currency->code, [
                                                                                                            \NumberFormatter::MIN_FRACTION_DIGITS => $order->currency->decimal_place,
                                                                                                            \NumberFormatter::MAX_FRACTION_DIGITS => $order->currency->decimal_place]) ?>
                                                                                                </td>
                                                                                            </tr>

                                                                                            <?php if ($order->voucher_id != null && $order->voucher_id && $order->voucher->discount_type == Voucher::DISCOUNT_TYPE_FREE_DELIVERY) { ?>

                                                                                                <tr>
                                                                                                    <td colspan="2" style="padding: 0 15px;  vertical-align:top;">
                                                                                                        <p style="margin:0;padding:0;">Voucher Discount</p>
                                                                                                    </td>
                                                                                                    <td style="color:#828585; text-align: right; vertical-align:top;">
                                                                                                        -<?= Yii::$app->formatter->asCurrency($order->delivery_fee,$order->currency->code, [
                                                                                                                \NumberFormatter::MIN_FRACTION_DIGITS => $order->currency->decimal_place,
                                                                                                                \NumberFormatter::MAX_FRACTION_DIGITS => $order->currency->decimal_place]) ?>
                                                                                                    </td>
                                                                                                </tr>


                                                                                                <tr>
                                                                                                    <td colspan="2" style="padding: 0 15px;  vertical-align:top;">
                                                                                                        <p style="margin:0;padding:0;">Delivery fee After Voucher</p>
                                                                                                    </td>
                                                                                                    <td style="color:#828585; text-align: right; vertical-align:top;">
                                                                                                        <?= Yii::$app->formatter->asCurrency(0, $order->currency->code, [
                                                                                                                \NumberFormatter::MIN_FRACTION_DIGITS => $order->currency->decimal_place,
                                                                                                                \NumberFormatter::MAX_FRACTION_DIGITS => $order->currency->decimal_place
                                                                                                        ]) ?>
                                                                                                    </td>
                                                                                                </tr>

                                                                                            <?php } ?>
                                                                                        <?php } ?>
                                                                                        <?php if ($order->tax) { ?>
                                                                                        <tr>
                                                                                            <td colspan="2" style="padding: 0 15px; padding-top:0px; vertical-align:top;">
                                                                                                <p style="margin:0;padding:0;">Tax</p>
                                                                                            </td>
                                                                                            <td style="color:#828585;padding-top:0px; text-align: right; vertical-align:top;">
                                                                                                <?= Yii::$app->formatter->asCurrency($order->tax, $order->currency->code, [
                                                                                                        \NumberFormatter::MIN_FRACTION_DIGITS => $order->currency->decimal_place,
                                                                                                        \NumberFormatter::MAX_FRACTION_DIGITS => $order->currency->decimal_place
                                                                                                ]) ?>
                                                                                            </td>
                                                                                        </tr>
                                                                                      <?php } ?>

                                                                                    </table>
                                                                                </td>
                                                                            </tr>
                                                                            <!-- TOTAL -->
                                                                            <tr>
                                                                                <td align="left" style="font-size:0px;padding:10px 25px;padding-bottom:30px;word-break:break-word;">
                                                                                    <table cellpadding="0" cellspacing="0" width="100%" border="0" style="color:#000000;font-family:Proxima Nova, Arial, Arial, Helvetica, sans-serif;font-size:15px;line-height:22px;table-layout:auto;width:100%;border:none;">
                                                                                        <tr>
                                                                                            <td colspan="2" style="font-size:23px; padding: 0 15px; padding-top:30px; vertical-align:top;">
                                                                                                <p style="margin:0;padding:0;">Total</p>
                                                                                            </td>
                                                                                            <td style="font-size:23px; padding: 0 15px 0 0; padding-top:30px; text-align: right; vertical-align:top;">
                                                                                                <?= Yii::$app->formatter->asCurrency($order->total, $order->currency->code, [
                                                                                                        \NumberFormatter::MIN_FRACTION_DIGITS => $order->currency->decimal_place,
                                                                                                        \NumberFormatter::MAX_FRACTION_DIGITS => $order->currency->decimal_place
                                                                                                ]) ?>
                                                                                            </td>
                                                                                        </tr>
                                                                                    </table>
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td style="font-size:0px;padding:10px 25px;word-break:break-word;">
                                                                                    <p style="border-top:solid 1px #d8e2e7;font-size:1;margin:0px auto;width:100%;"></p>
                                                                                    <!--[if mso | IE]><table align="center" border="0" cellpadding="0" cellspacing="0" style="border-top:solid 1px #d8e2e7;font-size:1;margin:0px auto;width:538px;" role="presentation" width="538px" ><tr><td style="height:0;line-height:0;"> &nbsp;
                                        </td></tr></table><![endif]-->
                                                                                </td>
                                                                            </tr>
                                                                        </table>
                                                                    </div>
                                                                    <!--[if mso | IE]></td></tr></table><![endif]-->
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                                <!--[if mso | IE]></td></tr></table></td></tr><tr><td class="" width="600px" ><table align="center" border="0" cellpadding="0" cellspacing="0" class="" style="width:588px;" width="588" ><tr><td style="line-height:0px;font-size:0px;mso-line-height-rule:exactly;"><![endif]-->
                                                <div style="margin:0px auto;max-width:588px;">
                                                    <table align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="width:100%;">
                                                        <tbody>
                                                            <tr>
                                                                <td style="direction:ltr;font-size:0px;padding:0px;padding-bottom:30px;text-align:center;">
                                                                    <!--[if mso | IE]><table role="presentation" border="0" cellpadding="0" cellspacing="0"><tr><td class="" style="vertical-align:top;width:294px;" ><![endif]-->
                                                                    <div class="mj-column-per-50 mj-outlook-group-fix" style="font-size:0px;text-align:left;direction:ltr;display:inline-block;vertical-align:top;width:100%;">
                                                                        <?php if ($order->order_mode == Order::ORDER_MODE_DELIVERY) { ?>

                                                                            <table border="0" cellpadding="0" cellspacing="0" role="presentation" style="vertical-align:top;" width="100%">
                                                                                <tr>
                                                                                    <td align="center" style="font-size:0px;padding:10px 25px;padding-top:15px;padding-bottom:6px;word-break:break-word;">
                                                                                        <div style="font-family:Proxima Nova, Arial, Arial, Helvetica, sans-serif;font-size:18px;line-height:24px;text-align:center;color:#000000;">Delivering to</div>
                                                                                    </td>
                                                                                </tr>
                                                                                <tr>
                                                                                  <td align="left" style="font-size:0px;padding:10px 25px;word-break:break-word;">
                                                                                      <div style="font-family:Proxima Nova, Arial, Arial, Helvetica, sans-serif;font-size:15px;line-height:24px;text-align:left;color:#828585;">
                                                                                        <div style="display:block">
                                                                                          <?= $customerName ?>
                                                                                        </div>
                                                                                        <div style="display:block">
                                                                                          <?= $customerPhoneNumber ?>
                                                                                        </div>
                                                                                        <?= $deliveryAddress ?>

                                                                                      </div>
                                                                                  </td>
                                                                                </tr>
                                                                            </table>
                                                                        <?php } else if ($order->order_mode == Order::ORDER_MODE_PICK_UP) { ?>
                                                                            <table border="0" cellpadding="0" cellspacing="0" role="presentation" style="vertical-align:top;" width="100%">
                                                                                <tr>
                                                                                    <td align="center" style="font-size:0px;padding:10px 25px;padding-top:15px;padding-bottom:6px;word-break:break-word;">
                                                                                        <div style="font-family:Proxima Nova, Arial, Arial, Helvetica, sans-serif;font-size:18px;line-height:24px;text-align:center;color:#000000;">Pickup from</div>
                                                                                    </td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td align="left" style="font-size:0px;padding:10px 25px;word-break:break-word;">
                                                                                        <div style="font-family:Proxima Nova, Arial, Arial, Helvetica, sans-serif;font-size:15px;line-height:24px;text-align:left;color:#828585;">
                                                                                          <div style="display:block">
                                                                                              <?= Html::encode($order->pickupLocation->business_location_name) ?>,
                                                                                              <?= Html::encode($order->pickupLocation->country->country_name) ?>
                                                                                           </div>
                                                                                            <div style="display:block">
                                                                                              <?= $customerName ?>
                                                                                            </div>
                                                                                            <div style="display:block">
                                                                                              <?= $customerPhoneNumber ?>
                                                                                            </div>
                                                                                      </div>
                                                                                    </td>
                                                                                </tr>
                                                                            </table>
                                                                        <?php } ?>
                                                                    </div>
                                                                    <!--[if mso | IE]></td>
                                                                    <td class="" style="vertical-align:top;width:294px;" ><![endif]-->
                                                                    <div class="mj-column-per-50 mj-outlook-group-fix" style="font-size:0px;text-align:left;direction:ltr;display:inline-block;vertical-align:top;width:100%;">
                                                                        <table border="0" cellpadding="0" cellspacing="0" role="presentation" style="vertical-align:top;" width="100%">
                                                                            <tr>
                                                                                <td align="center" style="font-size:0px;padding:10px 25px;padding-top:15px;padding-bottom:6px;word-break:break-word;">
                                                                                    <div style="font-family:Proxima Nova, Arial, Arial, Helvetica, sans-serif;font-size:18px;line-height:24px;text-align:center;color:#000000;">Payment</div>
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td align="left" style="font-size:0px;padding:10px 25px;padding-bottom:0;word-break:break-word;">
                                                                                    <div style="font-family:Proxima Nova, Arial, Arial, Helvetica, sans-serif;font-size:15px;line-height:24px;text-align:left;color:#828585;">Date: <?= date('M d, Y'); ?></div>
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td align="left" style="font-size:0px;padding:10px 25px;padding-top:0;padding-bottom:0;word-break:break-word;">
                                                                                    <div style="font-family:Proxima Nova, Arial, Arial, Helvetica, sans-serif;font-size:15px;line-height:24px;text-align:left;color:#828585;">
                                                                                        Payment mode:
                                                                                        <?= $paymentMethodName ?></div>
                                                                                </td>
                                                                            </tr>

                                                                            <?php if ($order->payment) { ?>
                                                                                <tr>
                                                                                    <td align="left" style="font-size:0px;padding:10px 25px;padding-top:0;padding-bottom:0;word-break:break-word;">
                                                                                        <div style="font-family:Proxima Nova, Arial, Arial, Helvetica, sans-serif;font-size:15px;line-height:24px;text-align:left;color:#828585;">
                                                                                            Result: <?= $paymentStatus ?>
                                                                                        </div>
                                                                                    </td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td align="left" style="font-size:0px;padding:10px 25px;padding-top:0;padding-bottom:0;word-break:break-word;">
                                                                                        <div style="font-family:Proxima Nova, Arial, Arial, Helvetica, sans-serif;font-size:15px;line-height:24px;text-align:left;color:#828585;">
                                                                                            Ref: <?= $paymentGatewayOrderId ?>
                                                                                        </div>
                                                                                    </td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td align="left" style="font-size:0px;padding:10px 25px;padding-top:0;padding-bottom:0;word-break:break-word;">
                                                                                        <div style="font-family:Proxima Nova, Arial, Arial, Helvetica, sans-serif;font-size:15px;line-height:24px;text-align:left;color:#828585;">
                                                                                            Charge: <?= $paymentGatewayTransactionId ?>
                                                                                        </div>
                                                                                    </td>
                                                                                </tr>
                                                                            <?php } ?>
                                                                        </table>
                                                                    </div>
                                                                    <!--[if mso | IE]></td></tr></table><![endif]-->
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                                <!--[if mso | IE]></td></tr></table></td></tr></table><![endif]-->
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <!--[if mso | IE]></td></tr></table><table align="center" border="0" cellpadding="0" cellspacing="0" class="" style="width:600px;" width="600" ><tr><td style="line-height:0px;font-size:0px;mso-line-height-rule:exactly;"><![endif]-->
                            <div style="margin:0px auto;max-width:600px;">
                                <table align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="width:100%;">
                                    <tbody>
                                        <tr>
                                            <td style="direction:ltr;font-size:0px;padding:0px;padding-bottom:24px;padding-top:10px;text-align:center;">
                                                <!--[if mso | IE]><table role="presentation" border="0" cellpadding="0" cellspacing="0"><tr><td class="" style="vertical-align:top;width:600px;" ><![endif]-->
                                                <div class="mj-column-per-100 mj-outlook-group-fix" style="font-size:0px;text-align:left;direction:ltr;display:inline-block;vertical-align:top;width:100%;">
                                                    <table border="0" cellpadding="0" cellspacing="0" role="presentation" style="vertical-align:top;" width="100%">
                                                        <tr>
                                                            <td align="center" style="font-size:0px;padding:10px 25px;word-break:break-word;">
                                                                <div style="font-family:Proxima Nova, Arial, Arial, Helvetica, sans-serif;font-size:14px;line-height:24px;text-align:center;color:grey;">This email is delivered by <a href="https://plugn.io">Plugn</a></div>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </div>
                                                <!--[if mso | IE]></td></tr></table><![endif]-->
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <!--[if mso | IE]></td></tr></table><![endif]-->
                        </div>
                    </body>

                    </html>
