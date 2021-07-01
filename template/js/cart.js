"use strict";

/**
 * @author <Enj Digital> [<enjseo@yandex.ru>]
 *
 * @class Cart
 * @classdesc Класс для работы с корзиной
 */
class CartService {

    constructor() { }


    static cart = [];
    static cartUrl = '/cart';
    static counterSelector = '';
    static priceProductFormattedSelector = '';
    static priceProductSelector = '';
    static cartCountProducts = 0;
    static cartSumOrder = '';
    static cartBonus = '';
    static cartCheckFreeDelivery = false;

    static Events = {
        getAllProducts: function (products) {
            return false;
        },

        addProductToCart: function (product) {
            return false;
        },

        editProductFromCart: function (product) {
            return false;
        },

        removeProductFromCart: function (product) {
            return false;
        },
        checkPromocodeInCart: function (promocode) {
            return false;
        },
    };


    static _errorHandler(jqXHR, exception) {
        let msg = "";

        if (jqXHR.status === 0) {
            msg = "Not connect.\n Verify Network.";
        } else if (jqXHR.status === 404) {
            msg = "Requested page not found. [404]";
        } else if (jqXHR.status === 500) {
            msg = "Internal Server Error [500].";
        } else if (exception === "parsererror") {
            msg = "Requested JSON parse failed.";
        } else if (exception === "timeout") {
            msg = "Time out error.";
        } else if (exception === "abort") {
            msg = "Ajax request aborted.";
        } else {
            msg = "Uncaught Error.\n" + jqXHR.responseText;
        }

        const error = new Error();

        error.name = exception;
        error.message = msg;

        throw error;
    }

    static _getProductToArray(product_id, _products = CartService._basket) {
        const idx = _products.findIndex(product => product.id === +product_id);

        if (idx !== -1) {
            return idx;
        } else {
            return false;
        }
    }

    static _updateCountProductFromCart() {
        if (this.counterSelector.length !== 0) {
            document.querySelector(this.counterSelector).innerHTML = this._basket.length || 0;
            return true;
        }
        return false;
    }


     static _updateOptionsCartPage() {

        if (window.location.pathname !== this.cartUrl) {
            return false;
        }

        const self = this;
        const request = $.ajax({
            method: "GET",
            url: "/cart/get/options",
            async: false,
            dataType: "json",
        });

        request.done(function(options) {
            if (self.cartCheckFreeDelivery.length !== 0) {
                const element = document.querySelector(self.cartCheckFreeDelivery);
                
                if (options.freeDelivery && !element || !options.freeDelivery && element) {
                    window.location.reload();
                    return false;
                }
            }

            if (self.cartSumOrder.length !== 0) {
                document.querySelector(self.cartSumOrder).innerHTML = options.summaryOrder + " P";
            }

            if (self.cartCountProducts.length !== 0) {
                document.querySelector(self.cartCountProducts).innerHTML = options.countProduct;
            }

            if (self.cartBonus.length !== 0) {
                document.querySelector(self.cartBonus).innerHTML = options.bonus;
            }

            
            
        });

        request.fail(self._errorHandler);

        return true;
    }


     static getAllProducts() {
        const self = this;
        const request = $.ajax({
            method: "GET",
            url: "/cart/get/products",
            dataType: "json",
        });

        request.done(function(products) {
            self._basket = products;
            self.Events.getAllProducts(self._basket);

            return true;
        });

        request.fail(self._errorHandler);
    }



    static addProductToCart(id_product, count) {
        const self = this;
        const request = $.ajax({
            method: "POST",
            url: "/cart/product/add",
            dataType: "json",
            data: {
                count_product: count,
                id_product: id_product,
            },
        });

        request.done(function(product) {
            self._basket.push(product);
            self.Events.addProductToCart(product);

            self._updateCounterCart();
            self._updateOptionsCart();
        });

        request.fail(self._errorHandler);

        return true;
    }

    static removeProductFromCart(id_product) {
        const self = this;
        const request = $.ajax({
            method: "POST",
            url: "/cart/product/remove",
            async: false,
            dataType: "json",
            data: {
                id_product: id_product,
            },
        });

        request.done(function () {
            const idxCandidate = self._getProductToArray(id_product, self._basket);

            if (idxCandidate !== false) {
                const product = self._basket[idxCandidate];

                self._basket.splice(idxCandidate, 1);
                self.Events.removeProductFromCart(product);

                if (self._basket.length === 0) {
                    return window.location.reload();
                }

                self._updateCounterCart();
                self._updateOptionsCart();
            }
        });

        request.fail(self._errorHandler);

        return true;
    }

    static checkPromocodeInCart(promocode) {
        const self = this;
        const request = $.ajax({
            method: "POST",
            url: "/cart/promocode/checking",
            dataType: "json",
            data: {
                promocode: promocode,
            },
        });

        request.done(function (data) {
            self.Events.checkPromocodeInCart(data.promocode);
        });

        request.fail(self._errorHandler);

        return true;
    }
}


document.addEventListener('DOMContentLoaded', function () {

    cartService.Events.getAllProducts = function (products) {
        return true;
    };

    cartService.Events.addProductToCart = function (product) {
        dataLayer.push({
            "ecommerce": {
                "add": {
                    "products": [product]
                }
            }
        });
        return true;
    };

    cartService.Events.removeProductFromCart = function (product) {
        dataLayer.push({
            "ecommerce": {
                "remove": {
                    "products": [product]
                }
            }
        });

        return true;
    };

    cartService.Events.editProductFromCart = function (product) {
        return true;
    };

    cartService.Events.checkPromocodeInCart = function (promocode) {
        return true;
    };


    $('.container').on('click', '.btn_basket, .productView_btnBasket', function () {
        const count_product = 1;
        const id_product = $(this).attr('data-id-product');

        if (id_product > 0) {
            if (!$(this).hasClass('active')) {
                if (cartService.addProductToCart(id_product, count_product)) {
                    $(this).html('<div class="btn_basket-text">В корзине</div>');
                    $(this).addClass('active');
                }
            } else {
                if (cartService.removeProductFromCart(id_product)) {
                    $(this).html('<div class="btn_basket-text">В корзину</div>');
                    $(this).removeClass('active');
                }
            }
        }
    });


    $('.container').on('click', '.one-click', function () {
        const count_product = 1;
        const id_product = $(this).attr('data-id-product');

        if (id_product > 0 && cartService.addProductToCart(id_product, count_product)) {
            window.location.replace('/cart');
        }
    });


    $('.minus, .plus').click(function () {
        const id_product = $(this).attr('data-id-product');
        const $input = $(this).parent().find('input');

        let count = parseInt($input.val());
        

        if ($(this).hasClass('minus')) {
            count = count - 1;
        }

        if ($(this).hasClass('plus')) {
            count = count + 1;
        }

        count = count < 1 ? 1 : count;
        $input.val(count);
        $input.change();


        cartService.editProductFromCart(id_product, count);

        if (cartService.priceProductSelector.length !== 0 && cartService.priceProductFormattedSelector.length !== 0 ) {
            const price_formatted_instance = $('[data-product-card-id="'+ id_product +'"]').find(cartService.priceProductFormattedSelector);
            let price = $('[data-product-card-id="'+ id_product +'"]').find(cartService.priceProductSelector).text();

            price = price.replace(/\s/g, '');
            price = cartService.calcFormattedPrice(+count, +price);

            price_formatted_instance.text(price);
            
        }

        return false;
    });


    $('.page-content').on('click', '.cart_delete', function () {
        const id_product = $(this).attr('data-id-product');

        if (cartService.removeProductFromCart(id_product)) {
            $('[data-product-card-id="'+ id_product +'"]').remove();
        }
    });


    $('.input-group-append').on('click', '.btn-check-cart', function (e) {
        e.preventDefault();
        const promocode = $('#promocode').val();

        if (promocode.length !== 0) {
            cartService.checkPromocodeInCart(promocode);
        }

        window.location.replace('/order');
    });
});