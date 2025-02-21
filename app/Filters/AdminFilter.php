<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RedirectResponse;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\HTTP\Exceptions\HTTPException;
use Exception;

class AdminFilter implements FilterInterface
{
    /**
     * @param RequestInterface $request
     * @param $arguments
     * @return RedirectResponse|RequestInterface|ResponseInterface|string|void|null
     */
    public function before(RequestInterface $request, $arguments = null)
    {
        $user = auth()->user();

        if (!$user || !$user->inGroup('admin')) {
            throw new Exception("Доступ запрещен", 403);
        }
    }

    /**
     * @param RequestInterface $request
     * @param ResponseInterface $response
     * @param $arguments
     * @return void
     */
    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {

    }
}