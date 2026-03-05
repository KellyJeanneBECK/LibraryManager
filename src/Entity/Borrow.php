<?php

namespace App\Entity;

use App\Repository\BorrowRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BorrowRepository::class)]
class Borrow
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $borrowDate = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $returnDate = null;

    #[ORM\Column(length: 25, options: ['deafult' => 'in_session'])]
    private ?string $status = null;

    #[ORM\ManyToOne(inversedBy: 'borrow')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Book $book = null;

    #[ORM\ManyToOne(inversedBy: 'borrow')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBorrowDate(): ?\DateTimeImmutable
    {
        return $this->borrowDate;
    }

    public function setBorrowDate(\DateTimeImmutable $borrowDate): static
    {
        $this->borrowDate = $borrowDate;

        return $this;
    }

    public function getReturnDate(): ?\DateTimeImmutable
    {
        return $this->returnDate;
    }

    public function setReturnDate(?\DateTimeImmutable $returnDate): static
    {
        $this->returnDate = $returnDate;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getBook(): ?Book
    {
        return $this->book;
    }

    public function setBook(?Book $book): static
    {
        $this->book = $book;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }
}