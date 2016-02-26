<?php

namespace App\Logic;

use Colorium\Http\Error\AccessDeniedException;
use Colorium\Http\Error\ServiceUnavailableException;
use Colorium\Web\Context;
use Colorium\Http\Response;
use Colorium\Http\Error\NotFoundException;

class ErrorHandler
{

    /**
     * 404
     *
     * @html errors/notfound
     *
     * @param NotFoundException $event
     * @param Context $ctx
     * @return array
     */
    public function notFound(NotFoundException $event, Context $ctx)
    {
        $ctx->response->code = $event->getCode();
        return ['message' => $event->getMessage()];
    }


    /**
     * 401
     *
     * @param AccessDeniedException $event
     * @param Context $ctx
     * @return Response\Redirect
     */
    public function accessDenied(AccessDeniedException $event, Context $ctx)
    {
        $path = $ctx->request->uri->path;
        $from = ($path and $path != '/') ? '?from=' . $path : null;

        return Response::redirect('/login' . $from);
    }


    /**
     * 503
     *
     * @html errors/maintenance
     *
     * @param ServiceUnavailableException $event
     * @param Context $ctx
     * @return Response\Redirect
     */
    public function maintenance(ServiceUnavailableException $event, Context $ctx)
    {
        $ctx->response->code = $event->getCode();
    }


    /**
     * Fatal error
     *
     * @html errors/fatal
     *
     * @param Context $ctx
     */
    public function fatal(Context $ctx)
    {
        $ctx->response->code = 500;
    }

}