<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\SettingsRepository")
 */
class Settings
{
    const TYPE_ABOUT = 1;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Assert\NotBlank()
     *
     * @ORM\Column(type="integer")
     */
    private $type;

    /**
     * @Gedmo\Translatable()
     *
     * @Assert\NotBlank()
     *
     * @ORM\Column(type="text")
     */
    private $body;

    public function getHumanType(): string
    {
        return self::getTypes(false)[$this->getType()];
    }

    static public function getTypes(?bool $flip = true): array
    {
        $result = [
            self::TYPE_ABOUT => 'settings_type_about',
        ];

        return $flip ? array_flip($result) : $result;
    }

    public function __toString()
    {
        return (string)'';
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param mixed $type
     */
    public function setType($type): self
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * @param mixed $body
     */
    public function setBody($body): self
    {
        $this->body = $body;

        return $this;
    }





}
