<?php declare(strict_types = 1);

namespace App\Model\Database\Entity;

use Doctrine\ORM\Mapping as ORM;

trait TId
{

	#[ORM\Id]
	#[ORM\GeneratedValue]
	#[ORM\Column(type: 'integer', unique: true, nullable: false)]
	private int $id;

	public function getId(): int
	{
		return $this->id;
	}

	public function __clone()
	{
		unset($this->id);
	}

}
