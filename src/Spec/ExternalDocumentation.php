<?php declare(strict_types = 1);

namespace Orisai\OpenAPI\Spec;

use Orisai\ObjectMapper\Attributes\Expect\StringValue;
use Orisai\ObjectMapper\Attributes\Modifiers\CreateWithoutConstructor;
use Orisai\ObjectMapper\MappedObject;

/**
 * @CreateWithoutConstructor()
 */
final class ExternalDocumentation extends MappedObject implements SpecObject
{

	use SpecObjectSupportsExtensions;

	/** @StringValue() */
	public ?string $description = null;

	/** @StringValue() */
	public string $url;

	public function __construct(string $url)
	{
		$this->url = $url;
	}

	public function toArray(): array
	{
		$data = [
			'url' => $this->url,
		];

		if ($this->description !== null) {
			$data['description'] = $this->description;
		}

		$this->addExtensionsToData($data);

		return $data;
	}

}
