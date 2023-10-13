<?php

namespace App\Controller;

use App\Entity\Ad;
use App\Form\AnnonceType;
use App\Repository\AdRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdController extends AbstractController
{
    #[Route('/ads', name: 'ads_index')]
    public function index( AdRepository $repo): Response
    {
        $ads=$repo->findAll();
        return $this->render('ad/index.html.twig', [
            'ads'=>$ads
        ]);
    }


   /**
     * Permet d'ajouter une annonce à la bdd 
     *
     * @return Response
     */
    #[Route("/ads/new", name:"ads_create")]
    public function create( Request $request,EntityManagerInterface $manager): Response
    {
        $ad = new Ad();
        $form = $this->createForm(AnnonceType::class,$ad);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $manager->persist($ad);
            //j'envoie les persistances
            $manager->flush();

            $this->addFlash(
                'success',
                "L'annonce <strong>".$ad->getTitle()."</strong> a bien été enregistrée"
            );

            return $this->redirectToRoute('ads_show',[
                'slug'=>$ad->getSlug()
            ]);
        }

        // dump($arrayForm);


        return $this->render("ad/new.html.twig",[
            'myForm' => $form->createView()
        ]);

    }
    #[Route("/ads/{slug}", name:"ads_show")]
    public function show(string $slug, Ad $ad): Response
    {
        return $this->render("ad/show.html.twig",[
            'ad'=>$ad
        ]);
    }
}
