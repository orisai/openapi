<?php declare(strict_types = 1);

namespace Orisai\OpenAPI\Enum;

use ValueError;

final class SecuritySchemeType
{

	private const ApiKey = 'apiKey',
		Http = 'http',
		MutualTLS = 'mutualTLS',
		OAuth2 = 'oauth2',
		OpenIdConnect = 'openIdConnect';

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
	public string $value;

	/** @var array<string, self> */
	private static array $instances = [];

	private function __construct(string $name, string $value)
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
