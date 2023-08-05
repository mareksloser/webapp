<?php declare(strict_types=1);

namespace Database\Fixtures;

use App\Domain\Auth\Permission\Permission;
use App\Domain\Auth\Role\Role;
use Doctrine\Persistence\ObjectManager;


class RolePermissionInitFixture extends AbstractFixture
{
	public function getOrder(): int
	{
		return 1;
	}

	public function load(ObjectManager $manager): void
	{
		$entityPermission = new Permission('Web', 'Web');
		$manager->persist($entityPermission);

		# Role + permission
		foreach ($this->getRoles() as $role)
		{
			$entity = new Role(
				$role['name'],
				$role['key']
			);
			$entity->addPermission($entityPermission);

			$manager->persist($entity);
		}

		$manager->flush();
	}

	/**
	 * @return mixed[]
	 */
	protected function getRoles(): iterable
	{
		yield ['name' => 'Guest', 'key' => Role::RoleGuest];
	}
}
