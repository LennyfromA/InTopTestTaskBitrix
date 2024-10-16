<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");

\Bitrix\Main\Loader::includeModule('crm');

$name = $_POST['name'];
$phone = $_POST['phone'];
$comment = $_POST['comment'];

$contactFields = [
    'NAME' => $name,
    'CONTACT_PHONE' => [['VALUE' => $phone, 'VALUE_TYPE' => 'WORK']],
    'ASSIGNED_BY_ID' => 1,
    'MODIFY_BY_ID' => 1,
];
$contact = new \Bitrix\Crm\ContactTable();
$contactResult = $contact->add($contactFields);

$contactId = $contactResult->getId();

$arFields = [
    'TITLE' => 'Заявка с сайта ' . date('d.m.Y H:i'),
    'COMMENTS' => $comment,
    'CONTACT_ID' => $contactId,
    'ASSIGNED_BY_ID' => 1,
    'MODIFY_BY_ID' => 1,
    'STAGE_ID' => 'NEW',
    'CURRENCY_ID' => 'RUB',
    "SOURCE_ID" => "Сайт",
];

$entity = new \Bitrix\Crm\DealTable();
$res = $entity->add($arFields);

if ($res->isSuccess()) {
    echo 'Заявка отправлена в Bitrix24';
} else {
    $errors = $res->getErrorMessages();
    error_log('Deal creation failed: ' . print_r($errors, true), 3, 'error.log');
    echo 'Произошла ошибка при создании сделки: ' . implode(', ', $errors);
}
?>