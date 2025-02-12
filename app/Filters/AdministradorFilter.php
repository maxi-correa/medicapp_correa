<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class AdministradorFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        if (session('rol') !== 'Admin. Medicina Laboral') {
            return redirect()->to('error');
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        //
    }
}
