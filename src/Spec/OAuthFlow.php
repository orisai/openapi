<?php declare(strict_types = 1);

namespace Orisai\OpenAPI\Spec;

final class OAuthFlow
{

	public string $authorizationUrl;

	public string $tokenUrl;

	public ?string $refreshUrl;

	/** @var array<string, string> */
	public array $scopes;

}
