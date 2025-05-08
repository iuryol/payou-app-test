<?php

namespace App\Http\Controllers;

use App\Enums\StatusType;
use App\Enums\TransactionType;
use App\Interfaces\ReversalServiceInterface;
use App\Models\Transaction;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RevesalController extends Controller
{
    public function __construct(
        protected ReversalServiceInterface $service
    ) {
    }
    public function index()
    {
        $transactions = Transaction::where('sender_id', Auth::user()->id)->where('status', StatusType::COMPLETED->value)
        ->whereNot('type', TransactionType::REVERSAL->value)
        ->get();
        return view('reversal', compact('transactions'));
    }

    public function store(Transaction $transaction)
    {
       
        try{
            $this->service->execute($transaction);
            return redirect()->route('reversal.index')->with('success', 'Transação revertida com sucesso.');
        }catch(Exception $error){
            return back()->withErrors('Erro ao reverter a transação.');
        }
    }
}
