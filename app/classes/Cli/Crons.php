<?php
namespace App\Cli;

use App\Model\Album;
use App\Model\User;
use App\Service\Mail;
use Colorium\App\Context;

class Crons
{

    /**
     * Email newest albums
     *
     * @param Context $ctx
     * @return int
     */
    public function newest(Context $ctx)
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

        return 'No new album' . "\n";
    }

}