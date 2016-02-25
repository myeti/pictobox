<?php
namespace App\Logic;

use App\Model\Album;
use App\Model\User;
use App\Service\Mail;
use Colorium\Web\Context;

class CronTask
{

    /**
     * Email newest albums
     *
     * @param Context $ctx
     * @return int
     */
    public function lastAlbums(Context $ctx)
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
            $users = User::fetch();
            foreach ($users as $user) {
                $email = new Mail(APP_NAME . ' - ' . text('email.newest.title'));
                $email->content = $ctx->templater->render('emails/newest', [
                    'user' => $user,
                    'albums' => $newest
                ]);
                $email->send($user->email);
            }

            return count($newest) . ' new album(s), ' . count($users) . ' emails sent' . "\n";
        }

        echo 'No new album' . "\n";
    }

}