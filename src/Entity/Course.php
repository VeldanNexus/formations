<?php

namespace App\Entity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use App\Repository\CourseRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CourseRepository::class)]
class Course
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private string $title;

    #[ORM\OneToMany(mappedBy: 'course', targetEntity: CourseSchedule::class, cascade: ['persist', 'remove'])]
    private Collection $schedules;


// ...
#[ORM\OneToMany(mappedBy: 'course', targetEntity: CourseSubscription::class, cascade: ['persist', 'remove'])]
private Collection $subscriptions;

 

public function getSubscriptions(): Collection
{
    return $this->subscriptions;
}

    public function __construct()
    {
         $this->schedules = new ArrayCollection();
    $this->subscriptions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;
        return $this;
    }

    /**
     * @return Collection<int, CourseSchedule>
     */
    public function getSchedules(): Collection
    {
        return $this->schedules;
    }

    public function addSchedule(CourseSchedule $schedule): self
    {
        if (!$this->schedules->contains($schedule)) {
            $this->schedules[] = $schedule;
            $schedule->setCourse($this);
        }
        return $this;
    }

    public function removeSchedule(CourseSchedule $schedule): self
    {
        if ($this->schedules->removeElement($schedule)) {
            if ($schedule->getCourse() === $this) {
                $schedule->setCourse(null);
            }
        }
        return $this;
    }
    //added for entity coversion to string  in the dropdown of the add schedule view 
    public function __toString(): string
    {
        return $this->title; // This is what will be displayed in the dropdown
    }
}