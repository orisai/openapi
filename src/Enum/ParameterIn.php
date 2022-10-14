<?php declare(strict_types = 1);

namespace Orisai\OpenAPI\Enum;

use ValueError;

final class ParameterIn
{

	private const Cookie = 1,
		Header = 2,
		Path = 3,
		Query = 4;

	private const ValuesAndNames = [
		self::Cookie => 'cookie',
		self::Header => 'header',
		self::Path => 'path',
		self::Query => 'query',
	];

	/** @readonly */
	public string $name;

	/** @readonly */
	public int $value;

	/** @var array<string, self> */
	private static array $instances = [];

	private function __construct(string $name, int $value)
	{
		$this->name = $name;
		$this->value = $value;
	}

	public static function cookie(): self
	{
		return self::from(self::Cookie);
	}

	public static function header(): self
	{
		return self::from(self::Header);
	}

	public static function path(): self
	{
		return self::from(self::Path);
	}

	public static function query(): self
	{
		return self::from(self::Query);
	}

	public static function tryFrom(int $value): ?self
	{
		$key = self::ValuesAndNames[$value] ?? null;

		if ($key === null) {
			return null;
		}

		return self::$instances[$key] ??= new self($key, $value);
	}

	public static function from(int $value): self
	{
		$self = self::tryFrom($value);

		if ($self === null) {
			throw new ValueError();
		}

		return $self;
	}

	/**
	 * @return array<self>
	 */
	public static function cases(): array
	{
		$cases = [];
		foreach (self::ValuesAndNames as $value => $name) {
			$cases[] = self::from($value);
		}

		return $cases;
	}

}
