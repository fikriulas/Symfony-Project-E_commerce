<?php

namespace App\Controller\Admin;

use App\Entity\Admin\Product;
use App\Form\Admin\ProductType;
use App\Repository\Admin\CategoryRepository;
use App\Repository\Admin\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/product")
 */
class ProductController extends AbstractController
{
    /**
     * @Route("/", name="admin_product_index", methods="GET")
     */
    public function index(ProductRepository $productRepository): Response
    {
        $emcount = $this->getDoctrine()->getManager();
        $sqlcount = "SELECT COUNT(*) as say FROM messages WHERE status='Yeni'";
        $statementcount = $emcount->getConnection()->prepare($sqlcount);
        $statementcount->execute();
        $result = $statementcount->fetchAll();

        return $this->render('admin/product/index.html.twig', ['products' => $productRepository->findAll(),'result' => $result,]);
    }

    /**
     * @Route("/new", name="admin_product_new", methods="GET|POST")
     */
    public function new(Request $request, CategoryRepository $categoryRepository): Response
    {
        $emcount = $this->getDoctrine()->getManager();
        $sqlcount = "SELECT COUNT(*) as say FROM messages WHERE status='Yeni'";
        $statementcount = $emcount->getConnection()->prepare($sqlcount);
        $statementcount->execute();
        $result = $statementcount->fetchAll();

        $catlist = $categoryRepository->findAll();
        $catTitle = $categoryRepository->findBy(
            ['id' => 0]
        );
        $product = new Product();
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($product);
            $em->flush();

            $this->addFlash('succes',"Ürün Eklendi!");
            return $this->redirectToRoute('admin_product_index');
        }

        return $this->render('admin/product/new.html.twig', [
            'product' => $product,
            'form' => $form->createView(),
            'catlist' => $catlist,
            'catTitle' => $catTitle,
            'result' => $result,
        ]);
    }

    /**
     * @Route("/{id}", name="admin_product_show", methods="GET")
     */
    public function show(Product $product): Response
    {
        $emcount = $this->getDoctrine()->getManager();
        $sqlcount = "SELECT COUNT(*) as say FROM messages WHERE status='Yeni'";
        $statementcount = $emcount->getConnection()->prepare($sqlcount);
        $statementcount->execute();
        $result = $statementcount->fetchAll();

        return $this->render('admin/product/show.html.twig',
            ['product' => $product,
                'result' => $result,
            ]
        );
    }

    /**
     * @Route("/{id}/edit", name="admin_product_edit", methods="GET|POST")
     */
    public function edit(Request $request, Product $product, CategoryRepository $categoryRepository): Response
    {
        $emcount = $this->getDoctrine()->getManager();
        $sqlcount = "SELECT COUNT(*) as say FROM messages WHERE status='Yeni'";
        $statementcount = $emcount->getConnection()->prepare($sqlcount);
        $statementcount->execute();
        $result = $statementcount->fetchAll();

        $catlist = $categoryRepository->findAll();
        $catid = $product->getCategoryid();
        $catTitle = $categoryRepository->findBy(
            ['id' => $catid]
        );

        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('succes',"Güncelleme Başarılı!");
            return $this->redirectToRoute('admin_product_index', ['id' => $product->getId(),'result' => $result,]);
        }

        return $this->render('admin/product/edit.html.twig', [
            'product' => $product,
            'catlist' => $catlist,
            'catTitle' => $catTitle,
            'form' => $form->createView(),
            'result' => $result,
        ]);
    }


    /**
     * @Route("/{id}/iedit", name="admin_product_iedit", methods="GET|POST")
     */
    public function iedit(Request $request, $id, Product $product): Response
    {
        $emcount = $this->getDoctrine()->getManager();
        $sqlcount = "SELECT COUNT(*) as say FROM messages WHERE status='Yeni'";
        $statementcount = $emcount->getConnection()->prepare($sqlcount);
        $statementcount->execute();
        $result = $statementcount->fetchAll();

        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            dump($request);
            die();
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('admin_product_edit', ['id' => $product->getId(),'result' => $result,]);
        }

        return $this->render('admin/product/imageedit.html.twig', [
            'product' => $product,
            'id' => $id,
            'form' => $form->createView(),
            'result' => $result,
        ]);
    }

    /**
     * @Route("/{id}/iupdate", name="admin_product_iupdate" , methods="POST")
     */
    public function iupdate(Request $request, $id, Product $product): Response
    {
        $emcount = $this->getDoctrine()->getManager();
        $sqlcount = "SELECT COUNT(*) as say FROM messages WHERE status='Yeni'";
        $statementcount = $emcount->getConnection()->prepare($sqlcount);
        $statementcount->execute();
        $result = $statementcount->fetchAll();

        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);
        $file = $request->files->get('imagename');
        $fileName = $this->generateUniqueFileName().'.'.$file->guessExtension();
        // Move the file to the directory where brochures are stored
        try {
            $file->move(
                $this->getParameter('images_directory'), //services.yaml deki tanımladığımız resim yolu
                $fileName
            );
        } catch (FileException $e) {
            // ... handle exception if something happens during file upload
        }
        $product->setImage($fileName);
        $this->getDoctrine()->getManager()->flush();
        return $this->redirectToRoute('admin_product_iedit', ['id' => $product->getId(),'result' => $result,]);

    }

    /**
     * @return string
     */
    private function generateUniqueFileName()
    {
        //uniqe isim tanımlama fonksiyonu.
        return md5(uniqid());
    }
    /**
     * @Route("/{id}", name="admin_product_delete", methods="DELETE")
     */
    public function delete(Request $request, Product $product): Response
    {
        $emcount = $this->getDoctrine()->getManager();
        $sqlcount = "SELECT COUNT(*) as say FROM messages WHERE status='Yeni'";
        $statementcount = $emcount->getConnection()->prepare($sqlcount);
        $statementcount->execute();
        $result = $statementcount->fetchAll();

        if ($this->isCsrfTokenValid('delete'.$product->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($product);
            $em->flush();
        }

        return $this->redirectToRoute('admin_product_index');
    }
}
