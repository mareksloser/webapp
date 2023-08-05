<?php declare(strict_types=1);

namespace App\Domain\Auth\Permission;

use App\Domain\Auth\Role\Role;
use App\Model\Database\Entity\AbstractEntity;
use App\Model\Database\Entity\TId;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PermissionRepository::class)]
#[ORM\Table(name: "permissions")]
#[ORM\HasLifecycleCallbacks]
class Permission extends AbstractEntity
{
	use TId;

	#[ORM\Column(type: 'string', length: 32, nullable: false, unique: true)]
	private string $name;

	#[ORM\Column(type: 'string', length: 32, nullable: false, unique: true)]
	private string $value;

	#[ORM\Column(type: 'string', length: 32, nullable: true, unique: false)]
	private ?string $description;

	#[ORM\ManyToOne(targetEntity: self::class, inversedBy: 'child')]
	private ?self $parent = null;

	/** @var Collection<self> */
	#[ORM\OneToMany(mappedBy: 'parent', targetEntity: self::class)]
	private Collection $child;

	/** @var Collection<int, Role> */
	#[ORM\ManyToMany(targetEntity: Role::class, mappedBy: 'permissions')]
	private Collection $roles;


	public function __construct(
		string $name,
		string $value,
		string $description = null,
		Permission $parent = null
	)
	{
		$this->name = $name;
		$this->value = $value;
		$this->description = $description;
		$this->parent = $parent;

		$this->child = new ArrayCollection();
		$this->roles = new ArrayCollection();
	}

	/**
	 * @return Collection<int, Permission>
	 */
	public function getChild(): Collection
	{
		return $this->child;
	}

	/**
	 * @return string
	 */
	public function getDescription(): string
	{
		return $this->description;
	}

	/**
	 * @return string
	 */
	public function getName(): string
	{
		return $this->name;
	}

	/**
	 * @return Permission|null
	 */
	public function getParent(): ?Permission
	{
		return $this->parent;
	}

	public function getParentValue(): string
	{
		if($this->parent === null) {
			return $this->value;
		}

		return $this->parent->value;
	}

	/**
	 * @param string $name
	 */
	public function setName(string $name): void
	{
		$this->name = $name;
	}

	/**
	 * @return Collection<int, Role>
	 */
	public function getRoles(): Collection
	{
		return $this->roles;
	}

	/**
	 * @return string
	 */
	public function getValue(): string
	{
		return $this->value;
	}

	/**
	 * @param string $value
	 */
	public function setValue(string $value): void
	{
		$this->value = $value;
	}

	/**
	 * @param string $description
	 */
	public function setDescription(string $description): void
	{
		$this->description = $description;
	}

	/**
	 * @param Permission|null $parent
	 */
	public function setParent(?Permission $parent): void
	{
		$this->parent = $parent;
	}

	/**
	 * @param Collection $child
	 */
	public function setChild(Collection $child): void
	{
		$this->child = $child;
	}

	/**
	 * @param Collection $roles
	 */
	public function setRoles(Collection $roles): void
	{
		$this->roles = $roles;
	}
}
