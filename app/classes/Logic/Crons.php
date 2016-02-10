<?php

namespace App\Logic;

use App\Model\Album;
use App\Model\User;
use App\Service\Mail;
use Colorium\App\Context;
use Colorium\Http\Response;

class Crons
{

    /**
     * Generate albums cache
     *
     * @access 9
     * @html crons/cache
     */
    public function cache()
    {
        $files = [];
        $albums = Album::fetch();
        foreach($albums as $album) {
            foreach($album->authors() as $author) {
                foreach($author->pics() as $pic) {
                    $files[] = $pic->path;
                }
            }
        }

        return ['files' => $files];
    }


    /**
     * Generate albums cache
     *
     * @access 9
     * @json
     *
     * @param Context $self
     * @return bool
     */
    public function cacheclear(Context $self)
    {
        set_time_limit(300);

        $file = $self->request->value('file');
        $author = dirname($file);
        $album = dirname($author);

        $album = new Album($album);
        $author = $album->author(basename($author));
        $pic = $author->pic(basename($file));

        return $pic->cache();
    }


    /**
     * Email newest albums
     *
     * @param Context $self
     * @return int
     */
    public function newest(Context $self)
    {
        $yesterday = strtotime('-1 day');
        $newest = [];

        $albums = Album::fetch();
        foreach($albums as $album) {
            foreach($album->authors() as $author) {
                foreach($author->pics() as $pic) {
                    if($pic->ctime() > $yesterday) {
                        $newest[] = $album;
                        break 2;
                    }
                }
            }
        }

        if($newest) {

            $html = null;
            $users = User::fetch();
            foreach ($users as $user) {
                $email = new Mail(APP_NAME . ' - Nouveaux albums !');
                $email->content = $self->templater->render('emails/new-albums', [
                    'user' => $user,
                    'albums' => $albums
                ]);
                if($user->email == ADMIN_EMAIL) {
                    $html = $email->content;
                }
                $email->send($user->email);
            }

            return Response::html($html);
        }
    }

}