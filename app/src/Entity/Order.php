<?php

namespace App\Entity;

use App\Repository\OrderRepository;
use App\Traits\TimeStampTrait;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=OrderRepository::class)
 * @ORM\Table(name="`order`")
 * @ORM\HasLifecycleCallbacks()
 */
class Order
{
    use TimeStampTrait;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable="false")
     * @Assert\NotNull(message="Sipariş Kodu boş bırakılamaz!!")
     * @Assert\NotBlank(message="Sipariş Kodu boş bırakılamaz!")
     */
    private $orderCode;

    /**
     * @ORM\Column(type="float")
     * @Assert\NotNull(message="Sipariş Adeti boş bırakılamaz!!")
     * @Assert\NotBlank(message="Sipariş Adeti boş bırakılamaz!")
     */
    private $quantity;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotNull(message="Sipariş Adresi boş bırakılamaz!!")
     * @Assert\NotBlank(message="Sipariş Adresi boş bırakılamaz!")
     */
    private $address;

    /**
     * @ORM\Column(type="datetime")
     * @Assert\NotNull(message="Gönderim Tarihi boş bırakılamaz!!")
     * @Assert\NotBlank(message="Gönderim Tarihi boş bırakılamaz!")
     */
    private $shippingDate;

    /**
     * @ORM\ManyToOne(targetEntity=Product::class, inversedBy="orders")
     */
    private $product;

    /**
     * @ORM\ManyToOne(targetEntity=Customer::class, inversedBy="orders")
     * @ORM\JoinColumn(nullable=false)
     */
    private $customer;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOrderCode(): ?string
    {
        return $this->orderCode;
    }

    public function setOrderCode(string $orderCode): self
    {
        $this->orderCode = $orderCode;

        return $this;
    }

    public function getQuantity(): ?float
    {
        return $this->quantity;
    }

    public function setQuantity(float $quantity): self
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(string $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function getShippingDate(): ?\DateTimeInterface
    {
        return $this->shippingDate;
    }

    public function setShippingDate(\DateTimeInterface $shippingDate): self
    {
        $this->shippingDate = $shippingDate;

        return $this;
    }

    public function getProduct(): ?Product
    {
        return $this->product;
    }

    public function setProduct(?Product $product): self
    {
        $this->product = $product;

        return $this;
    }

    public function getCustomer(): ?Customer
    {
        return $this->customer;
    }

    public function setCustomer(?Customer $customer): self
    {
        $this->customer = $customer;

        return $this;
    }
}
