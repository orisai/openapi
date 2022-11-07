<?php declare(strict_types = 1);

namespace Orisai\OpenAPI\Utils;

use function preg_match;

/**
 * @internal
 */
final class MediaTypeValidator
{

	private const Expression = /** @lang PhpRegExp */
		<<<'REGEX'
@^
	(application|audio|font|example|image|message|model|multipart|text|video|x-(?:[0-9A-Za-z!#$%&'*+.^_`|~-]+))
	/
	([0-9A-Za-z!#$%&'*+.^_`|~-]+)
	((?:[ \t]*;[ \t]*[0-9A-Za-z!#$%&'*+.^_`|~-]+=(?:[0-9A-Za-z!#$%&'*+.^_`|~-]+|\"(?:[^\"\\\\]|\\.)*\"))*)
$@ix
REGEX;

	public static function isValid(string $mediaType): bool
	{
		return preg_match(self::Expression, $mediaType) === 1;
	}

}
