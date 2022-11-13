<?php declare(strict_types = 1);

namespace Orisai\OpenAPI\Spec;

use Orisai\OpenAPI\Enum\EncodingStyle;
use Orisai\OpenAPI\Utils\SpecUtils;

final class Encoding implements SpecObject
{

	use SpecObjectSupportsExtensions;

	public ?string $contentType = null;

	/** @var array<string, Header|Reference> */
	public array $headers = [];

	public ?EncodingStyle $style = null;

	public bool $explode = false;

	public bool $allowReserved = false;

	public function toArray(): array
	{
		//TODO - https://spec.openapis.org/oas/v3.1.0#encoding-object
		//TODO - pro style, explode a allowReserved specifikace uvádí změnu chování v případě, že jsou definované explicitně
		//		- implicitní default se tedy liší od explicitního
		$data = [];

		//TODO - mime type
		//		- získávání defaults
		//		- více mime types - čárkou oddělený seznam
		//		- application/* apod. - ověřit i ostatní místa užití
		if ($this->contentType !== null) {
			$data['contentType'] = $this->contentType;
		}

		//TODO - totéž co v Response?
		//		- nedovoluje Content-Type
		if ($this->headers !== []) {
			$data['headers'] = SpecUtils::specsToArray($this->headers);
		}

		if ($this->style !== null) {
			$data['style'] = $this->style->value;
		}

		// TODO - pro style=form je true, jinak false
		if ($this->explode) {
			$data['explode'] = $this->explode;
		}

		if ($this->allowReserved) {
			$data['allowReserved'] = $this->allowReserved;
		}

		$this->addExtensionsToData($data);

		return $data;
	}

}
