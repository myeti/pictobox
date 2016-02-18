<?php

namespace App\Logic;

use Colorium\Web\Context;
use Colorium\Http\Response;

class ErrorHandler
{

    /**
     * 404
     *
     * @html errors/notfound
     */
    public function notFound() {}


    /**
     * 401
     *
     * @param Context $ctx
     * @return Response\Redirect
     */
    public function accessDenied(Context $ctx)
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