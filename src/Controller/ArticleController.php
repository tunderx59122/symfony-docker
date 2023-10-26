<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Article;
use Doctrine\ORM\EntityManagerInterface;

class ArticleController extends AbstractController
{
    #[Route('/article', name: 'create_article')]
    public function createArticle(EntityManagerInterface $entityManager): Response
    {
        $article = new Article();
        $article->setTitre('Keyboard');
        $article->setEtat(true);
        $article->setPrice(25);
        $article->setText('Ergonomic and stylish!');
        $article->setDate(new \DateTime());

        $entityManager->persist($article);

        $entityManager->flush();

        return new Response('Saved article '.$article->getId());
    }

    #[Route('/article/voir/{id}', name: 'article_voir')]
    public function voir(EntityManagerInterface $entityManager, int $id): Response
    {
        $article = $entityManager->getRepository(Article::class)->find($id);

        if (!$article) {
            throw $this->createNotFoundException(
                'Not found for id '.$id
            );
        }

        return $this->render('article/index.html.twig', ['articles' => [$article]]);
    }

    #[Route('/article/voir', name: 'article_voir')]
    public function voir(EntityManagerInterface $entityManager, int $id): Response
    {
        $article = $entityManager->getRepository(Article::class)->find($id);

        if (!$article) {
            throw $this->createNotFoundException(
                'Not found for id '.$id
            );
        }

        return $this->render('article/index.html.twig', ['articles' => [$article]]);
    }

    #[Route('/article/update/{id}', name: 'article_update')]
    public function update(EntityManagerInterface $entityManager, int $id): Response
    {
        $article = $entityManager->getRepository(Article::class)->find($id);

        if (!$article) {
            throw $this->createNotFoundException(
                'No found for id '.$id
            );
        }

        $article->setTitre('New name!');
        $entityManager->flush();

        return $this->redirectToRoute('article_voir', [
            'id' => $article->getId()
        ]);
    }
}