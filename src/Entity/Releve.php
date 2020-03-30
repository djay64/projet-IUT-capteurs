<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Releve
 *
 * @ORM\Table(name="releve", indexes={@ORM\Index(name="releve_ibfk_1", columns={"capteur_id"})})
 * @ORM\Entity(repositoryClass="App\Repository\ReleveRepository")
 */
class Releve
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var float
     *
     * @ORM\Column(name="pm25", type="float", precision=10, scale=0, nullable=false)
     */
    private $pm25;

    /**
     * @var float
     *
     * @ORM\Column(name="pm10", type="float", precision=10, scale=0, nullable=false)
     */
    private $pm10;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_heure", type="datetime", nullable=false)
     */
    private $dateHeure;

    /**
     * @var string
     *
     * @ORM\Column(name="capteur_id", type="string", length=50, nullable=false)
     */
    private $capteurId;

    /**
     * @var int|null
     *
     * @ORM\Column(name="heure", type="integer", nullable=true)
     */
    private $heure;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="date", type="date", nullable=true)
     */
    private $date;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPm25(): ?float
    {
        return $this->pm25;
    }

    public function setPm25(float $pm25): self
    {
        $this->pm25 = $pm25;

        return $this;
    }

    public function getPm10(): ?float
    {
        return $this->pm10;
    }

    public function setPm10(float $pm10): self
    {
        $this->pm10 = $pm10;

        return $this;
    }

    public function getDateHeure(): ?\DateTimeInterface
    {
        return $this->dateHeure;
    }

    public function setDateHeure(\DateTimeInterface $dateHeure): self
    {
        $this->dateHeure = $dateHeure;

        return $this;
    }

    public function getCapteurId(): ?string
    {
        return $this->capteurId;
    }

    public function setCapteurId(string $capteurId): self
    {
        $this->capteurId = $capteurId;

        return $this;
    }

    public function getHeure(): ?int
    {
        return $this->heure;
    }

    public function setHeure(?int $heure): self
    {
        $this->heure = $heure;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(?\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }


}
