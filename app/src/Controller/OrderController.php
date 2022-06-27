<?php

namespace App\Controller;

use App\Service\OrderService;
use App\Traits\ResponseTrait;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/order")
 */
class OrderController extends AbstractController
{
    use ResponseTrait;

    private $orderService;

    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    /**
     * @Route("/add", name="app_order_add", methods={"POST"})
     * @param Request $request
     * @return JsonResponse
     * @throws \Exception
     */
    public function add(Request $request): JsonResponse
    {
        $data = $request->request->all();

        if ($data)
        {
            return $this->orderService->save($data);
        }
        else
        {
            return $this->prepareJsonResponse(null, "Hatalı İstek!", false, Response::HTTP_BAD_REQUEST);
        }

    }

    /**
     * @Route("/edit", name="app_order_edit", methods={"POST"})
     * @param Request $request
     * @return JsonResponse
     * @throws \Exception
     */
    public function edit(Request $request): JsonResponse
    {
        $data = $request->request->all();

        if ($data && isset($data["id"]))
        {
            $data["user"] = $this->getUser();
            return $this->orderService->save($data);
        }
        else
        {
            return $this->prepareJsonResponse(null, "Hatalı İstek!", false, Response::HTTP_BAD_REQUEST);
        }

    }

    /**
     * @Route("/get/{id}", name="app_order_get", methods={"GET"})
     * @param $id
     * @return JsonResponse
     */
    public function getOrder($id): JsonResponse
    {
        return $this->orderService->getDetail($id, $this->getUser());
    }

    /**
     * @Route("/list", name="app_order_list", methods={"POST", "GET"})
     * @param $id
     * @return JsonResponse
     */
    public function getOrderList(): JsonResponse
    {
        return $this->orderService->getList($this->getUser());
    }
}
