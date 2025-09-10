<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Auth\Middleware\EnsureEmailIsVerified;

class ConditionalEmailVerification
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // التحقق من إعداد تفعيل البريد الإلكتروني
        $emailVerificationEnabled = env('EMAIL_VERIFICATION_ENABLED', true);

        // إذا كان تفعيل البريد الإلكتروني معطل، تجاهل التحقق
        if (!$emailVerificationEnabled) {
            return $next($request);
        }

        // إذا كان مفعل، استخدم middleware Laravel الافتراضي
        $ensureEmailIsVerified = new EnsureEmailIsVerified();
        return $ensureEmailIsVerified->handle($request, $next);
    }
}
