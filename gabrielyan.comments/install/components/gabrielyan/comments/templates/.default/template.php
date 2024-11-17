<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
use Bitrix\Main\Localization\Loc;
$url = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
?>

<?php if ($arResult['ERROR']): ?>
    <div class="error"><?= htmlspecialcharsbx($arResult['ERROR']); ?></div>
<?php endif; ?>

<?php if ($arResult['SUCCESS']): ?>
    <div class="success"><?= htmlspecialcharsbx($arResult['SUCCESS']); ?></div>
<?php endif; ?>

<div class="comment-form">
    <form action="<?= POST_FORM_ACTION_URI ?>" method="POST" enctype="multipart/form-data">
        <?= bitrix_sessid_post() ?>

        <input type="hidden" name="page_hash" value="<?= $arResult['PAGE_HASH'] ?>">

        <label for="user_name"><?= Loc::getMessage('USER_NAME_LABEL') ?></label>
        <input type="text" name="user_name" id="user_name" value="фыавфымоеимя" required>

        <label for="user_last_name"><?= Loc::getMessage('USER_LAST_NAME_LABEL') ?></label>
        <input type="text" name="user_last_name" id="user_last_name" value="Это мое имя">

        <label for="user_email"><?= Loc::getMessage('USER_EMAIL_LABEL') ?></label>
        <input type="email" name="user_email" id="user_email" value="afdasf@ya.ru">

        <label for="comment"><?= Loc::getMessage('COMMENT_LABEL') ?></label>
        <textarea name="comment" id="comment" required>
            <?= $_SERVER['REQUEST_URI'] ?>
        </textarea>

        <button type="submit" name="submit_comment"><?= Loc::getMessage('SUBMIT_COMMENT') ?>Отправить сообщение</button>
    </form>
</div>

<div class="comments-list">
    <?php foreach ($arResult['COMMENTS'] as $comment): ?>
        <div class="comment">
            <strong><?= htmlspecialcharsbx($comment['USER_NAME']) ?> <?= htmlspecialcharsbx($comment['USER_LAST_NAME']) ?></strong>
            <div class="comment-text"><?= nl2br(htmlspecialcharsbx($comment['COMMENT'])) ?></div>
            <div class="comment-date"><?= $comment['DATE_CREATE']->toString() ?></div>
        </div>
    <?php endforeach; ?>
</div>
