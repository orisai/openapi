<?php declare(strict_types = 1);

namespace Orisai\OpenAPI\Spec;

use Orisai\Exceptions\Logic\InvalidArgument;
use Orisai\Exceptions\Message;
use Orisai\OpenAPI\Utils\SpecUtils;
use function array_map;
use function explode;
use function implode;
use function preg_match;
use function strtolower;
use function ucfirst;

final class Response implements SpecObject
{

	use SpecObjectHasContent;
	use SpecObjectSupportsExtensions;

	public string $description;

	/** @var array<string, Header|Reference> */
	private array $headers = [];

	/** @var array<string, Link|Reference> */
	private array $links = [];

	public function __construct(string $description)
	{
		$this->description = $description;
	}

	/**
	 * @param Header|Reference $header
	 */
	public function addHeader(string $name, $header): void
	{
		//TODO - validovat název headeru (i cookie header)
		//	- neměla by být header cookie zakázaná?
		$this->headers[$this->formatHeaderName($name)] = $header;
	}

	private function formatHeaderName(string $name): string
	{
		//TODO - sjednotit implementaci s formátováním v parametru
		return implode(
			'-',
			array_map(
				static fn (string $word): string => ucfirst($word),
				explode('-', strtolower($name)),
			),
		);
	}

	/**
	 * @return array<string, Header|Reference>
	 */
	public function getHeaders(): array
	{
		return $this->headers;
	}

	/**
	 * @param Link|Reference $link
	 */
	public function addLink(string $key, $link): void
	{
		$this->checkName($key, 'Link');
		$this->links[$key] = $link;
	}

	/**
	 * @return array<string, Link|Reference>
	 */
	public function getLinks(): array
	{
		return $this->links;
	}

	private function checkName(string $key, string $specType): void
	{
		if (preg_match('~^[a-zA-Z0-9\.\-_]+$~', $key) === 1) {
			return;
		}

		$message = Message::create()
			->withContext("Assigning a spec object '$specType' with key '$key'.")
			->withProblem("Key must match regular expression '^[a-zA-Z0-9\.\-_]+\$'.");

		throw InvalidArgument::create()
			->withMessage($message);
	}

	public function toArray(): array
	{
		$data = [
			'description' => $this->description,
		];

		if ($this->headers !== []) {
			$data['headers'] = SpecUtils::specsToArray($this->headers);
		}

		$content = $this->getContent();
		if ($content !== []) {
			$data['content'] = SpecUtils::specsToArray($content);
		}

		if ($this->links !== []) {
			$data['links'] = SpecUtils::specsToArray($this->links);
		}

		$this->addExtensionsToData($data);

		return $data;
	}

}
