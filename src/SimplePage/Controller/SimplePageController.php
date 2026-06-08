<?php



namespace App\SimplePage\Controller;

use App\SimplePage\Repository\SimplePageRepositoryInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Attribute\Route;

class SimplePageController extends AbstractController
{
    #[Route('/about-us', name: 'app_about_us', methods: ['GET'])]
    public function aboutUs(SimplePageRepositoryInterface $simplePageRepository): Response
    {
        return $this->renderSimplePage('about-us', 'simple_page/about_us.html.twig', $simplePageRepository);
    }

    #[Route('/pages/{slug}', name: 'app_simple_page', methods: ['GET'])]
    public function show(string $slug, SimplePageRepositoryInterface $simplePageRepository): Response
    {
        return $this->renderSimplePage($slug, 'simple_page/simple_page.html.twig', $simplePageRepository);
    }

    private function renderSimplePage(
        string $slug,
        string $template,
        SimplePageRepositoryInterface $simplePageRepository,
    ): Response {
        $page = $simplePageRepository->findOneBySlug($slug);

        if ($page === null) {
            throw new NotFoundHttpException(sprintf('Page "%s" was not found.', $slug));
        }

        return $this->render($template, [
            'page' => $page,
            'breadcrumbs' => $page->breadcrumbs(),
            'contentBlocks' => $page->contentBlocks(),
            'content_blocks' => $page->contentBlocks(),
        ]);
    }
}
