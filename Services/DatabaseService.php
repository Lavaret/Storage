<?php
/**
 * DatabaseService
 */
class DatabaseService
{
	private $username;

	private $password;

	private $host;

	private $dbname;

	public function __construct()
	{
		$config = include('config.php');

		$this->username = $config['database_username'];
		$this->password = $config['database_password'];
		$this->host = $config['database_host'];
		$this->dbname = $config['database_name'];
	}

	public function getConnection()
	{
		$connection = new mysqli($this->host, $this->username, $this->password, $this->dbname);

		if ($connection->connect_error) {
    		throw new Exception("Connection failed: " . $connection->connect_error);
		} 

		return $connection;
	}

	/*
	* Creates table
	*/
	public function createTable() : void
	{
		$connection = $this->getConnection();

		//check table exists
		$sql = "SELECT 1 FROM `unity_storage`";

		$tableExists = $connection->query($sql);

		if ($tableExists === false) {
			//create table
			$sql = "CREATE TABLE `unity_storage` (
				`id` INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY, 
				`price` FLOAT(9, 2) NOT NULL,
				`amount` INT(9) NOT NULL
			)";

			try {
				$table = $connection->query($sql);
				if (!$table) {
					die($connection->error);
				}
			} catch (Exception $exception) {
				die($exception->getMessage());
			}
		}

		$connection->close();
	}

	public function saveStorage(int $amount, float $price) : void
	{
		$connection = $this->getConnection();

		$sql = "INSERT INTO `unity_storage` (`id`, `price`, `amount`) 
		VALUES (NULL, "."'".$price."', '".$amount."')";

		try {
			$this->executeQuery($sql);
		} catch (Exception $e) {
			echo $e->getMessage();
		}
	}

	public function getAll() : array
	{
		$sql = "SELECT * FROM `unity_storage`";
		$values = [];

		try {
			$response = $this->executeQuery($sql);
		} catch (Exception $e) {
			echo $e->getMessage();
		}

		if ($response->num_rows) {
			while ($row = $response->fetch_object()) {
				$values[] = $row;
			}
		}

		return $values;
	}

	public function delete(int $id) : void
	{
		$sql = "DELETE FROM `unity_storage` WHERE `id` = $id";

		try {
			$this->executeQuery($sql);
		} catch (Exception $e) {
			echo $e->getMessage();
		}
	}

	public function updateAmount(int $id, int $amount) : void
	{
		$sql = "UPDATE `unity_storage` SET `amount` = $amount WHERE `id` = $id";

		try {
			$this->executeQuery($sql);
		} catch (Exception $e) {
			echo $e->getMessage();
		}
	}

	public function getAvailableStorage() : int
	{
		$sql = "SELECT SUM(amount) sum FROM `unity_storage`";
		$value = 0;
		try {
			$response = $this->executeQuery($sql);
			$value = $response->fetch_object();

		} catch (Exception $e) {
			echo $e->getMessage();
		}

		return (int) $value->sum;
	}

	private function executeQuery(string $sql)
	{
		$connection = $this->getConnection();

		$response = $connection->query($sql);

		if (!$response) {
			throw new Exception($connection->error);
		}

		$connection->close();

		return $response;
	}
}