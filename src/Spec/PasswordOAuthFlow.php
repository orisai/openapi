<?php declare(strict_types = 1);

namespace Orisai\OpenAPI\Spec;

use Orisai\ObjectMapper\Attributes\Expect\StringValue;
use Orisai\ObjectMapper\Attributes\Modifiers\CreateWithoutConstructor;

/**
 * @CreateWithoutConstructor()
 */
final class PasswordOAuthFlow extends OAuthFlow
{

	use SpecObjectSupportsExtensions;

	/** @StringValue() */
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
