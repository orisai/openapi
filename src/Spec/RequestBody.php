<?php declare(strict_types = 1);

namespace Orisai\OpenAPI\Spec;

use Orisai\ObjectMapper\Attributes\Expect\BoolValue;
use Orisai\ObjectMapper\Attributes\Expect\StringValue;
use Orisai\ObjectMapper\Attributes\Modifiers\CreateWithoutConstructor;
use Orisai\ObjectMapper\MappedObject;
use Orisai\OpenAPI\Utils\SpecUtils;

/**
 * @CreateWithoutConstructor()
 */
final class RequestBody implements SpecObject, MappedObject
{

	use SpecObjectSupportsExtensions;
	use SpecObjectHasContent;

	/** @StringValue() */
	public ?string $description = null;

	/** @BoolValue() */
	public bool $required = false;

	/**
	 * @return array<int|string, mixed>
	 */
	public function toRaw(): array
	{
		$data = [
			'content' => SpecUtils::specsToArray($this->getContent()),
		];

		if ($this->description !== null) {
			$data['description'] = $this->description;
		}

		if ($this->required) {
			$data['required'] = $this->required;
		}

		$this->addExtensionsToData($data);

		return $data;
	}

}
