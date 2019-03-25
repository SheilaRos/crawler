<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CrawlRepository")
 */
class Crawl
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=500)
     */
    private $url;

    /**
     * @ORM\Column(type="string", length=500, nullable=true)
     */
    private $header1;

    /**
     * @ORM\Column(type="string", length=500, nullable=true)
     */
    private $header2;

    /**
     * @ORM\Column(type="string", length=500, nullable=true)
     */
    private $header3;

    /**
     * @ORM\Column(type="string", length=500, nullable=true)
     */
    private $header4;

    /**
     * @ORM\Column(type="string", length=500, nullable=true)
     */
    private $header5;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(string $url): self
    {
        $this->url = $url;

        return $this;
    }

    public function getHeader1(): ?string
    {
        return $this->header1;
    }

    public function setHeader1(?string $header1): self
    {
        $this->header1 = $header1;

        return $this;
    }

    public function getHeader2(): ?string
    {
        return $this->header2;
    }

    public function setHeader2(?string $header2): self
    {
        $this->header2 = $header2;

        return $this;
    }

    public function getHeader3(): ?string
    {
        return $this->header3;
    }

    public function setHeader3(string $header3): self
    {
        $this->header3 = $header3;

        return $this;
    }

    public function getHeader4(): ?string
    {
        return $this->header4;
    }

    public function setHeader4(?string $header4): self
    {
        $this->header4 = $header4;

        return $this;
    }

    public function getHeader5(): ?string
    {
        return $this->header5;
    }

    public function setHeader5(?string $header5): self
    {
        $this->header5 = $header5;

        return $this;
    }

    public static function arrayMapperIdUrl($array){
        $result = [];
        foreach ($array as $idx => $attr){
            $result[$attr->getId()] = $attr->getUrl();
        }
        return $result;
    }

}
