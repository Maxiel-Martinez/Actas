<template>
    <div>
        <section>
            <article class="border-2 p-2 m-2 text-center">
                <p>Total de actas realizadas: {{lista_actas.length}}</p>
            </article>
            <article class="grid grid-cols-4 gap-3">
                <button :class="botones" @click="showActasGestorLogin"><i class="fa-solid fa-magnifying-glass"></i>   Mostrar actas</button>
                <input type="text" name="" id="" :class="[inputs,'']" @input="buscarCasos" v-model="buscarCaso">
                <input type="date" name="" id="" :class="inputs" @input="buscarCasosAvanzado" v-model="fecha">
            </article>
        </section>
        <table>
            <thead>
                <tr class="cabeceras_tabla">
                    <th :class="tabla">N° Caso</th>
                    <th :class="tabla">Tipo de acta</th>
                    <th :class="tabla">Fecha de creacion</th>
                    <th :class="tabla">PDF en servidor</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="acta in lista_actas" :key="acta.id">
                    <td :class="tabla">{{ acta.numero_caso }}</td>
                    <td :class="tabla">{{ acta.tipo_acta }}</td>
                    <td :class="tabla">{{ acta.fecha_creacion }}</td>
                    <td :class="tabla"><button :class="botones" @click="downloadPdfAgain(acta.id)"><i class="fa-regular fa-file"></i>   Descargar acta</button></td>
                </tr>
            </tbody>
        </table>
        <a href="" id="tryPdf"></a>
    </div>
</template>


<style scope>
.cabeceras_tabla th{
    background-color: var(--color_americas);
    color: aliceblue;
}
</style>

<script>
import { mapMutations, mapState } from 'vuex';
import axios from 'axios';

export default {
    data() {
        return {
            lista_actas: [],
            buscarCaso:null,
            fecha:''
        }
    },
    methods: {
        ...mapMutations(['getIDGestor', 'cerrarSesionAuto','downloadPdfAgain']),
        // Metodo para buscar por dias las actas realizadas por el gestor 
        buscarCasosAvanzado() {
            axios.post(`/Actas_de_responsabilidad/Historial/BuscarCasoAvanzado/${this.fecha}/${this.fecha}/${this.name_gestor_session}`)
                .then(res => {
                    this.lista_actas = res.data;
                })
                .catch(err => {

                });
        },
        // Metodo para buscar un id de caso y mostrarlo
        buscarCasos: async function () {
            await axios.post(`/Actas_de_responsabilidad/Historial/BuscarCaso/${this.buscarCaso}`)
                .then(res => {
                    this.lista_actas = res.data;
                })
                .catch(err => {

                });
        },
        // Metodo para iniciar la tabla de actas que lleva
        async showActasGestorLogin() {
            await axios.post(`/Actas_de_responsabilidad/Historial/MyDocuments/Show`, { id: this.name_gestor_session })
                .then((res) => {
                    this.lista_actas = res.data;
                })
                .catch(err => {
                    console.log(err)
                })
        }
    },
    computed: {
        ...mapState(['tabla','name_gestor_session', 'inputs', 'botones']),
    },
    mounted() {
        this.getIDGestor();
        this.showActasGestorLogin();
        this.cerrarSesionAuto([null]);
    },


}
</script>