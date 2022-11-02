<?php declare(strict_types = 1);

namespace Orisai\OpenAPI\Spec;

use Orisai\Exceptions\Logic\InvalidArgument;
use Orisai\Exceptions\Message;
use Orisai\OpenAPI\Utils\SpecUtils;
use function ksort;
use function preg_match;
use const SORT_STRING;

final class Response implements SpecObject
{

	use SupportsSpecExtensions;

	public string $description;

	/** @var array<string, Header|Reference> */
	private array $headers = [];

	/** @var array<string, MediaType> */
	private array $content = [];

	/** @var array<string, Link|Reference> */
	private array $links = [];

	public function __construct(string $description)
	{
		$this->description = $description;
	}

	/**
	 * @param Header|Reference $header
	 */
	public function addHeader(string $key, $header): void
	{
		//TODO - $this->checkName($key, 'Header');
		$this->headers[$key] = $header;
	}

	/**
	 * @return array<string, Header|Reference>
	 */
	public function getHeaders(): array
	{
		return $this->headers;
	}

	public function addContent(string $name, MediaType $mediaType): void
	{
		//TODO - tests
		if (preg_match(
			'@' .
				"^(application|audio|font|example|image|message|model|multipart|text|video|x-(?:[0-9A-Za-z!#$%&'*+.^_`|~-]+))" .
				'/' .
				"([0-9A-Za-z!#$%&'*+.^_`|~-]+)" .
				"((?:[ \t]*;[ \t]*[0-9A-Za-z!#$%&'*+.^_`|~-]+=(?:[0-9A-Za-z!#$%&'*+.^_`|~-]+|\"(?:[^\"\\\\]|\\.)*\"))*)" .
				'$@',
			$name,
		) !== 1) {
			$message = Message::create()
				->withContext("Adding a media type '$name'.")
				->withProblem('Type is not a valid media type.')
				->with(
					'Hint',
					'Validation is performed in compliance with https://www.rfc-editor.org/rfc/rfc2045#section-5.1' .
						'and https://www.rfc-editor.org/rfc/rfc7231#section-3.1.1.1',
				);

			throw InvalidArgument::create()
				->withMessage($message);
		}

		$this->content[$name] = $mediaType;
	}

	/**
	 * @return array<string, MediaType>
	 */
	public function getContent(): array
	{
		//TODO - tests
		ksort($this->content, SORT_STRING);

		return $this->content;
	}

	/**
	 * @param Link|Reference $link
	 */
	public function addLink(string $key, $link): void
	{
		//TODO - $this->checkName($key, 'Link');
		$this->links[$key] = $link;
	}

	/**
	 * @return array<string, Link|Reference>
	 */
	public function getLinks(): array
	{
		return $this->links;
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

		$content = $this->getContent();
		if ($content !== []) {
			$data['content'] = SpecUtils::specsToArray($content);
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
