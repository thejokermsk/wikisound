<?php


/**
 * Class Controller
 *
 * Базовый класс который содержит общие методы для контроллеров
 *
 * @name Controller
 *
 * @author Enj Digital <enjseo@yandex.ru>
 */
class Controller
{
    /**
     * @static
     * @param array $data - Данные для отправки клиенту
     * @param int $statusCode - Статус код ответа сервера
     */
    protected static function sendRequest(array $data, int $statusCode = 200): void
    {
        header('Content-Type: application/json');
        http_response_code($statusCode);
        echo json_encode($data);
        exit();
    }


    /**
     * Метод позволяет обработать запрос только с определенным методом, методы запроса указывать через запятую
     *
     * @static
     * @param string $methods - Метод запроса
     */
    protected static function checkMethod(string $methods = 'get'): void
    {
        $requestMethod = $_SERVER['REQUEST_METHOD'];

        $methods = mb_strtoupper($methods);
        $methods = explode(',', $methods);
        $methods = array_map('trim', $methods);

        if (!in_array($requestMethod, $methods)) {
            http_response_code(405);
            exit();
        }
    }


}