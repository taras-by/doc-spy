<?php

namespace App\Traits;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

trait EnableStatusTrait
{

    /**
     * @Assert\NotNull()
     * @ORM\Column(name="is_enabled", type="boolean", options={"default":false})
     */
    private $isEnabled = false;

    public function setIsEnabled(bool $isEnabled): self
    {
        $this->isEnabled = $isEnabled;

        return $this;
    }

    public function isEnabled(): bool
    {
        return $this->isEnabled;
    }

    public static function getEnableLabels()
    {
        return [
            false => 'Disable',
            true => 'Enable',
        ];
    }

    public static function getEnableLabelClasses()
    {
        return [
            false => 'danger',
            true => 'success',
        ];
    }

    public function getEnableLabel(): string
    {
        return self::getEnableLabels()[$this->isEnabled];
    }

    public function getEnableLabelClass(): string
    {
        return self::getEnableLabelClasses()[$this->isEnabled];
    }

    public static function getEnableChoices(): array
    {
        return array_flip(self::getEnableLabels());
    }
}
