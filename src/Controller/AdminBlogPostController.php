<?php

namespace App\Controller;

use App\Entity\BlogPost;
use App\Entity\BlogPostHistory;
use App\Form\BlogPostFormType;
use Doctrine\ORM\EntityManagerInterface;
use Faker\Factory;
use function getenv;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class AdminBlogPostController extends AbstractController
{
    /** @var EntityManagerInterface */
    private $entityManager;

    /** @var \Doctrine\Common\Persistence\ObjectRepository */
    private $blogPostRepository;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;

        $this->blogPostRepository = $entityManager->getRepository('App:BlogPost');
    }

    /**
     * @Route("/", name="admin_blog_index",
     *     host="admin.{domain}",
     *     defaults={"domain"="%domain%"},
     *     requirements={"domain"="%domain%"}
     * )
     */
    public function index()
    {
        $posts = $this->blogPostRepository->findBy([
            'user_id' => $this->getUser()->getId(),
            'is_deleted' => false,
        ], [
            'post_date' => 'DESC',
        ]);
        return $this->render('admin_blog_post/index.html.twig', [
            'posts' => $posts,
        ]);
    }


    /**
     * @Route("/blog/update/{id}", name="admin_blog_update",
     *      host="admin.{domain}",
     *      defaults={"domain"="%domain%"},
     *      requirements={"domain"="%domain%"}
     * )
     * @param BlogPost $blogPost
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function updatePost(BlogPost $blogPost, Request $request)
    {
        $form = $this->createForm(BlogPostFormType::class, $blogPost);
        $form->add('save', SubmitType::class, ['label' => 'Update Blog Post']);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $up = $this->entityManager->getUnitOfWork();
            $up->computeChangeSets();
            $changes = $up->getEntityChangeSet($blogPost);

            $this->entityManager->persist($blogPost);

            if (!empty($changes)) {
                $changesEntity = new BlogPostHistory();
                $changesEntity->setParent($blogPost);
                $changesEntity->setChanges($changes);

                $this->entityManager->persist($changesEntity);

            }

            $this->entityManager->flush();

            $this->addFlash('success', 'Success! Post is updated');

            return $this->redirectToRoute('admin_blog_index');
        }

        return $this->render('admin_blog_post/update.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/blog/delete/{id}", name="admin_blog_delete",
     *      host="admin.{domain}",
     *      defaults={"domain"="%domain%"},
     *      requirements={"domain"="%domain%"}
     * )
     * @param BlogPost $blogPost
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deletePost(BlogPost $blogPost)
    {
        if ($this->getUser() !== $blogPost->getUser()) {
            $this->addFlash('danger', 'Unable to delete post.');
            return $this->redirectToRoute('admin_blog_index');
        }

        $blogPost->setIsDeleted(true);
        $this->entityManager->persist($blogPost);
        $this->entityManager->flush();

        $this->addFlash('success', "Post successfully deleted");

        return $this->redirectToRoute('admin_blog_index');
    }


    /**
     * @Route("/blog/new", name="admin_blog_add",
     *     host="admin.{domain}",
     *     defaults={"domain"="%domain%"},
     *     requirements={"domain"="%domain%"}
     * )
     */
    public function addPost(Request $request)
    {
        $blog = new BlogPost();
        $blog->setPostDate(new \DateTime('now'));
        $blog->setUser($this->getUser());


        // Attention! Only for testing
        if (getenv('APP_ENV') === "dev") {
            /** @var \Faker\Generator $faker */
            $faker = Factory::create();
            if (empty($blog->getTitle())) $blog->setTitle($faker->text(50));
            if (empty($blog->getText())) $blog->setText($faker->realText());
        }


        $form = $this->createForm(BlogPostFormType::class, $blog);
        $form->add('save', SubmitType::class, ['label' => 'Create Blog Post']);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($blog);
            $this->entityManager->flush();

            $this->addFlash('success', 'Success! Post is created');

            return $this->redirectToRoute('admin_blog_index');
        }

        return $this->render('admin_blog_post/add.html.twig',  [
            'form' => $form->createView(),
        ]);
    }
}
