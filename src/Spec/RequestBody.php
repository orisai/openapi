<?php declare(strict_types = 1);

namespace Orisai\OpenAPI\Spec;

use Orisai\OpenAPI\Utils\SpecUtils;

final class RequestBody implements SpecObject
{

	use SupportsSpecExtensions;

	public ?string $description = null;

	/** @var array<string, MediaType> */
	public array $content;

	public bool $required = false;

	/**
	 * @param array<string, MediaType> $content
	 */
	public function __construct(array $content)
	{
		$this->content = $content;
	}

	public function toArray(): array
	{
		//TODO - klíč je media type / media type range - validovat
		//TODO - řadit media types - abecedně, * je poslední
		//TODO - je required - má být tedy alespoň jeden??
		$data = [
			'content' => SpecUtils::specsToArray($this->content),
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
