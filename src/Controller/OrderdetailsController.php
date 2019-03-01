<?php

namespace App\Controller;

use App\Entity\Orderdetails;
use App\Form\OrderdetailsType;
use App\Repository\OrderdetailsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/orderdetails")
 */
class OrderdetailsController extends AbstractController
{
    /**
     * @Route("/", name="orderdetails_index", methods="GET")
     */
    public function index(OrderdetailsRepository $orderdetailsRepository): Response
    {
        return $this->render('orderdetails/index.html.twig', ['orderdetails' => $orderdetailsRepository->findAll()]);
    }

    /**
     * @Route("/new", name="orderdetails_new", methods="GET|POST")
     */
    public function new(Request $request): Response
    {
        $orderdetail = new Orderdetails();
        $form = $this->createForm(OrderdetailsType::class, $orderdetail);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($orderdetail);
            $em->flush();

            return $this->redirectToRoute('orderdetails_index');
        }

        return $this->render('orderdetails/new.html.twig', [
            'orderdetail' => $orderdetail,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="orderdetails_show", methods="GET")
     */
    public function show(Orderdetails $orderdetail): Response
    {
        return $this->render('orderdetails/show.html.twig', ['orderdetail' => $orderdetail]);
    }

    /**
     * @Route("/{id}/edit", name="orderdetails_edit", methods="GET|POST")
     */
    public function edit(Request $request, Orderdetails $orderdetail): Response
    {
        $form = $this->createForm(OrderdetailsType::class, $orderdetail);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('orderdetails_index', ['id' => $orderdetail->getId()]);
        }

        return $this->render('orderdetails/edit.html.twig', [
            'orderdetail' => $orderdetail,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="orderdetails_delete", methods="DELETE")
     */
    public function delete(Request $request, Orderdetails $orderdetail): Response
    {
        if ($this->isCsrfTokenValid('delete'.$orderdetail->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($orderdetail);
            $em->flush();
        }

        return $this->redirectToRoute('orderdetails_index');
    }
}
