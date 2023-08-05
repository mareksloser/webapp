<?php declare(strict_types=1);

namespace App\UI\Control;

use App\Domain\Auth\Permission\Permission;
use App\Model\Attributes\Resource;
use App\Model\Exception\Runtime\PermissionException;
use App\Model\Helper\ClassParser;
use App\UI\Modules\Base\BasePresenter;
use Nette;
use Nette\Application\UI\ComponentReflection;
use Nette\Application\UI\MethodReflection;


/**
 * @mixin BasePresenter
 */
trait TCheckRequirements
{
	/**
	 * @throws Nette\Application\ForbiddenRequestException
	 * @throws Nette\Application\UI\InvalidLinkException
	 * @throws Nette\Application\AbortException
	 */
	public function checkRequirements(mixed $element): void
	{
		parent::checkRequirements($element);

		$resource = $this->findResource($element);

		if ($this->isAllowed($resource)) {
			return;
		}

		//if (! $this->user->isLoggedIn() && ! $this->isLinkCurrent(Dest::AdminLogin)) {
		//	$this->redirect(Dest::AdminLogin);
		//}

		throw new Nette\Application\ForbiddenRequestException('You have no permission. Is required: ' . $resource);
	}

	public function findResource(mixed $element): ?string
	{
		$resource = null;

		if ($element instanceof ComponentReflection) {
			$reflection = new \ReflectionClass($element->name);

			$resource = ClassParser::getArgument($reflection, Resource::class);

		} elseif($element instanceof MethodReflection) {
			$reflection = new \ReflectionMethod($element->class, $element->name);

			$resource = ClassParser::getArgument($reflection, Resource::class);

			// if child has no resource, use parent resource
			if($resource === null) {
				$refParent = new \ReflectionClass($element->class);

				$resource = ClassParser::getArgument($refParent, Resource::class);
			}
		}

		if($resource === null) {
			throw new PermissionException('No permission found in file: ' . $reflection->getName());
		}

		return $resource;
	}

	protected function isAllowed(?string $resource): bool
	{
		$isAllowed = false;

		try {
			$isAllowed = $this->user->isAllowed($resource);

		} catch (Nette\InvalidStateException $e) {
			$parentParse = explode(':', $resource);
			$parentName = $parentParse[0] ?? NULL;

			$this->permissionFacade->createPermission([
				'name' => $resource,
				'value' => $resource,
				'description' => null,
				'parent' => $parentName
			]);

			$this->redirect('this');
		}


		return $isAllowed;
	}
}
