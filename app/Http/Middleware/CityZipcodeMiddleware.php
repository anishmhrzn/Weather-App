<?php

namespace App\Http\Middleware;

use App\Models\City;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CityZipcodeMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure(Request): (Response) $next
     * @return Response
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!$request->anyFilled(['city', 'zipCode'])) {
            return response()->json(['error' => 'Either city name or zipCode is required.'], 400);
        }

        if ($request->filled('city')) {
            $city = City::where('name', $request->input('city'))->first();
            if (!$city) {
                return response()->json(['error' => 'Provided city does not lie within Nepal.'], 404);
            }
        } else {
            $zipCode = $request->input('zipCode');
            $city = City::where('postal_code', $zipCode)->first();
            if (!$city) {
                return response()->json(['error' => 'Provided zipcode does not lie within Nepal.'], 404);
            }
        }
        $request->merge(['search_param' => $city->postal_code . ' NP']);

        return $next($request);
    }
}
