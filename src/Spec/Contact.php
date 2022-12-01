<?php declare(strict_types = 1);

namespace Orisai\OpenAPI\Spec;

use Orisai\ObjectMapper\Attributes\Expect\StringValue;
use Orisai\ObjectMapper\Attributes\Modifiers\CreateWithoutConstructor;
use Orisai\ObjectMapper\MappedObject;

/**
 * @CreateWithoutConstructor()
 */
final class Contact extends MappedObject implements SpecObject
{

	use SpecObjectSupportsExtensions;

	/** @StringValue() */
	public ?string $name = null;

	/** @StringValue() */
	public ?string $url = null;

	/** @StringValue() */
	public ?string $email = null;

	public function toArray(): array
	{
		$data = [];

		if ($this->name !== null) {
			$data['name'] = $this->name;
		}

		if ($this->url !== null) {
			$data['url'] = $this->url;
		}

		if ($this->email !== null) {
			$data['email'] = $this->email;
		}

		$this->addExtensionsToData($data);

		return $data;
	}

}
