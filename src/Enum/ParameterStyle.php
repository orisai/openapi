<?php declare(strict_types = 1);

namespace Orisai\OpenAPI\Enum;

use ValueError;

final class ParameterStyle
{

	private const DeepObject = 'deepObject',
		Form = 'form',
		Label = 'label',
		Matrix = 'matrix',
		PipeDelimited = 'pipeDelimited',
		Simple = 'simple',
		SpaceDelimited = 'spaceDelimited';

	private const ValuesAndNames = [
		self::DeepObject => 'DeepObject',
		self::Form => 'Form',
		self::Label => 'Label',
		self::Matrix => 'Matrix',
		self::PipeDelimited => 'PipeDelimited',
		self::Simple => 'Simple',
		self::SpaceDelimited => 'SpaceDelimited',
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

	public static function deepObject(): self
	{
		return self::from(self::DeepObject);
	}

	public static function form(): self
	{
		return self::from(self::Form);
	}

	public static function label(): self
	{
		return self::from(self::Label);
	}

	public static function matrix(): self
	{
		return self::from(self::Matrix);
	}

	public static function pipeDelimited(): self
	{
		return self::from(self::PipeDelimited);
	}

	public static function simple(): self
	{
		return self::from(self::Simple);
	}

	public static function spaceDelimited(): self
	{
		return self::from(self::SpaceDelimited);
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

		return $cases;
	}

}
