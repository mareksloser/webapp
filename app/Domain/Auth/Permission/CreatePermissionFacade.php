<?php declare(strict_types = 1);

namespace App\Domain\Auth\Permission;

use App\Model\Database\EntityManagerDecorator;

class CreatePermissionFacade
{

	private EntityManagerDecorator $em;

	public function __construct(
		EntityManagerDecorator $em
	)
	{
		$this->em = $em;
	}

	/**
	 * @param array<string, scalar|null> $data
	 */
	public function createPermission(array $data): Permission
	{
		$permission = new Permission(
			(string) $data['name'],
			(string) $data['value'],
			(string) $data['description'],
			$this->em->getRepository(Permission::class)
				->findOneBy(['value' => (string) $data['parent'], 'parent' => null])
		);

		$this->em->persist($permission);
		$this->em->flush();

		return $permission;
	}

}
