<x-app-layout>
    <div class="min-h-screen bg-slate-200 flex flex-col items-center py-10">
        <div class="h-20 justify-end border  w-[1000px] mb-2">
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
          
        <div class="bg-white p-8 rounded-2xl shadow-lg w-full max-w-[1000px] relative">

            {{-- Alerta de sucesso --}}
          

            <h2 class="text-2xl font-bold mb-6 text-slate-700">Transações na Conta</h2>

            @if ($transactions->isEmpty())
                <p class="text-slate-500">Nenhuma transação encontrada.</p>
            @else
                <div class="overflow-x-auto">
                    <table class="min-w-full border border-slate-300 rounded-xl overflow-hidden">
                        <thead class="bg-slate-100 text-slate-600 text-left text-sm uppercase font-semibold">
                            <tr>
                                <th class="px-4 py-3">#</th>
                                <th class="px-4 py-3">Tipo</th>
                                <th class="px-4 py-3">Valor (R$)</th>
                                <th class="px-4 py-3">Destino</th>
                                <th class="px-4 py-3">Status</th>
                                <th class="px-4 py-3">Data</th>
                                <th class="px-4 py-3 text-center">Ação</th>
                            </tr>
                        </thead>
                        <tbody class="text-slate-700 text-sm divide-y divide-slate-200">
                            @foreach ($transactions as $transaction)
                                <tr>
                                    <td class="px-4 py-3">{{ $transaction->id }}</td>
                                    <td class="px-4 py-3 capitalize">{{ $transaction->type }}</td>
                                    <td class="px-4 py-3">R$ {{ number_format($transaction->amount, 2, ',', '.') }}</td>
                                    <td class="px-4 py-3">{{ $transaction->receiver->account_id }} -
                                        {{ $transaction->receiver->name }}</td>
                                    <td class="px-4 py-3">
                                        <span class="px-2 py-1 rounded-full text-xs font-semibold
                                            {{ $transaction->status === 'completed'
                                                ? 'bg-green-100 text-green-700'
                                                : ($transaction->status === 'pending'
                                                    ? 'bg-yellow-100 text-yellow-700'
                                                    : 'bg-red-100 text-red-700') }}">
                                            {{ ucfirst($transaction->status) }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3">{{ $transaction->created_at->format('d/m/Y H:i') }}</td>
                                    <td class="px-4 py-3 text-center">
                                        @if ($transaction->status === 'completed')
                                            <button
                                                onclick="document.getElementById('modal-{{ $transaction->id }}').classList.remove('hidden')"
                                                class="bg-red-500 hover:bg-red-600 text-white text-xs px-3 py-1 rounded-full transition">
                                                Reverter
                                            </button>

                                            {{-- Modal --}}
                                            <div id="modal-{{ $transaction->id }}"
                                                class="fixed inset-0 z-50 bg-black bg-opacity-40 flex items-center justify-center hidden">
                                                <div class="bg-white w-full max-w-sm p-6 rounded-xl shadow-lg">
                                                    <h3 class="text-lg font-bold text-slate-700 mb-4">Confirmar Reversão</h3>
                                                    <p class="text-slate-600 text-sm mb-6">Tem certeza que deseja reverter esta transação?</p>

                                                    <div class="flex justify-end gap-2">
                                                        <button type="button"
                                                            onclick="document.getElementById('modal-{{ $transaction->id }}').classList.add('hidden')"
                                                            class="bg-gray-300 text-slate-800 px-4 py-2 rounded hover:bg-gray-400">
                                                            Cancelar
                                                        </button>
                                                        <form method="POST" action="{{ route('reversal.store', $transaction->id) }}">
                                                            @csrf
                                                            <button type="submit"
                                                                class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600">
                                                                Reverter
                                                            </button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        @else
                                            <span class="text-xs text-slate-400 italic">—</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>

    {{-- Script para esconder os alertas após 3s --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            setTimeout(() => {
                const successAlert = document.getElementById('alert-success');
                const errorAlert = document.getElementById('alert-error');
                if (successAlert) successAlert.classList.add('opacity-0');
                if (errorAlert) errorAlert.classList.add('opacity-0');
            }, 3000);
        });
    </script>
</x-app-layout>
