<?php declare(strict_types = 1);

namespace Orisai\OpenAPI\Spec;

final class OAuthFlows implements SpecObject
{

	public ?OAuthFlow $implicit;

	public ?OAuthFlow $password;

	public ?OAuthFlow $clientCredentials;

	public ?OAuthFlow $authorizationCode;

	public function toArray(): array
	{
		return [
			'implicit' => $this->implicit !== null ? $this->implicit->toArray() : null,
			'password' => $this->password !== null ? $this->password->toArray() : null,
			'clientCredentials' => $this->clientCredentials !== null ? $this->clientCredentials->toArray() : null,
			'authorizationCode' => $this->authorizationCode !== null ? $this->authorizationCode->toArray() : null,
		];
	}

}
