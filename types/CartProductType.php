<?php
/**
 * Class Product
 *
 * Класс для создания экземпляра продукта
 *
 * @property int $id
 * @property string $name
 * @property int $price
 * @property int $count
 */
class CartProduct {
    public $id;
    public $name;
    public $price;
    public $count;
    public $promoPrice = null;
}
