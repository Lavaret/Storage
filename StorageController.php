<?php

/**
 * Storage controller serves 
 */
class StorageController
{	
	private $database;

	private $messageService;

	function __construct()
	{
		$this->database = new DatabaseService();
		$this->database->createTable();
		$this->messageService = new MessageService();
	}

	public function store(int $amount, float $price) :void
	{
		try {
			$this->database->saveStorage($amount, $price);
		} catch (Exception $exception) {
			$this->messageService->setErrorMessage($exception->getMessage());
		}
	}

	public function pull(int $amount) : float
	{
		try {
			$price = 0;
			$values = $this->database->getAll();
			$available = $this->database->getAvailableStorage();

			if ($amount > $available) {
				throw new Exception("Niewystarczająca ilość zasobów!");
			}

			foreach ($values as $value) {
				if ($value->amount > $amount) {
					//update storage by id with new amount 
					$newAmount = $value->amount - $amount;
					$this->database->updateAmount($value->id, $newAmount);

					$price += $amount * $value->price;

					return $price;
				} elseif ($value->amount < $amount) {
					//amount is greater than available stock
					$price += $value->amount * $value->price;
					$amount -= $value->amount;
					$this->database->delete($value->id);
				} else {
					$price += $amount * $value->price;
					$this->database->delete($value->id);

					return $price;
				}
			}

		} catch (Exception $e) {

			$this->messageService->setErrorMessage($e->getMessage());
			return 0;
		}
	}
}