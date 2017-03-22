<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="genus_scientist")
 * @UniqueEntity(
 *     fields={"genus", "user"},
 *     message="This user is already studying this genus",
 *     errorPath="user"
 * )
 */
class GenusScientist
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="Genus", inversedBy="genusScientists")
     * @ORM\JoinColumn(nullable=false)
     */
    private $genus;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="studiedGenuses")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotBlank()
     */
    private $yearsStudied;

    public function getId()
    {
        return $this->id;
    }

    public function getGenus()
    {
        return $this->genus;
    }

    public function setGenus($genus)
    {
        $this->genus = $genus;
    }

    public function getUser()
    {
        return $this->user;
    }

    public function setUser($user)
    {
        $this->user = $user;
    }

    public function getYearsStudied()
    {
        return $this->yearsStudied;
    }

    public function setYearsStudied($yearsStudied)
    {
        $this->yearsStudied = $yearsStudied;
    }
}