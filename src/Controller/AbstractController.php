<?php

declare(strict_types=1);

namespace Controller;

class AbstractController
{
    protected function get(string $key, mixed $default = null): mixed
    {
        return $_GET[$key] ?? $_POST[$key] ?? $default;
    }

    /**
     * use to get Json
     */
    protected function getContent(): false|string
    {
        return file_get_contents('php://input');
    }

    protected function sendView(string $viewName, int $statusCode = 200): void
    {
        $viewFile = dirname(__DIR__) . "/View/$viewName.php";

        ob_start();
        require $viewFile;
        $result = ob_get_clean();
        ob_end_clean();

        $this->send($result, $statusCode, ['Content-Type: text/html']);
    }

    protected function sendJson(array $content = [], int $statusCode = 200): void
    {
        $this->send(json_encode($content), $statusCode, ['Content-Type: application/json']);
    }

    protected function getViewContent(string $view): string
    {
        ob_start();
        require dirname(__DIR__) . "/View/$view.php";
        $content = ob_get_contents();
        ob_end_clean();

        return $content;
    }

    private function send(string $content, $statusCode, array $headers): void
    {
        http_response_code($statusCode);
        foreach ($headers as $header)
        {
            header($header);
        }

        echo $content;
    }
}