<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\ArtistRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: ArtistRepository::class)]
class Artist
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['read'])]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    #[Groups(['read','create','update'])]
    private ?string $name = null;

    /**
     * @var Collection<int, Album>
     */
    #[ORM\OneToMany(targetEntity: Album::class, mappedBy: 'artist')]
    // #[Groups(['read'])]
    private Collection $artistAlbum;

    public function __construct()
    {
        $this->artistAlbum = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection<int, Album>
     */
    public function getArtistAlbum(): Collection
    {
        return $this->artistAlbum;
    }

    public function addArtistAlbum(Album $artistAlbum): static
    {
        if (!$this->artistAlbum->contains($artistAlbum)) {
            $this->artistAlbum->add($artistAlbum);
            $artistAlbum->setArtist($this);
        }

        return $this;
    }

    public function removeArtistAlbum(Album $artistAlbum): static
    {
        if ($this->artistAlbum->removeElement($artistAlbum)) {
            // set the owning side to null (unless already changed)
            if ($artistAlbum->getArtist() === $this) {
                $artistAlbum->setArtist(null);
            }
        }

        return $this;
    }
}
