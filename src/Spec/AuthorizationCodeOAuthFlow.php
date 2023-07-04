<?php declare(strict_types = 1);

namespace Orisai\OpenAPI\Spec;

use Orisai\ObjectMapper\Modifiers\CreateWithoutConstructor;
use Orisai\ObjectMapper\Rules\StringValue;

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

	/**
	 * @return array<int|string, mixed>
	 */
	public function toRaw(): array
	{
		$data = parent::toRaw();
		$data['authorizationUrl'] = $this->authorizationUrl;
		$data['tokenUrl'] = $this->tokenUrl;
		$this->addExtensionsToData($data);

		return $data;
	}

}
