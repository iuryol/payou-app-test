<?php

namespace App\Http\Controllers;

use App\Interfaces\TransferServiceInterface;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransferController extends Controller
{

    public function __construct(
        protected TransferServiceInterface $service
    ) {
    }
    public function index()
    {
        return view('transfer');
    }

    public function store(Request $request)
    {
        $account_id = $request->account_id;
        $amount = $request->amount;
        try{
            $this->service->execute($account_id, $amount);
            return redirect()->route('dashboard')
                ->with('success', 'Depósito realizado com sucesso!');
        }catch(Exception $error){
            return back()->withErrors(['error' => 'Erro ao processar o depósito.']);
        }
    }
}
