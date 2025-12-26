<?php

namespace App\Http\Middleware;

use App\Models\Merchant;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ResolveMerchant
{
    public function handle(Request $request, Closure $next): Response
    {
        $merchantId = $request->header('X-Merchant-Id');

        if (!$merchantId) {
            return response()->json([
                'message' => 'X-Merchant-Id header is required',
            ], 400);
        }

        $merchant = Merchant::find($merchantId);

        if (!$merchant) {
            return response()->json([
                'message' => 'Invalid merchant',
            ], 404);
        }

        // Attach merchant to request (clean & explicit)
        $request->attributes->set('merchant', $merchant);

        return $next($request);
    }
}
