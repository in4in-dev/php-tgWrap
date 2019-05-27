<?php

include_once (__DIR__ . '/vendor/autoload.php');
include_once (__DIR__ . '/telegram-bot/AbstractBot.php');
include_once (__DIR__ . '/telegram-bot/AbstractDatabase.php');

class DatabaseTest extends TgWrap\AbstractDatabase
{
	public function getUser(string $chat) : ?array
	{
		return [];
	}

	public function addUser(string $chat) : int
	{
		return 1;
	}

}

class BotTest extends TgWrap\AbstractBot{

	protected function getDb(): \TgWrap\AbstractDatabase
	{
		return new DatabaseTest;
	}

}

$a = new BotTest('867592484:AAHMKBc_6CVxn5Q8cPT-EKBSgvXiKpsdKYw');

//Текстовый запрос
$a->onText('test', function(BotTest $bot, array $vars){

	$bot->sendMessage($bot->chat, $vars[1], false, null, false, new \TelegramBot\Api\Types\Inline\InlineKeyboardMarkup(
		[
			[
				['text' => 'Test', 'callback_data' => 'test cb']
			]
		]
	));

	exit;

});

//Кнопка
$a->onCallback('test cb', function(BotTest $bot, array $vars){

	$bot->sendMessage($bot->chat, 'Yeah');

	exit;

});
