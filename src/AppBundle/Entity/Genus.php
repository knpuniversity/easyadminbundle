<?php
declare(strict_types = 1);

namespace AppBundle\Entity;

use AppBundle\Repository\GenusRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;


/**
 * @ORM\Entity(repositoryClass="AppBundle\Repository\GenusRepository")
 * @ORM\Table(name="genus")
 */
class Genus
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Assert\NotBlank()
     * @ORM\Column(type="string")
     */
    private $name;

    /**
     * @ORM\Column(type="string", unique=true)
     * @Gedmo\Slug(fields={"name"})
     */
    private $slug;

    /**
     * @Assert\NotBlank()
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\SubFamily")
     * @ORM\JoinColumn(nullable=false)
     */
    private $subFamily;

    /**
     * @Assert\NotBlank()
     * @Assert\Range(min=0, minMessage="Negative species! Come on...")
     * @ORM\Column(type="integer")
     */
    private $speciesCount;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $funFact;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isPublished = true;

    /**
     * @Assert\NotBlank()
     * @ORM\Column(type="date")
     */
    private $firstDiscoveredAt;

    /**
     * @ORM\OneToMany(targetEntity="GenusNote", mappedBy="genus")
     * @ORM\OrderBy({"createdAt" = "DESC"})
     */
    private $notes;

    /**
     * @ORM\OneToMany(
     *     targetEntity="GenusScientist",
     *     mappedBy="genus",
     *     fetch="EXTRA_LAZY",
     *     orphanRemoval=true,
     *     cascade={"persist"}
     * )
     * @Assert\Valid()
     */
    private $genusScientists;

    public function __construct()
    {
        $this->notes = new ArrayCollection();
        $this->genusScientists = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): void
    {
        $this->name = $name;
    }

    public function getSubFamily(): ?SubFamily
    {
        return $this->subFamily;
    }

    public function setSubFamily(SubFamily $subFamily = null): void
    {
        $this->subFamily = $subFamily;
    }

    public function getSpeciesCount(): ?int
    {
        return $this->speciesCount;
    }

    public function setSpeciesCount(?int $speciesCount): void
    {
        $this->speciesCount = $speciesCount;
    }

    public function getFunFact(): ?string
    {
        return $this->funFact;
    }

    public function setFunFact(?string $funFact): void
    {
        $this->funFact = $funFact;

        return;
    }

    public function getUpdatedAt(): \DateTimeInterface
    {
        return new \DateTime('-'.rand(0, 100).' days');
    }

    public function setIsPublished(bool $isPublished): void
    {
        $this->isPublished = $isPublished;
    }

    public function getIsPublished(): bool
    {
        return $this->isPublished;
    }

    /**
     * @return Collection|GenusNote[]
     */
    public function getNotes(): Collection
    {
        return $this->notes;
    }

    public function getFirstDiscoveredAt(): ?\DateTimeInterface
    {
        return $this->firstDiscoveredAt;
    }

    public function setFirstDiscoveredAt(\DateTime $firstDiscoveredAt = null): void
    {
        $this->firstDiscoveredAt = $firstDiscoveredAt;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(?string $slug): void
    {
        $this->slug = $slug;
    }

    public function addGenusScientist(GenusScientist $genusScientist): void
    {
        if ($this->genusScientists->contains($genusScientist)) {
            return;
        }

        $this->genusScientists[] = $genusScientist;
        // needed to update the owning side of the relationship!
        $genusScientist->setGenus($this);
    }

    public function removeGenusScientist(GenusScientist $genusScientist): void
    {
        if (!$this->genusScientists->contains($genusScientist)) {
            return;
        }

        $this->genusScientists->removeElement($genusScientist);
        // needed to update the owning side of the relationship!
        $genusScientist->setGenus(null);
    }

    /**
     * @return Collection|GenusScientist[]
     */
    public function getGenusScientists(): Collection
    {
        return $this->genusScientists;
    }

    /**
     * @return \Doctrine\Common\Collections\Collection|GenusScientist[]
     */
    public function getExpertScientists(): Collection
    {
        return $this->getGenusScientists()->matching(
            GenusRepository::createExpertCriteria()
        );
    }

    public function feed(iterable $iterableFood): string
    {
        $food = $iterableFood;

        if ($iterableFood instanceof \Traversable) {
            $food = $iterableFood->getArrayCopy();
        }

        if (count($food) === 0) {
            return sprintf('%s is looking at you in a funny way', $this->getName());
        }

        return sprintf('%s recently ate: %s', $this->getName(), implode(', ', $food));
    }
}
