<?php declare(strict_types = 1);

namespace App\Domain\Setup\Config;

use App\Model\Database\Repository\AbstractRepository;

/**
 * @method Config|NULL find($id, ?int $lockMode = NULL, ?int $lockVersion = NULL)
 * @method Config|NULL findOneBy(array $criteria, array $orderBy = NULL)
 * @method Config[] findAll()
 * @method Config[] findBy(array $criteria, array $orderBy = NULL, ?int $limit = NULL, ?int $offset = NULL)
 * @extends AbstractRepository<Config>
 */
class ConfigRepository extends AbstractRepository
{

}
