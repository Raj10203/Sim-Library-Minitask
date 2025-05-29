<?php

namespace App\Controller;

use App\Repository\LoanRepository;
use Knp\Component\Pager\PaginatorInterface;
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
        $totalLoans = count($loanRepository->createLoanQueryBuilder()->getResult());
        return $this->render('home/index.html.twig', [
            'loans' => $paginator,
            'totalLoans' => $totalLoans,
        ]);
    }
}
