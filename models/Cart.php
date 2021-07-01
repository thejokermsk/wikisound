<?php
require_once '../types/CartProductType.php';


/**
 * Class Cart
 *
 */
class Cart
{
    /**
     * function getCart
     *
     * Метод получения списка товаров в корзине
     *
     * @static
     * @return CartProduct[]
     */
    public static function getCart(): array
    {
        return $_SESSION['cart'] ?? [];
    }

    /**
     * function updateCart
     *
     * Метод для обновления корзины
     *
     * @static
     * @param CartProduct[] $cart
     * @return void
     */
    public static function updateCart(array $cart): void
    {
        $_SESSION['cart'] = $cart;
    }

    /**
     * function clearCart
     *
     * Метод ощищает содержимое корзины
     */
    public static function clearCart(): void
    {
          unset($_SESSION['cart']);
    }

    /**
     * function getProduct
     *
     * Метод возвращает товар в корзине по id
     *
     * @static
     * @param int $id_product
     * @return CartProduct|false
     */
    public static function getProduct(int $id_product): CartProduct {

        $cart = self::getCart();

        if (!isset($id_product) && $id_product < 1 && array_key_exists($id_product, $cart)) {
            return false;
        }

        return $cart[$id_product];
    }

    /**
     * function addProduct
     *
     * Метод добавляет товар в корзину или обновляет его количество
     *
     * @param int $id_product
     * @param int $count_product
     * @return CartProduct|false
     */
    public static function addProduct(int $id_product, int $count_product): CartProduct
    {
        if (!Product::chekProductById($id_product) && $count_product < 1) {
            return false;
        }

        $cart = self::getCart();

        if (empty($cart[$id_product])) {
            $cartProduct = new CartProduct();
            $product = Product::getInfoProductById($id_product);

            $cartProduct->id = $id_product;
            $cartProduct->name = (string)$product['name'];
            $cartProduct->price = (int)$product['price'];
            $cartProduct->count = $count_product;

            $cart[$id_product] = $cartProduct;

            self::updateCart($cart);
            return $cartProduct;
        }

        $cart[$id_product]->count = $count_product;


        $db = Db::getConnection();
        $sql = "INSERT INTO events SET type='added_to_cart', created_at=CURDATE(), product_id=:product_id";
        $result = $db->prepare($sql);
        $result->bindParam(':product_id', $id_product, PDO::PARAM_INT);
        $result->execute();

        self::updateCart($cart);
        return $cart[$id_product];
    }

    /**
     * function removeProduct
     *
     * Метод удаляет товар из корзины
     *
     * @param int $id_product
     * @return bool
     */
    public static function removeProduct(int $id_product): bool
    {
        $cart = self::getCart();

        if (self::getProduct($id_product)) {
            unset($cart[$id_product]);

            self::updateCart($cart);
            return true;
        }

        return false;
    }

    /**
     * function getSummaryOrder
     *
     * Метод возвращает сумму товаров в корзине
     *
     * @return int
     */
    public static function getSummaryOrder(): int
    {
        $cart = self::getCart();
        $sumOrder = 0;

        if (!empty($cart)) {
            foreach ($cart as $cartProduct) {
                $sumOrder += $cartProduct->price * $cartProduct->count;
            }
        }
        return $sumOrder;
    }

    /**
     * function getBonusCart
     *
     * Метод для получение количество бонусов
     *
     * @return array|false
     */
    public static function getBonusCart(): array
    {
        $course = [];
        $cart = self::getCart();

        if (empty($cart)) {
            return false;
        }

        $products = Product::getArrayProductList($cart);
        $SITE = Setting::getSettingSite();

        $countCourse = 0;
        $totalCourse = 0;

        $count_wk_kurs = 0;
        $count_other_kurs = 0;

        foreach ($products['product'] as $product) {
            $priceProduct = (int)$product['discount_price'] > 0 ? $product['discount_price'] : $product['price'];

            for ($num = 0; $num < $cart[$product['id']]->count; $num++) {
                if ($product['promocode_on'] == 0) {
                    $countCourse = Bonus::GetCountBonusForProduct($product['id']);
                    $countCourse = $countCourse['id'];
                } else {
                    if ($priceProduct >= $SITE['two_kurs_price']) {
                        $countCourse = 2;
                    } else if ($priceProduct >= $SITE['one_kurs_price']) {
                        $countCourse = 1;
                    } else {
                        $countCourse = 0;
                    }
                }

                $totalCourse += $countCourse;
                if ($countCourse == 1) {
                    $count_wk_kurs += 1;
                    $count_other_kurs += 0;
                } else if ($countCourse == 2) {
                    $count_wk_kurs += 2;
                    $count_other_kurs += 0;
                } else if ($countCourse == 3) {
                    $count_wk_kurs += 2;
                    $count_other_kurs += 1;
                } else if ($countCourse == 4) {
                    $count_wk_kurs += 2;
                    $count_other_kurs += 2;
                }
            }
        }

        $course['text'] = 'Подарочных видеокурсов (' . $totalCourse . ' шт)';
        $course['count'] = $totalCourse;
        $course['count_wk_kurs'] = $count_wk_kurs;
        $course['count_other_kurs'] = $count_other_kurs;

        return $course;
    }

    /**
     * function checkFreeDelivery
     *
     * Метод проверки бесплатной доставки
     *
     * @return bool
     */
    public static function checkFreeDelivery(): bool
    {
        $cart = self::getCart();

        if (empty($cart)) {
            return false;
        }

        $products = Product::getArrayProductList($cart);
        $checkFreeDelivery = false;

        foreach ($products['product'] as $product) {
            $bonus = base64_decode($product['bonus']);
            $bonus = unserialize($bonus);

            if (!empty($bonus) and in_array("5", $bonus)) {
                $checkFreeDelivery = true;
                break;
            }
        }

        return $checkFreeDelivery;
    }

    /**
     * function checkPromocodeInCart
     *
     * Метод проверяет промокод на существование и применяет цену со скидкой к товару
     *
     * @param string $promocode
     * @return CartProduct[]|false
     */
    public static function checkPromocodeInCart($promocode)
    {
        $cart = self::getCart();

        if (empty($promocode) or empty($cart)) {
            return false;
        }

        $stringId = implode(',', array_keys($cart));

        $db = Db::getConnection();
        $result = $db->query('SELECT id,promocode,promo_price,promocode_on FROM product WHERE id IN(' . $stringId . ') ');

        while ($row = $result->fetch()) {
            if ($row['promocode_on'] == 0) {
                continue;
            }

            if (mb_strtolower($row['promocode']) == $promocode) {
                $cart[$row['id']]->promoPrice = $row['promoPrice'];
            }
        }

        self::updateCart($cart);
        return $cart;
    }

}