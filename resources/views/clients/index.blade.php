<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Clientes
        </h2>
    </x-slot>


    <x-container id="app" class="py-8">
        <x-form-section>
            <x-slot name="title">
                Crear Nuevo Cliente
            </x-slot>

            <x-slot name="description">
                Ingrese los Datos solicitados para crear un Nuevo Cliente
            </x-slot>

            <div class=" grid grid-cols-6 gap-6">
                <div class="col-span-6 sm:col-span-4">
                    <div v-if="createForm.errors.length >0"  class="mb-4 bg-red-100 border-red-400 text-red-600 px-4 py-3 rounded">
                        <strong class="font-bold">Whopps!!!</strong>
                        <span>algo salio Mal!!!</span>
                        <ul>
                            <li v-for="error in createForm.errors">@{{error}}</li>
                        </ul>
                    </div>
                    <x-label>
                        Nombre
                    </x-label>
                    <x-input v-model="createForm.name" type="text" class="w-full mt-1" />
                </div>
            </div>

            <div class=" grid grid-cols-6 gap-6">
                <div class="col-span-6 sm:col-span-4">
                    <x-label>
                        URL de Redireccion
                    </x-label>
                    <x-input type="text" v-model="createForm.redirect" class="w-full mt-1" />
                </div>
            </div>

            <x-slot name="actions">
                <x-button v-on:click="save" v-bind:disabled="createForm.disabled">
                    Guardar
                </x-button>
            </x-slot>
        </x-form-section>

        <x-form-section v-if="clients.length >0" class="mt-12">
            <x-slot name="title">
                Lista de Clientes
            </x-slot>

            <x-slot name="description">
                Clientes Agregados
            </x-slot>

            <div>
                <table class=" text-gray-600">
                    <thead class=" border-b border-gray-200">
                        <tr class=" text-left">
                            <th class="py-2 w-full">Nombre</th>
                        <th class="py-2">Accion</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-300">
                        <tr v-for="client in clients">
                            <td class="py-2">
                                @{{ client.name}}
                            </td>
                            <td class="flex divide-x divide-gray-600 py-2">
                                <a  class = "pr-2 hover:text-blue-500 font-semibold cursor-pointer" href="">editar</a>
                                <a class="pl-2 hover:text-red-500 font-semibold cursor-pointer" href="">Eliminar</a>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </x-form-section>
    </x-container>

    @push('js')

        <script>
            new Vue({
                el: "#app",
                data: {
                    clients:[],
                    createForm: {
                        errors:[],
                        disabled: false,
                        errors: [],
                        name: null,
                        redirect: null,
                    }
                },
                mounted() {
                    this.getClients();
                },
                methods: {
                    getClients(){
                        axios.get('/oauth/clients')
                        .then(response =>{
                            this.clients = response.data
                        });
                    },
                    save() {
                        this.createForm.disabled = true;
                        axios.post('/oauth/clients', this.createForm)
                            .then(response => {
                                this.createForm.name = null;
                                this.createForm.redirect = null;
                                Swal.fire(
                                    'Buen Trabajo!',
                                    'Cliente Creado!',
                                    'success'
                                );

                                this.getClients();
                            }).catch(error => {
                                this.createForm.errors = _.flatten(_.toArray(error.response.data.errors));
                            });
                            this.createForm.disabled = false;
                    }
                },
            });
        </script>

    @endpush

</x-app-layout>
