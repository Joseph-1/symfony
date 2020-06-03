<?php
// src/Controller/WildController.php
namespace App\Controller;

use App\Entity\Episode;
use App\Entity\Season;
use App\Form\ProgramSearchType;
use phpDocumentor\Reflection\Types\String_;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Program;
use App\Entity\Category;
use Symfony\Component\HttpFoundation\Request;

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
        $form = $this->createForm(
            ProgramSearchType::class,
            null,
            ['method' => Request::METHOD_GET]
        );

        $program = $this->getDoctrine()
            ->getrepository(Program::class)
            ->findAll();

        if (!$program) {
            throw $this->createNotFoundException(
                'No program found in program\'s table.'
            );
        }
        return $this->render('wild/index.html.twig', [
            'programs' => $program,
            'form' => $form->createView(),
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

    /**
     * @param $slug
     * @return Response
     * @Route("/show/program/{slug}", name="show_program")
     */
    public function showByProgram(?string $slug): Response
    {
        $slug = preg_replace(
            '/-/',
            ' ', ucwords(trim(strip_tags($slug)), "-")
        );
        $program = $this->getDoctrine()
            ->getRepository(Program::class)
            ->findOneBy(['title' => mb_strtolower($slug)]);

        $season = $this->getDoctrine()
            ->getrepository(Season::class)
            ->findBy(['program' => $program]);
            //->findAll();

        return $this->render('wild/show_program.html.twig', [
            'program' => $program,
            'slug'  => $slug,
            'season' => $season,
        ]);
    }

    /**
     * @param $id
     * @return Response
     * @Route("/program/season/{id}", name="show_season")
     */
    public function showBySeason($id): Response
    {
        $season = $this->getDoctrine()
            ->getrepository(Season::class)
            //->findBy(['id' => $id]);
            ->find($id);
        $program = $season->getProgram();
        $episode = $season->getEpisodes();

       return $this->render('wild/show_season.html.twig', [
           'season' => $season,
           'episode' => $episode,
           'program' => $program,
       ]);
    }

    /**
     * @param Episode $episode
     * @return Response
     * @Route("/episode/{id}", name="show_episode")
     */
    public function showEpisode(Episode $episode)
    {
        $season = $episode->getSeason();
        $program = $season->getProgram();

        return $this->render('wild/show_episode.html.twig', [
            'episode' => $episode,
            'season' => $season,
            'program' => $program,
        ]);
    }
}

