<?php
namespace TgWrap;

abstract class AbstractDatabase
{

	abstract public function getUser(string $chat) : ?array;
	abstract public function addUser(string $chat) : int;


}
