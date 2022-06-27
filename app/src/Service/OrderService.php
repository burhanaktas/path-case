<?php


namespace App\Service;


use App\Entity\Order;
use App\Repository\Auth\UserRepository;
use App\Repository\CustomerRepository;
use App\Repository\OrderRepository;
use App\Repository\ProductRepository;
use App\Traits\HelperTrait;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Log\Logger;
use Symfony\Component\Security\Core\User\UserInterface;

class OrderService
{
    use HelperTrait;

    private $orderRespository;
    private $customerRepository;
    private $productRepository;

    public function __construct(OrderRepository $orderRepository, CustomerRepository $customerRepository, ProductRepository $productRepository)
    {
        $this->customerRepository = $customerRepository;
        $this->orderRespository = $orderRepository;
        $this->productRepository = $productRepository;
    }

    /**
     * @param $data
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     * @throws \Exception
     */
    public function save($data)
    {

        $orderCode = @$data["orderCode"];
        $address = @$data["address"];
        $quantity = @$data["quantity"];
        $shippingDate = new \DateTime(@$data["shippingDate"]);

        $product = $this->productRepository->findOneBy(array("id" => @$data["productId"]));
        $customer = $this->customerRepository->findOneBy(array("id" => @$data["customerId"]));

        if (isset($data["id"]))
        {
            $order = $this->orderRespository->find($data["id"]);
            $now = new \DateTime();

            if ($now >= $order->getShippingDate())
            {
                return $this->prepareJsonResponse(null, "Gönderim tarihi geçmiş siparişler düzenlenemez!", false, Response::HTTP_BAD_REQUEST);
            }

            if ($order->getCustomer()->getUser() != @$data["user"])
            {
                return $this->prepareJsonResponse(null, "Bu siparişi düzenlemek için yetkiniz yok!!", false, Response::HTTP_UNAUTHORIZED);
            }

        }
        else
        {
            $order = new Order();
            $order->setOrderCode($orderCode);
            $order->setCustomer($customer);
        }


        $order->setAddress($address);
        $order->setQuantity($quantity);
        $order->setProduct($product);
        $order->setShippingDate($shippingDate);

        $validateErrors = $this->validateEntity($order);

        if (count($validateErrors) > 0)
        {
            return $this->prepareJsonResponse(null, $validateErrors->get(0)->getMessage(), false, Response::HTTP_BAD_REQUEST);
        }

        try
        {
            $this->orderRespository->add($order, true);

            return $this->prepareJsonResponse(array("orderCode" => $order->getOrderCode()), "Sipariş başarıyla kaydedildi.");
        }
        catch (\Exception $exception)
        {
            $log = new Logger();
            $log->error($exception->getMessage(), array("exception" => $exception));
            return $this->prepareJsonResponse(null, "Sipariş kaydedilirken bir hata oluştu!", false, Response::HTTP_CONFLICT);
        }


    }

    /**
     * @param $id
     * @param UserInterface $user
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function getDetail($id, UserInterface $user): JsonResponse
    {

        $order = $this->orderRespository->find($id);

        if ($order->getCustomer()->getUser() !== $user)
        {
            return $this->prepareJsonResponse(null, "Bu siparişi görmek için yetkiniz yok!!", false, Response::HTTP_UNAUTHORIZED);
        }

        if ($order)
        {
            $responseData = array(
                "id" => $order->getId(),
                "orderCode" => $order->getOrderCode(),
                "quantity" => $order->getQuantity(),
                "address" => $order->getAddress(),
                "product" => $order->getProduct()->getName(),
                "customer" => $order->getCustomer()->getName(),
                "shippingDate" => $order->getShippingDate()->format('d.m.Y H.i'),
                "createdDate" => $order->getCreatedAt()->format('d.m.Y H.i'),
                "updatedDate" => $order->getUpdatedAt()->format('d.m.Y H.i'),
            );

            return $this->prepareJsonResponse($responseData, "Sipariş detayları alındı.");
        }
        else
        {
            return $this->prepareJsonResponse(null, "Sipariş kaydı bulunamadı!", false, Response::HTTP_BAD_REQUEST);
        }

    }

    public function getList(UserInterface $user): JsonResponse
    {

        $customer = $this->customerRepository->findOneBy(array("user" => $user));

        $orders = $this->orderRespository->findBy(array("customer" => $customer));


        $responseData = array();
        if ($orders)
        {
            foreach ($orders as $order) {
                $responseData[] = array(
                    "id" => $order->getId(),
                    "orderCode" => $order->getOrderCode(),
                    "quantity" => $order->getQuantity(),
                    "address" => $order->getAddress(),
                    "product" => $order->getProduct()->getName(),
                    "customer" => $order->getCustomer()->getName(),
                    "shippingDate" => $order->getShippingDate()->format('d.m.Y H.i'),
                    "createdDate" => $order->getCreatedAt()->format('d.m.Y H.i'),
                    "updatedDate" => $order->getUpdatedAt()->format('d.m.Y H.i'),
                );
            }


            return $this->prepareJsonResponse($responseData, "Sipariş listesi alındı.");
        }
        else
        {
            return $this->prepareJsonResponse(null, "Sipariş kaydı bulunamdı!", false, Response::HTTP_BAD_REQUEST);
        }

    }


}