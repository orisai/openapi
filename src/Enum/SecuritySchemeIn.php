<?php declare(strict_types = 1);

namespace Orisai\OpenAPI\Enum;

use ValueError;
use function array_key_exists;

final class SecuritySchemeIn
{

	private const Cookie = 1,
		Header = 2,
		Query = 3;

	private const ValuesAndNames = [
		self::Cookie => 'cookie',
		self::Header => 'header',
		self::Query => 'query',
	];

	/** @readonly */
	public string $name;

	/** @readonly */
	public int $value;

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

	public static function query(): self
	{
		return self::from(self::Query);
	}

	public static function tryFrom(int $value): ?self
	{
		if (!array_key_exists($value, self::ValuesAndNames)) {
			return null;
		}

		return new self(self::ValuesAndNames[$value], $value);
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
