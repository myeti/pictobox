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
     * @param Context $self
     * @return \Colorium\Http\Response\Redirect
     */
    public function unauthorized(Context $self)
    {
        return Response::redirect('/login?from=' . $self->request->uri->path);
    }


    /**
     * Fatal error
     *
     * @html errors/fatal
     */
    public function fatal() {}

}