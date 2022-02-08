<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Api Tokens') }}
        </h2>
    </x-slot>

    <div id="app">
        <x-container class=" py-8">
           {{-- crear Acces Token --}}
            <x-form-section class="mb-12">
                <x-slot name="title">
                    Access Tokens
                </x-slot>
                <x-slot name="description">
                    Aqui Crearas tu token
                </x-slot>
                <div v-if="form.errors.length >0"
                    class="bg-red-100 border-red-400 text-red-600 px-4 py-3 rounded">
                    <strong class="font-bold">Whopps!!!</strong>
                    <span>algo salio Mal!!!</span>
                    <ul>
                        <li v-for="error in form.errors">@{{ error }}</li>
                    </ul>
                </div>
                <div class="grid grid-cols-6 gap-6">

                    <div class=" col-span-6 sm:col-span-4">
                        <x-label>
                            nombre
                        </x-label>
                        <x-input v-model="form.name" type="text" class="w-full mt-1" />
                    </div>
                </div>
                <x-slot name="actions">
                    <x-button v-on:click="store()" v-bind:disabled="form.disabled">
                        Crear
                    </x-button>
                </x-slot>
            </x-form-section>

            {{-- Mostrar Access Token --}}
            <x-form-section v-if="tokens.length >0" class="mt-12">
                <x-slot name="title">
                    Lista de Access token
                </x-slot>

                <x-slot name="description">
                    tokens Agregados
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
                            <tr v-for="token in tokens">
                                <td class="py-2">
                                    @{{ token.name }}
                                </td>
                                {{-- mostrar token --}}
                                <td class="flex divide-x divide-gray-600 py-2">
                                    <a class="pr-2 hover:text-green-500 font-semibold cursor-pointer"
                                        v-on:click="">Ver</a>
                                    {{-- editar token --}}
                                    <a class="px-2 hover:text-blue-500 font-semibold cursor-pointer"
                                        v-on:click="">editar</a>
                                    {{-- eliminar token --}}
                                    <a class="pl-2 hover:text-red-500 font-semibold cursor-pointer"
                                        v-on:click="revoke(token)">Eliminar</a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </x-form-section>
        </x-container>
    </div>

    @push('js')
        <script>
            new Vue({
                el:"#app",
                data: {
                    tokens:[],
                    form:{
                        name:'',
                        errors:[],
                        disabled:false,
                    }
                },
                mounted() {
                    this.getTokens();
                },
                methods: {
                    getTokens(){
                        axios.get('/oauth/personal-access-tokens')
                        .then(response =>{
                            this.tokens = response.data;
                        })
                    },
                    store(){
                        this.form.disabled = true;
                        axios.post('/oauth/personal-access-tokens', this.form)
                        .then(response =>{
                            this.form.name = '';
                            this.form.errors = [];
                            this.form.disabled = false;
                            this.getTokens();
                        }).catch(error => {
                            this.form.errors = _.flatten(_.toArray(error.response.data.errors));
                            this.form.disabled = false;
                        });
                    },
                    revoke(token){
                        Swal.fire({
                            title: 'Estas Seguro de Eliminar a ' + token.name + '?',
                            text: "Esta accion no se Podra Revertir!",
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#d33',
                            confirmButtonText: 'Si, Eliminar!'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                axios.delete('/oauth/personal-access-tokens/' + token.id)
                                    .then(response => {
                                        this.getTokens();
                                    });

                                Swal.fire(
                                    'Eliminado!',
                                    'El Cliente Fue Eliminado con Exito!!.',
                                    'success'
                                )
                            }
                        });
                    }
                },
            });
        </script>
    @endpush
</x-app-layout>
