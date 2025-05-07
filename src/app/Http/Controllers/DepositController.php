<?php

namespace App\Http\Controllers;

use App\Interfaces\DepositServiceInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Throwable;

class DepositController extends Controller
{
    public function __construct(
        protected DepositServiceInterface $service
    ){}
    public function index()
    {
        return view('deposit');
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        $amount = $request->amount;
        $description = $request->description;
        try{
            $this->service->execute($user,$amount);
            return redirect()->route('dashboard')->with('success', 'Depósito realizado com sucesso!');
        }catch(Throwable $error){
            return back()->withErrors(['error' => 'Erro ao processar o depósito.']);
        }
    }
}
