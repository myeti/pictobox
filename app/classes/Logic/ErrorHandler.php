<?php

namespace Pictobox\Logic;

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
     * @return array
     */
    public function notFound(NotFoundException $event)
    {
        return ['message' => $event->getMessage()];
    }


    /**
     * 401
     *
     * @html errors/accessdenied
     *
     * @param AccessDeniedException $event
     * @param Context $ctx
     * @return Response\Redirect
     */
    public function accessDenied(AccessDeniedException $event, Context $ctx)
    {
        // not logged in
        if(!$ctx->user) {
            $path = $ctx->request->uri->path;
            $from = ($path and $path != '/') ? '?from=' . $path : null;
            return Response::redirect('/login' . $from);
        }
    }


    /**
     * 503
     *
     * @html errors/maintenance
     *
     * @param ServiceUnavailableException $event
     * @return Response\Redirect
     */
    public function maintenance(ServiceUnavailableException $event) {}


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