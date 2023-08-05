<?php declare(strict_types=1);

namespace App\Model\Attributes;

use Attribute;

#[Attribute]
class Resource
{
	protected string $main;

	public function __construct(string $main)
	{
		$this->main = $main;
	}
}
