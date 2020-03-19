<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\VerbeRepository")
 */
class Verbe
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=2)
     */
    private $lang;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $fr;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $infinitif;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $form1;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $form2;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $level;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLang(): ?string
    {
        return $this->lang;
    }

    public function setLang(string $lang): self
    {
        $this->lang = $lang;

        return $this;
    }

    public function getFr(): ?string
    {
        return $this->fr;
    }

    public function setFr(string $fr): self
    {
        $this->fr = $fr;

        return $this;
    }

    public function getInfinitif(): ?string
    {
        return $this->infinitif;
    }

    public function setInfinitif(string $infinitif): self
    {
        $this->infinitif = $infinitif;

        return $this;
    }

    public function getForm1(): ?string
    {
        return $this->form1;
    }

    public function setForm1(?string $form1): self
    {
        $this->form1 = $form1;

        return $this;
    }

    public function getForm2(): ?string
    {
        return $this->form2;
    }

    public function setForm2(?string $form2): self
    {
        $this->form2 = $form2;

        return $this;
    }

    public function getLevel(): ?int
    {
        return $this->level;
    }

    public function setLevel(?int $level): self
    {
        $this->level = $level;

        return $this;
    }
}
