<?php

namespace App\Entity;

use Cocur\Slugify\Slugify;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\MovieRepository")
 */
class Movie
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;
    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Le titre est obligatoire")
     */
    private $title;
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $slug;
    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="L'affiche est obligatoire")
     * @Assert\Url
     */
    private $poster;
    /**
     * @ORM\Column(type="datetime")
     */
    private $releaseAt;
    /**
     * @ORM\Column(type="text")
     */
    private $synopsis;
    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Category", inversedBy="movies")
     * @Assert\NotBlank(message="Le résumé est obligatoire")
     */
    private $categories;
    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\People", mappedBy="actedIn")
     * @Assert\NotBlank(message="Les acteurs sont obligatoires")
     * @Assert\Count(min=1, minMessage="Sélectionner au moins un acteur ou actrice")
     */
    private $actors;
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\People", inversedBy="directed")
     * @Assert\NotBlank(message="Le réalisateur ou réalisatrice est obligatoire")
     */
    private $director;
    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Rating", mappedBy="movie", orphanRemoval=true)
     */
    private $ratings;
    public function __construct()
    {
        $this->categories = new ArrayCollection();
        $this->actors = new ArrayCollection();
        $this->ratings = new ArrayCollection();
    }

    /**
     * @ORM\PrePersist
     */
    public function onCreate()
    {
        if (empty($this->slug)) {
            $slugify = new Slugify();
            $this->slug = $slugify->slugify($this->title);
        }
    }

    public function getAverageRating()
    {
        if ($this->ratings->count() == 0) {
            return 0;
        }

        $total = 0;

        foreach ($this->ratings as $rating) {
            $total += $rating->getNotation();
        }

        $average = round($total / $this->ratings->count(), 1);

        return $average;
    }

    public function getId(): ?int
    {
        return $this->id;
    }
    public function getTitle(): ?string
    {
        return $this->title;
    }
    public function setTitle(string $title): self
    {
        $this->title = $title;
        return $this;
    }
    public function getSlug(): ?string
    {
        return $this->slug;
    }
    public function setSlug(?string $slug): self
    {
        $this->slug = $slug;
        return $this;
    }
    public function getPoster(): ?string
    {
        return $this->poster;
    }
    public function setPoster(string $poster): self
    {
        $this->poster = $poster;
        return $this;
    }
    public function getReleaseAt(): ?\DateTimeInterface
    {
        return $this->releaseAt;
    }
    public function setReleaseAt(\DateTimeInterface $releaseAt): self
    {
        $this->releaseAt = $releaseAt;
        return $this;
    }
    public function getSynopsis(): ?string
    {
        return $this->synopsis;
    }
    public function setSynopsis(string $synopsis): self
    {
        $this->synopsis = $synopsis;
        return $this;
    }
    /**
     * @return Collection|Category[]
     */
    public function getCategories(): Collection
    {
        return $this->categories;
    }
    public function addCategory(Category $category): self
    {
        if (!$this->categories->contains($category)) {
            $this->categories[] = $category;
        }
        return $this;
    }
    public function removeCategory(Category $category): self
    {
        if ($this->categories->contains($category)) {
            $this->categories->removeElement($category);
        }
        return $this;
    }
    /**
     * @return Collection|People[]
     */
    public function getActors(): Collection
    {
        return $this->actors;
    }
    public function addActor(People $actor): self
    {
        if (!$this->actors->contains($actor)) {
            $this->actors[] = $actor;
            $actor->addActedIn($this);
        }
        return $this;
    }
    public function removeActor(People $actor): self
    {
        if ($this->actors->contains($actor)) {
            $this->actors->removeElement($actor);
            $actor->removeActedIn($this);
        }
        return $this;
    }
    public function getDirector(): ?People
    {
        return $this->director;
    }
    public function setDirector(?People $director): self
    {
        $this->director = $director;
        return $this;
    }
    /**
     * @return Collection|Rating[]
     */
    public function getRatings(): Collection
    {
        return $this->ratings;
    }
    public function addRating(Rating $rating): self
    {
        if (!$this->ratings->contains($rating)) {
            $this->ratings[] = $rating;
            $rating->setMovie($this);
        }
        return $this;
    }
    public function removeRating(Rating $rating): self
    {
        if ($this->ratings->contains($rating)) {
            $this->ratings->removeElement($rating);
            // set the owning side to null (unless already changed)
            if ($rating->getMovie() === $this) {
                $rating->setMovie(null);
            }
        }
        return $this;
    }
}
