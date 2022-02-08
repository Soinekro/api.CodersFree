<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Clientes
        </h2>
    </x-slot>

    <div id="app">
        {{-- crear Cliente --}}
        <x-container class="py-8">
            <x-form-section>
                <x-slot name="title">
                    Crear Nuevo Cliente
                </x-slot>

                <x-slot name="description">
                    Ingrese los Datos solicitados para crear un Nuevo Cliente
                </x-slot>

                <div class=" grid grid-cols-6 gap-6">
                    <div class="col-span-6 sm:col-span-4">
                        <div v-if="createForm.errors.length >0"
                            class="mb-4 bg-red-100 border-red-400 text-red-600 px-4 py-3 rounded">
                            <strong class="font-bold">Whopps!!!</strong>
                            <span>algo salio Mal!!!</span>
                            <ul>
                                <li v-for="error in createForm.errors">@{{ error }}</li>
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

            {{-- Mostrar Clientes --}}
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
                                    @{{ client.name }}
                                </td>
                                <td class="flex divide-x divide-gray-600 py-2">

                                    <a class="pr-2 hover:text-blue-500 font-semibold cursor-pointer"
                                        v-on:click="edit(client)">editar</a>
                                    <a class="pl-2 hover:text-red-500 font-semibold cursor-pointer"
                                        v-on:click="destroy(client)">Eliminar</a>

                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </x-form-section>


        </x-container>
        {{-- modal editar Cliente --}}
        <x-dialog-modal modal="editForm.open">
            <x-slot name="title">
                Editar Cliente
            </x-slot>
            <x-slot name="content">
                <div class="space-y-6">
                    {{-- errores modal --}}
                    <div v-if="editForm.errors.length >0"
                        class="bg-red-100 border-red-400 text-red-600 px-4 py-3 rounded">
                        <strong class="font-bold">Whopps!!!</strong>
                        <span>algo salio Mal!!!</span>
                        <ul>
                            <li v-for="error in editForm.errors">@{{ error }}</li>
                        </ul>
                    </div>
                    <div class="">
                        <x-label>
                            Nombre
                        </x-label>
                        <x-input v-model="editForm.name" type="text" class="w-full mt-1" />
                    </div>
                </div>

                <div class=" ">
                    <div class="col-span-6 sm:col-span-4">
                        <x-label>
                            URL de Redireccion
                        </x-label>
                        <x-input type="text" v-model="editForm.redirect" class="w-full mt-1" />
                    </div>
                </div>
            </x-slot>
            <x-slot name="footer">
                <button type="button"
                v-on:click="update()"
                v-bind:disabled="editForm.disabled"
                    class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm disabled:opacity-20">Actualizar</button>
                <button type="button"
                    class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm" v-on:click="editForm.open = false">Cancel</button>
            </x-slot>
        </x-dialog-modal>
    </div>



    @push('js')

        <script>
            new Vue({
                el: "#app",
                data: {
                    clients: [],
                    createForm: {
                        errors: [],
                        disabled: false,
                        errors: [],
                        name: null,
                        redirect: null,
                    },
                    editForm: {
                        open: false,
                        errors: [],
                        id:null,
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
                    getClients() {
                        axios.get('/oauth/clients')
                            .then(response => {
                                this.clients = response.data;
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
                                this.createForm.disabled = false;
                                this.createForm.errors = [];
                            }).catch(error => {
                                this.createForm.errors = _.flatten(_.toArray(error.response.data.errors));
                                this.createForm.disabled = false;
                                this.createForm.errors = [];
                            });
                    },
                    destroy(client) {
                        Swal.fire({
                            title: 'Are you sure?',
                            text: "You won't be able to revert this!",
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#d33',
                            confirmButtonText: 'Yes, delete it!'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                axios.delete('/oauth/clients/' + client.id)
                                    .then(response => {
                                        this.getClients();
                                    });

                                Swal.fire(
                                    'Deleted!',
                                    'Your file has been deleted.',
                                    'success'
                                )
                            }
                        });
                    },
                    edit(client) {
                        this.editForm.open = true;
                        this.editForm.id = client.id;
                        this.editForm.name = client.name;
                        this.editForm.redirect = client.redirect;
                        this.editForm.errors = [];
                    },
                    update(){

                        this.editForm.disabled = true;

                        axios.put('/oauth/clients/'+this.editForm.id, this.editForm)
                            .then(response => {
                                this.editForm.open = false;
                                this.editForm.name = null;
                                this.editForm.redirect = null;
                                Swal.fire(
                                    'Buen Trabajo!',
                                    'Cliente Actualizado!',
                                    'success'
                                );
                                this.getClients();
                                this.editForm.disabled = false;
                                this.editForm.errors = [];
                            }).catch(error => {
                                this.editForm.errors = _.flatten(_.toArray(error.response.data.errors));
                                this.editForm.disabled = false;
                            });
                    }
                }
            });
        </script>

    @endpush

</x-app-layout>
