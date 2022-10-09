<?php declare(strict_types = 1);

namespace Orisai\OpenAPI\Spec;

final class Info
{

	public string $title;

	public ?string $summary;

	public ?string $description;

	public ?string $termsOfService;

	public ?Contact $contact;

	public ?License $license;

	public string $version;

}
