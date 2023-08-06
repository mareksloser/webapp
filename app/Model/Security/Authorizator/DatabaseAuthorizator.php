<?php declare(strict_types = 1);

namespace App\Model\Security\Authorizator;

use App\Domain\Auth\Permission\Permission;
use App\Domain\Auth\Role\Role;
use App\Model\Database\EntityManagerDecorator;
use Nette\Security\Authorizator;
use Nette\Security\Permission as NettePermission;

class DatabaseAuthorizator extends NettePermission
{

	private EntityManagerDecorator $em;

	/**
	 * Create ACL
	 */
	public function __construct(EntityManagerDecorator $em)
	{
		$this->em = $em;

		$this->create();
	}

	protected function create(): void
	{
		$roleRepository = $this->em->getRepository(Role::class);
		$roles = $roleRepository->findAll();

		$permissionRepository = $this->em->getRepository(Permission::class);
		$permissions = $permissionRepository->findAll();

		/**
		 * Setup roles
		 * @var Role $role
		 */
		foreach ($roles as $role) {
			$this->addRole(
				$role->getKey(),
				$role->getParentKey() !== $role->getKey() ? $role->getParentKey() : null
			);
		}

		/**
		 * Define resources
		 * @var Permission $permission
		 */
		foreach ($permissions as $permission) {
			$this->addResource(
				$permission->getValue(),
				$permission->getParentValue() !== $permission->getValue() ? $permission->getParentValue() : null
			);
		}

		/**
		 * Allow resources to role
		 * @var Role $role
		 */
		foreach ($roles as $role) {
			if ($role->isSuperRole()) {
				$this->allow($role->getKey(), Authorizator::ALL, Authorizator::ALL);

				continue;
			}

			/**
			 * @var Permission $permission
			 */
			foreach ($role->getPermissions() as $permission) {
				$this->allow($role->getKey(), $permission->getValue());
			}
		}
	}

}
