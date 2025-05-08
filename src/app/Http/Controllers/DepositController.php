<?php

namespace App\Http\Controllers;

use App\Dto\DepositDto;
use App\Interfaces\DepositServiceInterface;
use Illuminate\Http\Request;
use Throwable;


class DepositController extends Controller
{
    public function __construct(
        protected DepositServiceInterface $depositService
    ) {
    }
    public function index()
    {
        return view('deposit');
    }

    public function store(Request $request)
    {
        $depositDto = new DepositDto(
            amount: $request->amount,
            description: $request->description
        );
      
        try{
            $isCompleted = $this->depositService->execute($depositDto);
            if($isCompleted){
                return redirect()->route('home.index')->with('success', 'Depósito realizado com sucesso!');
            }
        }catch(Throwable $error){
            return back()->withErrors(['error' => 'Erro ao processar o depósito.']);
        }
    }
}
