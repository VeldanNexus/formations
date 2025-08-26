<?php
// src/Entity/CourseSubscription.php
namespace App\Entity;

use App\Enum\SubscriptionStatus;
use App\Repository\CourseSubscriptionRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Entity(repositoryClass: CourseSubscriptionRepository::class)]
#[ORM\Table(name: 'course_subscription')]
#[ORM\UniqueConstraint(name: 'uniq_user_course', columns: ['user_id', 'course_id'])]
#[UniqueEntity(fields: ['user', 'course'], message: 'This user is already subscribed to this course.')]
class CourseSubscription
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'courseSubscriptions')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\ManyToOne(inversedBy: 'subscriptions')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Course $course = null;

   
 

    public function __construct()
    {
     }

    public function getId(): ?int { return $this->id; }

    public function getUser(): ?User { return $this->user; }
    public function setUser(User $user): self { $this->user = $user; return $this; }

    public function getCourse(): ?Course { return $this->course; }
    public function setCourse(Course $course): self { $this->course = $course; return $this; }

 
     
}
