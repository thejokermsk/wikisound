<?php include ROOT . '/views/layouts/header.php'; ?>
<title> Корзина </title>


<? $slider = false; ?>

<!-- START PAGE-CONTENT -->
<section class="page-content">
    <div class="container">
        <div class="">
            <? if (isset($CART)): ?>
            <? foreach ($CART as $idProduct => $product): ?>
            <div class="flex cart_wrapper clearfix">
                <div class="cart_wrapper_img col-md-1 col-xs-4">
                    <a href="/product/<? echo Product::getLinkProductById($idProduct); ?>">
                        <img class="cart_img" src="/template/img/product/<? echo $idProduct; ?>.jpg">
                    </a>
                </div>
                <div
                    class="cart_nameProduct col-md-5 col-xs-8"
                    style="display: flex; flex-direction: column; justify-content: center;"
                >
                    <div class="cart_name">
                        <a href="/product/<? echo Product::getLinkProductById($idProduct); ?>">
                            <? echo Brand::GetNameBrandByIdProduct($idProduct) . ' - ' . $product['name']; ?>
                        </a>
                    </div>
                    <div class="cart_code">Код товара: <? echo $idProduct; ?></div>
                </div>
                <div class="col-md-2  col-xs-3 cart_price">
                    <span
                        data-product-price
                        data-id-product="<? echo $idProduct; ?>"
                    >
                        <? echo number_format($product['price'], 0, '', ' '); ?>
                    </span> Р
                </div>
                <div class="col-md-1 col-xs-3 flex cart_reg">
                    <div class="number">
                        <span
                            class="minus cart_regulator"
                            data-id-product="<? echo $idProduct; ?>"
                        >
                            <img src="/template/img/-.png">
                        </span>
                        <input type="text" class="cart_regulator_input" value="<? echo $product['count']; ?>">
                        <span
                            class="plus cart_regulator"
                            data-id-product="<? echo $idProduct; ?>"
                        >
                            <img src="/template/img/+.png">
                        </span>
                    </div>
                </div>
                <div class="col-md-2 col-xs-4 cart_price" data-product-formatted-price>
                    <? echo number_format($product['price'] * $product['count'], 0, '', ' '); ?>
                    Р
                </div>
                <div class="cart_deleteWrapper col-md-1 col-xs-2">
                    <div class="cart_delete" data-id-product="<? echo $idProduct; ?>">
                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="12px" height="15px">
                            <path fill-rule="evenodd" fill="rgb(111, 113, 115)" d="M8.308,13.123 C8.563,13.123 8.769,12.913 8.769,12.655 L8.769,7.031 C8.769,6.772 8.563,6.562 8.308,6.562 C8.053,6.562 7.846,6.772 7.846,7.031 L7.846,12.655 C7.846,12.913 8.053,13.123 8.308,13.123 ZM11.077,1.875 L8.308,1.875 L8.308,0.938 C8.308,0.420 7.895,0.001 7.385,0.001 L4.615,0.001 C4.105,0.001 3.692,0.420 3.692,0.938 L3.692,1.875 L0.923,1.875 C0.413,1.875 -0.000,2.295 -0.000,2.813 L-0.000,3.750 C-0.000,4.267 0.413,4.688 0.923,4.688 L0.923,13.123 C0.923,14.159 1.749,14.998 2.769,14.998 L9.231,14.998 C10.251,14.998 11.077,14.159 11.077,13.123 L11.077,4.688 C11.587,4.688 12.000,4.267 12.000,3.750 L12.000,2.813 C12.000,2.295 11.587,1.875 11.077,1.875 ZM4.615,1.407 C4.615,1.148 4.822,0.938 5.077,0.938 L6.923,0.938 C7.178,0.938 7.385,1.148 7.385,1.407 L7.385,1.875 C6.937,1.875 4.615,1.875 4.615,1.875 L4.615,1.407 ZM10.154,13.123 C10.154,13.641 9.741,14.061 9.231,14.061 L2.769,14.061 C2.259,14.061 1.846,13.641 1.846,13.123 L1.846,4.688 L10.154,4.688 L10.154,13.123 ZM10.616,3.750 L1.384,3.750 C1.130,3.750 0.923,3.540 0.923,3.282 C0.923,3.023 1.130,2.813 1.384,2.813 L10.616,2.813 C10.870,2.813 11.077,3.023 11.077,3.282 C11.077,3.540 10.870,3.750 10.616,3.750 ZM3.692,13.123 C3.947,13.123 4.154,12.913 4.154,12.655 L4.154,7.031 C4.154,6.772 3.947,6.562 3.692,6.562 C3.437,6.562 3.231,6.772 3.231,7.031 L3.231,12.655 C3.231,12.913 3.437,13.123 3.692,13.123 ZM6.000,13.123 C6.255,13.123 6.462,12.913 6.462,12.655 L6.462,7.031 C6.462,6.772 6.255,6.562 6.000,6.562 C5.745,6.562 5.538,6.772 5.538,7.031 L5.538,12.655 C5.538,12.913 5.745,13.123 6.000,13.123 Z"/>
                        </svg>
                    </div>
                </div>
            </div>
            <? endforeach; ?>
            <? else: ?>
            <span style="margin: 50px;  text-align: center; display: block; font-size: 24px; color: #383838; font-weight: bold;">
                Ваша корзина пока пуста.
            </span>
            <? endif; ?>
        </div>

        <? if (isset($CART)): ?>
        <div class="clearfix order_totalWrap" style="margin-bottom:90px; margin-top: 15px ">
            <span style="position: relative;   top: 10px;" class="order_text">Ваше примечание к заказу</span><br>
            <textarea id="promocode" class="cart_promocode col-xs-12 col-md-8 " style="border-radius: 5px; margin-bottom: 50px; border: 1px solid #dddddd;resize: none; padding: 2px;     margin-top: 14px;  height: 132px;"></textarea>
            <div class="col-xs-12 col-md-4 cart_wrapperTotal">
                <div class="order_text">
                    В корзине <span data-count-product><? echo $countCart; ?></span>
                    товара на сумму<span class="order_textTotal" data-summary-order><? echo $summaryOrder; ?> Р</span>
                </div>
                <div class="order_text2">
                    <span data-bonus>
                        <? echo $bonus['text']; ?>
                    </span>
                    <span>
                        <a href="/video">
                            <span class="order_question">
                                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="13px" height="13px">
                                    <path fill-rule="evenodd" fill="rgb(34, 35, 43)" d="M6.499,12.998 C2.910,12.998 0.001,10.088 0.001,6.499 C0.001,2.910 2.910,0.000 6.499,0.000 C10.089,0.000 12.998,2.910 12.998,6.499 C12.998,10.088 10.089,12.998 6.499,12.998 ZM6.499,0.591 C3.236,0.591 0.591,3.236 0.591,6.499 C0.591,9.762 3.236,12.407 6.499,12.407 C9.763,12.407 12.407,9.762 12.407,6.499 C12.407,3.236 9.763,0.591 6.499,0.591 ZM7.281,6.595 C7.004,6.864 6.825,7.091 6.742,7.277 C6.660,7.463 6.619,7.738 6.619,8.101 L5.848,8.101 C5.848,7.689 5.897,7.356 5.995,7.104 C6.094,6.851 6.308,6.562 6.640,6.236 L6.986,5.894 C7.090,5.796 7.173,5.693 7.237,5.586 C7.352,5.398 7.410,5.204 7.410,5.002 C7.410,4.720 7.326,4.475 7.157,4.267 C6.989,4.059 6.709,3.955 6.320,3.955 C5.838,3.955 5.505,4.134 5.320,4.491 C5.217,4.690 5.157,4.978 5.143,5.352 L4.373,5.352 C4.373,4.729 4.549,4.228 4.901,3.849 C5.253,3.470 5.736,3.280 6.351,3.280 C6.919,3.280 7.374,3.442 7.716,3.767 C8.058,4.091 8.229,4.507 8.229,5.011 C8.229,5.317 8.165,5.565 8.040,5.755 C7.915,5.946 7.662,6.225 7.281,6.595 ZM4.901,3.849 C4.850,3.904 4.817,3.939 4.792,3.966 C4.838,3.916 4.901,3.849 4.901,3.849 ZM6.692,9.719 L5.831,9.719 L5.831,8.819 L6.692,8.819 L6.692,9.719 Z"></path>
                                </svg>
                            </span>
                        </a>
                    </span>
                </div>
                <? if ($freeDelivery): ?>
                <div class="freeDelivery order_delivery_text">
                    <a href="information/free_delivery" target="_blank" onclick="ym(55633720, 'reachGoal', 'dostavka_po_predoplate'); return true;">
                        Получить бесплатную доставку
                        <span class="order_question">
                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="13px" height="13px">
                                <path fill-rule="evenodd" fill="rgb(34, 35, 43)" d="M6.499,12.998 C2.910,12.998 0.001,10.088 0.001,6.499 C0.001,2.910 2.910,0.000 6.499,0.000 C10.089,0.000 12.998,2.910 12.998,6.499 C12.998,10.088 10.089,12.998 6.499,12.998 ZM6.499,0.591 C3.236,0.591 0.591,3.236 0.591,6.499 C0.591,9.762 3.236,12.407 6.499,12.407 C9.763,12.407 12.407,9.762 12.407,6.499 C12.407,3.236 9.763,0.591 6.499,0.591 ZM7.281,6.595 C7.004,6.864 6.825,7.091 6.742,7.277 C6.660,7.463 6.619,7.738 6.619,8.101 L5.848,8.101 C5.848,7.689 5.897,7.356 5.995,7.104 C6.094,6.851 6.308,6.562 6.640,6.236 L6.986,5.894 C7.090,5.796 7.173,5.693 7.237,5.586 C7.352,5.398 7.410,5.204 7.410,5.002 C7.410,4.720 7.326,4.475 7.157,4.267 C6.989,4.059 6.709,3.955 6.320,3.955 C5.838,3.955 5.505,4.134 5.320,4.491 C5.217,4.690 5.157,4.978 5.143,5.352 L4.373,5.352 C4.373,4.729 4.549,4.228 4.901,3.849 C5.253,3.470 5.736,3.280 6.351,3.280 C6.919,3.280 7.374,3.442 7.716,3.767 C8.058,4.091 8.229,4.507 8.229,5.011 C8.229,5.317 8.165,5.565 8.040,5.755 C7.915,5.946 7.662,6.225 7.281,6.595 ZM4.901,3.849 C4.850,3.904 4.817,3.939 4.792,3.966 C4.838,3.916 4.901,3.849 4.901,3.849 ZM6.692,9.719 L5.831,9.719 L5.831,8.819 L6.692,8.819 L6.692,9.719 Z"></path>
                            </svg>
                        </span>
                    </a>
                </div>
                <? endif; ?>
                <div style="border-radius:3px;width: 216px;  margin-bottom: 12px;" class="order_btn input-group-append">
                    <a class="btn_link" href="/order">
                        <button class="btn btn-outline-secondary btn-check-cart" type="button" id="button-addon2">
                            ОФОРМИТЬ ЗАКАЗ
                        </button>
                    </a>
                </div>
                <div style="font-weight: lighter;">
                    и принять
                    <a href="/oferta" target="_blank" style="color: #00b7ff">
                        договор оферты
                    </a>
                </div>
            </div
        </div>
        <? endif; ?>
    </div>
</section>


<?php include ROOT . '/views/layouts/footer.php'; ?>


