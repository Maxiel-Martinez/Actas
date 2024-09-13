<template>
  <div>
    <div id="operaciones m-2">
      <section>
        <article>
          <!-- Validar que se guarde el formulario del usuario logueado -->
          <button :class="botones" @click="guardarOperacion"><i class="fa-solid fa-floppy-disk"></i> Guardar formulario</button>
          <button :class="botones" @click="cargarOperacion"><i class="fa-solid fa-rotate-right"></i> Cargar formulario</button>
        </article>
      </section>
      <div class="xl:grid grid-cols-2 border-2 p-2 mt-5">
        <h1 class="text-2xl bg-fuchsia-950 p-2 text-white col-span-2">Datos basicos</h1>
        <p :class="color_label">Señor(a) <input type="text"
            v-model="form_data.nombre_encargado" :class="inputs"></p>
        <p :class="color_label">N° Documento: <input type="text" :class="[inputs,'p-2']"
            v-model.number="form_data.documento_encargado"></p>
        <p :class="color_label">Correo: <input type="email"
            v-model="form_data.correo_encargado" :class="inputs"></p>
        <p :class="color_label">Datos gestor:
          <input name="" id=""  type="text" :class="inputs" v-model="form_data.nombre_gestor" disabled>
        </p>
      </div>
      <p :class="color_label"><b>PARÁGRAFO PRIMERO:</b> EL TRABAJADOR
        declara recibir en buen estado y a entera satisfacción el Equipo de Cómputo, los componentes y accesorios que se
        entregan de forma concomitante con la suscripción de la presenta acta, por lo anterior, el Equipo de Cómputo ,
        los componentes y accesorios, que se mencionan en esta acta se encuentra bajo la custodia y cuidado de
        <b>EL TRABAJADOR;</b>
        Igualmente, la perdida, daño o destrucción de cualquiera de los elementos entregados en esta acta constituye una
        falta grave meritoria de la terminación del contrato de trabajo,
        <b>EL TRABAJADOR</b>
        autoriza con la firma del presente documento, que en caso de pérdida o extravío, daño o destrucción de
        cualquiera de los elementos tecnológicos por cualquier motivo, le sea deducido o descontado el valor depreciado
        del bien entregado en custodia para el ejercicio de sus labores, en todo caso el empleador dará a conocer el
        valor del descuento al trabajador valor que se tendrá en cuenta conforme a los procedimiento establecidos por la
        compañía, generando la deducción de la sumas que se le adeuden por salarios, prestaciones sociales, vacaciones,
        intereses de cesantía, pagos de naturaleza extralegal, eventuales indemnizaciones y cualquier otra acreencia a
        que pueda tener derecho en vigencia del contrato de trabajo o al momento de terminación del contrato de trabajo
        por cualquier motivo. <input type="text" placeholder="Ingrese el caso" v-model="form_data.n_caso"
          :class="inputs"></p>
      <ul class="grid grid-cols-2 justify-center align-around">
        <p :class="['col-span-2 text-2xl bg-fuchsia-950 p-2 text-white']">Definiciones</p>
        <li :class="color_label">Motivo de Solicitud <input type="text"
            v-model="form_data.motivo_solicitud" :class="[inputs]"></li>
        <li :class="color_label">Operación Solicitante <span :class="[cargar1]"></span>
          <!-- Lista que permite escribir las opciones -->
          <select  :class="[inputs, 'p-3']" v-model="form_data.op_solicitante" 
          @click="mostrarCamps" placeholder="Ingrese la operacion solicitante">
          <!-- Iterar todas las campañas de la base de datos -->
            <option :value="cam.nombre_camp.toUpperCase()" v-for="cam in lista_operaciones" :key="cam.id">
              {{ cam.nombre_camp.toUpperCase() }}</option>
           </select> 

        </li>
        <li :class="color_label">Estado Nuevo Activo
          <select :class="[inputs, 'p-3']" v-model="form_data.est_entrega_nuevoActivo">
            <option value="EN REPARACION">EN REPARACION</option>
            <option value="DAÑADO">DAÑADO</option>
            <option value="OPERATIVO">OPERATIVO</option>
          </select>
        </li>
        <li :class="color_label">Estado Activo Recogido
          <select :class="[inputs, 'p-3']" v-model="form_data.est_recibido_activo">
            <option value="EN REPARACION">EN REPARACION</option>
            <option value="DAÑADO">DAÑADO</option>
            <option value="OPERATIVO">OPERATIVO</option>
          </select>
        </li>
      </ul>
      <div class="border-2 p-2 mt-5 grid grid-cols-1">

        <p :class="['text-2xl bg-fuchsia-950 p-2 text-white']">Activos relacionados:</p>
        <table :class="['cabeceras_tabla']">
          <tr>
            <th :class="tabla">Elemento Recogido</th>
            <th :class="tabla">Serial</th>
            <th :class="tabla">Activo</th>
            <th :class="tabla">Observaciones (Estado)</th>
            <th :class="tabla">Acciones</th>
          </tr>
          <tr>
            <td :class="tabla">
              <select name="" id="" :class="[inputs, 'p-3']" v-model="form_data.elemento_recogido"
                @click="mostrarComponentes">
                <option :value="componente.nombre_componente" v-for="componente in componentes_vuex"
                  :key="componente.id">
                  {{ componente.nombre_componente.toUpperCase() }}</option>
              </select>
            </td>
            <td :class="tabla"><input type="text" placeholder="Ingrese el serial" v-model="form_data.serial_recogido"
                :class="inputs"></td>
            <td :class="tabla"><input type="text" placeholder="Ingrese el activo" v-model="form_data.activo_recogido"
                :class="inputs"></td>
            <td :class="tabla"><input type="text" placeholder="Ingrese las observaciones"
                v-model="form_data.observaciones_recogido" :class="inputs" @keyup.enter="agregarRecogidos"></td>
            <td :class="tabla">
              <button :class="[botones]" @click="agregarRecogidos"><i
                  class="fa-solid fa-plus"></i> Agregar</button>
            </td>
          </tr>
        </table>
        <!-- vista de agregados de equipos recogidos -->
        <div class="p-1" v-if="form_data.data_recogido.length !== 0">
          <table :class="['cabeceras_tabla']">
            <thead>
              <tr>
                <th :class="tabla">Elemento</th>
                <th :class="tabla">N° Serial</th>
                <th :class="tabla">N° Activo</th>
                <th :class="tabla">Eliminar</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="data in form_data.data_recogido" :key="data.id">
                <td :class="[tabla, color_label]" scope="col">{{ data.elemento_recogido }}</td>
                <td :class="[tabla, color_label]" scope="col">{{ data.serial_recogido }}</td>
                <td :class="[tabla, color_label]" scope="col">{{ data.activo_recogido }}</td>
                <td :class="[tabla, color_label]" scope="col">
                  <button :class="[botones]" @click="quitarRecogidos(data)"><i
                      class="fa-solid fa-trash"></i></button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
        <!-- Tabla entregados -->
        <table :class="['cabeceras_tabla']">
          <thead>
            <tr>
              <th :class="tabla">Elemento Entregado</th>
              <th :class="tabla">Serial</th>
              <th :class="tabla">Activo</th>
              <th :class="tabla">Observaciones (Estado)</th>
              <th :class="tabla">Acciones</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td :class="tabla">
                <select name="" id="" :class="[inputs, 'p-3']" v-model="form_data.elemento_entregado"
                  @click="mostrarComponentes">
                  <option :value="componente.nombre_componente" v-for="componente in componentes_vuex"
                    :key="componente.id">
                    {{ componente.nombre_componente.toUpperCase() }}</option>
                </select>
              </td>
              <td :class="tabla"><input type="text" placeholder="Ingrese el serial" v-model="form_data.serial_entregado"
                  :class="inputs"></td>
              <td :class="tabla"><input type="text" placeholder="Ingrese el activo" v-model="form_data.activo_entregado"
                  :class="inputs"></td>
              <td :class="tabla"><input type="text" placeholder="Ingrese las observaciones"
                  v-model="form_data.observaciones_entregado" :class="inputs" @keyup.enter="agregarEntregados"></td>
              <td :class="tabla">
                <button @click="agregarEntregados" :class="[botones]"><i
                    class="fa-solid fa-plus"></i> Agregar</button>
              </td>
            </tr>
          </tbody>
        </table>

        <!-- Vista de elementos entregados -->
        <div class="p-1" v-if="form_data.data_entregado.length !== 0">

          <table :class="['cabeceras_tabla']">
            <thead>
              <tr>
                <th :class="tabla">Elemento</th>
                <th :class="tabla">N° Serial</th>
                <th :class="tabla">N° Activo</th>
                <th :class="tabla">Eliminar</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="data in form_data.data_entregado" :key="data.id">
                <td :class="[tabla, color_label]" scope="col">{{ data.elemento_entregado }}</td>
                <td :class="[tabla, color_label]" scope="col">{{ data.serial_entregado }}</td>
                <td :class="[tabla, color_label]" scope="col">{{ data.activo_entregado }}</td>
                <td :class="[tabla, color_label]" scope="col">
                  <button :class="[botones]" @click="quitarEntregados(data)"><i
                      class="fa-solid fa-trash"></i></button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

      <section class="grid grid-cols-1 border-2 p-2 mt-5">
        <p class="text-2xl bg-fuchsia-950 p-2 text-white">Observación:</p>
        <textarea :class="inputs" v-model="form_data.observaciones"></textarea>
        <hr>
      </section>
      <!-- Componente firmas -->
      <section class="flex border-2 mt-5 p-2 items-center justify-center">
        <article>
          <h2 class="text-2xl text-slate-900 text-center">Gestor de bodega: </h2>
          <select name="" id="" :class="[inputs,'p-3']" @click="mostrarGestores"
            v-model="form_data.nombre_gestor_bodega">
            <option :value="g.nombre_gestor" v-for="g in lista_gestores" :key="g.nombre_gestor">{{
              g.nombre_gestor.toUpperCase() }}</option>
          </select>
        </article>
      </section>
      <div class="flex flex-wrap gap-4 mt-5 p-3 justify-around">
        <Firma ref="signaturePad" firma_d="Firma de quien entrega" class="mb-3"
          cargo_persona="GESTOR SOLUCIONES TECNOLOGICAS" :Nombre_de_quien_entrega="form_data.nombre_gestor"></Firma>
        <Firma ref="signaturePad3" firma_d="Firma de quien entrega de bodega" class="mb-3"
          cargo_persona="GESTOR SOLUCIONES TECNOLOGICAS" :Nombre_de_quien_entrega="form_data.nombre_gestor_bodega">
        </Firma>
        <Firma ref="signaturePad2" firma_d="Firma Operacion" class="mb-3" :cargo_persona="form_data.cargo_operacion"
          :Nombre_de_quien_entrega="form_data.nombre_encargado"></Firma>
      </div>
      <table>
        <tr>
          <td :class="tabla">
            <p>Cargo persona de la operacion</p>
            <select :class="[inputs,'p-3']" v-model="form_data.cargo_operacion" required>
              <!-- Cargos de personas que pueden firmar -->
              <option value="JEFE DE OPERACION">Jefe de Operacion</option>
              <option value="DIRECTOR DE OPERACIONES">Director de Operaciones</option>
              <option value="COORDINADOR DE OPERACION">Coordinador de Operación</option>
              <option value="GERENTE DE OPERACION">Gerente de Operación</option>
              <option value="JEFE DE SOPORTE">Jefe de Soporte</option>
              <option value="COORDINADOR DE SOPORTE">Coordinador de Soporte</option>
            </select>

          </td>
        </tr>
      </table>
      <div class="container-fluid b-finales">
        <!-- Llamar a la funcion para generar el pdf -->
        <button :class="botones" @click="generarPDF"><span :class="[cargar]"></span> Generar
          PDF</button>
          <button @click="sendInventory" :class="botones"><i :class="carga_envio"></i>  Envio acta por correo</button>
        <button :class="botones" @click="limpiarTodo"><span class="fa-solid fa-eraser" />
          Limpiar formulario</button>
      </div>
      <a href="#" download="" id="link"></a>
    </div>
    <br>
    <div class="container-fluid" align="center">

      <p class="text-slate-800">Derechos reservados MaxJP 2024 1.1</p>
    </div>
  </div>
</template>

<script>
import axios from 'axios'

import Swal from 'sweetalert2'
import Firma from './Firma.vue'
import { mapState, mapMutations } from 'vuex';


export default {
  components: {
    Firma
  },
  name: 'form-operacion',

  // Recoger los datos del formulario
  data() {
    return {
      form_data: {
        tipo_formulario: 'operacion',
        // Datos basicos de la persona
        nombre_encargado: '',
        documento_encargado: null,
        correo_encargado: '',
        // N° Caso
        n_caso: '',
        // Motivos
        motivo_solicitud: '',
        op_solicitante: '',
        est_entrega_nuevoActivo: '',
        est_recibido_activo: '',
        // Datos del equipos recogido
        data_recogido: [],
        // Datos del equipos entregado
        data_entregado: [],
        // observaciones
        observaciones: '',
        // Firmas
        nombre_gestor: '',
        cargo_operacion: '',
        nombre_operacion: '',
        nombre_gestor_bodega: '',
        // Firmas
        firma1: null,
        firma2: null,
        firma3: null,
      },
      // Mostrar un spinner para señalar la carga de la respuesta.
      cargar: 'fa-solid fa-file-pdf',
      // Guardar los gestores de la base de datos.
      cargar1: '',
      // Cargar gestores
      cargar2: '',
    }
  },
  mounted() {
    this.form_data.observaciones_recogido = 'N/A'
    this.form_data.observaciones_entregado = 'N/A';
    this.cerrarSesionAuto(this.form_data)
    this.getSession();
    this.getNameGestor();
    this.form_data.nombre_gestor = this.name_gestor_session.toUpperCase();

  },
  methods: {
    ...mapMutations(['mostrarComponentes', 'mostrarGestores', 'getSession', 'mostrarCamps', 'cerrarSesionAuto', 'validateActas', 'getNameGestor','saveForm','loadForm','sendEmails']),
    // Envio correo de inventario
    sendInventory(){
      this.carga_envio = 'fa-solid fa-spinner fa-spin';
      if (this.validarInformacion()) {
        this.notificacion(4);
      } else {
        const firma1 = this.$refs.signaturePad.getSignatureDataUrl();
        const firma2 = this.$refs.signaturePad2.getSignatureDataUrl();
        const firma3 = this.$refs.signaturePad3.getSignatureDataUrl();
        // Recoger las firmas para laravel
        this.form_data.firma1 = firma1;
        this.form_data.firma2 = firma2;
        this.form_data.firma3 = firma3;
        this.sendEmails(this.form_data);
      }
      this.carga_envio = 'fa-sharp fa-solid fa-envelope fa-bounce';
      
    },
    // Guardar formulario de la operacion
    guardarOperacion(){
      let cedula = this.usuario_session[0].cedula;
      this.saveForm({documento:cedula, form:this.form_data});
    },

    // Cargar los datos del formulario de la operacion
    cargarOperacion(){
      this.loadForm(this.usuario_session[0].cedula);
      this.form_data = this.datos_form;
    },
    generarPDF: async function () {
      this.cargar = 'fa-solid fa-spinner fa-spin';
      if (this.validarInformacion()) {
        this.notificacion(4);
        this.cargar = '';
      } else {
        // Generar la alerta
        this.notificacion(1);
        const firma1 = this.$refs.signaturePad.getSignatureDataUrl();
        const firma2 = this.$refs.signaturePad2.getSignatureDataUrl();
        const firma3 = this.$refs.signaturePad3.getSignatureDataUrl();
        // Recoger las firmas para laravel
        this.form_data.firma1 = firma1;
        this.form_data.firma2 = firma2;
        this.form_data.firma3 = firma3;

        await axios.post('/PDF_OP', this.form_data, {
          responseType: 'blob'
        })
          .then((res) => {
            if (res.status == 200) {
              // Descargar el pdf desde laravel
              var link = document.getElementById('link');
              link.download = (new Date().getDate().toLocaleString() + '_' + (new Date().getMonth() + 1).toString() + '_' + (new Date().getFullYear()).toString() + '_' + new Date().getTime().toString()) + 'OPERACION_' + this.form_data.op_solicitante.toUpperCase() + '.pdf';
              link.href = URL.createObjectURL(res.data);
              link.click();

              URL.revokeObjectURL(link.href);
            } else {
              this.notificacion(2);
            }
          })
          .catch((err) => {
            this.notificacion(2);
          });
      }
      this.cargar = 'fa-solid fa-file-pdf';

    },
    // Agregar los elementos recogidos de la operacion
    agregarRecogidos() {
      try {
        // Validar que los cmapos no esten vacios
        if (this.form_data.serial_recogido != '' && this.form_data.activo_recogido != '') {
          this.form_data.data_recogido.unshift({
            elemento_recogido: this.form_data.elemento_recogido.toUpperCase(),
            serial_recogido: this.form_data.serial_recogido.toUpperCase(),
            activo_recogido: this.form_data.activo_recogido.toUpperCase(),
            observaciones_recogido: this.form_data.observaciones_recogido
          });
          this.form_data.elemento_recogido = '';
          this.form_data.serial_recogido = '';
          this.form_data.activo_recogido = '';
          this.form_data.observaciones_recogido = 'N/A';
        } else {
          this.notificacion(3);
        }
      } catch (err) {
        this.notificacion(3);
      }
    },
    // Quitar elementos recogidos por equivocacion del usuario
    quitarRecogidos(elemento) {
      for (let ind in this.form_data.data_recogido) {
        if (elemento.serial_recogido === this.form_data.data_recogido[ind].serial_recogido) {
          this.form_data.data_recogido.splice(ind, 1);
        }
      }

    },

    // Agregar datos del entragado
    agregarEntregados() {
      try {
        if (this.form_data.serial_entregado !== '' && this.form_data.activo_entregado !== '') {
          this.form_data.data_entregado.unshift(
            {
              elemento_entregado: this.form_data.elemento_entregado.toUpperCase(),
              serial_entregado: this.form_data.serial_entregado.toUpperCase(),
              activo_entregado: this.form_data.activo_entregado.toUpperCase(),
              observaciones_entregado: this.form_data.observaciones_entregado,
            }
          );
          this.form_data.elemento_entregado = '';
          this.form_data.serial_entregado = '';
          this.form_data.activo_entregado = '';
          this.form_data.observaciones_entregado = 'N/A';
        } else {
          this.notificacion(3);
        }

      } catch (err) {
        this.notificacion(3);
      }
    },
    // Quitar elementos entregados
    quitarEntregados(elemento) {
      for (let ind in this.form_data.data_entregado) {
        if (elemento.serial_entregado === this.form_data.data_entregado[ind].serial_entregado) {
          this.form_data.data_entregado.splice(ind, 1);
        }
      }
    },
    // Limpiar todos los campos
    limpiarTodo() {
      this.form_data = {
        // Datos basicos de la persona
        nombre_encargado: '',
        documento_encargado: null,
        correo_encargado: '',
        // N° Caso
        n_caso: '',
        // Motivos
        motivo_solicitud: '',
        op_solicitante: '',
        est_entrega_nuevoActivo: '',
        est_recibido_activo: '',
        // Fecha entrega
        fecha_entrega: '',
        // Datos del equipos recogido
        data_recogido: [],
        // Datos del equipos entregado
        data_entregado: [],
        // observaciones
        observaciones: '',
        // Firmas
        nombre_gestor: this.name_gestor_session.toUpperCase(),
        cargo_operacion: '',
        nombre_operacion: '',
        // Firmas
        firma1: null,
        firma2: null,
        observaciones_recogido: 'N/A',
        observaciones_entregado: 'N/A',
      }
    },
    // Retornar un valor booleano para indicar que se puede generar el PDF.
    validarInformacion() {
      if (this.form_data.correo_encargado == '' || this.form_data.documento_encargado == '' || this.form_data.n_caso == '' ||
        this.form_data.est_entrega_nuevoActivo == '' || this.form_data.est_recibido_activo == ''
        || this.form_data.op_solicitante == ''
      ) {
        return true;
      }
      return false;
    },
    // alertas de sweetalert2 
    notificacion(contexto) {
      let datos = {};
      switch (contexto) {
        case 1: // Generar alerta del pdf
          datos = {
            title: '¡Generando PDF!',
            timer: 3000,
            icon: 'success',
            toast: true,
            position: "top-end",
            showConfirmButton: false,
          };
          break;
        case 2: // Generar alerta de error
          datos = {
            title: '¡Oops!',
            text: 'Ha ocurrido un error al generar el pdf',
            icon: 'error',
            toast: true,
            position: "top-end",
            showConfirmButton: false,
          };
          break;
        case 3: // Generar alerta de error al no completar los seriales
          datos = {
            text: 'Llene los campos de serial y activo',
            icon: 'error',
            showConfirmButton: false,
            timer: 3000,
            toast: true,
            position: "top-end",
            showConfirmButton: false,
          };
          break;
        case 4: // Generar alerta de error al no llenar los campos del formulario
          datos = {
            title: '¡Oops!',
            text: '¡Valide los campos del formulario!',
            icon: 'warning',
            toast: true,
            position: "top-end",
            showConfirmButton: false,
            timer: 3000,
          };
          break;

      }
      Swal.fire(datos);

    },
  },
  computed: {
    ...mapState(['componentes_vuex', 'lista_gestores', 'usuario_session', 'lista_operaciones', 'datos_form', 'inputs', 'botones', 'color_label', 'tabla',
      'name_gestor_session', 'carga_envio'
    ])
  },

}
</script>