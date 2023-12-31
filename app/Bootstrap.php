<?php declare(strict_types = 1);

namespace App;

use Contributte\Bootstrap\ExtraConfigurator;
use Nette\Application\Application as NetteApplication;
use Nette\DI\Compiler;
use Symfony\Component\Console\Application as SymfonyApplication;
use Symfony\Component\Dotenv\Dotenv;
use Tracy\Debugger;

final class Bootstrap
{

	public static function boot(?string $envPath = null): ExtraConfigurator
	{
		// ENVIRONMENT settings
		$_ENV = array_merge(getenv(), $_ENV);
		$dotenv = new Dotenv();
		$dotenv->overload($envPath ?? __DIR__ . '/../.env');

		$configurator = new ExtraConfigurator();
		$configurator->setTempDirectory(__DIR__ . '/../var/tmp');

		// @phpstan-ignore-next-line
		$configurator->onCompile[] = function (ExtraConfigurator $configurator, Compiler $compiler): void {
			// Add env variables to config structure
			$compiler->addConfig(['parameters' => $configurator->getEnvironmentParameters()]);
		};

		// According to NETTE_DEBUG env
		$configurator->setDebugMode($_ENV['NETTE_DEBUG'] === '1');

		// Enable tracy and configure it
		$configurator->enableTracy(__DIR__ . '/../var/log');
		Debugger::$errorTemplate = __DIR__ . '/../resources/tracy/500.phtml';

		// Provide some parameters
		$configurator->addStaticParameters([
			'rootDir' => realpath(__DIR__ . '/..'),
			'appDir' => __DIR__,
			'wwwDir' => realpath(__DIR__ . '/../www'),
		]);

		// Load development or production config
		if (getenv('NETTE_ENV', true) === 'dev') {
			$configurator->addConfig(__DIR__ . '/../config/env/dev.neon');
		} else {
			$configurator->addConfig(__DIR__ . '/../config/env/prod.neon');
		}

		$configurator->addConfig(__DIR__ . '/../config/local.neon');

		return $configurator;
	}

	public static function runWeb(): void
	{
		self::boot()
			->addStaticParameters([
				'scope' => 'web',
			])
			->createContainer()
			->getByType(NetteApplication::class)
			->run();
	}

	public static function runCli(): void
	{
		self::boot()
			->addStaticParameters([
				'scope' => 'cli',
			])
			->createContainer()
			->getByType(SymfonyApplication::class)
			->run();
	}

}
