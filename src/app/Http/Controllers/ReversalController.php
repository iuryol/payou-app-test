<?php

namespace App\Http\Controllers;

use App\Exceptions\ReversalNotAllowedForIncompleteTransactionException;
use App\Exceptions\ReversalNotAllowedForReversalTypeException;
use App\Interfaces\ReversalServiceInterface;
use App\Interfaces\TransactionRepositoryInterface;
use App\Models\Transaction;
use Exception;


class ReversalController extends Controller
{
    public function __construct(
        protected ReversalServiceInterface $reversalService,
        protected TransactionRepositoryInterface $transactionRepository
    ) {
    }
    public function index()
    {
        $transactions = $this->transactionRepository->getAllReversableTransactions();
        return view('reversal', compact('transactions'));
    }

    public function store(Transaction $transaction)
    {
        try {
            $this->reversalService->execute($transaction);
            return redirect()->route('reversal.index')->with('success', 'Transação revertida com sucesso.');
        } catch (
            ReversalNotAllowedForIncompleteTransactionException |
            ReversalNotAllowedForReversalTypeException $error
        ) {
            return back()->withErrors(
                [
                $error->getMessage()
                ]
            );
        } catch (Exception $error) {
            return back()->withErrors('Erro ao processar reversão');
        }
    }
}
