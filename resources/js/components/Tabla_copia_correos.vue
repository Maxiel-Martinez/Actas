<template>
    <div>
        <button :class="[botones,'m-3']" @click="showGestorEmail">Mostrar usuarios autorizados a copia</button>
        <select name="" id="" :class="[inputs,'p-2 m-2 mxl-3']" @click="mostrarGestores();addGestorEmail(id_gestor)" v-model="id_gestor">
            <option :value="gestor.id" v-for="gestor in lista_gestores" :key="gestor.id">{{gestor.nombre_gestor}}</option>
        </select>
        <table :class="tabla">
            <thead class="cabeceras_tabla">
                <tr>
                    <th :class="tabla">Correo</th>
                    <th :class="tabla">Accion</th>
                </tr>

            </thead>
            <tbody>
                <tr v-for="correo in lista_correos" :key="correo.id">
                    <td :class="[tabla,color_label]">{{correo.correo}}</td>
                    <td :class="[tabla,color_label]">
                        <button :class="botones" @click="deleteGestorEmail(correo.id)"><i :class="icon_delete"></i>   Eliminar a {{correo.nombre_gestor}} </button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>

<script>
import { mapMutations, mapState } from 'vuex';
import axios from 'axios';

export default {
    data() {
        return {
            lista_correos:[],     
            icon_delete:'fa-solid fa-user-minus',
            id_gestor:0,
        }
    },
    methods: {
        ...mapMutations(['mostrarGestores']),
        // Buscar las correos con copia
        showGestorEmail(){
            axios.get('/Actas_de_responsabilidad/Gestores/SendCC')
            .then(res=>{
                this.lista_correos = res.data;
            })
            .catch(err=>{
                console.log('errorLoad');
            })
        },

        // Escoger al gestor para concederle el permiso de tener siempre una copia
        addGestorEmail(id){
            axios.post(`/Actas_de_responsabilidad/Gestores/SendCC/Add/${id}`)
            .then(res=>{
                this.showGestorEmail() ? res.data : this.icon_delete;
            })
            .catch(err=>{
                console.log('errorLoadAdd');
            })
        },
        // Eliminar a los usuarios que ya no se les enviara copia
        deleteGestorEmail(id){
            
            axios.post(`/Actas_de_responsabilidad/Gestores/SendCC/Delete/${id}`)
            .then(res=>{
                this.showGestorEmail() ? res.data : this.icon_delete;
            })
            .catch(err=>{
                console.log('errorLoadDelete');
            })
        }
    },
    computed: {
        ...mapState(['botones','inputs','tabla','color_label','lista_gestores']),
    },
}
</script>

<style>
</style>