<?php declare(strict_types = 1);

namespace Orisai\OpenAPI\Spec;

use Orisai\ObjectMapper\MappedObject;
use Orisai\ObjectMapper\Rules\StringValue;

final class Contact implements SpecObject, MappedObject
{

	use SpecObjectSupportsExtensions;

	/** @StringValue() */
	public ?string $name = null;

	/** @StringValue() */
	public ?string $url = null;

	/** @StringValue() */
	public ?string $email = null;

	/**
	 * @return array<int|string, mixed>
	 */
	public function toRaw(): array
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
