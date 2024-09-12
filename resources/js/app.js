import { createApp } from 'vue';

import FormularioOperacion from './components/FormularioOperacion.vue';
import ModalGestor from './components/ModalGestor.vue';
import ModalCamp from './components/ModalCamp.vue';
import TablaAcciones from './components/TablaAcciones.vue';
import ModelComponentes from './components/ModelComponentes.vue';
import DevolucionEquipoForm from './components/DevolucionEquipoForm.vue';
import login from './components/login.vue';
import Navbar from './components/Navbar.vue';
import EntregaEquipo from './components/EntregaEquipo.vue'
import TablaGestor from './components/TablaGestor.vue';
import Tabla_copia_correos from './components/Tabla_copia_correos.vue';


import 'bootstrap';
import '@fortawesome/fontawesome-free/css/all.css';
import store from './store/store';
import "../css/app.css"


const app = createApp({
    data(){
        return {
            store:store,
            seleccion_operacion : 0,
        }
    }
});

app.use(store);
// Componente para agregar copias de correo
app.component('copia-correos', Tabla_copia_correos);
app.component('form-operacion', FormularioOperacion);
// Modales para gestores y campañas
app.component('modal-gestor', ModalGestor);
app.component('modal-camp', ModalCamp);
app.component('modal-componente', ModelComponentes);
app.component('tabla-acciones', TablaAcciones);
app.component('form-retorno', DevolucionEquipoForm);
app.component('form-entregas', EntregaEquipo)
app.component('navbar-actas', Navbar);
app.component('tabla-gestor', TablaGestor);


// Inicio de sesion
app.component('login', login);

app.mount('#app');