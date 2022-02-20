<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Cart;
use App\Entity\Order;
use App\Entity\User;
use App\Entity\Product;
use App\Form\CartType;
use App\Repository\OrderRepository;
use Symfony\Component\HttpFoundation\Request;


class OrderController extends AbstractController
{
    /**
     * @Route("/order", name="order")
     */
    public function index(): Response
    {
        return $this->render('order/index.html.twig', [
            'controller_name' => 'OrderControllebreir"rr',
        ]);
    }

    /**
     * @Route("/api/orders", name="orders_show", methods={"GET"})
     */
    public function showOrders(OrderRepository $orderRepository): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
       
        $user = $this->getUser();
        $orders = $orderRepository->findBy(array("user" => $user));
        $ordersResult = array();

        foreach ($orders as $order)
        {
            $ordersResult[] = array(
                'id' => $order->getId(),
                'totalPrice' =>$order->getTotalPrice(),
                'creationDate' => $order->getCreationDate(),
                'products' => $order->getProduct(),
            );
        }
       
        return $this->json(['success' => $ordersResult], 200);
    }

    /**
     * @Route("/api/order/{id}", name="orders_id_show", methods={"GET"})
     */
    public function showSpecificOrder(Order $order): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $user = $this->getUser();
        if($order->getUser() == $user){
            return $this->json([ 'id' => $order->getId(),
            'totalPrice' =>$order->getTotalPrice(),
            'creationDate' => $order->getCreationDate(),
            'products' => $order->getProduct()], 200);
        }
        else {
            return $this->json(['error' => "It's not your orders users."], 400);
        }
    }
}
