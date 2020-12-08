<?php
namespace WebApp;
use DataAccess;
use PDO;

/**
 * Data Access Object factory.
 * @author Vince <vincent.boursier@gmail.com>
 */
class Data
{
	const DEFAULT_IMAGE = '/static/logo/square%u.svg';
	const NB_DEFAULT_IMAGE = 8;
	const NB_INSTAGRAM_PICTURE = 6;

	private static $pdoInstance = null;

	private static function getPdoInstance()
	{
		if (self::$pdoInstance instanceof PDO)
		{ return self::$pdoInstance; }

		$dbSource = Config::DB_SOURCE;

		if (empty($dbSource))
		{ $dbSource = 'sqlite:SQLite.db'; }

		self::$pdoInstance = new PDO(
			$dbSource, Config::DB_USERNAME, Config::DB_PASSWORD, [
				PDO::ATTR_PERSISTENT => true,
				PDO::ATTR_EMULATE_PREPARES => false,
				PDO::ATTR_STRINGIFY_FETCHES => false]);

		return self::$pdoInstance;
	}

	public static function getCategory()
	{
		return new DataAccess\Category(self::getPdoInstance());
	}

	public static function getCompany()
	{
		return new DataAccess\Company(self::getPdoInstance());
	}

	public static function getCompanyInfo($permalink)
	{
		$company = self::getCompany()->selectPermalink($permalink);

		if ($company == null)
		{ return null; }

		if ($company->logo == '')
		{ $company->logo = sprintf(self::DEFAULT_IMAGE, rand(1, self::NB_DEFAULT_IMAGE)); }

		if (isset($company->instagram))
		{
			for ($i=0; $i < self::NB_INSTAGRAM_PICTURE; ++$i)
			{
				if (empty($company->instagram->pictures[$i]))
				{ $company->instagram->pictures[$i] = sprintf(self::DEFAULT_IMAGE, rand(1, self::NB_DEFAULT_IMAGE)); }
			}
		}

		return $company;
	}

	public static function getLocation()
	{
		return new DataAccess\Location(self::getPdoInstance());
	}

	public static function getUser()
	{
		return new DataAccess\User(self::getPdoInstance());
	}

	public static function getTagIterator()
	{
		return new DataAccess\Tag(self::getPdoInstance());
	}
}