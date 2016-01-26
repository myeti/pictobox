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

        <menu>

            <span class="switch glyphicon glyphicon-menu-hamburger"></span>

            <ul>
                <li>
                    <div class="title"><?= $self->user->username ?></div>
                </li>
                <li>
                    <div class="form">
                        <div class="header">
                            <span class="glyphicon glyphicon-user"></span> Modifier mes infos
                        </div>
                        <form action="<?= self::url('/login/edit') ?>" method="post">

                            <?php if($self->user->rank == 9): ?>
                            <label for="user-id">Identifiant</label>
                            <select name="id" id="user-id">
                                <?php foreach(\App\Model\User::fetch() as $user): ?>
                                    <option value="<?= $user->id ?>" <?= $self->user->id == $user->id ? 'selected' : null ?>><?= $user->username ?></option>
                                <?php endforeach; ?>
                            </select>
                            <?php endif; ?>

                            <label for="user-email">Adresse email</label>
                            <input type="email" id="user-email" name="email" value="<?= $self->user->email ?>">

                            <label for="user-pwd">Mot de passe</label>
                            <input type="password" id="user-pwd" name="password">

                            <button type="submit">Mettre à jour</button>

                        </form>
                    </div>
                </li>
                <?php if($album): ?>
                <li>
                    <div class="form">
                        <div class="header">
                            <span class="glyphicon glyphicon-open"></span> Ajouter des photos
                        </div>
                        <form action="<?= self::url($album->url, 'upload') ?>" method="post">
                             // todo
                        </form>
                    </div>
                </li>
                <li>
                    <a href="<?= self::url($album->url, 'download') ?>">
                        <span class="glyphicon glyphicon-save"></span> Télécharger l'album
                    </a>
                </li>
                <?php else: ?>
                <li>
                    <div class="form">
                        <div class="header">
                            <span class="glyphicon glyphicon-plus-sign"></span> Créer un nouvel album
                        </div>
                        <form action="<?= self::url('/new') ?>" method="post">

                            <label for="album-name">Titre</label>
                            <input type="text" id="album-name" name="name" required>

                            <label for="album-date">Date</label>
                            <input type="text" id="album-date" name="date" placeholder="<?= date('d/m/Y') ?>" title="jj/mm/aaaa"
                                   pattern="(0?[1-9]|1[0-9]|2[0-9]|3[01])/(0?[1-9]|1[012])/(19|20)[0-9]{2}" required>

                            <button type="submit">Créer</button>

                        </form>
                    </div>
                </li>
                <?php endif; ?>
            </ul>

        </menu>

    </header>

    <main>
        <?= self::content(); ?>
    </main>

    <script src="<?= self::url('/js/jquery-2.2.0.min.js') ?>"></script>
    <script src="<?= self::url('/js/private.js') ?>"></script>

</body>
</html