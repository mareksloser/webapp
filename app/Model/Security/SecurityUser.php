<?php declare(strict_types=1);

namespace App\Model\Security;

use App\Domain\User\User;
use Nette\Security\User as NetteUser;

/**
 * @method Identity getIdentity()
 */
class SecurityUser extends NetteUser
{
	public function isAdmin(): bool
	{
		return $this->isInRole(User::ROLE_ADMIN);
	}
}
