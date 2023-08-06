<?php declare(strict_types = 1);

namespace App\UI\Control;

use App\Model\Attributes\Resource;
use App\Model\Exception\Runtime\PermissionException;
use App\Model\Helper\ClassParser;
use App\UI\Modules\Base\BasePresenter;
use Nette\Application\AbortException;
use Nette\Application\ForbiddenRequestException;
use Nette\Application\UI\InvalidLinkException;
use Nette\InvalidStateException;
use ReflectionClass;
use ReflectionException;
use ReflectionMethod;

/**
 * @mixin BasePresenter
 */
trait TCheckRequirements
{

	/**
	 * @throws ForbiddenRequestException
	 * @throws InvalidLinkException
	 * @throws AbortException
	 * @throws \ReflectionException
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

		throw new ForbiddenRequestException('You have no permission. Is required: ' . $resource);
	}

	/**
	 * @template T of mixed
	 * @param T $element
	 * @throws ReflectionException
	 */
	public function findResource(mixed $element): string
	{
		$resource = null;

		if ($element instanceof ReflectionClass) {
			// Component signal
			if ($this->presenter->getSignal() !== null) {
				bdump([$this->presenter->getSignal(), 'Component signal']);

			// Presenter
			} else {
				if (class_exists($element->name)) {
					$reflection = new ReflectionClass($element->name);

					$resource = ClassParser::getArgument($reflection, Resource::class);
				}
			}
		} elseif ($element instanceof ReflectionMethod) {

			$reflection = new ReflectionMethod($element->class, $element->name);
			$resource = ClassParser::getArgument($reflection, Resource::class);

			// If Presenter signal or action argument isn't found - try to found presenter argument
			if ($resource === null) {
				$refParent = new ReflectionClass($element->class);
				$resource = ClassParser::getArgument($refParent, Resource::class);
			}
		}

		if ($resource === null) {
			throw new PermissionException('No permission found in file: ' . $element->name);
		}

		return $resource;
	}

	protected function isAllowed(string $resource): bool
	{
		$isAllowed = false;

		try {
			$isAllowed = $this->user->isAllowed($resource);

		} catch (InvalidStateException $e) {
			$parentParse = explode(':', $resource);
			$parentName = $parentParse[0] ?? null;

			$this->permissionFacade->createPermission([
				'name' => $resource,
				'value' => $resource,
				'description' => null,
				'parent' => $parentName,
			]);

			$this->redirect('this');
		}

		return $isAllowed;
	}

}
