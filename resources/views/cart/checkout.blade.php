<x-app-layout>
    {{-- TÍTULO: Estilo más sutil, alineado con el resto del sitio --}}
    <div class="border-b border-gray-200 mb-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <h1 class="text-xl font-bold text-[#1a202c] tracking-tight">Confirmar Pedido</h1>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-20 font-['Manrope']">
        <form action="{{ route('cart.checkout') }}" method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-10">
            @csrf

            {{-- COLUMNA IZQUIERDA: DATOS DE ENTREGA --}}
            <div class="space-y-8 bg-white p-8 border border-gray-100 shadow-sm">
                <div>
                    <h3 class="text-[10px] font-black uppercase tracking-widest text-gray-400 mb-6 italic">// 01. Método
                        de Entrega</h3>

                    <div class="space-y-4">
                        <label onclick="updateUI('recoger')" id="card-recoger"
                            class="flex items-center p-4 border border-gray-200 cursor-pointer hover:bg-gray-50 transition duration-200">
                            <input type="radio" name="entrega" value="recoger" class="text-black focus:ring-black"
                                required onchange="toggleEnvio(false)">
                            <span class="ml-3 text-sm font-bold uppercase tracking-tight title-text">Recoger en
                                Sucursal</span>
                        </label>

                        <label onclick="updateUI('envio')" id="card-envio"
                            class="flex items-center p-4 border border-gray-200 cursor-pointer hover:bg-gray-50 transition duration-200">
                            <input type="radio" name="entrega" value="envio" class="text-black focus:ring-black"
                                onchange="toggleEnvio(true)">
                            <span class="ml-3 text-sm font-bold uppercase tracking-tight title-text">Envío a
                                Domicilio</span>
                        </label>
                    </div>
                </div>

                {{-- CAMPOS DE ENVÍO --}}
                <div id="seccion-envio" class="hidden space-y-4 pt-4 border-t border-dashed border-gray-200">
                    <input type="text" name="direccion" placeholder="CALLE Y NÚMERO"
                        class="w-full border-gray-200 text-xs font-bold p-3 focus:border-black focus:ring-0 uppercase bg-gray-50">
                    <div class="grid grid-cols-2 gap-4">
                        <input type="text" name="colonia" placeholder="COLONIA"
                            class="w-full border-gray-200 text-xs font-bold p-3 focus:border-black focus:ring-0 uppercase bg-gray-50">
                        <input type="text" name="cp" placeholder="C.P."
                            class="w-full border-gray-200 text-xs font-bold p-3 focus:border-black focus:ring-0 uppercase bg-gray-50">
                    </div>
                </div>
            </div>

            {{-- COLUMNA DERECHA: PAGO Y RESUMEN --}}
            <div class="space-y-8 bg-white p-8 border border-gray-100 shadow-sm">
                <div>
                    <h3 class="text-[10px] font-black uppercase tracking-widest text-gray-400 mb-6 italic">// 02. Método
                        de Pago</h3>
                    <select name="pago"
                        class="w-full border-gray-200 text-xs font-black p-4 focus:border-black focus:ring-0 uppercase cursor-pointer"
                        required>
                        <option value="tarjeta">Tarjeta de Crédito/Débito</option>
                        <option value="efectivo">Efectivo al recibir/recoger</option>
                        <option value="transferencia">Transferencia Bancaria</option>
                    </select>
                </div>

                <div class="pt-6 border-t-2 border-black">
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Subtotal</span>
                        <span class="text-sm font-bold text-gray-800">${{ number_format($total, 2) }}</span>
                    </div>
                    <div class="flex justify-between items-center mb-8">
                        <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Total Final</span>
                        <span
                            class="text-3xl font-black tracking-tighter text-[#1a202c]">${{ number_format($total, 2) }}</span>
                    </div>

                    {{-- Cambiamos type="submit" por type="button" para evitar el doble envío automático --}}
                    <button type="button" id="btn-finalizar" onclick="enviarFormulario(this)"
                        style="background-color: #1a202c; color: white; width: 100%; padding: 12px 0; font-size: 11px; font-weight: 900; text-transform: uppercase; letter-spacing: 0.25em; border: none; cursor: pointer;">
                        Finalizar Transacción
                    </button>
                </div>
            </div>
        </form>
    </div>

    <style>
        .card-selected {
            background-color: #1a202c !important;
            border-color: #1a202c !important;
        }

        .card-selected .title-text {
            color: white !important;
        }
    </style>

    <script>
        function toggleEnvio(show) {
            const seccion = document.getElementById('seccion-envio');
            seccion.classList.toggle('hidden', !show);
        }

        function updateUI(tipo) {
            const recoger = document.getElementById('card-recoger');
            const envio = document.getElementById('card-envio');

            // Buscamos el input dentro de la tarjeta y lo marcamos como checked
            const input = document.querySelector(`input[name="entrega"][value="${tipo}"]`);
            if (input) {
                input.checked = true;
            }

            if (tipo === 'recoger') {
                recoger.classList.add('card-selected');
                envio.classList.remove('card-selected');
                toggleEnvio(false); // Forzamos que se oculte por seguridad
            } else {
                envio.classList.add('card-selected');
                recoger.classList.remove('card-selected');
                toggleEnvio(true); // Forzamos que se muestre por seguridad
            }
        }
    </script>
    <script>
        function enviarFormulario(btn) {
            // 1. Validar que el formulario sea válido (HTML5 validation como el "required")
            const form = btn.closest('form');
            if (!form.checkValidity()) {
                form.reportValidity();
                return;
            }

            // 2. Bloqueo inmediato para evitar el doble clic
            btn.disabled = true;
            btn.innerText = "PROCESANDO PEDIDO...";
            btn.style.opacity = "0.5";
            btn.style.cursor = "not-allowed";

            // 3. Enviar el formulario una sola vez
            form.submit();
        }
    </script>
</x-app-layout>