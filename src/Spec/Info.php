<?php declare(strict_types = 1);

namespace Orisai\OpenAPI\Spec;

final class Info implements SpecObject
{

	public string $title;

	public ?string $summary;

	public ?string $description;

	public ?string $termsOfService;

	public ?Contact $contact;

	public ?License $license;

	public string $version;

	public function toArray(): array
	{
		return [
			'title' => $this->title,
			'summary' => $this->summary,
			'description' => $this->description,
			'termsOfService' => $this->termsOfService,
			'contact' => $this->contact !== null ? $this->contact->toArray() : null,
			'licence' => $this->license !== null ? $this->license->toArray() : null,
			'version' => $this->version,
		];
	}

}
