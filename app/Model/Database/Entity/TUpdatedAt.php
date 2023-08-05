<?php declare(strict_types = 1);

namespace App\Model\Database\Entity;

use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;

trait TUpdatedAt
{

	#[ORM\Column(type: 'datetime_immutable')]
	protected ?DateTimeImmutable $updatedAt = null;

	public function getUpdatedAt(): ?DateTimeImmutable
	{
		return $this->updatedAt;
	}

	/**
	 * Doctrine annotation
	 *
	 * @ORM\PreUpdate
	 * @internal
	 */
	public function setUpdatedAt(): void
	{
		$this->updatedAt = new DateTimeImmutable();
	}

}
