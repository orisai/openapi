<?php declare(strict_types = 1);

namespace Orisai\OpenAPI\Spec;

use Orisai\ObjectMapper\Attributes\Expect\StringValue;
use Orisai\ObjectMapper\Attributes\Modifiers\CreateWithoutConstructor;

/**
 * @CreateWithoutConstructor()
 */
final class ImplicitOAuthFlow extends OAuthFlow
{

	use SpecObjectSupportsExtensions;

	/** @StringValue() */
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
