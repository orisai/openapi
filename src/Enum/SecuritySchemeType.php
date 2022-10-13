<?php declare(strict_types = 1);

namespace Orisai\OpenAPI\Enum;

use ValueError;
use function array_key_exists;

final class SecuritySchemeType
{

	private const ApiKey = 1,
		Http = 2,
		MutualTLS = 3,
		OAuth2 = 4,
		OpenIdConnect = 5;

	private const ValuesAndNames = [
		self::ApiKey => 'apiKey',
		self::Http => 'http',
		self::MutualTLS => 'mutualTLS',
		self::OAuth2 => 'oauth2',
		self::OpenIdConnect => 'openIdConnect',
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

	public static function apiKey(): self
	{
		return self::from(self::ApiKey);
	}

	public static function http(): self
	{
		return self::from(self::Http);
	}

	public static function mutualTLS(): self
	{
		return self::from(self::MutualTLS);
	}

	public static function oAuth2(): self
	{
		return self::from(self::OAuth2);
	}

	public static function openIdConnect(): self
	{
		return self::from(self::OpenIdConnect);
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
