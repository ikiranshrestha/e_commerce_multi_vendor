<?php

namespace App\Http\Controllers;

use App\Support\ApiResponse;

abstract class BaseController extends Controller
{
    protected function success($data = null, string $message = 'Success', int $code = 200)
    {
        return ApiResponse::success($data, $message, $code);
    }

    protected function error(string $message, int $code = 400, $errors = null)
    {
        return ApiResponse::error($message, $code, $errors);
    }

    protected function paginate($collection, string $message = 'Success')
    {
        return ApiResponse::success([
            'items'      => $collection->items(),
            'pagination' => [
                'total'        => $collection->total(),
                'per_page'     => $collection->perPage(),
                'current_page' => $collection->currentPage(),
                'last_page'    => $collection->lastPage(),
            ]
        ], $message);
    }

    protected function authorizeAction($ability, $arguments = [])
    {
        $this->authorize($ability, $arguments);
    }
}
