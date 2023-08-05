<?php declare(strict_types=1);

namespace App\Model\Helper;

class ClassParser
{
	public const ANNOTATION_REG = '/@resource\s[a-zA-Z:]+/m';

	public static function parse(\Reflector $ref, string $name): ?string
	{
		if (!preg_match_all(self::ANNOTATION_REG, (string) $ref->getDocComment(), $m)) {
			return null;
		}

		$res = '';

		foreach ($m[0] as $s)
		{
			$res = str_replace('@' . $name . ' ', '', $s);
		}

		return $res;
	}

	public static function getArgument(\Reflector $ref, string $name): ?string
	{
		$res = null;

		foreach ($ref->getAttributes($name) as $attribute)
		{
			$res = $attribute->getArguments()[0];
		}

		return $res;
	}

	public static function getList(string $src): array
	{
		$classes = [];
		$tokens = token_get_all($src);
		$count = count($tokens);

		for ($i = 2; $i < $count; $i++) {
			if (   $tokens[$i - 2][0] === T_FUNCTION
				&& $tokens[$i - 1][0] === T_WHITESPACE
				&& $tokens[$i][0] === T_STRING) {

				$class_name = $tokens[$i][1];
				$classes[] = $class_name;
			}
		}

		return $classes;
	}

	public static function getClassname(string $src): ?string
	{
		if (preg_match('/class ([a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*)/', $src, $matches)) {
			return $matches[1];
		}

		return null;
	}

	public static function getNamespace(string $src): ?string
	{
		if (preg_match('/namespace\s+(.*)?;/', $src, $matches)) {
			return $matches[1];
		}

		return null;
	}

	public static function getFullClassName(string $src): string
	{
		return self::getNamespace($src) . '\\' . self::getClassname($src);
	}
}
