<?php declare(strict_types = 1);

namespace Orisai\OpenAPI\Spec;

final class Info implements SpecObject
{

	use SupportsSpecExtensions;

	public string $title;

	public ?string $summary = null;

	public ?string $description = null;

	public ?string $termsOfService = null;

	public ?Contact $contact = null;

	public ?License $license = null;

	public string $version;

	public function __construct(string $title, string $version)
	{
		$this->title = $title;
		$this->version = $version;
	}

	public function toArray(): array
	{
		$data = [
			'title' => $this->title,
			'version' => $this->version,
		];

		if ($this->summary !== null) {
			$data['summary'] = $this->summary;
		}

		if ($this->description !== null) {
			$data['description'] = $this->description;
		}

		if ($this->termsOfService !== null) {
			$data['termsOfService'] = $this->termsOfService;
		}

		if ($this->contact !== null) {
			$data['contact'] = $this->contact->toArray();
		}

		if ($this->license !== null) {
			$data['license'] = $this->license->toArray();
		}

		$this->addExtensionsToData($data);

		return $data;
	}

}
