<?php

/**
 * MessageService
 */
class MessageService
{

	public function setErrorMessage(string $message) : void
	{
		$_SESSION['error-message'] = $message;
	}

	public function getErrorMessage() : string
	{
		if (array_key_exists('error-message', $_SESSION)) {
			$message = $_SESSION['error-message'];
			$this->setErrorMessage('');
		} else {
			$message = '';
		}

		return $message;
	}

	public function setMessage(string $name, string $message) : void
	{
		$_SESSION[$name] = $message;
	}

	public function getMessage(string $name) : string
	{
		if (array_key_exists($name, $_SESSION)) {
			$message = $_SESSION[$name];
			$this->setMessage($name, '');
		} else {
			$message = '';
		}

		return $message;
	}
}