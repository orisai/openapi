<?php declare(strict_types = 1);

namespace Orisai\OpenAPI\Spec;

final class ImplicitOAuthFlow extends OAuthFlow
{

	public string $authorizationUrl;

	public function __construct(array $scopes, string $authorizationUrl)
	{
		parent::__construct($scopes);
		$this->authorizationUrl = $authorizationUrl;
	}

	public function toArray(): array
	{
		$data = parent::toArray();
		$data['authorizationUrl'] = $this->authorizationUrl;

		return $data;
	}

}
