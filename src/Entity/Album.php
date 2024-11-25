<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\AlbumRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

#[ORM\Entity(repositoryClass: AlbumRepository::class)]
class Album
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private string $name;

    /**
     * @var Collection<int, Media>
     */
    #[ORM\OneToMany(targetEntity: Media::class, mappedBy: 'album', cascade: ['remove'])]
    private Collection $media;
    public function __construct()
    {
        $this->media = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }
}
