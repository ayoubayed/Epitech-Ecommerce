<?php

namespace App\Controller;

use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use App\Entity\Order;
use App\Entity\Product;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;


class UserController extends AbstractController 
{
    /**
     * @Route("/user", name="homepage")
     */
    public function index(): Response
    {
        return $this->render('user/index.html.twig', [
            'controller_name' => 'UserController',
        ]);
    }

    /**
     * @Route("/api/register", name="user_register", methods={"POST"})
     */
    public function newUser(UserPasswordEncoderInterface  $passwordEncoder, Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = new User();

        $login = $request->request->get('login');
        $password = $request->request->get('password');
        $email = $request->request->get('email');
        $firstname = $request->request->get('firstname');
        $lastname = $request->request->get('lastname');
        
        $user->setLogin($login);
        $user->setPassword($passwordEncoder->encodePassword($user, $password));
        $user->setEmail($email);
        $user->setFirstname($firstname);
        $user->setLastname($lastname);
        
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($user);
        $entityManager->flush();
    
        return $this->json(['user' => $user], 200);
    }

    /**
     * @Route("/api/login", name="user_login", methods={"POST"})
     */
    public function log(Request $request): Response
    {
        return new JsonResponse([
            'message' => 'Invalid inputs, Please check your informations' 
        ], 400);
    }

    /**
     * @Route("/api/user", name="user_edit", methods={"POST"})
     */
    public function postInfosUser(UserPasswordEncoderInterface  $passwordEncoder, Request $request, EntityManagerInterface $entityManager): Response
    {
       
        $user = new User();
        $user = $this->getUser();
        
        
        if($request->isMethod('POST')) {
           
            $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

            $email = $request->request->get('email');
            $lastname = $request->request->get('lastname');
            $firstname = $request->request->get('firstname');
            $login = $request->request->get('login');
            $password = $request->request->get('password');

            $user->setEmail($email);
            $user->setLastname($lastname);
            $user->setFirstname($firstname);
            $user->setLogin($login);
            $user->setPassword($passwordEncoder->encodePassword($user, $password));

            
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();
            

        }
        return $this->json(['user' => $user], 200);
    }

    /**
     * 
     * @Route("/api/user", name="user_show", methods={"GET"})
     */
    public function getInfoUser(Request $request): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $user = new User();
        $user = $this->getUser();
        
        return $this->json([
            'user' => $user,
        ]);
    }

     /**
     * @Route("/api/cart/{id}", methods={"PUT"})
     * @ParamConverter("product", class="App:Product")
     */
    public function new(Request $request, Product $product): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        
        $user = $this->getUser();
        $entityManager = $this->getDoctrine()->getManager();
        $user->addcart($product);
        $entityManager->flush();

        return $this->json(['shopcart' => $product], 200);
    }

    /**
     * @Route("/api/cart", name="shopcart_show", methods={"GET"})
     */
    public function show(Request $request): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $user = $this->getUser();
        $cart = $user->getCart();

        return $this->json(['success' => $cart], 200);
    }

    /**
     * @Route("/api/cart/{id}", methods={"DELETE"})
     * @ParamConverter("product", class="App:Product")
     */
    public function deleteCart(Request $request, Product $product): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $user = $this->getUser();
        $user->removeCart($product);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($user);
        $entityManager->flush();

        return $this->json(['success' => "Deleted Successfully !"], 200);
    }

     /**
     * @Route("/api/cart/validate", name="cart_del", methods={"POST"})
     * 
     */
    public function validateCart(Request $request): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $orders = new Order();
        $userInfo = $this->getUser();
        $userCart = $userInfo->getCart();
        $price = 0.00;

        foreach($userCart as $val) {
            $orders->addProduct($val);
            $price += $val->getPrice();
        }

        $orders->setUser($userInfo);
        $orders->setCreationDate(new \DateTime());
        $orders->setTotalPrice($price);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($orders);
        $userCart->clear();
        $entityManager->flush();
       
        return $this->json(['success' => $orders], 200);
    }
}
