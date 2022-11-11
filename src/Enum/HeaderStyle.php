<?php declare(strict_types = 1);

namespace Orisai\OpenAPI\Enum;

use ValueError;

final class HeaderStyle
{

	private const Simple = 'simple';

	private const ValuesAndNames = [
		self::Simple => 'Simple',
	];

	/** @readonly */
	public string $name;

	/** @readonly */
	public string $value;

	/** @var array<string, self> */
	private static array $instances = [];

	private function __construct(string $name, string $value)
	{
		$this->name = $name;
		$this->value = $value;
	}

	public static function simple(): self
	{
		return self::from(self::Simple);
	}

	public static function tryFrom(string $value): ?self
	{
		$key = self::ValuesAndNames[$value] ?? null;

		if ($key === null) {
			return null;
		}

		return self::$instances[$key] ??= new self($key, $value);
	}

	public static function from(string $value): self
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

		/** @infection-ignore-all because we have just one case */
		return $cases;
	}

	public function getDefaultExplode(): bool
	{
		return false;
	}

}
