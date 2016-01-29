<!DOCTYPE html>
<html>
<head>
    <title><?= APP_NAME ?></title>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="<?= self::url('/css/bootstrap.min.css') ?>" rel="stylesheet">
    <link href="<?= self::url('/css/common.css') ?>" rel="stylesheet">
    <link href="<?= self::url('/css/private.css') ?>" rel="stylesheet">
</head>
<body>

    <div id="modals">

        <div class="modal" id="user-details">
            <form action="<?= self::url('/login/edit') ?>" method="post" data-ajax>

                <h4><?= $user->username ?></h4>

                <?php if($user->rank >= 9): ?>
                <label for="user-id">Identifiant</label>
                <select name="id" id="user-id">
                    <?php foreach(\App\Model\User::fetch() as $other): ?>
                        <option value="<?= $other->id ?>" data-email="<?= $other->email ?>" <?= $other->id == $user->id ? 'selected' : null ?> >
                            <?= $other->username ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <?php endif; ?>

                <label for="user-email">Adresse email</label>
                <input type="email" id="user-email" name="email" value="<?= $self->access->user->email ?>">

                <label for="user-pwd">Mot de passe</label>
                <input type="password" id="user-pwd" name="password">

                <button type="submit" data-loading="glyphicon-cog" data-ok="glyphicon-ok">Sauvegarder</button>
                <button type="reset" class="cancel">Fermer</button>

            </form>
        </div>

        <?php if($album): ?>
        <div class="modal" id="upload-pics">
            <form action="<?= self::url($album->url, 'upload') ?>" method="post">
                <h4>Ajouter des photos</h4>
                <button type="reset" class="cancel">Fermer</button>
            </form>
        </div>
        <?php else: ?>
        <div class="modal" id="create-album">
            <form action="<?= self::url('/create') ?>" method="post" data-ajax>

                <h4>Nouvel album</h4>

                <label for="album-name">Titre</label>
                <input type="text" id="album-name" name="name" required>

                <label for="album-date">Date</label>
                <input type="text" id="album-date" name="date" placeholder="<?= date('d/m/Y') ?>" title="jj/mm/aaaa"
                       pattern="(0?[1-9]|1[0-9]|2[0-9]|3[01])/(0?[1-9]|1[012])/(19|20)[0-9]{2}" required>

                <button type="submit" data-loading="glyphicon-cog" data-ok="glyphicon-ok">Créer</button>
                <button type="reset" class="cancel">Fermer</button>

            </form>
        </div>
        <?php endif; ?>

    </div>

    <header>

        <nav>
            <a href="<?= self::url('/') ?>" id="logo"><?= strtoupper(APP_NAME) ?></a> <span>/</span>

            <?php foreach($ariane as $text => $link): ?>
            <a href="<?= self::url($link) ?>"><?= $text ?></a>
            <?php endforeach; ?>

            <?php if($ariane and $album): ?>
            <span>/</span> <a href="<?= self::url($album->url) ?>" class="album"><?= $album->name ?></a>
            <?php endif; ?>
        </nav>

    </header>

    <menu>

        <span class="switch glyphicon glyphicon-menu-hamburger"></span>

        <ul>
            <li>
                <a data-modal="#user-details" href="#">
                    <span class="glyphicon glyphicon-user" title="Modifier mes informations"></span>
                </a>
            </li>
            <?php if($album): ?>
            <li>
                <a data-modal="#upload-pics" href="#">
                    <span class="glyphicon glyphicon-open" title="Ajouter des photos"></span>
                </a>
            </li>
            <li>
                <a href="<?= self::url($album->url, 'download') ?>" title="Télécharger l'album">
                    <span class="glyphicon glyphicon-save"></span>
                </a>
            </li>
            <?php else: ?>
            <li>
                <a data-modal="#create-album" href="#">
                    <span class="glyphicon glyphicon-plus-sign" title="Créer un nouvel album"></span>
                </a>
            </li>
            <?php endif; ?>
        </ul>

    </menu>

    <main>
        <?= self::content(); ?>
    </main>

    <script src="<?= self::url('/js/jquery-2.2.0.min.js') ?>"></script>
    <script src="<?= self::url('/js/common.js') ?>"></script>
    <script src="<?= self::url('/js/private.js') ?>"></script>

</body>
</html