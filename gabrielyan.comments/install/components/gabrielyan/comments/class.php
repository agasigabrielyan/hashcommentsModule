<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Loader;
use Gabrielyan\Comments\Models\CommentsTable;
use Bitrix\Main\Localization\Loc;

class GabrielyanCommentsComponent extends CBitrixComponent
{
    public function executeComponent()
    {
        try {
            // Проверяем наличие модуля
            $this->checkModule();

            // Обрабатываем форму, если она была отправлена
            $this->handleCommentForm();

            // Подготавливаем данные для отображения
            $this->prepareData();

            // Включаем шаблон компонента
            $this->includeComponentTemplate();
        } catch (\Exception $e) {
            ShowError($e->getMessage());
        }
    }

    protected function checkModule()
    {
        // Проверяем, установлен ли модуль
        if (!Loader::includeModule('gabrielyan.comments')) {
            throw new \Exception(Loc::getMessage('GABRIELYAN_COMMENTS_MODULE_NOT_INSTALLED'));
        }
    }

    protected function handleCommentForm()
    {
        // Проверяем, была ли отправлена форма
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit_comment'])) {
            $userName = trim($_POST['user_name']);
            $commentText = trim($_POST['comment']);
            $pageHash = trim($_POST['page_hash']);
            $userLastName = trim($_POST['user_last_name']); // Фамилия (если требуется)
            $userEmail = trim($_POST['user_email']); // Email (если требуется)
            $sectionId = (int)$_POST['section_id']; // ID секции, если необходимо
            $elementId = (int)$_POST['element_id']; // ID элемента, если необходимо

            // Проводим базовую проверку данных
            if (empty($userName) || empty($commentText)) {
                $this->arResult['ERROR'] = Loc::getMessage('COMMENT_EMPTY_FIELDS');
            } else {
                // Создаем запись в базе данных через ORM (сохранение комментария)
                try {
                    CommentsTable::add([
                        'COMMENT' => $commentText,
                        'USER_NAME' => $userName,
                        'USER_LAST_NAME' => $userLastName,
                        'USER_EMAIL' => $userEmail,
                        'HASH' => $pageHash,
                        'DATE_CREATE' => new \Bitrix\Main\Type\DateTime(),
                        'USER_ID' => $GLOBALS['USER']->GetID(), // ID пользователя (если авторизован)
                        'SHOW_FLAG' => 'Y', // Можно добавить логику для скрытия комментариев
                        'SECTION_ID' => $sectionId,
                        'ELEMENT_ID' => $elementId,
                    ]);

                    // Сообщение об успешной отправке
                    $this->arResult['SUCCESS'] = Loc::getMessage('COMMENT_ADDED');
                } catch (\Exception $e) {
                    $this->arResult['ERROR'] = $e->getMessage();
                }
            }
        }
    }

    protected function prepareData()
    {
        // Генерация хеша для текущего URL и параметров (если они есть)
        $this->arResult['PAGE_HASH'] = $this->generateHash();

        // Получаем список комментариев для текущего хеша страницы
        $filter = ['=HASH' => $this->arResult['PAGE_HASH']];

        // Проверяем, если в URL есть параметры, которые нужно учитывать
        $acceptedParams = $this->arParams['ACCEPTED_URL_PARAMETERS'] ?? '';
        if (!empty($acceptedParams)) {
            $acceptedParams = array_map('trim', explode(',', $acceptedParams)); // Преобразуем строку в массив

            // Получаем параметры URL
            $queryParams = [];
            parse_str(parse_url($_SERVER['REQUEST_URI'], PHP_URL_QUERY), $queryParams);

            // Добавляем параметры в фильтр для комментариев
            foreach ($acceptedParams as $param) {
                if (isset($queryParams[$param])) {
                    $filter['=' . $param] = $queryParams[$param];
                }
            }
        }

        // Получаем комментарии с фильтрацией
        $this->arResult['COMMENTS'] = CommentsTable::getList([
            'filter' => $filter,
            'order' => ['DATE_CREATE' => 'DESC'],
        ])->fetchAll();
    }

    protected function generateHash()
    {
        // Получаем путь из текущего URL
        $url = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

        // Получаем параметры, которые нужно учитывать (если они есть)
        $acceptedParams = $this->arParams['ACCEPTED_URL_PARAMETERS'] ?? '';
        $acceptedParams = array_map('trim', explode(',', $acceptedParams)); // Преобразуем строку в массив

        // Получаем параметры URL
        $queryParams = [];
        parse_str(parse_url($_SERVER['REQUEST_URI'], PHP_URL_QUERY), $queryParams);

        // Формируем новый URL с нужными параметрами
        $filteredParams = [];
        foreach ($acceptedParams as $param) {
            if (isset($queryParams[$param])) {
                $filteredParams[$param] = $queryParams[$param];
            }
        }

        // Формируем строку с параметрами, которые будут добавлены к URL
        $urlWithParams = $url . '?' . http_build_query($filteredParams);

        return md5($urlWithParams); // Возвращаем хеш от URL с параметрами
    }
}
