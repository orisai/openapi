<?php declare(strict_types = 1);

namespace Orisai\OpenAPI\Utils;

use function array_map;
use function explode;
use function implode;
use function preg_match;
use function strtolower;
use function ucfirst;

final class HeaderValidator
{

	public static function formatName(string $name): string
	{
		return implode(
			'-',
			array_map(
				static fn (string $word): string => ucfirst($word),
				explode('-', strtolower($name)),
			),
		);
	}

	public static function isNameValid(string $name): bool
	{
		return preg_match("@^[!#$%&'*+.^_`|~0-9A-Za-z-]+$@", $name) === 1;
	}

}
