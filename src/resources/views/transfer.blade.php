<x-app-layout>
    <div class="min-h-screen bg-slate-200 flex items-center justify-center">

        <div class="bg-white p-8 rounded-2xl shadow-lg w-full max-w-md">
            <h2 class="text-2xl font-bold mb-6 text-slate-700">Transferência de Valor</h2>
            @if ($errors->any())
                <div class="bg-red-100 text-red-800 p-3 rounded mb-4">
                    <ul class="list-disc ml-4">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <form action="{{ route('transfer.store') }}" method="POST" class="space-y-5">
                @csrf

                <!-- Conta de destino -->
                <div>
                    <x-input-label :required="true" for="account_id" class="block text-slate-700 font-medium">Conta de
                        destino (ex: ACC-000123)</x-input-label>
                    <input type="text" name="account_id" id="account_id" required value="{{ old('account_id') }}"
                        class="mt-1 w-full px-4 py-2 border border-slate-300 rounded-xl bg-slate-100 text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-300" />
                </div>

                <!-- Valor da transferência -->
                <div>
                    <x-input-label :required="true" for="amount" class="block text-slate-700 font-medium">Valor
                        (R$)</x-input-label>
                    <input type="number" name="amount" id="amount" step="0.01" min="0.01" required
                        pattern="^\d+(\.\d{1,2})?$" inputmode="decimal"
                        class="mt-1 w-full px-4 py-2 border border-slate-300 rounded-xl bg-slate-100 text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-300" />
                </div>

                <!-- Descrição (opcional) -->
                <div>
                    <label for="description" class="block text-slate-700 font-medium">Descrição</label>
                    <input type="text" name="description" id="description" placeholder="Ex: aluguel, dívida"
                        class="mt-1 w-full px-4 py-2 border border-slate-300 rounded-xl bg-slate-100 text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-300" />
                </div>

                <!-- Botão de envio -->
                <div class="pt-4">
                    <button type="submit"
                        class="w-full bg-green-300 text-white font-semibold py-2 px-4 rounded-xl hover:bg-green-400 transition-colors">
                        Transferir
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
