<?php declare(strict_types=1);

namespace App\Domain\Auth\Role;

use App\Model\Database\Repository\AbstractRepository;

/**
 * @method Role|NULL find($id, ?int $lockMode = NULL, ?int $lockVersion = NULL)
 * @method Role|NULL findOneBy(array $criteria, array $orderBy = NULL)
 * @method Role[] findAll()
 * @method Role[] findBy(array $criteria, array $orderBy = NULL, ?int $limit = NULL, ?int $offset = NULL)
 * @extends AbstractRepository<Role>
 */
class RoleRepository extends AbstractRepository
{

}
