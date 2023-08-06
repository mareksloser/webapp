<?php declare(strict_types = 1);

namespace App\UI\Modules\Base;

use App\Domain\Auth\Permission\CreatePermissionFacade;
use App\Model\Latte\TemplateProperty;
use App\Model\Security\SecurityUser;
use App\UI\Control\TCheckRequirements;
use App\UI\Control\TModuleUtils;
use Contributte\Application\UI\Presenter\StructuredTemplates;
use Nette\Application\UI\Presenter;
use Nette\DI\Attributes\Inject;

/**
 * @property-read TemplateProperty $template
 * @property-read SecurityUser $user
 */
abstract class BasePresenter extends Presenter
{

	use StructuredTemplates;
	use TCheckRequirements;
	//use TFlashMessage;
	use TModuleUtils;

	#[Inject]
	public CreatePermissionFacade $permissionFacade;

}
