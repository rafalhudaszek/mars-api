<?php

namespace App\Entity;

use App\Repository\MeasurementRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=MeasurementRepository::class)
 */
class Measurement
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $date;

    /**
     * @ORM\Column(type="float")
     */
    private $tmax;

    /**
     * @ORM\Column(type="float")
     */
    private $tavg;

    /**
     * @ORM\Column(type="float")
     */
    private $tmin;

    /**
     * @ORM\Column(type="float")
     */
    private $pavg;

    /**
     * @ORM\Column(type="float")
     */
    private $wind;

    /**
     * @ORM\Column(type="array", nullable=true)
     */
    private $compass = [];

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): self
    {
        $this->id = $id;
        return $this;
    }

    public function getDate(): ?string
    {
        return $this->date;
    }

    public function setDate(string $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getTmax(): ?float
    {
        return $this->tmax;
    }

    public function setTmax(float $tmax): self
    {
        $this->tmax = $tmax;

        return $this;
    }

    public function getTavg(): ?float
    {
        return $this->tavg;
    }

    public function setTavg(float $tavg): self
    {
        $this->tavg = $tavg;

        return $this;
    }

    public function getTmin(): ?float
    {
        return $this->tmin;
    }

    public function setTmin(float $tmin): self
    {
        $this->tmin = $tmin;

        return $this;
    }

    public function getPavg(): ?float
    {
        return $this->pavg;
    }

    public function setPavg(float $pavg): self
    {
        $this->pavg = $pavg;

        return $this;
    }

    public function getWind(): ?float
    {
        return $this->wind;
    }

    public function setWind(float $wind): self
    {
        $this->wind = $wind;

        return $this;
    }

    public function getCompass(): ?array
    {
        return $this->compass;
    }

    public function setCompass(?array $compass): self
    {
        $this->compass = $compass;

        return $this;
    }
}
