<x-app-layout>
    <div class="min-h-screen bg-slate-200 flex flex-col items-center justify-center">
        <div class="h-20 justify-end border  w-full max-w-md mb-2">
            @if (session('success'))
                <div id="alert-success"
                    class="bg-green-100 border w-[300px] border-green-400 text-green-700 px-4 py-3 rounded relative  mb-6 transition-opacity duration-500"
                    role="alert">
                    <strong class="font-bold">Sucesso:</strong>
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            {{-- Alerta de erro --}}
            @if ($errors->any())
                <div id="alert-error"
                    class="bg-red-100 border w-[300px] border-red-400 text-red-700 px-4 py-3 rounded relative  mb-6 transition-opacity duration-500"
                    role="alert">
                    <strong class="font-bold">Erro:</strong>
                    <ul class="mt-1 list-disc pl-5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>
        <div class="bg-white p-8 rounded-2xl shadow-lg w-full max-w-md">
            <h2 class="text-2xl font-bold mb-6 text-slate-700">Depósito na Conta</h2>
            <form action="/deposit" method="POST" class="space-y-5">
                @csrf

                <!-- Valor do depósito -->
                <div>
                    <x-input-label :required=true for="valor" class="block text-slate-700 font-medium">Valor
                        (R$)</x-input-label>
                    <input type="number" name="amount" id="amount" step="0.01" min="0.01" required
                        pattern="^\d+(\.\d{1,2})?$" inputmode="decimal"
                        class="mt-1 w-full px-4 py-2 border border-slate-300 rounded-xl bg-slate-100 text-slate-800 focus:outline-none focus:ring-2 focus:ring-green-300" />
                </div>

                <!-- Descrição (opcional) -->
                <div>
                    <label for="descricao" class="block text-slate-700 font-medium">Descrição</label>
                    <input type="text" name="description" id="description" placeholder="Ex: salário, transferência"
                        class="mt-1 w-full px-4 py-2 border border-slate-300 rounded-xl bg-slate-100 text-slate-800 focus:outline-none focus:ring-2 focus:ring-green-300" />
                </div>

                <!-- Botão de envio -->
                <div class="pt-4">
                    <button type="submit"
                        class="w-full bg-green-300 text-white font-semibold py-2 px-4 rounded-xl hover:bg-green-400 transition-colors">
                        Depositar
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Script para esconder os alertas após 3s --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(() => {
                const successAlert = document.getElementById('alert-success');
                const errorAlert = document.getElementById('alert-error');
                if (successAlert) successAlert.classList.add('opacity-0');
                if (errorAlert) errorAlert.classList.add('opacity-0');
            }, 3000);
        });
    </script>
</x-app-layout>
