<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Exception;

class ProfileAccessFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $currentUser = auth()->user();

        $uriSegments = $request->getUri()->getSegments();
        $userId = isset($uriSegments[2]) ? (int) $uriSegments[2] : (int) $uriSegments[1];

        if (!$userId || ($currentUser->id != $userId && !$currentUser->inGroup('admin'))) {
            throw new Exception("Доступ запрещен", 403);
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Ничего не делаем после запроса
    }
}