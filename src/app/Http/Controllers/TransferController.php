<?php

namespace App\Http\Controllers;

use App\Dto\TransferDto;
use App\Interfaces\TransferServiceInterface;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransferController extends Controller
{

    public function __construct(
        protected TransferServiceInterface $transferService
    ) {
    }
    public function index()
    {
        return view('transfer');
    }

    public function store(Request $request)
    {
    
        $transferDto = new TransferDto(
            receiverAccountId:$request->account_id,
            amount:$request->amount,
            description:$request->description
        );
        try{
            $this->transferService->execute($transferDto);
            return redirect()->route('home.index')
                ->with('success', 'Depósito realizado com sucesso!');
        }catch(Exception $error){
            return back()->withErrors(['error' => 'Erro ao processar o transferencia.']);
        }
    }
}
