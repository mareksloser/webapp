<?php declare(strict_types = 1);

namespace App\Domain\Auth\Role;

use App\Domain\Auth\Permission\Permission;
use App\Model\Database\Entity\AbstractEntity;
use App\Model\Database\Entity\TId;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RoleRepository::class)]
#[ORM\Table(name: 'roles')]
#[ORM\HasLifecycleCallbacks]
class Role extends AbstractEntity
{

	use TId;

	public const RoleGuest = 'guest';
	public const RoleAuthenticated = 'authenticated';
	public const RoleSuperAdmin = 'super-admin';

	#[ORM\Column(type: 'string', length: 32, unique: true, nullable: false)]
	private string $name;

	#[ORM\Column(type: 'string', length: 32, unique: true, nullable: false)]
	private string $key;

	#[ORM\Column(type: 'string', length: 32, unique: false, nullable: true)]
	private ?string $class;

	#[ORM\ManyToOne(targetEntity: self::class, inversedBy: 'children')]
	private ?Role $parent;

	/** @var Collection<int, Role> */
	#[ORM\OneToMany(mappedBy: 'parent', targetEntity: self::class)]
	private Collection $children;

	/** @var Collection<int, Permission> An ArrayCollection of Permissions objects. */
	#[ORM\ManyToMany(targetEntity: Permission::class, inversedBy: 'roles')]
	#[ORM\JoinTable(name: 'roles_permissions')]
	private Collection $permissions;

	public function __construct(string $name, string $key, ?string $class = null, ?Role $parent = null)
	{
		$this->name = $name;
		$this->key = $key;
		$this->class = $class;
		$this->parent = $parent;

		$this->children = new ArrayCollection();
		$this->permissions = new ArrayCollection();
	}

	/**
	 * @return Collection<int, Role>
	 */
	public function getChildren(): Collection
	{
		return $this->children;
	}

	public function getClass(): ?string
	{
		return $this->class;
	}

	public function getKey(): string
	{
		return $this->key;
	}

	public function getName(): string
	{
		return $this->name;
	}

	public function setName(string $name): void
	{
		$this->name = $name;
	}

	public function setKey(string $key): void
	{
		$this->key = $key;
	}

	public function setClass(string $class): void
	{
		$this->class = $class;
	}

	public function getParent(): ?Role
	{
		return $this->parent;
	}

	public function setParent(?Role $parent): void
	{
		$this->parent = $parent;
	}

	public function setChildren(Role $children): void
	{
		$this->children[] = $children;
	}

	public function getParentKey(): string
	{
		if ($this->parent === null) {
			return $this->key;
		}

		return $this->parent->key;
	}

	/**
	 * @return Collection<int, Permission>
	 */
	public function getPermissions(): Collection
	{
		return $this->permissions;
	}

	public function isSuperRole(): bool
	{
		return $this->key === self::RoleSuperAdmin;
	}

	public function addPermission(Permission $permission): void
	{
		$this->permissions[] = $permission;
	}

}
