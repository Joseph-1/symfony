<?php
// src/Controller/WildController.php
namespace App\Controller;

use phpDocumentor\Reflection\Types\String_;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Program;
use App\Entity\Category;

/**
 * @Route("/wild", name="wild_")
 */

class WildController extends AbstractController
{
    // funciton index() qui récupère toute les séries contenues dans la table Program
    /**
     * Show all rows from Program's entity
     *
     * @Route("/", name="wild_index")
     * @return Response A response instance
     */
    public function index(): Response
    {
        $programs = $this->getDoctrine()
            ->getrepository(Program::class)
            ->findAll();

        if (!$programs) {
            throw $this->createNotFoundException(
                'No program found in program\'s table.'
            );
        }
        return $this->render('wild/index.html.twig', [
            'programs' => $programs,
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
    /*
     /**
     * @Route("/show/{slug}",
      *requirements={"slug"="[a-z0-9-]+"},
      *defaults={"slug"="Aucune série sélectionnée, veuillez choisir une série"},
      *name="wild_show")
     */
    /*public function show($slug): Response
    {
        if ($slug ===!empty($slug)){
        $slug = str_replace('-', ' ', $slug);
        $slug = ucwords($slug);
        return $this->render('wild/show.html.twig', [
            'slug' => $slug,
        ]);
        } else {
            return $this->render('wild/show.html.twig',
                ['slug'=> "Aucune série sélectionnée, veuillez choisir une série"]);
        }
    }
    */

    /**
     * Getting a program with a formatted slug for title
     *
     * @param string $slug The slugger
     * @Route("/show/{slug<^[a-z0-9-]+$>}", defaults={"slug" = null}, name="show")
     * @return Response
     */
    public function show(?string $slug):Response
    {
        if (!$slug) {
            throw $this
                ->createNotFoundException('No slug has been sent to find a program in program\'s table.');
        }
        $slug = preg_replace(
            '/-/',
            ' ', ucwords(trim(strip_tags($slug)), "-")
        );
        $program = $this->getDoctrine()
            ->getRepository(Program::class)
            ->findOneBy(['title' => mb_strtolower($slug)]);
        if (!$program) {
            throw $this->createNotFoundException(
                'No program with '.$slug.' title, found in program\'s table.'
            );
        }

        return $this->render('wild/show.html.twig', [
            'program' => $program,
            'slug'  => $slug,
        ]);
    }

    /**
     *
     * @param string $categoryName
     * @return Response
     * @Route("/category/{categoryName}", name="show_category")
     */
    public function showByCategory(string $categoryName): Response
    {
        $category = $this->getDoctrine()
            ->getRepository(Category::class)
            ->findOneBy(['name' => $categoryName]);

        $program = $this->getDoctrine()
            ->getRepository(Program::class)
            ->findBy(['category' => $category], ['id' => 'DESC'], 3 );

        return $this->render('wild/category.html.twig',
            [
                'category' => $category,
                'programs' => $program,
            ]);
    }
}