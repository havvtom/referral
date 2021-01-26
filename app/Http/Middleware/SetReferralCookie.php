<?php

namespace App\Http\Middleware;

use App\Models\Referral;
use Closure;
use Illuminate\Http\Request;

class SetReferralCookie
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $referral = Referral::whereToken($request->referral)
                                ->whereNotCompleted()
                                ->whereNotFromUser($request->user())
                                ->first();
        if($request->referral && !$referral){
            return response()->json([
                'data' => [
                    'errors' => 'Could not sign you up with that token.'
                ]
            ], 422);
        }

        return $next($request);
    }
}
