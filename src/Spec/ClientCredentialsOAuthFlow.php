<?php declare(strict_types = 1);

namespace Orisai\OpenAPI\Spec;

final class ClientCredentialsOAuthFlow extends OAuthFlow
{

	public string $tokenUrl;

	public function __construct(array $scopes, string $tokenUrl)
	{
		parent::__construct($scopes);
		$this->tokenUrl = $tokenUrl;
	}

	public function toArray(): array
	{
		$data = parent::toArray();
		$data['tokenUrl'] = $this->tokenUrl;

		return $data;
	}

}