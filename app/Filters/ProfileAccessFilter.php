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

        if ($request->getMethod() === 'post') {
            $userId = (int) $request->getPost('userId');
        }

        if (!$userId || ($currentUser->id != $userId && !$currentUser->inGroup('admin'))) {
            if ($request->getMethod() === 'post') {
                return redirect()->to('/profile/' . $currentUser->id)->with('error', 'Доступ запрещен.');
            }

            throw new Exception("У вас нет прав для просмотра этой страницы", 403);
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Ничего не делаем после запроса
    }
}