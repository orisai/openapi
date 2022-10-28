<?php declare(strict_types = 1);

namespace Orisai\OpenAPI\Spec;

final class AuthorizationCodeOAuthFlow extends OAuthFlow
{

	use SupportsSpecExtensions;

	public string $authorizationUrl;

	public string $tokenUrl;

	public function __construct(string $authorizationUrl, string $tokenUrl)
	{
		$this->authorizationUrl = $authorizationUrl;
		$this->tokenUrl = $tokenUrl;
	}

	public function toArray(): array
	{
		$data = parent::toArray();
		$data['authorizationUrl'] = $this->authorizationUrl;
		$data['tokenUrl'] = $this->tokenUrl;
		$this->addExtensionsToData($data);

		return $data;
	}

}
