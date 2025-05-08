<?php

namespace App\Http\Controllers;

use App\Enums\StatusType;
use App\Enums\TransactionType;
use App\Interfaces\ReversalServiceInterface;
use App\Interfaces\TransactionRepositoryInterface;
use App\Models\Transaction;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RevesalController extends Controller
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
        try{
            $this->reversalService->execute($transaction);
            return redirect()->route('reversal.index')->with('success', 'Transação revertida com sucesso.');
        }catch(Exception $error){
            return back()->withErrors('Erro ao reverter a transação.');
        }
    }
}
