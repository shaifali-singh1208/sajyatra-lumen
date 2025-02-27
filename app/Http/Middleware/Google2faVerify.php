<?php

namespace App\Http\Middleware;

use App\Models\AdminSession;
use Carbon\Carbon;
use Closure;
use Illuminate\Support\Facades\Auth;

class Google2faVerify
{
    public function handle($request, Closure $next)
    {
        $aUser = Auth::guard('admin')->user();

        $session = AdminSession::where('user_id', $aUser->id)->where('session_token', session('admin_session_token'))->first();
        if (!$session) {
            Auth::guard('admin')->logout();
            return redirect()->route('admin.login_form')->with('error', 'Your session has expired.');
        }


        if (config('google2fa.enabled') == false) {
            return $next($request);
        }
        if ($aUser->google2fa_secret) {
            if (!$session->google2fa_ts) {
                return redirect()->route('admin.google-2fa-validate');
            }
            $now = Carbon::now();
            if ($now->gt(Carbon::create($session->google2fa_ts))) {
                return redirect()->route('admin.google-2fa-validate');
            }
            $session->google2fa_ts = Carbon::now()->addMinutes(15);
            $session->save();
        }
        return $next($request);
    }
}
