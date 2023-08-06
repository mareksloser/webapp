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
		$appURL = getenv('APP_URL');

		$this->buildAdmin($appURL);
		$this->buildTool($appURL);
		$this->buildWeb($appURL);

		return $this->router;
	}

	private function buildAdmin(string $appURL): void
	{
		$this->router[] = $list = new RouteList('Admin');
		$list->addRoute($appURL . '/admin/<presenter>/<action>[/<id \d+>]', 'Home:default');
	}

	private function buildTool(string $appURL): void
	{
		$this->router[] = $list = new RouteList('Tool');
		$list->addRoute($appURL . '/tool/<presenter>/<action>[/<id \d+>]');
	}

	private function buildWeb(string $appURL): void
	{
		$this->router[] = $list = new RouteList('Web');
		$list->addRoute($appURL . '/<presenter>/<action>[/<id \d+>]', 'Home:default');
	}

}
