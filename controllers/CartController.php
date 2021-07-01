<?php
require_once 'Controller.php';
require_once '../types/CartProductType.php';

/**
 * Class CartController
 *
 * @property CartProduct $cartProduct
 */
class CartController extends Controller
{
    /** URL: [GET] /cart */
    public function actionIndex(): void
    {
        $CATEGORY = Category::getCategoryAndSubCat();
        $CART = Cart::getCart();

        $countCart = count($CART);

        if (!empty($CART)) {
            $summaryOrder = number_format(Cart::getSummaryOrder(), 0, '', ' ');
            $bonus = Cart::getBonusCart();
            $freeDelivery = Cart::checkFreeDelivery();
        }

        include(ROOT . '/views/cart/index.php');
        exit();
    }

    /** URL: [GET] /cart/get/options */
    public function actionGetOptionsCart(): void
    {
        self::checkMethod();

        $cart = Cart::getCart();

        $data = [
            "countProduct" => count($cart),
            "summaryOrder" => Cart::getSummaryOrder(),
            "bonus" => Cart::getBonusCart(),
            "freeDelivery" => Cart::checkFreeDelivery(),
        ];

        self::sendRequest($data);
    }

    /** URL: [GET] /cart/get/products */
    public function actionGetAllProductFromCart(): void
    {
        self::checkMethod();
        self::sendRequest(Cart::getCart());
    }

    /** URL: [POST] /cart/product/add */
    public function actionProductAdd(): void
    {
        self::checkMethod('post');

        $id_product = $_POST['id_product'];
        $count_product = $_POST['count_product'];

        $cartProduct = Cart::addProduct($id_product, $count_product);

        if ($cartProduct) {
            self::sendRequest([$cartProduct]);
        }

        self::sendRequest([
            "status" => false,
            "message" => "Товара с таким id не существует"
        ], 404);
    }

    /** URL: [POST] /cart/product/remove */
    public function actionProductRemove(): void
    {
        self::checkMethod('post');

        $id_product = $_POST['id_product'];

        if (Cart::removeProduct($id_product)) {
            self::sendRequest([
                "status" => true,
            ]);
        }

        self::sendRequest([
            "status" => false,
            "message" => "Товара с таким id не существует"
        ], 404);
    }

    /** URL: [POST] /cart/promocode/check */
    public function actionPromocodeChecking(): void
    {
        self::checkMethod('post');

        $cart = Cart::getCart();
        $promocode = $_POST['promocode'];

        if (empty($promocode) or empty($cart)) {
            self::sendRequest([
                "status" => false,
                "message" => "Промокод не найден"
            ], 404);
        }

        $_SESSION['promocode'] = $promocode;

        $string = explode(' ', trim($promocode));
        $promocode = $string[0];

        $promocode = strip_tags($promocode);
        $promocode = htmlspecialchars($promocode);
        $promocode = mb_strtolower($promocode);

        Cart::checkPromocodeInCart($promocode);

        self::sendRequest([
            "promocode" => $promocode
        ]);
    }

    /** URL: [GET] /cart/clear */
    public function actionClear(): void
    {
        self::checkMethod('post');

        Cart::clearCart();

        self::sendRequest(["status" => true]);
    }

}
