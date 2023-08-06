<?php declare(strict_types = 1);

namespace App\Domain\Auth\Permission;

use App\Model\Database\Repository\AbstractRepository;

/**
 * @method Permission|NULL find($id, ?int $lockMode = NULL, ?int $lockVersion = NULL)
 * @method Permission|NULL findOneBy(array $criteria, array $orderBy = NULL)
 * @method Permission[] findAll()
 * @method Permission[] findBy(array $criteria, array $orderBy = NULL, ?int $limit = NULL, ?int $offset = NULL)
 * @extends AbstractRepository<Permission>
 */
class PermissionRepository extends AbstractRepository
{

}
