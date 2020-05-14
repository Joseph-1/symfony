<?php
// src/Controller/WildController.php
namespace App\Controller;

use phpDocumentor\Reflection\Types\String_;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/wild", name="wild_")
 */

class WildController extends AbstractController
{
    /**
     * @Route("/", name="wild_index")
     */
    public function index() :Response
    {
        return $this->render('default/index.html.twig', [
            'website' => 'Wild Séries',
        ]);
    }

    // Exemple vu pendant le cours (contenu)
   /* /**
     * @Route("/wild/show/{page}", requirements={"page"="\d+"}, name="wild_show")
     * @param int $page
     * @return Response
     */
    /* public function show(int $page): Response
    {
        return $this->render('wild/show.html.twig', [
            'page' => $page,
        ]);
    }
    */

    // Exemple à réaliser (challenge)
     /**
     * @Route("/show/{slug}",
      *requirements={"slug"="[a-z0-9-]+"},
      *defaults={"slug"="Aucune série sélectionnée, veuillez choisir une série"},
      *name="wild_show")
     */
    public function show($slug): Response
    {
        $slug = str_replace('-', ' ', $slug);
        $slug = ucwords($slug);
        return $this->render('wild/show.html.twig', [
            'slug' => $slug,
        ]);
    }

}