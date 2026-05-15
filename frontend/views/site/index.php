<?php

use common\models\Restaurant;
use yii\helpers\Html;
use common\models\Order;
use common\models\AgentAssignment;
use yii\grid\GridView;

/* @var $this yii\web\View */

$this->params['restaurant_uuid'] = $restaurant->restaurant_uuid;
$this->title = 'Dashboard';

$currencyCode = (string) $restaurant->currency->code;
$currencyCodeJson = json_encode($currencyCode, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT);
$rawRestaurantDomain = trim((string) $restaurant->restaurant_domain);
$restaurantDomainText = Html::encode($rawRestaurantDomain);
$restaurantDomainHref = preg_match('/^https?:\/\//i', $rawRestaurantDomain) ? Html::encode($rawRestaurantDomain) : null;

$js = "
 $(document).ready(function(){
    $('#dropdownCustomerGained').html('Last 7 days');
    $('#dropdownRevenueGenerated').html('Last 7 days');
    $('#dropdownSoldItems').html('Last 7 days');
    $('#dropdownOrdersReceived').html('Last 7 days');
});
";
$this->registerJs($js);
?>


<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>


<!-- <script src="https://code.jquery.com/jquery-1.11.0.min.js"></script> -->
<script type="text/javascript">

    var currency_code = <?= $currencyCodeJson ?>;

    $(window).on('load', function () {


        var primary = '#7367F0';
        var success = '#28C76F';
        var danger = '#EA5455';
        var warning = '#FF9F43';
        var soldItemsChartOptions = {
            chart: {
                height: 100,
                type: 'area',
                toolbar: {
                    show: false,
                },
                sparkline: {
                    enabled: true
                },
                grid: {
                    show: false,
                    padding: {
                        left: 0,
                        right: 0
                    }
                },
            },
            colors: [danger],
            dataLabels: {
                enabled: false
            },
            stroke: {
                curve: 'smooth',
                width: 2.5
            },
            fill: {
                type: 'gradient',
                gradient: {
                    shadeIntensity: 0.9,
                    opacityFrom: 0.7,
                    opacityTo: 0.5,
                    stops: [0, 80, 100]
                }
            },
            series: [{
                    name: 'Items',
                    data: <?= json_encode($sold_item_chart_data_this_week) ?>,
                }],
            xaxis: {
                labels: {
                    show: false,
                },
                axisBorder: {
                    show: false,
                }
            },
            yaxis: [{
                    y: 0,
                    offsetX: 0,
                    offsetY: 0,
                    padding: {left: 0, right: 0},
                }],
            tooltip: {
                x: {show: false}
            },
        }

        var soldItemsChart = new ApexCharts(
                document.querySelector('#sold-item-chart'),
                soldItemsChartOptions
                );
        var orderReceivedChartOptions = {
            chart: {
                height: 100,
                type: 'area',
                toolbar: {
                    show: false,
                },
                sparkline: {
                    enabled: true
                },
                grid: {
                    show: false,
                    padding: {
                        left: 0,
                        right: 0
                    }
                },
            },
            colors: [warning],
            dataLabels: {
                enabled: false
            },
            stroke: {
                curve: 'smooth',
                width: 2.5
            },
            fill: {
                type: 'gradient',
                gradient: {
                    shadeIntensity: 0.9,
                    opacityFrom: 0.7,
                    opacityTo: 0.5,
                    stops: [0, 80, 100]
                }
            },
            series: [{
                    name: 'Orders',
                    data: <?= json_encode($orders_received_chart_data_this_week) ?>,
                }],
            xaxis: {
                labels: {
                    show: false,
                },
                axisBorder: {
                    show: false,
                }
            },
            yaxis: [{
                    y: 0,
                    offsetX: 0,
                    offsetY: 0,
                    padding: {left: 0, right: 0},
                }],
            tooltip: {
                x: {show: false}
            },
        }


        var orderReceivedChart = new ApexCharts(
                document.querySelector('#orders-recevied-chart'),
                orderReceivedChartOptions
                );
        var revenueGeneratedChartOptions = {
            chart: {
                height: 100,
                type: 'area',
                toolbar: {
                    show: false,
                },
                sparkline: {
                    enabled: true
                },
                grid: {
                    show: false,
                    padding: {
                        left: 0,
                        right: 0
                    }
                },
            },
            colors: [success],
            dataLabels: {
                enabled: false
            },
            stroke: {
                curve: 'smooth',
                width: 2.5
            },
            fill: {
                type: 'gradient',
                gradient: {
                    shadeIntensity: 0.9,
                    opacityFrom: 0.7,
                    opacityTo: 0.5,
                    stops: [0, 80, 100]
                }
            },
            series: [{
                    name: 'Orders',
                    data: <?= json_encode($revenue_generated_chart_data_this_week) ?>,
                }],
            xaxis: {
                labels: {
                    show: false,
                },
                axisBorder: {
                    show: false,
                }
            },
            yaxis: [{
                    y: 0,
                    offsetX: 0,
                    offsetY: 0,
                    padding: {left: 0, right: 0},
                }],
            tooltip: {
                y: {
                  formatter: function (val) {
                    return val + ' ' + currency_code ;
                  }
                },
                x: {show: false}
            },
        }

        var revenueGeneratedChart = new ApexCharts(
                document.querySelector('#revenue-generated-chart'),
                revenueGeneratedChartOptions
                );
        var customerGainedChartOptions = {
            chart: {
                height: 100,
                type: 'area',
                toolbar: {
                    show: false,
                },
                sparkline: {
                    enabled: true
                },
                grid: {
                    show: false,
                    padding: {
                        left: 0,
                        right: 0
                    }
                },
            },
            colors: [primary],
            dataLabels: {
                enabled: false
            },
            stroke: {
                curve: 'smooth',
                width: 2.5
            },
            fill: {
                type: 'gradient',
                gradient: {
                    shadeIntensity: 0.9,
                    opacityFrom: 0.7,
                    opacityTo: 0.5,
                    stops: [0, 80, 100]
                }
            },
            series: [{
                    name: 'Customers',
                    data: <?= json_encode($customer_chart_data_this_week) ?>,
                }],
            xaxis: {
                labels: {
                    show: false,
                },
                axisBorder: {
                    show: false,
                }
            },
            yaxis: [{
                    y: 0,
                    offsetX: 0,
                    offsetY: 0,
                    padding: {left: 0, right: 0},
                }],
            tooltip: {
                x: {show: false}
            },
        }

        var customerGainedChart = new ApexCharts(
                document.querySelector('#customer-gained-chart'),
                customerGainedChartOptions
                );
        customerGainedChart.render();
        revenueGeneratedChart.render();
        soldItemsChart.render();
        orderReceivedChart.render();
        function addData(chart, data) {
            chart.opts.series[0].data = data;
            chart.update();
        }

        //Get customer gained
        document.getElementById("getCustomerGainedLast7DaysData").addEventListener("click", function () {
            $('#dropdownCustomerGained').html('Last 7 days');
            $('.number-of-customer-gained').html(<?= $number_of_all_customer_gained_this_week ?>);
            addData(customerGainedChart,<?= json_encode($customer_chart_data_this_week) ?>);
        });
        document.getElementById("getCustomerGainedLastMonth").addEventListener("click", function () {
            $('#dropdownCustomerGained').html('Last Month');
            $('.number-of-customer-gained').html(<?= $number_of_all_customer_gained_last_month ?>);
            addData(customerGainedChart,<?= json_encode($customer_chart_data_last_month) ?>);
        });
        document.getElementById("getCustomerGainedLast3Months").addEventListener("click", function () {
            $('#dropdownCustomerGained').html('Last 3 Months');
            $('.number-of-customer-gained').html(<?= $number_of_all_customer_gained_last_three_months ?>);
            addData(customerGainedChart,<?= json_encode($customer_chart_data_last_three_months) ?>);
        });
        // Get Revenue Generated
        document.getElementById("getRevenueGeneratedLast7DaysData").addEventListener("click", function () {
            $('#dropdownRevenueGenerated').html('Last 7 days');
            $('.number-of-revenue-generated').html(<?= number_format((float) $number_of_all_revenue_generated_this_week, 2, '.', ''); ?>);
            addData(revenueGeneratedChart,<?= json_encode($revenue_generated_chart_data_this_week) ?>);
        });
        document.getElementById("getRevenueGeneratedLastMonth").addEventListener("click", function () {
            $('#dropdownRevenueGenerated').html('Last Month');
            $('.number-of-revenue-generated').html(<?= number_format((float) $number_of_all_revenue_generated_last_month, 2, '.', ''); ?>);
            addData(revenueGeneratedChart,<?= json_encode($revenue_generated_chart_data_last_month) ?>);
        });
        document.getElementById("getRevenueGeneratedLast3Months").addEventListener("click", function () {
            $('#dropdownRevenueGenerated').html('Last 3 Months');
            $('.number-of-revenue-generated').html(<?= number_format((float) $number_of_all_revenue_generated_last_three_months, 2, '.', ''); ?>);
            addData(revenueGeneratedChart,<?= json_encode($revenue_generated_chart_data_last_three_months) ?>);
        });
        // Get Sold Items
        document.getElementById("getSoldItemsLast7DaysData").addEventListener("click", function () {
            $('#dropdownSoldItems').html('Last 7 days');
            $('.number-of-sold-items').html(<?= $number_of_all_sold_item_this_week ?>);
            addData(soldItemsChart,<?= json_encode($sold_item_chart_data_this_week) ?>);
        });
        document.getElementById("getSoldItemsLastMonth").addEventListener("click", function () {
            $('#dropdownSoldItems').html('Last Month');
            $('.number-of-sold-items').html(<?= $number_of_all_sold_item_last_month ?>);
            addData(soldItemsChart,<?= json_encode($sold_item_chart_data_last_month) ?>);
        });
        document.getElementById("getSoldItemsLast3Months").addEventListener("click", function () {
            $('#dropdownSoldItems').html('Last 3 Months');
            $('.number-of-sold-items').html(<?= $number_of_all_sold_item_last_three_months ?>);
            addData(soldItemsChart,<?= json_encode($sold_item_chart_data_last_three_months) ?>);
        });
        // Get Order Recevied
        document.getElementById("getOrderReceivedLast7DaysData").addEventListener("click", function () {
            $('#dropdownOrdersReceived').html('Last 7 days');
            $('.number-of-orders-received').html(<?= $number_of_all_orders_received_this_week ?>);
            addData(orderReceivedChart,<?= json_encode($orders_received_chart_data_this_week) ?>);
        });
        document.getElementById("getOrderReceivedLastMonth").addEventListener("click", function () {
            $('#dropdownOrdersReceived').html('Last Month');
            $('.number-of-orders-received').html(<?= $number_of_all_orders_received_last_month ?>);
            addData(orderReceivedChart,<?= json_encode($orders_received_chart_data_last_month) ?>);
        });
        document.getElementById("getOrderReceivedLast3Months").addEventListener("click", function () {
            $('#dropdownOrdersReceived').html('Last 3 Months');
            $('.number-of-orders-received').html(<?= $number_of_all_orders_received_last_three_months ?>);
            addData(orderReceivedChart,<?= json_encode($orders_received_chart_data_last_three_months) ?>);
        });
    });


</script>
<section id="tutorial-card">


  <?php
  if($numberOfOrders == 0){ ?>

    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-sm-12 col-md-9 col-lg-9">
                    <div>
                        <h3><span>Learn how to use Plugn</span></h3>
                    </div>
                    <p> Check out our introductory course on how to design, build, and launch your online store.</p>
                    <div>
                        <div>
                            <div>
                                <?= Html::a('Learn', 'https://www.plugn.io/learn', ['class' => 'btn btn-primary']) ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
    <?php
    }


   if( !$restaurant->is_myfatoorah_enable && !$restaurant->is_tap_enable ){ ?>

  <div class="card">
      <div class="card-body">
          <div class="row">
              <div class="col-9">
                  <div>
                      <h3><span>Looking to accept online payments?</span></h3>
                  </div>
                  <p>Set up payment profile to start collecting online payments</p>
                  <div>
                      <div>
                          <div>
                              <?= Html::a('Set up payments', ['store/view-payment-methods', 'storeUuid' => $restaurant->restaurant_uuid], ['class' => 'btn btn-primary']) ?>
                          </div>
                      </div>
                  </div>
              </div>

          </div>

      </div>
  </div>

<?php }

?>




<?php


  if($restaurant->getItems()->count() == 0){ ?>

    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-sm-12 col-md-9 col-lg-9">
                    <div>
                        <h3><span>Add your first item</span></h3>
                    </div>
                    <p>Add physical items, services, or anything else you dream up.</p>
                    <div>
                        <div>
                            <div>
                                <?= Html::a('Add Product', ['item/create', 'storeUuid' => $restaurant->restaurant_uuid], ['class' => 'btn btn-primary']) ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-12 col-md-3 col-lg-3">
                    <div  style="float: right">
                        <img src="https://res.cloudinary.com/plugn/image/upload/v1603284452/home-onboard.svg" alt="" />
                    </div>
                </div>
            </div>

        </div>

    </div>

  <?php } ?>

  <?php
  if(!$restaurant->has_deployed){ ?>
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-sm-12 col-md-9 col-lg-9">
                    <div>
                        <h3><span>Edit the look and feel of your online store</span></h3>
                    </div>
                    <p>Choose a theme and add your logo, colors, and images to reflect your brand.</p>
                    <div>
                        <div>
                            <div>
                                <?= Html::a('Customize theme', ['store/update-design-layout', 'id' => $restaurant->restaurant_uuid], ['class' => 'btn btn-primary']) ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-12 col-md-3 col-lg-3">
                    <div  style="float: right">
                        <img src="https://res.cloudinary.com/plugn/image/upload/v1603284769/home-onboard-theme-incomplete.svg" alt="" />
                    </div>
                </div>
            </div>

        </div>
    </div>

      <?php }
    if(str_contains($rawRestaurantDomain, '.plugn.store') &&   $restaurant->has_deployed == 1 ){ ?>
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-sm-12 col-md-9 col-lg-9">
                    <div>
                        <h3><span>Strengthen your brand with a custom domain</span></h3>
                    </div>
                    <p>Your current domain is <?php if ($restaurantDomainHref): ?><a target="_blank" rel="noopener noreferrer" href="<?= $restaurantDomainHref ?>"> <?= $restaurantDomainText ?> </a><?php else: ?><span><?= $restaurantDomainText ?></span><?php endif; ?> but you can add a custom domain to help customers find your online store.</p>
                    <div>
                        <div>
                            <div>
                                <?= Html::a('Add domain', ['site/connect-domain', 'id' => $restaurant->restaurant_uuid], ['class' => 'btn btn-primary']) ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-12 col-md-3 col-lg-3">
                    <div  style="float: right">
                        <img src="https://res.cloudinary.com/plugn/image/upload/v1603284844/home-onboard-domain-incomplete.svg" alt="" />
                    </div>
                </div>
            </div>

        </div>
    </div>

      <?php
      }

      // if($numberOfOrders > 0){
      ?>

    <!-- <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-sm-12 col-md-9 col-lg-9">
                    <div>
                        <h3><span>Keep track of your real time orders</span></h3>
                    </div>
                    <p>Live update of your real time orders.</p>
                    <div>
                        <div>
                            <div>
                                <?php
                                // Html::a('View orders', ['site/real-time-orders', 'storeUuid' => $restaurant->restaurant_uuid], ['class' => 'btn btn-primary'])
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-12 col-md-3 col-lg-3">
                    <div  style="float: right">
                        <img src="https://res.cloudinary.com/plugn/image/upload/c_scale,h_120,w_100/v1603286062/a64ef20cde1af82ef69556c7ab33c727_pa1xe7.svg" width="120" alt="" />
                    </div>
                </div>
            </div>

        </div>
    </div> -->

    <?php
    // }
    ?>



</section>
<!-- Dashboard Ecommerce Starts -->
<section id="dashboard-ecommerce">

    <div class="row">
        <div class="col-lg-3 col-sm-6 col-12">
            <div class="card">
                <div style="padding:21px">
                    <div class="avatar bg-rgba-primary p-50 m-0">
                        <div class="avatar-content">
                            <i class="feather icon-users text-primary font-medium-5"></i>
                        </div>
                    </div>
                    <div class="dropdown chart-dropdown" style="float:right">
                        <button class="btn btn-sm border-0 dropdown-toggle" style="font-size:15px; padding-right: 0px;" type="button" id="dropdownCustomerGained" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        </button>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownCustomerGained">

                            <button   id="getCustomerGainedLast7DaysData" class="dropdown-item" style="width:100%">Last 7 Days</button>
                            <button   id="getCustomerGainedLastMonth" class="dropdown-item" style="width:100%">Last Month</button>
                            <button   id="getCustomerGainedLast3Months" class="dropdown-item" style="width:100%">Last 3 Months</button>

                        </div>
                    </div>

                    <h2 class="text-bold-700 mt-1 number-of-customer-gained">
                        <?= $number_of_all_customer_gained_this_week ? $number_of_all_customer_gained_this_week : 0 ?>
                    </h2>
                    <p class="mb-0">
                        <?= $number_of_all_customer_gained_this_week <= 1 ? 'Customer Gained' : 'Customers Gained' ?>
                    </p>
                </div>
                <div class="card-content">
                    <div id="customer-gained-chart"></div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-sm-6 col-12">
            <div class="card">
                <div style="padding:21px">
                    <div class="avatar bg-rgba-success p-50 m-0">
                        <div class="avatar-content">
                            <i class="feather icon-credit-card text-success font-medium-5"></i>
                        </div>
                    </div>
                    <div class="dropdown chart-dropdown" style="float:right">
                        <button class="btn btn-sm border-0 dropdown-toggle" style="font-size:15px;    padding-right: 0px;" type="button" id="dropdownRevenueGenerated" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        </button>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownRevenueGenerated">
                            <button id="getRevenueGeneratedLast7DaysData" class="dropdown-item" style="width:100%">Last 7 Days</button>
                            <button id="getRevenueGeneratedLastMonth" class="dropdown-item" style="width:100%">Last Month</button>
                            <button id="getRevenueGeneratedLast3Months" class="dropdown-item" style="width:100%">Last 3 Months</button>

                        </div>
                    </div>
                    <div style="  margin-bottom: 0.5rem;  margin-top: 1rem !important;">
                        <h2 style="  display: contents;" class="text-bold-700 mt-1 number-of-revenue-generated">
                            <?= Yii::$app->formatter->asCurrency($number_of_all_revenue_generated_this_week, $currencyCode, [
                                    \NumberFormatter::MIN_FRACTION_DIGITS => 3,
                                \NumberFormatter::MAX_FRACTION_DIGITS => 3
                            ]) ?>
                        </h2>
                    </div>
                    <p class="mb-0">Revenue Generated</p>
                </div>

                <div class="card-content">
                    <div id="revenue-generated-chart"></div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-sm-6 col-12">
            <div class="card">
                <div style="padding:21px">
                    <div class="avatar bg-rgba-danger p-50 m-0">
                        <div class="avatar-content">
                            <i class="feather icon-shopping-cart text-danger font-medium-5"></i>
                        </div>
                    </div>
                    <div class="dropdown chart-dropdown" style="float:right">
                        <button class="btn btn-sm border-0 dropdown-toggle" style="font-size:15px;    padding-right: 0px;" type="button" id="dropdownSoldItems" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        </button>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownSoldItems">
                            <button   id="getSoldItemsLast7DaysData" class="dropdown-item" style="width:100%">Last 7 Days</button>
                            <button   id="getSoldItemsLastMonth" class="dropdown-item" style="width:100%">Last Month</button>
                            <button   id="getSoldItemsLast3Months" class="dropdown-item" style="width:100%">Last 3 Months</button>
                        </div>

                    </div>
                    <h2 class="text-bold-700 mt-1 number-of-sold-items">
                        <?= $number_of_all_sold_item_this_week ? $number_of_all_sold_item_this_week : 0 ?>
                    </h2>
                    <p class="mb-0">
                        <?= $number_of_all_sold_item_this_week <= 1 ? 'Sold Item' : 'Sold Items' ?>
                    </p>
                </div>
                <div class="card-content">
                    <div id="sold-item-chart"></div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-sm-6 col-12">
            <div class="card">
                <div style="padding:21px">
                    <div class="avatar bg-rgba-warning p-50 m-0">
                        <div class="avatar-content">
                            <i class="feather icon-package text-warning font-medium-5"></i>
                        </div>
                    </div>
                    <div class="dropdown chart-dropdown" style="float:right">
                        <button class="btn btn-sm border-0 dropdown-toggle" style="font-size:15px;    padding-right: 0px;" type="button" id="dropdownOrdersReceived" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        </button>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownOrdersReceived">
                            <button   id="getOrderReceivedLast7DaysData" class="dropdown-item" style="width:100%">Last 7 Days</button>
                            <button   id="getOrderReceivedLastMonth" class="dropdown-item" style="width:100%">Last Month</button>
                            <button   id="getOrderReceivedLast3Months" class="dropdown-item" style="width:100%">Last 3 Months</button>
                        </div>
                    </div>
                    <h2 class="text-bold-700 mt-1 number-of-orders-received">
                        <?= $number_of_all_orders_received_this_week ? $number_of_all_orders_received_this_week : 0 ?>
                    </h2>
                    <p class="mb-0">
                        <?= $number_of_all_orders_received_this_week <= 1 ? 'Order Received' : 'Orders Received' ?>
                    </p>
                </div>



                <div class="card-content">
                    <div id="orders-recevied-chart"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-3 col-sm-6 col-12">
            <div class="card text-center">

                <div class="card-content">


                    <div class="card-body">
                        <div class="avatar bg-rgba-primary p-50 m-0 mb-1">
                            <div class="avatar-content">
                                <i class="feather icon-users text-primary font-medium-5"></i>
                            </div>
                        </div>
                        <h2 class="text-bold-700"><?= $today_customer_gained ?></h2>
                        <p class="mb-0 line-ellipsis">
                            <?= $today_customer_gained <= 1 ? "Today's Customer Gained" : "Today's Customers Gained" ?>
                        </p>

                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-sm-6 col-12">
            <div class="card text-center">
                <div class="card-content">
                    <div class="card-body">
                        <div class="avatar bg-rgba-success p-50 m-0 mb-1">
                            <div class="avatar-content">
                                <i class="feather icon-credit-card text-success font-medium-5"></i>
                            </div>
                        </div>
                        <h2 class="text-bold-700">
                          <?= Yii::$app->formatter->asCurrency($today_revenue_generated, $currencyCode, [\NumberFormatter::MIN_FRACTION_DIGITS => 3, \NumberFormatter::MAX_FRACTION_DIGITS => 3]) ?>
                        </h2>
                        <p class="mb-0 line-ellipsis">Today's Revenue Generated</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-sm-6 col-12">
            <div class="card text-center">
                <div class="card-content">
                    <div class="card-body">
                        <div class="avatar bg-rgba-danger p-50 m-0 mb-1">
                            <div class="avatar-content">
                                <i class="feather icon-shopping-cart text-danger font-medium-5"></i>
                            </div>
                        </div>
                        <h2 class="text-bold-700"><?= $today_sold_items ?></h2>
                        <p class="mb-0 line-ellipsis">
                            <?= $today_sold_items <= 1 ? "Today's Sold Item" : "Today's Sold Items" ?>
                        </p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-sm-6 col-12">
            <div class="card text-center">
                <div class="card-content">
                    <div class="card-body">
                        <div class="avatar bg-rgba-warning p-50 m-0 mb-1">
                            <div class="avatar-content">
                                <i class="feather icon-package text-warning font-medium-5"></i>
                            </div>
                        </div>
                        <h2 class="text-bold-700"><?= $today_orders_received ?></h2>
                        <p class="mb-0 line-ellipsis">
                            <?= $today_orders_received <= 1 ? "Today's Order Received" : "Today's Orders Received" ?>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

</section>
<!-- Dashboard Ecommerce ends -->
