<?php if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;

$arComponentParameters = [
    "GROUPS" => [

    ],
    "PARAMETERS" => [
        "ACCEPTED_URL_PARAMETERS" => [
            "PARENT" => "BASE",
            "NAME" => Loc::getMessage("ACCEPTED_URL_PARAMETERS_NAME"),
            "TYPE" => "STRING",
            "DEFAULT" => "",
            "MULTIPLE" => "N",
            "ROWS" => 1,
            "COLS" => 30,
            "HELP_MESSAGE" => Loc::getMessage("ACCEPTED_URL_PARAMETERS_HELP"),
        ],
    ],
];
