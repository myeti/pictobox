<?php foreach($states as $file => $state): ?>
<div style="color: <?= $state ? 'red' : 'green' ?>"><?= $file ?> <?= $state ? '. ' . $state : null ?></div>
<?php endforeach; ?>
