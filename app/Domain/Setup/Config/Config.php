<?php declare(strict_types = 1);

namespace App\Domain\Setup\Config;

use App\Model\Database\Entity\TId;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ConfigRepository::class)]
#[ORM\Table(name: 'config')]
#[ORM\HasLifecycleCallbacks]
class Config
{

	use TId;

	protected array $unSerializeOptions = [
		's' => 'string',
		'a' => 'array',
		'b' => 'bool',
		'i' => 'int',
		'd' => 'float',
		'N;' => 'null',
	];

	#[ORM\Column(type: 'string', length: 32, unique: true, nullable: false)]
	private string $key;

	#[ORM\Column(type: 'text', length: 32, unique: false, nullable: true)]
	private ?string $data;

	public function __construct(string $key, mixed $data)
	{
		$this->key = $key;
		$this->data = serialize($data);
	}

	public function getData(): ?string
	{
		return $this->data;
	}

	public function getDataSerialized(): mixed
	{
		return unserialize($this->data, $this->unSerializeOptions);
	}

	public function getKey(): string
	{
		return $this->key;
	}

	public function setKey(string $key): void
	{
		$this->key = $key;
	}

	public function setData(mixed $data): void
	{
		$this->data = serialize($data);
	}

}
