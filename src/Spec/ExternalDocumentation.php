<?php declare(strict_types = 1);

namespace Orisai\OpenAPI\Spec;

final class ExternalDocumentation implements SpecObject
{

	use SpecObjectSupportsExtensions;

	public ?string $description = null;

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
