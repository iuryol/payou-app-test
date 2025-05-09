<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 gap-2 flex flex-col">
            <div class="flex flex-col h-[200px] bg-white p-4  overflow-hidden shadow-sm sm:rounded-lg">
                <div class="flex flex-col">
                    <h2 class="text-2xl font-bold mb-6 text-slate-700">Bem vindo, {{ Auth::user()->name }}</h2>
                </div>
                <div class="flex flex-row ">
                    <div class="flex flex-row gap-4">
                        <div
                            class="w-16 h-16 rounded-full overflow-hidden border-2 border-green-400 shadow-sm hover:shadow-md transition duration-200">
                            <img src="https://i.pravatar.cc" alt="Foto de perfil" class="w-full h-full object-cover">
                        </div>
                        <div class="flex flex-col  justify-center text-slate-500">
                            <span>{{ Auth::user()->email }}</span>
                            <span>{{ Auth::user()->account_id }}</span>
                        </div>
                    </div>
                    <div class="flex flex-col  w-full">
                        <div class="flex flex-col p-2 border-2 rounded-md border-green-400 bg-slate-100 h-20 self-end mr-20  w-[200px]">
                            <span class="font-bold">Saldo</span>
                            <span>R$ {{ Auth::user()->balance }}</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="flex flex-row gap-2 h-[200px] border w-full p-4">
                <div class="flex flex-col gap-4 bg-white p-4 justify-center items-center   shadow-sm sm:rounded-lg  w-full">
                    <div class="flex flex-col text-slate-700 font-bold items-center">
                        <span>O que deseja fazer? </span>
                    </div>
                    <div class="flex flex-row gap-4">
                        <div class="flex flex-col">
                        <a href="/deposit"
                            class="inline-flex items-center justify-center w-[100px] h-[100px] gap-2 px-4 py-2 rounded-lg bg-slate-600 border-2 border-green-300 text-white hover:bg-slate-500 transition duration-200 shadow-md hover:shadow-lg text-sm">
                            Depositar
                        </a>
                    </div>
                    <div class="flex flex-col">
                        <a href="/transfer"
                            class="inline-flex items-center justify-center w-[100px] h-[100px] gap-2 px-4 py-2 rounded-lg bg-slate-600 border-2 border-green-300 text-white hover:bg-slate-500 transition duration-200 shadow-md hover:shadow-lg text-sm">
                            Transferir
                        </a>
                    </div>
                     <div class="flex flex-col">
                        <a href="/reversal"
                            class="inline-flex items-center justify-center w-[100px] h-[100px] gap-2 px-4 py-2 rounded-lg bg-slate-600 border-2 border-green-300 text-white hover:bg-slate-500 transition duration-200 shadow-md hover:shadow-lg text-sm">
                            Reverter
                        </a>
                    </div>


                    </div>
                    
                </div>
                <div class="flex flex-col bg-white p-4 gap-4  overflow-hidden shadow-sm sm:rounded-lg w-full">
                    <div class="flex flex-col text-slate-700 font-bold items-center">
                        <span>Últimas movimentações</span>
                    </div>
                    <div class="flex flex-col h-full border">

                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
