<?php declare(strict_types = 1);

namespace Orisai\OpenAPI\Spec;

use Orisai\OpenAPI\Utils\SpecUtils;

final class Response implements SpecObject
{

	use SupportsSpecExtensions;

	public string $description;

	/** @var array<string, Header|Reference> */
	public array $headers = [];

	/** @var array<string, MediaType> */
	public array $content = [];

	/** @var array<string, Link|Reference> */
	public array $links = [];

	public function __construct(string $description)
	{
		$this->description = $description;
	}

	public function toArray(): array
	{
		$data = [
			'description' => $this->description,
		];

		//TODO - case unsensitive (ve výstupu ale vypsat Pascal-Case)
		//TODO - Content-Type má být ignored (proč?)
		if ($this->headers !== []) {
			$data['headers'] = SpecUtils::specsToArray($this->headers);
		}

		//TODO - klíč je media type / media type range - validovat
		//TODO - řadit media types - abecedně, * je poslední
		if ($this->content !== []) {
			$data['content'] = SpecUtils::specsToArray($this->content);
		}

		//TODO - všechny klíče musí odpovídat tomuhle regexu
		//if (!preg_match('~^[a-zA-Z0-9\.\-_]+$~', $k)) {
		//	$this->addError("Invalid key '$k' used in Components Object for attribute '$attribute', does not match ^[a-zA-Z0-9\.\-_]+\$.");
		//}
		if ($this->links !== []) {
			$data['links'] = SpecUtils::specsToArray($this->links);
		}

		$this->addExtensionsToData($data);

		return $data;
	}

}
