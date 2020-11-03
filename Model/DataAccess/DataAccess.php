<?php
namespace DataAccess;
use PDO;

/**
 * Data Access Layer.
 * @author Vince <vincent.boursier@gmail.com>
 */
class DataAccess
{
	private $pdo;

	public function __construct(PDO $pdoInstance)
	{
		$this->pdo = $pdoInstance;
		date_default_timezone_set('UTC');
	}

	private function createDatabase()
	{
		foreach ([Category::getTableSchema(), Location::getTableSchema(), Company::getTableSchema()] as $sql)
		{
			$result = $this->pdo->exec($sql);

			if ($result === false)
			{ trigger_error(implode(' ', $this->pdo->errorInfo()), E_USER_ERROR); }
		}
	}

	public function recreateTable($tableName)
	{
		$sql = '';

		switch ($tableName)
		{
			case 'Category': $sql = Category::getTableSchema(); break;
			case 'Location': $sql = Location::getTableSchema(); break;
			case 'Company':  $sql = Company::getTableSchema(); break;
			default: return;
		}

		if ($this->pdo->exec('drop table ' . $tableName) === false)
		{ trigger_error(implode(' ', $this->pdo->errorInfo()), E_USER_ERROR); }

		$result = $this->pdo->exec($sql);

		if ($result === false)
		{ trigger_error(implode(' ', $this->pdo->errorInfo()), E_USER_ERROR); }
	}

	public function insertData($filePath, $tableName, $columns)
	{
		$preparedStatement = $this->pdo->prepare(
			'insert into ' . $tableName . ' ('
			. implode(',', array_keys($columns)) . ') values (?' . str_repeat(',?', count($columns) -1) . ')'
		);

		$file = fopen($filePath, 'r');
		fgets($file);  // ignore the first line (column header)

		while ($line = fgets($file))
		{
			$values = explode("\t", $line);
			$i = 0;

			foreach ($columns as $columnType)
			{
				$preparedStatement->bindValue($i+1, trim($values[$i]), $columnType);
				++$i;
			}

			$preparedStatement->execute();
		}
	}

	public function getLocations($usefulItemsOnly = false)
	{
		$sql = 'select * from ' . Location::TABLE_NAME;

		if ($usefulItemsOnly)
		{ $sql .= ' where ID in (select LocationID from Company)'; }

		$stmt = $this->pdo->query($sql);

		if ($stmt == false)
		{
			self::createDatabase();
			$stmt = $this->pdo->query($sql);
		}

		return Location::fetchObjects($stmt);
	}

	public function getCategories()
	{
		$sql = 'select * from ' . Category::TABLE_NAME;
		$stmt = $this->pdo->query($sql);

		if ($stmt == false)
		{
			self::createDatabase();
			$stmt = $this->pdo->query($sql);
		}

		return Category::fetchObjects($stmt);
	}

	public function getCompanies($categories = [], $regions = [], $limit = 0)
	{
		$sql = '';

		if (!empty($categories[0]))
		{
			$filter = [];

			foreach ($categories as $category)
			{ $filter[] = "CategoriesID like '%;$category;%'"; }

			$sql = ' where (' . implode(' or ', $filter) . ')';
		}

		if (!empty($regions[0]))
		{
			if ($sql == '')
			{ $sql = ' where LocationID in (' . implode(',', $regions) . ')'; }
			else
			{ $sql .= ' and LocationID in (' . implode(',', $regions) . ')'; }
		}

		$sql = 'select * from ' . Company::TABLE_NAME . $sql;

		$stmt = $this->pdo->query($sql);

		if ($stmt == false)
		{
			self::createDatabase();
			$stmt = $this->pdo->query($sql);
		}

		return Company::fetchObjects($stmt, $limit);
	}

	public function getUser($email)
	{
		$email = $this->pdo->quote(trim($email));

		$stmt = $this->pdo->query('select * from ' . User::TABLE_NAME . ' where Email=' . $email);
		$user = User::fetchObject($stmt);

		if ($user instanceof User)
		{ return $user; }

		$total = $this->pdo->query('select count(*) from ' . User::TABLE_NAME)->fetchColumn(0);
		$companyID = $total === 0 ? '-1' : '0';
		$code = rand(1000, 9999);
		$time = time();

		$result = $this->pdo->exec("insert into " . User::TABLE_NAME
			. "(Email,CompanyID,LastAccessCode,LastLogin) values ($email,$companyID,$code,$time)");

		if ($result == 0)  // or false
		{ return null; }

		$user = new User();
		$user->email = $email;
		$user->company = $companyID;
		$user->lastAccessCode = $code;
		$user->lastLogin = $time;
		return $user;
	}
}