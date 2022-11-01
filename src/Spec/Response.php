<?php declare(strict_types = 1);

namespace Orisai\OpenAPI\Spec;

use Orisai\OpenAPI\Utils\SpecUtils;

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

	public function addContent(string $key, MediaType $mediaType): void
	{
		$this->content[$key] = $mediaType;
	}

	/**
	 * @return array<string, MediaType>
	 */
	public function getContent(): array
	{
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
