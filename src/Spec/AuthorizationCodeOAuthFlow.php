<?php declare(strict_types = 1);

namespace Orisai\OpenAPI\Spec;

final class AuthorizationCodeOAuthFlow extends OAuthFlow
{

	public string $authorizationUrl;

	public string $tokenUrl;

	public function __construct(array $scopes, string $authorizationUrl, string $tokenUrl)
	{
		parent::__construct($scopes);
		$this->authorizationUrl = $authorizationUrl;
		$this->tokenUrl = $tokenUrl;
	}

	public function toArray(): array
	{
		$data = parent::toArray();
		$data['authorizationUrl'] = $this->authorizationUrl;
		$data['tokenUrl'] = $this->tokenUrl;

		return $data;
	}

}
