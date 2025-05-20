<?php

namespace App\Controller;

use App\Entity\Book;
use App\Entity\Loan;
use App\Event\LoanReturnedEvent;
use App\Form\LoanForm;
use App\Repository\BookRepository;
use App\Repository\LoanRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Psr\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/loan')]
final class LoanController extends BaseController
{
    public function __construct(private PaginatorInterface $paginator)
    {
    }

    #[Route('/borrow', name: 'app_loan_borrow', methods: ['GET'])]
    public function borrow(
        Request        $request,
        BookRepository $bookRepository,
    ): Response
    {
        $queryBuilder = $bookRepository->createAvailableBooksQueryBuilder();

        $paginator = $this->paginator->paginate(
            $queryBuilder,
            $request->query->getInt('page', 1),
            $request->query->getInt('limit', 5),
            [
                'pageRange' => 3
            ]
        );

        return $this->render('loan/borrow.html.twig', [
            'books' => $paginator
        ]);
    }

    #[Route('/borrow/{id}', name: 'app_loan_borrow_book', methods: ['GET', 'POST'])]
    public function borrowBook(
        Book                   $book,
        Request                $request,
        BookRepository         $bookRepository,
        EntityManagerInterface $entityManager,
    ): Response
    {
        if (!$book->isAvailable()) {
            return $this->redirectToRoute('app_loan_borrow', [], Response::HTTP_SEE_OTHER);
        }
        $loan = new Loan();
        $form = $this->createForm(LoanForm::class, $loan);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $loan->setUser($this->getUser());
            $loan->setBook($book);
            $book->setIsAvailable(false);
            $entityManager->persist($loan);
            $entityManager->flush();

            return $this->redirectToRoute('app_loan_borrow', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('loan/new.html.twig', [
            'loan' => $loan,
            'form' => $form,
            'book' => $book,
        ]);
    }

    #[Route(name: 'app_loan_index', methods: ['GET'])]
    public function index(LoanRepository $loanRepository): Response
    {
        return $this->render('loan/index.html.twig', [
            'loans' => $loanRepository->findAll(),
        ]);
    }

    #[Route('/return', name: 'app_loan_return', methods: ['GET'])]
    public function return(
        Request        $request,
        LoanRepository $loanRepository,
    ): Response
    {
        $queryBuilder = $loanRepository->createLoanQueryBuilder();

        $paginator = $this->paginator->paginate(
            $queryBuilder,
            $request->query->getInt('page', 1),
            $request->query->getInt('limit', 10),
            [
                'pageRange' => 3
            ]
        );
        return $this->render('loan/return.html.twig', [
            'loans' => $paginator,
        ]);
    }

    #[Route('/return/{id}', name: 'app_loan_return_book', methods: ['GET', 'POST'])]
    public function returnBook(
        Loan                     $loan,
        Request                  $request,
        BookRepository           $bookRepository,
        EntityManagerInterface   $entityManager,
        EventDispatcherInterface $dispatcher
    ): Response
    {
        if ($loan->getReturnedAt()) {
            return $this->redirectToRoute('app_loan_return', [], Response::HTTP_SEE_OTHER);
        }

        $form = $this->createForm(LoanForm::class, $loan, [
            'submit_label' => 'Return Book'
        ]);

        $form->handleRequest($request);
        $book = $loan->getBook();

        if ($form->isSubmitted() && $form->isValid()) {

            $book->setIsAvailable(true);
            $loan->setReturnedAt(new \DateTimeImmutable());
            $entityManager->persist($loan);
            $entityManager->flush();

            $dispatcher->dispatch(new LoanReturnedEvent($loan), LoanReturnedEvent::NAME);

            return $this->redirectToRoute('app_loan_borrow', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('loan/return_book.html.twig', [
            'loan' => $loan,
            'form' => $form,
            'book' => $book,
        ]);
    }

    #[Route('/{id}', name: 'app_loan_show', methods: ['GET'])]
    public function show(Loan $loan): Response
    {
        return $this->render('loan/show.html.twig', [
            'loan' => $loan,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_loan_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Loan $loan, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(LoanForm::class, $loan);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_loan_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('loan/edit.html.twig', [
            'loan' => $loan,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_loan_delete', methods: ['POST'])]
    public function delete(Request $request, Loan $loan, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $loan->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($loan);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_loan_index', [], Response::HTTP_SEE_OTHER);
    }
}
