<?php

namespace App\Controller;

use App\Entity\Ad;
use App\Entity\Booking;
use App\Form\BookingType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class BookingController extends AbstractController
{
    #[Route('/ads/{slug}/book', name: 'booking_create')]
    #[IsGranted("ROLE_USER")]
    public function book( Ad $ad,Request $request,EntityManagerInterface $manager): Response
    {
        $booking=new Booking();
        $form= $this->createForm(BookingType::class, $booking); //retourne nom de la classe namespace compris
        $form->handleRequest($request);

        if($form->isSubmitted()&& $form->isValid())
        {

        }

        return $this->render('booking/book.html.twig', [
            "myForm"=>$form->createView(),
            "ad"=>$ad
        ]);
    }
}
