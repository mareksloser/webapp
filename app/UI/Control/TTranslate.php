<?php declare(strict_types = 1);

namespace App\UI\Control;

use App\UI\Modules\Base\BasePresenter;
use Nette\Localization\Translator;

/**
 * @mixin BasePresenter
 */
trait TTranslate
{

	protected function _(string $message): string // phpcs:ignore
	{
		return $this->getContext()->getByType(Translator::class)->translate($message);
	}

}
