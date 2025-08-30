<?php

namespace App\Filters;

use Config\Services;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class JWTFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $header = $request->getHeaderLine('Authorization');
        if (!$header || !preg_match('/Bearer\s(\S+)/', $header, $matches)) {
            return Services::response()->setJSON(['error' => 'Token tidak ada'])->setStatusCode(401);
        }

        $token = $matches[1];
        $decoded = validateJWT($token);

        if (!$decoded) {
            return Services::response()->setJSON(['error' => 'Token tidak valid'])->setStatusCode(401);
        }

        // simpan user ke request biar bisa dipakai di controller
        $request->user = $decoded;
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // tidak perlu
    }
}