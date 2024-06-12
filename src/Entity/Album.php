<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\AlbumRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: AlbumRepository::class)]
class Album
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['read'])]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    #[Groups(['read'])]
    private ?string $name = null;

    #[ORM\Column]
    #[Groups(['read','create','update'])]
    private ?int $year = null;

    /**
     * @var Collection<int, Track>
     */
    #[ORM\OneToMany(targetEntity: Track::class, mappedBy: 'album')]
    // #[Groups(['read'])]
    private Collection $albumTracks;

    #[ORM\ManyToOne(inversedBy: 'artistAlbum')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Artist $artist = null;

    public function __construct()
    {
        $this->albumTracks = new ArrayCollection();
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

    public function getYear(): ?int
    {
        return $this->year;
    }

    public function setYear(int $year): static
    {
        $this->year = $year;

        return $this;
    }

    /**
     * @return Collection<int, Track>
     */
    public function getAlbumTracks(): Collection
    {
        return $this->albumTracks;
    }

    public function addAlbumTrack(Track $albumTrack): static
    {
        if (!$this->albumTracks->contains($albumTrack)) {
            $this->albumTracks->add($albumTrack);
            $albumTrack->setAlbum($this);
        }

        return $this;
    }

    public function removeAlbumTrack(Track $albumTrack): static
    {
        if ($this->albumTracks->removeElement($albumTrack)) {
            // set the owning side to null (unless already changed)
            if ($albumTrack->getAlbum() === $this) {
                $albumTrack->setAlbum(null);
            }
        }

        return $this;
    }

    public function getArtist(): ?Artist
    {
        return $this->artist;
    }

    public function setArtist(?Artist $artist): static
    {
        $this->artist = $artist;

        return $this;
    }
}
