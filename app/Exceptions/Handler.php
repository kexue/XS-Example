<?php

namespace App\Exceptions;

use App\Libs\ModRes;
use Exception;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Laravel\Lumen\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        AuthorizationException::class,
        HttpException::class,
        ModelNotFoundException::class,
        ValidationException::class,
        NotFoundHttpException::class,
        MethodNotAllowedHttpException::class,
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception  $e
     * @return void
     */
    public function report(Exception $e)
    {
        parent::report($e);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $e
     * @return array|\Illuminate\Http\Response
     */
    public function render($request, Exception $e)
    {
        if ($e instanceof ValidationException) {
            return ModRes::retFailed(ModRes::CODE_SYSERR, $e->validator->errors()->first(), $e->getResponse()->original);
        } elseif ($e instanceof NotFoundHttpException) {
            return ModRes::retFailed(ModRes::CODE_NOTFOUND);
        } elseif ($e instanceof MethodNotAllowedHttpException) {
            return ModRes::retFailed(ModRes::CODE_METHOT_NOTALLOWED, $e->getMessage());
        } elseif ($e instanceof XunsearchException) {
            return ModRes::retFailed(ModRes::CODE_XS_ERROR, $e->getMessage());
        } elseif ($e instanceof \XSException) {
            return ModRes::retFailed(ModRes::CODE_XS_ERROR, $e->getMessage());
        }

        return ModRes::retFailed(ModRes::CODE_SYSERR, $e->getMessage());
        //return parent::render($request, $e);
    }
}
