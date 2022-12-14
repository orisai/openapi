<?php declare(strict_types = 1);

namespace Orisai\OpenAPI\Spec;

use Orisai\Exceptions\Logic\InvalidArgument;
use Orisai\Exceptions\Message;
use Orisai\OpenAPI\Enum\EncodingStyle;
use Orisai\OpenAPI\Utils\Headers;
use Orisai\OpenAPI\Utils\MediaTypes;
use Orisai\OpenAPI\Utils\SpecUtils;
use function array_keys;
use function implode;

final class Encoding implements SpecObject
{

	use SpecObjectSupportsExtensions;

	/** @var array<string, null> */
	private array $contentTypes = [];

	/** @var array<string, Header|Reference> */
	private array $headers = [];

	private EncodingStyle $style;

	private bool $explode;

	private bool $allowReserved = false;

	public function __construct()
	{
		$this->style = EncodingStyle::form();
		$this->explode = $this->style->getDefaultExplode();
	}

	/**
	 * @param non-empty-string $name
	 */
	public function addContentType(string $name): void
	{
		if (!MediaTypes::isValid($name)) {
			$message = Message::create()
				->withContext("Adding a media type '$name'.")
				->withProblem('Type is not a valid media type or media type range.')
				->with(
					'Hint',
					'Validation is performed in compliance with https://www.rfc-editor.org/rfc/rfc2045#section-5.1 ' .
					'and https://www.rfc-editor.org/rfc/rfc7231#section-3.1.1.1',
				);

			throw InvalidArgument::create()
				->withMessage($message);
		}

		$this->contentTypes[MediaTypes::format($name)] = null;
	}

	/**
	 * @return list<string>
	 */
	public function getContentTypes(): array
	{
		MediaTypes::sortTypesInKeys($this->contentTypes);

		return array_keys($this->contentTypes);
	}

	/**
	 * @param Header|Reference $header
	 */
	public function addHeader(string $name, $header): void
	{
		if (!Headers::isNameValid($name)) {
			$message = Message::create()
				->withContext("Adding Encoding Header with name '$name'.")
				->withProblem('Name is not valid HTTP header name.')
				->with(
					'Hint',
					'Validation is performed in compliance with https://www.rfc-editor.org/rfc/rfc7230',
				);

			throw InvalidArgument::create()
				->withMessage($message);
		}

		$this->headers[Headers::formatName($name)] = $header;
	}

	/**
	 * @return array<string, Header|Reference>
	 */
	public function getHeaders(): array
	{
		return $this->headers;
	}

	public function setStyle(EncodingStyle $style, ?bool $explode = null): void
	{
		$this->style = $style;
		$this->explode = $explode ?? $style->getDefaultExplode();
	}

	public function getStyle(): EncodingStyle
	{
		return $this->style;
	}

	public function getExplode(): bool
	{
		return $this->explode;
	}

	public function setAllowReserved(bool $allow = true): void
	{
		$this->allowReserved = $allow;
	}

	public function getAllowReserved(): bool
	{
		return $this->allowReserved;
	}

	public function toArray(): array
	{
		$data = [];

		$contentTypes = $this->getContentTypes();
		if ($contentTypes !== []) {
			$data['contentType'] = implode(', ', $contentTypes);
		}

		if ($this->headers !== []) {
			$data['headers'] = SpecUtils::specsToArray($this->headers);
		}

		if ($this->style !== EncodingStyle::form()) {
			$data['style'] = $this->style->value;
		}

		if ($this->explode !== $this->style->getDefaultExplode()) {
			$data['explode'] = $this->explode;
		}

		if ($this->allowReserved) {
			$data['allowReserved'] = $this->allowReserved;
		}

		$this->addExtensionsToData($data);

		return $data;
	}

}
