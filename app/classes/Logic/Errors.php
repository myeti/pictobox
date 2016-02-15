<?php

namespace App\Logic;

use Colorium\App\Context;
use Colorium\Http\Response;

class Errors
{

    /**
     * 404
     *
     * @html errors/notfound
     */
    public function notfound() {}


    /**
     * 401
     *
     * @param Context $ctx
     * @return Response\Redirect
     */
    public function unauthorized(Context $ctx)
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