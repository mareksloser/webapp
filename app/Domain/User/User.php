<?php declare(strict_types = 1);

namespace App\Domain\User;

use App\Domain\Auth\Role\Role;
use App\Model\Database\Entity\AbstractEntity;
use App\Model\Database\Entity\TCreatedAt;
use App\Model\Database\Entity\TId;
use App\Model\Database\Entity\TUpdatedAt;
use App\Model\Exception\Logic\InvalidArgumentException;
use App\Model\Security\Identity;
use DateTime;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: "users")]
#[ORM\HasLifecycleCallbacks]
class User extends AbstractEntity
{

	use TId;
	use TCreatedAt;
	use TUpdatedAt;

	public const
		StateFresh = 1,
		StateActivated = 2,
		StateBlocked = 3,
		States = [self::StateFresh, self::StateBlocked, self::StateActivated];

	public const
		GenderFemale = 0,
		GenderMale = 1,
		Genders = [self::GenderFemale, self::GenderMale];

	#[ORM\Column(type: 'string', length: 255, unique: true, nullable: false)]
	private string $email;

	#[ORM\Column(type: 'string', length: 255, nullable: false)]
	private string $password;

	#[ORM\Column(type: 'string', length: 255, unique: false, nullable: false)]
	private string $firstname;

	#[ORM\Column(type: 'string', length: 255, unique: false, nullable: false)]
	private string $lastname;

	#[ORM\Column(type: 'integer', length: 10, nullable: false)]
	private int $gender;

	#[ORM\Column(type: 'integer', length: 10, nullable: false)]
	private int $state;

	#[ORM\ManyToOne(targetEntity: Role::class)]
	private string $role;

	/** @var DateTimeImmutable|NULL */
	#[ORM\Column(type: 'datetime_immutable', nullable: false)]
	private ?DateTimeImmutable $lastLoggedAt = null;

	public function __construct(string $firstname, string $lastname, string $email, string $username, string $passwordHash)
	{
		$this->firstname    = $firstname;
		$this->lastname     = $lastname;
		$this->email        = $email;
		$this->password     = $passwordHash;

		$this->role         = Role::RoleAuthenticated;
		$this->state        = self::StateFresh;
	}

	public function changeLoggedAt(): void
	{
		$this->lastLoggedAt = new DateTimeImmutable();
	}

	public function getEmail(): string
	{
		return $this->email;
	}

	public function getLastLoggedAt(): ?DateTimeImmutable
	{
		return $this->lastLoggedAt;
	}

	public function getRole(): string
	{
		return $this->role;
	}

	public function setRole(string $role): void
	{
		$this->role = $role;
	}

	public function getPasswordHash(): string
	{
		return $this->password;
	}

	public function changePasswordHash(string $password): void
	{
		$this->password = $password;
	}

	public function block(): void
	{
		$this->state = self::StateBlocked;
	}

	public function activate(): void
	{
		$this->state = self::StateActivated;
	}

	public function isActivated(): bool
	{
		return $this->state === self::StateActivated;
	}

	public function getFirstname(): string
	{
		return $this->firstname;
	}

	public function getLastname(): string
	{
		return $this->lastname;
	}

	public function getFullname(): string
	{
		return $this->firstname . ' ' . $this->lastname;
	}

	public function rename(string $firstname, string $lastname): void
	{
		$this->firstname = $firstname;
		$this->lastname = $lastname;
	}

	public function getState(): int
	{
		return $this->state;
	}

	public function getGender(): int
	{
		return $this->gender;
	}

	public function setState(int $state): void
	{
		if (!in_array($state, self::States, true)) {
			throw new InvalidArgumentException(sprintf('Unsupported state %s', $state));
		}

		$this->state = $state;
	}

	public function setGender(int $gender): void
	{
		if (!in_array($gender, self::Genders, true)) {
			throw new InvalidArgumentException(sprintf('Unsupported gender %s', $gender));
		}

		$this->gender = $gender;
	}

	public function toIdentity(): Identity
	{
		return new Identity($this->getId(), [$this->role], [
			'email' => $this->email,
			'firstname' => $this->firstname,
			'lastname' => $this->lastname,
			'state' => $this->state,
		]);
	}

}
