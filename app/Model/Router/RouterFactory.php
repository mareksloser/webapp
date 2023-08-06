<?php declare(strict_types = 1);

namespace App\Model\Router;

use Nette\Application\Routers\RouteList;

final class RouterFactory
{

	private RouteList $router;

	public function __construct()
	{
		$this->router = new RouteList();
	}

	public function create(): RouteList
	{
		$this->buildAdmin();
		$this->buildTool();
		$this->buildWeb();

		return $this->router;
	}

	private function buildAdmin(): void
	{
		$this->router[] = $list = new RouteList('Admin');
		$list->addRoute('/admin/<presenter>/<action>[/<id \d+>]', 'Home:default');
	}

	private function buildTool(): void
	{
		$this->router[] = $list = new RouteList('Tool');
		$list->addRoute('/tool/<presenter>/<action>[/<id \d+>]');
	}

	private function buildWeb(): void
	{
		$this->router[] = $list = new RouteList('Web');
		$list->addRoute('<presenter>/<action>[/<id \d+>]', 'Home:default');
	}

}
