<?php

namespace App\Logic;

use Colorium\Http\Error\AccessDeniedException;
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
     * @param AccessDeniedException $event
     * @param Context $ctx
     * @return Response\Redirect
     */
    public function accessDenied(AccessDeniedException $event, Context $ctx)
    {
        $from = $ctx->request->uri->path;
        if($from) {
            $from = '?from=' . $from;
        }

        return Response::redirect('/login' . $from);
    }


    /**
     * Fatal error
     *
     * @html errors/fatal
     */
    public function fatal() {}

}