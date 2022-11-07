<?php declare(strict_types = 1);

namespace Orisai\OpenAPI\Spec;

use Orisai\OpenAPI\Utils\SpecUtils;

final class RequestBody implements SpecObject
{

	use SupportsSpecExtensions;
	use SpecObjectHasContent;

	public ?string $description = null;

	public bool $required = false;

	public function toArray(): array
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
