<?php declare(strict_types = 1);

namespace Orisai\OpenAPI\Spec;

final class PasswordOAuthFlow extends OAuthFlow
{

	use SpecObjectSupportsExtensions;

	public string $tokenUrl;

	public function __construct(string $tokenUrl)
	{
		$this->tokenUrl = $tokenUrl;
	}

	public function toArray(): array
	{
		$data = parent::toArray();
		$data['tokenUrl'] = $this->tokenUrl;

		$this->addExtensionsToData($data);

		return $data;
	}

}
