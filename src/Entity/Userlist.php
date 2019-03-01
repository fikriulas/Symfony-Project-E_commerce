<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserlistRepository")
 */
class Userlist
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=35)
     */
    private $adsoyad;

    /**
     * @ORM\Column(type="string", length=15)
     */
    private $kullaniciadi;

    /**
     * @ORM\Column(type="string", length=15)
     */
    private $sifre;

    /**
     * @ORM\Column(type="string", length=30)
     */
    private $mail;

    /**
     * @ORM\Column(type="string", length=20, nullable=true)
     */
    private $tarih;

    /**
     * @ORM\Column(type="string", length=15,nullable=true)
     */
    private $uyetipi;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAdsoyad(): ?string
    {
        return $this->adsoyad;
    }

    public function setAdsoyad(string $adsoyad): self
    {
        $this->adsoyad = $adsoyad;

        return $this;
    }

    public function getKullaniciadi(): ?string
    {
        return $this->kullaniciadi;
    }

    public function setKullaniciadi(string $kullaniciadi): self
    {
        $this->kullaniciadi = $kullaniciadi;

        return $this;
    }

    public function getSifre(): ?string
    {
        return $this->sifre;
    }

    public function setSifre(string $sifre): self
    {
        $this->sifre = $sifre;

        return $this;
    }

    public function getMail(): ?string
    {
        return $this->mail;
    }

    public function setMail(string $mail): self
    {
        $this->mail = $mail;

        return $this;
    }

    public function getTarih(): ?string
    {
        return $this->tarih;
    }

    public function setTarih(string $tarih): self
    {
        $this->tarih = $tarih;

        return $this;
    }

    public function getUyetipi(): ?string
    {
        return $this->uyetipi;
    }

    public function setUyetipi(string $uyetipi): self
    {
        $this->uyetipi = $uyetipi;

        return $this;
    }
}
