<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Clientes
        </h2>
    </x-slot>


    <x-container class="py-8">
        <x-form-section>
            <x-slot name="title">
                Crear Nuevo Cliente
            </x-slot>

            <x-slot name="description">
                Ingrese los Datos solicitados para crear un Nuevo Cliente
            </x-slot>

            <x-slot name="actions">
                <x-button>
                    Guardar
                </x-button>
            </x-slot>
        </x-form-section>
    </x-container>

</x-app-layout>
