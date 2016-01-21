<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Document
 *
 * @ORM\Table()
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 */
class Document
{
     private $temp;
    /**
     * @Assert\File(
     * maxSize="10737418240",
     * mimeTypes = {"application/pdf", "application/x-pdf", "application/msword", "application/vnd.openxmlformats-officedocument.wordprocessingml.document"},
     * mimeTypesMessage = "Please upload a valid Document"
     * )
     */
    public $file;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var date
     *
     * @ORM\Column(name="date", type="date")
     */
    private $date;

    /**
     * @var string
     *
     * @ORM\Column(name="path", type="string", length=255, nullable=true)
     */
    private $path;

    /**
     * @ORM\ManyToOne(targetEntity="DocumentType", inversedBy="documents")
     * @ORM\JoinColumn(name="document_type_id", referencedColumnName="id")
     */
    protected $documenttype;

    /**
     * @var integer
     *
     * @ORM\Column(name="status", type="integer")
     */
    private $status=1;
    public $file_path = '/../../../uploads/documents/';

    public function getFile()
    {
        return $this->file;
    }

    public function setFile(UploadedFile $file = null)
    {
        $this->file = $file;
        //$avatarExt = pathinfo($file, PATHINFO_BASENAME);
        $avatarExt = $this->file->getClientOriginalName();
        // check if we have an old image path
        if (isset($this->path)) {
            // store the old name to delete after the update
            $this->temp = $this->path;
            $this->path = null;
        } else {
            $this->path = $avatarExt;
        }
    }

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
     * Set name
     *
     * @param string $name
     *
     * @return Document
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set status
     *
     * @param integer $status
     *
     * @return Document
     */
    public function setStatus($status)
    {
        return $this->status = $status;
    }
    /**
     * Get status
     *
     * @return status
     */
    public function getStatus()
    {
        return $this->status;
    }

     /* Set date
     *
     * @param date $date
     *
     * @return Document
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    
    /* Get date
     *
     * @return date
     */
    public function getDate()
    {
        return $this->date;
    }

     /* Set path
     *
     * @param path $path
     *
     * @return Document
     */
    public function setPath($path)
    {
        $this->path = $path;

        return $this;
    }


    
    /* Get path
     *
     * @return path
     */
     public function getPath()
    {
        return $this->path;
            
    }

     /* Set type
     *
     * @param type $type
     *
     * @return Document
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /* Get type
     *
     * @return type
     */
     public function getType()
    {
        return $this->type;
            
    }
    
    public function getAbsolutePath()
    {
        return null === $this->path
            ? null
            : $this->getUploadRootDir().'/'.$this->path;
    }

    public function getWebPath()
    {
        return null === $this->path
            ? null
            : $this->getUploadDir().'/'.$this->path;
    }

    protected function getUploadRootDir()
    {
        // the absolute directory path where uploaded
        // documents should be saved
        return __DIR__.'/../../../web/'.$this->getUploadDir();
    }

    protected function getUploadDir()
    {
        // get rid of the __DIR__ so it doesn't screw up
        // when displaying uploaded doc/image in the view.
        return 'uploads/documents';
    }
    
    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function preUpload()
    {
        if (null !== $this->getFile()) {
            // do whatever you want to generate a unique name
            $filename = sha1(uniqid(mt_rand(), true));
            $this->path = $filename.'.'.$this->getFile()->getExtension();
        }
    }

    /**
     * @ORM\PostPersist()
     * @ORM\PostUpdate()
     */
    public function upload()
    {
        if (null === $this->getFile()) {
            return;
        }

        // if there is an error when moving the file, an exception will
        // be automatically thrown by move(). This will properly prevent
        // the entity from being persisted to the database on error
        $this->getFile()->move($this->getUploadRootDir(), $this->path);

        // check if we have an old image
        if (isset($this->temp)) {
            // delete the old image
            unlink($this->getUploadRootDir().'/'.$this->temp);
            // clear the temp image path
            $this->temp = null;
        }
        $this->file = null;

        //for flashbag message
        $_SESSION["document_upload"] = "1";
    }

    /**
     * @ORM\PostRemove()
     */
    public function removeUpload()
    {
         $file = $this->getAbsolutePath(); 
        if ($file) {
            $this->status=0;
        }
    }

    /**
     * Set documenttype
     *
     * @param \AppBundle\Entity\DocumentType $documentType
     *
     * @return Document
     */
    public function setDocumentType(\AppBundle\Entity\DocumentType $documentType = null)
    {
        $this->documenttype = $documentType;

        return $this;
    }

    /**
     * Get documentType
     *
     * @return \AppBundle\Entity\DocumentType
     */
    public function getDocumentType()
    {
        return $this->documenttype;
    }
}

