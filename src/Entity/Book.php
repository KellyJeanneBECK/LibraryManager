<?php

namespace App\Entity;

use App\Repository\BookRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BookRepository::class)]
class Book
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column(length: 255)]
    private ?string $author = null;

    #[ORM\Column(length: 13, unique: true)]
    private ?string $isbn = null;

    #[ORM\Column(options: ['default' =>  0])]
    private ?int $stock = null;

    /**
     * @var Collection<int, Borrow>
     */
    #[ORM\OneToMany(targetEntity: Borrow::class, mappedBy: 'book')]
    private Collection $borrow;

    public function __construct()
    {
        $this->borrow = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getAuthor(): ?string
    {
        return $this->author;
    }

    public function setAuthor(string $author): static
    {
        $this->author = $author;

        return $this;
    }

    public function getIsbn(): ?string
    {
        return $this->isbn;
    }

    public function setIsbn(string $isbn): static
    {
        $this->isbn = $isbn;

        return $this;
    }

    public function getStock(): ?int
    {
        return $this->stock;
    }

    public function setStock(int $stock): static
    {
        $this->stock = $stock;

        return $this;
    }

    /**
     * @return Collection<int, Borrow>
     */
    public function getBorrow(): Collection
    {
        return $this->borrow;
    }

    public function addBorrow(Borrow $borrow): static
    {
        if (!$this->borrow->contains($borrow)) {
            $this->borrow->add($borrow);
            $borrow->setBook($this);
        }

        return $this;
    }

    public function removeBorrow(Borrow $borrow): static
    {
        if ($this->borrow->removeElement($borrow)) {
            // set the owning side to null (unless already changed)
            if ($borrow->getBook() === $this) {
                $borrow->setBook(null);
            }
        }

        return $this;
    }
}