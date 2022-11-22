<?php declare(strict_types = 1);

namespace Orisai\OpenAPI\Spec;

final class ImplicitOAuthFlow extends OAuthFlow
{

	use SpecObjectSupportsExtensions;

	public string $authorizationUrl;

	public function __construct(string $authorizationUrl)
	{
		$this->authorizationUrl = $authorizationUrl;
	}

	public function toArray(): array
	{
		$data = parent::toArray();
		$data['authorizationUrl'] = $this->authorizationUrl;
		$this->addExtensionsToData($data);

		return $data;
	}

}
