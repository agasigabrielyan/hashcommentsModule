<?php

namespace Gabrielyan\Comments\Models;

use Bitrix\Main\Entity;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\ORM\Data\DataManager;
use Bitrix\Main\ORM\Fields\BooleanField;
use Bitrix\Main\ORM\Fields\DatetimeField;
use Bitrix\Main\ORM\Fields\IntegerField;
use Bitrix\Main\ORM\Fields\StringField;
use Bitrix\Main\ORM\Fields\TextField;

Loc::loadMessages(__FILE__);

class CommentsTable extends DataManager
{
    /**
     * Возвращает карту полей ORM.
     *
     * @return array
     */
    public static function getMap()
    {
        return [
            new IntegerField('ID', [
                'primary' => true,
                'autocomplete' => true,
                'title' => Loc::getMessage('COMMENTS_FIELD_ID'),
            ]),
            new StringField('HASH', [
                'required' => true,
                'title' => Loc::getMessage('COMMENTS_FIELD_HASH'),
            ]),
            new TextField('COMMENT', [
                'required' => true,
                'title' => Loc::getMessage('COMMENTS_FIELD_COMMENT'),
            ]),
            new DatetimeField('DATE_CREATE', [
                'default_value' => function () {
                    return new \Bitrix\Main\Type\DateTime();
                },
                'title' => Loc::getMessage('COMMENTS_FIELD_DATE_CREATE'),
            ]),
            new IntegerField('USER_ID', [
                'default_value' => null,
                'title' => Loc::getMessage('COMMENTS_FIELD_USER_ID'),
            ]),
            new StringField('USER_NAME', [
                'default_value' => null,
                'title' => Loc::getMessage('COMMENTS_FIELD_USER_NAME'),
            ]),
            new StringField('USER_LAST_NAME', [
                'default_value' => null,
                'title' => Loc::getMessage('COMMENTS_FIELD_USER_LAST_NAME'),
            ]),
            new StringField('USER_EMAIL', [
                'default_value' => null,
                'title' => Loc::getMessage('COMMENTS_FIELD_USER_EMAIL'),
            ]),
            new BooleanField('SHOW_FLAG', [
                'values' => ['N', 'Y'],
                'default_value' => 'Y',
                'title' => Loc::getMessage('COMMENTS_FIELD_SHOW_FLAG'),
            ]),
            new IntegerField('SECTION_ID', [
                'default_value' => null,
                'title' => Loc::getMessage('COMMENTS_FIELD_SECTION_ID'),
            ]),
            new IntegerField('ELEMENT_ID', [
                'default_value' => null,
                'title' => Loc::getMessage('COMMENTS_FIELD_ELEMENT_ID'),
            ]),
        ];
    }
}