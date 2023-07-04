<?php declare(strict_types = 1);

namespace Orisai\OpenAPI\Spec;

use Orisai\ObjectMapper\Modifiers\CreateWithoutConstructor;
use Orisai\ObjectMapper\Rules\StringValue;

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

	/**
	 * @return array<int|string, mixed>
	 */
	public function toRaw(): array
	{
		$data = parent::toRaw();
		$data['authorizationUrl'] = $this->authorizationUrl;
		$this->addExtensionsToData($data);

		return $data;
	}

}
