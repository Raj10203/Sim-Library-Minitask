<?php

namespace App\Controller;

use App\Event\LoanReturnedEvent;
use App\Event\ReminderUserForDueLoans;
use App\Repository\LoanRepository;
use Knp\Component\Pager\PaginatorInterface;
use Psr\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\EventDispatcher;
use \Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[isGranted('ROLE_USER')]
final class HomeController extends BaseController
{
    public function __construct(private PaginatorInterface $paginator)
    {
    }

    #[Route('/', name: 'app_home')]
    public function index(
        LoanRepository $loanRepository,
        Request $request,
        EventDispatcherInterface $dispatcher,
    ): Response
    {
        $loansBuilder = $loanRepository->createDueLoanQueryBuilder();

        $paginator = $this->paginator->paginate(
            $loansBuilder,
            $request->query->getInt('page', 1),
            $request->query->getInt('limit', 5),
            [
                'pageRange' => 3
            ]
        );
        $dispatcher->dispatch(new ReminderUserForDueLoans(), ReminderUserForDueLoans::REMINDERUSERFORDUELOANS);
        $totalLoans = count($loanRepository->createLoanQueryBuilder()->getResult());
        return $this->render('home/index.html.twig', [
            'loans' => $paginator,
            'totalLoans' => $totalLoans,
        ]);
    }
}
