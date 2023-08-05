<?php declare(strict_types = 1);

namespace App\Model\Database\Entity;

use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;

trait TCreatedAt
{

	#[ORM\Column(type: 'datetime_immutable')]
	protected DateTimeImmutable $createdAt;

	public function getCreatedAt(): DateTimeImmutable
	{
		return $this->createdAt;
	}

	/**
	 * Doctrine annotation
	 * @internal
	 */
	#[ORM\PrePersist]
	public function setCreatedAt(): void
	{
		$this->createdAt = new DateTimeImmutable();
	}

}
