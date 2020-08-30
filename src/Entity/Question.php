<?php

namespace App\Entity;

use App\Repository\QuestionRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=QuestionRepository::class)
 */
class Question
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Quiz::class, inversedBy="questions")
     */
    private $Quiz;

    /**
     * @ORM\Column(type="text")
     */
    private $Contenu;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $Ans1;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $Ans2;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $Ans3;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $Ans4;

    /**
     * @ORM\Column(type="integer")
     */
    private $CorrectAns;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getQuiz(): ?Quiz
    {
        return $this->Quiz;
    }

    public function setQuiz(?Quiz $Quiz): self
    {
        $this->Quiz = $Quiz;

        return $this;
    }

    public function getContenu(): ?string
    {
        return $this->Contenu;
    }

    public function setContenu(string $Contenu): self
    {
        $this->Contenu = $Contenu;

        return $this;
    }

    public function getAns1(): ?string
    {
        return $this->Ans1;
    }

    public function setAns1(?string $Ans1): self
    {
        $this->Ans1 = $Ans1;

        return $this;
    }

    public function getAns2(): ?string
    {
        return $this->Ans2;
    }

    public function setAns2(?string $Ans2): self
    {
        $this->Ans2 = $Ans2;

        return $this;
    }

    public function getAns3(): ?string
    {
        return $this->Ans3;
    }

    public function setAns3(?string $Ans3): self
    {
        $this->Ans3 = $Ans3;

        return $this;
    }

    public function getAns4(): ?string
    {
        return $this->Ans4;
    }

    public function setAns4(?string $Ans4): self
    {
        $this->Ans4 = $Ans4;

        return $this;
    }

    public function getCorrectAns(): ?int
    {
        return $this->CorrectAns;
    }

    public function setCorrectAns(int $CorrectAns): self
    {
        $this->CorrectAns = $CorrectAns;

        return $this;
    }
}
