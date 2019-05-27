<?php
namespace TgWrap;

use mysql_xdevapi\Exception;
use TelegramBot\Api\Client;

abstract class AbstractBot extends Client
{

	public $bot;
	public $user;
	public $chat;
	protected $callback = null;
	protected $text = null;

	abstract protected function getDb() : AbstractDatabase;

	public function __construct(string $token)
	{
		parent::__construct($token);

		//Get Chat
		$raw = $this->getJsonBody();

		if(!$raw){
			throw new Exception('Bad json value');
		}

		//Get message
		if(array_key_exists('callback_query', $raw)){

			$this->callback = $raw['callback_query']['data'];
			$this->chat = $raw['callback_query']['from']['id'];

		}elseif(array_key_exists('message', $raw)){

			$this->chat = $raw['message']['chat']['id'];

			if(array_key_exists('text', $raw['message'])){
				$this->text = $raw['message']['text'];
			}

		}

		//Get User
		$user = $this->getDb()->getUser($this->chat);
		if(!$user){
			$this->getDb()->addUser($this->chat);
			$user = $this->getDb()->getUser($this->chat);
		}

		$this->user = $user;

	}

	public function onCallback(string $pattern, callable $func)
	{

		if($this->callback !== null){

			if(preg_match('/^' . $pattern . '$/ui', $this->callback, $vars)){
				$func($this, $vars);
			}

		}

	}

	public function onText(string $pattern, callable $func)
	{

		if($this->text !== null){

			if(preg_match('/^' . $pattern . '$/ui', $this->text, $vars)){
				$func($this, $vars);
			}

		}


	}

	public function getJsonBody() : ?array
	{
		return json_decode($this->getRawBody(), true);
	}

}
