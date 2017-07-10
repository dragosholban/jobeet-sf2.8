<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use AppBundle\Utils\Jobeet as Jobeet;

/**
 * @ORM\Entity()
 * @ORM\Table(name="job")
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Entity(repositoryClass="AppBundle\Entity\JobRepository")
 */
class Job
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    
    /**
     * @ORM\ManyToOne(targetEntity="Category", inversedBy="jobs")
     * @ORM\JoinColumn(name="category_id", referencedColumnName="id")
     * @Assert\NotBlank()
     */
    private $category;
    
    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     */
    private $type;
    
    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     */
    private $company;
    
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\NotBlank()
     */
    private $logo;
    
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\NotBlank()
     */
    private $url;
    
    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     */
    private $position;
    
    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     */
    private $location;
    
    /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank()
     */
    private $description;
    
    /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank()
     */
    private $howToApply;
    
    /**
     * @ORM\Column(type="string", length=255, unique=true)
     * @Assert\NotBlank()
     */
    private $token;
    
    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $isPublic;
    
    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $isActivated;
    
    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     */
    private $email;
    
    /**
     * @ORM\Column(type="datetime")
     * @Assert\NotBlank()
     */
    private $expiresAt;
    
    /**
     * @ORM\Column(type="datetime")
     * @Assert\NotBlank()
     */
    private $createdAt;
    
    /**
     * @ORM\Column(type="datetime")
     * @Assert\NotBlank()
     */
    private $updatedAt;

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set type
     *
     * @param string $type
     * @return Job
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string 
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set company
     *
     * @param string $company
     * @return Job
     */
    public function setCompany($company)
    {
        $this->company = $company;

        return $this;
    }

    /**
     * Get company
     *
     * @return string 
     */
    public function getCompany()
    {
        return $this->company;
    }

    /**
     * Set logo
     *
     * @param string $logo
     * @return Job
     */
    public function setLogo($logo)
    {
        $this->logo = $logo;

        return $this;
    }

    /**
     * Get logo
     *
     * @return string 
     */
    public function getLogo()
    {
        return $this->logo;
    }

    /**
     * Set url
     *
     * @param string $url
     * @return Job
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Get url
     *
     * @return string 
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Set position
     *
     * @param string $position
     * @return Job
     */
    public function setPosition($position)
    {
        $this->position = $position;

        return $this;
    }

    /**
     * Get position
     *
     * @return string 
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * Set location
     *
     * @param string $location
     * @return Job
     */
    public function setLocation($location)
    {
        $this->location = $location;

        return $this;
    }

    /**
     * Get location
     *
     * @return string 
     */
    public function getLocation()
    {
        return $this->location;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Job
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set howToApply
     *
     * @param string $howToApply
     * @return Job
     */
    public function setHowToApply($howToApply)
    {
        $this->howToApply = $howToApply;

        return $this;
    }

    /**
     * Get howToApply
     *
     * @return string 
     */
    public function getHowToApply()
    {
        return $this->howToApply;
    }

    /**
     * Set token
     *
     * @param string $token
     * @return Job
     */
    public function setToken($token)
    {
        $this->token = $token;

        return $this;
    }

    /**
     * Get token
     *
     * @return string 
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * Set isPublic
     *
     * @param boolean $isPublic
     * @return Job
     */
    public function setIsPublic($isPublic)
    {
        $this->isPublic = $isPublic;

        return $this;
    }

    /**
     * Get isPublic
     *
     * @return boolean 
     */
    public function getIsPublic()
    {
        return $this->isPublic;
    }

    /**
     * Set isActivated
     *
     * @param boolean $isActivated
     * @return Job
     */
    public function setIsActivated($isActivated)
    {
        $this->isActivated = $isActivated;

        return $this;
    }

    /**
     * Get isActivated
     *
     * @return boolean 
     */
    public function getIsActivated()
    {
        return $this->isActivated;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return Job
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string 
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set expiresAt
     *
     * @param \DateTime $expiresAt
     * @return Job
     */
    public function setExpiresAt($expiresAt)
    {
        $this->expiresAt = $expiresAt;

        return $this;
    }

    /**
     * Get expiresAt
     *
     * @return \DateTime 
     */
    public function getExpiresAt()
    {
        return $this->expiresAt;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return Job
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime 
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     * @return Job
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Get updatedAt
     *
     * @return \DateTime 
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * Set category
     *
     * @param \AppBundle\Entity\Category $category
     * @return Job
     */
    public function setCategory(\AppBundle\Entity\Category $category = null)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Get category
     *
     * @return \AppBundle\Entity\Category 
     */
    public function getCategory()
    {
        return $this->category;
    }
    
    /**
     * @ORM\PrePersist
     */
    public function setCreatedAtValue()
    {
        if(!$this->getCreatedAt())
        {
            $this->createdAt = new \DateTime();
        }
    }
    
    /**
     * @ORM\PreUpdate
     * @ORM\PrePersist
     */
    public function setUpdatedAtValue()
    {
        $this->updatedAt = new \DateTime();
    }
    
    public function getCompanySlug()
    {
        return Jobeet::slugify($this->getCompany());
    }
 
    public function getPositionSlug()
    {
        return Jobeet::slugify($this->getPosition());
    }
 
    public function getLocationSlug()
    {
        return Jobeet::slugify($this->getLocation());
    }
    
    /**
     * @ORM\PrePersist
     */
    public function setExpiresAtValue()
    {
        if (!$this->getExpiresAt()) {
            $now = $this->getCreatedAt() ? $this->getCreatedAt()->format('U') : time();
            $this->expiresAt = new \DateTime(date('Y-m-d H:i:s', $now + 86400 * 30));
        }
    }
}
