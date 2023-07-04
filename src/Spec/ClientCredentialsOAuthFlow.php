<?php declare(strict_types = 1);

namespace Orisai\OpenAPI\Spec;

use Orisai\ObjectMapper\Modifiers\CreateWithoutConstructor;
use Orisai\ObjectMapper\Rules\StringValue;

/**
 * @CreateWithoutConstructor()
 */
final class ClientCredentialsOAuthFlow extends OAuthFlow
{

	use SpecObjectSupportsExtensions;

	/** @StringValue() */
	public string $tokenUrl;

	public function __construct(string $tokenUrl)
	{
		$this->tokenUrl = $tokenUrl;
	}

	/**
	 * @return array<int|string, mixed>
	 */
	public function toRaw(): array
	{
		$data = parent::toRaw();
		$data['tokenUrl'] = $this->tokenUrl;

		$this->addExtensionsToData($data);

		return $data;
	}

}
