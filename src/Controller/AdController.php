<?php

namespace App\Controller;

use App\Entity\Ad;
use App\Repository\AdRepository;
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

    #[Route("/ads/{slug}", name:"ads_show")]
    public function show(string $slug, Ad $ad): Response
    {
        return $this->render("ad/show.html.twig",[
            'ad'=>$ad
        ]);
    }
}
