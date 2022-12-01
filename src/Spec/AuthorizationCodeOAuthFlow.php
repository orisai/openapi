<?php declare(strict_types = 1);

namespace Orisai\OpenAPI\Spec;

use Orisai\ObjectMapper\Attributes\Expect\StringValue;
use Orisai\ObjectMapper\Attributes\Modifiers\CreateWithoutConstructor;

/**
 * @CreateWithoutConstructor()
 */
final class AuthorizationCodeOAuthFlow extends OAuthFlow
{

	use SpecObjectSupportsExtensions;

	/** @StringValue() */
	public string $authorizationUrl;

	/** @StringValue() */
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
