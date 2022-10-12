<?php declare(strict_types = 1);

namespace Orisai\OpenAPI\Spec;

final class OAuthFlow implements SpecObject
{

	public string $authorizationUrl;

	public string $tokenUrl;

	public ?string $refreshUrl;

	/** @var array<string, string> */
	public array $scopes;

	public function toArray(): array
	{
		return [
			'authorizationUrl' => $this->authorizationUrl,
			'tokenUrl' => $this->tokenUrl,
			'refreshUrl' => $this->refreshUrl,
			'scopes' => $this->scopes,
		];
	}

}
