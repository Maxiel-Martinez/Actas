<template>
    <div class="card shadow-lg">
      <div class="card-body">
        <div class="row">
          <div class="col-md-6 mb-3">        
            <section>
              <article>
                <button :class="botones" @click="guardarEntrega"><i class="fa-solid fa-floppy-disk"></i> Guardar formulario</button>
                <button :class="botones" @click="cargarEntrega"><i class="fa-solid fa-rotate-right"></i> Cargar formulario</button>
              </article>
            </section>
      
            <section class="sm:grid grid-cols-3 mt-1 p-5 border-2 gap-8 justify-center items-center">
              <article class="text-2xl bg-fuchsia-950 text-white col-span-3 p-2">Datos de la entrega</article>
              <div class="row mb-3">
                <div class="col-md-4">
                  <label for="fechaEntrega" class="form-label">Fecha de entrega:</label>
                  <input type="date" id="fechaEntrega" v-model="formData.fechaEntrega" :class="inputs" >
                </div>
              </div>
              <article>
                <label for="numeroCaso" class="form-label">N° Caso:</label>
                <input type="text" id="numeroCaso" :class="inputs" v-model="formData.numeroCaso" @click="cargarUsuarioSession">
              </article>
              <div class="col-md-4">
                <label for="concepto" class="form-label">Por concepto de:</label>
                <select id="concepto" :class="[inputs, 'p-3']" v-model="formData.concepto">
                  <option value="Asignación">Asignación</option>
                  <option value="Cambio">Cambio</option>
                  <option value="Préstamo">Préstamo</option>
                  
                </select>
              </div>
               <!-- New fields for previous equipment (only visible when "Cambio" is selected) -->
            <div v-if="formData.concepto === 'Cambio'" class="mb-1">
              <label for="serialDispositivoAnterior" class="form-label">Serial del Dispositivo Anterior:</label>
              <input type="text" id="serialDispositivoAnterior" :class="inputs" v-model="formData.serialDispositivoAnterior">
            </div>
            <div v-if="formData.concepto === 'Cambio'" class="mb-1">
              <label for="activoDispositivoAnterior" class="form-label">Activo del Dispositivo Anterior:</label>
              <input type="text" id="activoDispositivoAnterior" :class="inputs" v-model="formData.activoDispositivoAnterior">
            </div>
              
            </section>
  
            <section class="sm:grid grid-cols-5 mt-5 p-2 border-2 gap-8 justify-center items-center ">
              <article class="text-2xl bg-fuchsia-950 text-white col-span-5 p-2">Datos del usuario</article>
              
              <article>
                <label for="nombres" class="form-label">Nombre:</label>
                <input type="text" id="nombres" :class="inputs" v-model="formData.nombres">
              </article>
              <article>
                <label for="cedula" class="form-label">C.C:</label>
                <input type="text" id="cedula" :class="inputs" v-model="formData.cedula">
              </article>
              <article>
                <label for="expedicionCedula"  class="form-label">Expedición Cedula:</label>
                <input type="text" id="expedicionCedula" :class="inputs" v-model="formData.expedicionCedula">
              </article>
              <article>
                <label for="campana" class="form-label">Campaña:</label>
                <select :class="[inputs,'p-3']" id="seleccion" v-model="formData.campana" 
                @click="mostrarCamps">
                <option class="mt-1 bg-gray-100" :value="cam.nombre_camp" v-for="cam in lista_operaciones" :key="cam.id">
                  {{ cam.nombre_camp.toUpperCase() }}</option>
                </select>
              </article>
              <article>
                <label for="correoPersonal" class="form-label">Correo usuario:</label>
                <input type="email" id="correoPersonal" :class="inputs" v-model="formData.correoPersonal">
              </article>
              <article>
                <label for="correoJefe" class="form-label">Correo del Jefe:</label>
                <input type="email" id="correoJefe" :class="inputs" v-model="formData.correoJefe">
              </article>
            </section>
          </div>
        </div>
        <div class="text-justify">
          <p style="padding-left: 5%; padding-right: 5%; padding-bottom: 1%;">
                <b> CLÁUSULA PRIMERA: EL EMPLEADOR</b> en forma concomitante a la suscripción de la presente acta
                le hace entrega a <b>EL TRABAJADOR</b> el equipo cómputo y los demás elementos tecnológicos 
                que a continuación se relacionan: 
        </p>
      </div>
        <div class="border p-3 rounded mb-3">
          <section class="sm:border-2 mt-5 p-2 grid grid-cols-3 gap-8 justify-center items-center">
            <h4 class="text-2xl p-2 bg-fuchsia-950 text-white col-span-3">Detalles del Dispositivo</h4>
  
            <div class="col-md-6 mb-3">
              <label for="dispositivo" class="form-label">Tipo de dispositivo:</label>
              <select id="dispositivo" :class="[inputs, 'p-3']" v-model="formData.dispositivo">
                <option value="">Seleccione</option>
                <option value="Portatil">Portátil</option>
                <option value="Escritorio">Escritorio</option>
                <option value="Celular">Celular</option>
              </select>
            </div>
            <!-- <div class="col-md-6 mb-3" v-if="formData.dispositivo === 'Escritorio'">
              <label for="Tipoescritorio" class="form-label">Tipo de escritorio:</label>
              <select id="Tipoescritorio" :class="[inputs, 'p-3']" v-model="formData.Tipoescritorio">
                <option value="Torre">Torre</option>
                <option value="Micro">Micro</option>
              </select>
            </div> -->
            <div v-if="formData.dispositivo === 'Celular'" class="col-span-3 grid grid-cols-2 gap-4">
                <div class="mb-1">
                    <label for="IMEI" class="form-label">IMEI 1:</label>
                    <input type="text" id="IMEI" :class="inputs" v-model="formData.IMEI">
                </div>
                <div class="mb-1">
                    <label for="IMEI2" class="form-label">IMEI 2:</label>
                    <input type="text" id="IMEI2" :class="inputs" v-model="formData.IMEI2">
                </div>
                </div>

                <div v-if="formData.dispositivo !== 'Celular'" class="col-span-3 grid grid-cols-3 gap-4">
                <div class="col-md-6 mb-3" v-if="formData.dispositivo === 'Escritorio'">
                    <label for="Tipoescritorio" class="form-label">Tipo de escritorio:</label>
                    <select id="Tipoescritorio" :class="[inputs, 'p-3']" v-model="formData.Tipoescritorio">
                    <option value="Torre">Torre</option>
                    <option value="Micro">Micro</option>
                    </select>
                </div>
    
            <div class="mb-1">
            <label for="marcaDispositivo" class="form-label">Marca del Dispositivo:</label>
            <input type="text" id="marcaDispositivo" :class="inputs" v-model="formData.marcaDispositivo">
          </div>
  
          <div class="mb-1">
            <label for="modeloDispositivo" class="form-label">Modelo del Dispositivo:</label>
            <input type="text" id="modeloDispositivo" :class="inputs" v-model="formData.modeloDispositivo">
          </div>
            <div class="mb-1">
              <label for="serialDispositivo" class="form-label">Serial del Dispositivo:</label>
              <input type="text" id="serialDispositivo" :class="inputs" v-model="formData.serialDispositivo">
            </div>
            <div class="mb-1">
              <label for="activoDispositivo" class="form-label">Activo del Dispositivo:</label>
              <input type="text" id="activoDispositivo" :class="inputs" v-model="formData.activoDispositivo">
            </div>
  
            <div class="col-md-4 mt-3">
              <label for="estadoDispositivo" class="form-label">Estado del Dispositivo:</label>
              <select id="estadoDispositivo" :class="[inputs, 'p-3']" v-model="formData.estadoDispositivo">
                <option value="Bueno">Bueno</option>
                <option value="Dañado">Dañado</option>
                <option value="Dado de baja">Dado de baja</option>
              </select>
            </div>
            <div class="mb-1">
            <label for="marcaMonitor" class="form-label">Marca del Monitor:</label>
            <input type="text" id="marcaMonitor" :class="inputs" v-model="formData.marcaMonitor">
          </div>
  
          <div class="mb-1">
            <label for="modeloMonitor" class="form-label">Modelo del Monitor:</label>
            <input type="text" id="modeloMonitor" :class="inputs" v-model="formData.modeloMonitor">
          </div>
            <div class="mb-1">
              <label for="serialMonitor" class="form-label">Serial del Monitor:</label>
              <input type="text" id="serialMonitor" :class="inputs" v-model="formData.serialMonitor">
            </div>
            <div class="mb-1">
              <label for="activoMonitor" class="form-label">Activo del Monitor:</label>
              <input type="text" id="activoMonitor" :class="inputs" v-model="formData.activoMonitor">
            </div>
            <div class="col-md-4 mt-3">
              <label for="estadoMonitor" class="form-label">Estado del Monitor:</label>
              <select id="estadoMonitor" :class="[inputs, 'p-3']" v-model="formData.estadoMonitor">
                <option value="Bueno">Bueno</option>
                <option value="Dañado">Dañado</option>
                <option value="Dado de baja">Dado de baja</option>
              </select>
            </div>
        </div>

        <div v-if="formData.segundoMonitor === 'Si'" class="mb-1">
      <label for="segundoMonitor" class="form-label">¿Tiene segundo monitor?</label>
            <select id="segundoMonitor" :class="[inputs, 'p-3']" v-model="formData.segundoMonitor">
                <option value="No">No</option>
                <option value="Si">Sí</option>
            </select>
        </div>
    
        <div v-if="formData.segundoMonitor === 'Si'" class="mb-1">
            <label for="marcaMonitor2" class="form-label">Marca del Monitor:</label>
            <input type="text" id="marcaMonitor2" :class="inputs" v-model="formData.marcaMonitor2">
        </div>

          <div v-if="formData.segundoMonitor === 'Si'" class="mb-1">
            <label for="modeloMonitor2" class="form-label">Modelo del Monitor:</label>
            <input type="text" id="modeloMonitor2" :class="inputs" v-model="formData.modeloMonitor2">
            </div>

          <div v-if="formData.segundoMonitor === 'Si'" class="mb-1">
            <label for="serialMonitor2" class="form-label">Serial del Segundo Monitor:</label>
            <input type="text" id="serialMonitor2" :class="inputs" v-model="formData.serialMonitor2">
          </div>
          <div v-if="formData.segundoMonitor === 'Si'" class="mb-1">
            <label for="activoMonitor2" class="form-label">Activo del Segundo Monitor:</label>
            <input type="text" id="activoMonitor2" :class="inputs" v-model="formData.activoMonitor2">
          </div>
  
          <div v-if="formData.segundoMonitor === 'Si'" class="col-md-4 mt-3">
            <label for="estadoMonitor2" class="form-label">Estado del Segundo Monitor:</label>
            <select id="estadoMonitor2" :class="[inputs, 'p-3']" v-model="formData.estadoMonitor2">
              <option value="Bueno">Bueno</option>
              <option value="Dañado">Dañado</option>
              <option value="Dado de baja">Dado de baja</option>
            </select>
          </div>
       </section>
          
          <!-- Sección para periféricos -->
          <section class="sm:border-2 mt-5 p-2 grid grid-cols-3 gap-8 justify-center items-center">
            <h4 class="text-2xl p-2 bg-fuchsia-950 text-white col-span-3">Periféricos</h4>
            
            <!-- Mouse -->
            <div class="mb-1">
              <label for="raton" class="form-label">Mouse:</label>
              <select id="raton" :class="[inputs, 'p-3']" v-model="formData.raton">
                <option value="Si">Sí</option>
                <option value="No">No</option>
              </select>
            </div>
  
            <!-- Teclado -->
            <div class="mb-1">
              <label for="teclado" class="form-label">Teclado:</label>
              <select id="teclado" :class="[inputs, 'p-3']" v-model="formData.teclado">
                <option value="Si">Sí</option>
                <option value="No">No</option>
              </select>
            </div>
  
            <!-- Diadema -->
            <div class="mb-1">
              <label for="diadema" class="form-label">Diadema:</label>
              <select id="diadema" :class="[inputs, 'p-3']" v-model="formData.diadema">
                <option value="Si">Sí</option>
                <option value="No">No</option>
              </select>
            </div>
            <div class="mb-1" v-if="formData.diadema === 'Si'">
              <label for="marcaDiadema" class="form-label">Marca de la Diadema:</label>
              <select id="diadema" :class="[inputs, 'p-3']" v-model="formData.marcaDiadema">
                <option value="PLANTRONICS">PLANTRONICS</option>
                <option value="JABRA">JABRA</option>
                <option value="XKIM">XKIM</option>
              </select>
            </div>
            <div class="mb-1" v-if="formData.diadema === 'Si'">
              <label for="serialDiadema" class="form-label">Serial de la Diadema:</label>
              <input type="text" id="serialDiadema" :class="inputs" v-model="formData.serialDiadema">
            </div>
            <div class="mb-1" v-if="formData.diadema === 'Si'">
              <label for="estadoDiadema" class="form-label">Estado de la Diadema:</label>
              <select id="estadoDiadema" :class="[inputs, 'p-3']" v-model="formData.estadoDiadema">
                <option value="Bueno">Bueno</option>
                <option value="Dañado">Dañado</option>
                <option value="Dado de baja">Dado de baja</option>
              </select>
            </div>
            
          </section>  
        </div>
         
        
  
      <div class="text-justify">
          <p style="padding-left: 5%; padding-right: 5%; padding-bottom: 3%; ">
            <br>
            <b>PARÁGRAFO PRIMERO: EL TRABAJADOR </b>declara recibir en buen estado y a entera satisfacción el Equipo de Cómputo, 
            los componentes y accesorios que se entregan de forma concomitante con la suscripción de la presenta acta, 
            por lo anterior, el Equipo de Cómputo , los componentes y accesorios,  que se mencionan  en esta acta se encuentra bajo la custodia
            y cuidado de <b>EL TRABAJADOR</b>; Igualmente, la perdida, daño o destrucción de cualquiera de los elementos entregados en esta acta constituye 
            una falta grave meritoria de la terminación del contrato de trabajo, <b>EL TRABAJADOR</b> autoriza con la firma del presente documento, que en caso de pérdida o extravío,
            daño o destrucción de cualquiera de los elementos tecnológicos por cualquier motivo, le sea deducido o descontado el valor depreciado del bien entregado 
            en custodia para el ejercicio de sus labores, en todo caso el empleador dará a conocer el valor del descuento al trabajador valor que se tendrá 
            en cuenta conforme a los procedimiento establecidos por la compañía, generando la deducción de la sumas que se le adeuden por salarios, 
            prestaciones sociales, vacaciones, intereses de cesantía, pagos de naturaleza extralegal, eventuales indemnizaciones y 
            cualquier otra acreencia a que pueda tener derecho en vigencia del contrato de trabajo o al momento de terminación del contrato de trabajo por cualquier motivo. 
            <br>
            <br>
            <b>PARÁGRAFO SEGUNDO:</b> En concordancia con lo anterior, <b>EL TRABAJADOR</b> se obliga de manera especial a reportar inmediatamente
            a su jefe inmediato y al área de tecnología de la compañía cualquier novedad o variación en el Equipo de Cómputo, sus componentes o 
            accesorios entregados, bien sea por hurto, extravío o avería.
            <br>
            <br>
            <b>EL TRABAJADOR</b> en el evento o ocurrencia mencionado en el presente parágrafo, se obliga a notificar
            mediante en la herramienta de gestión designada por la compañía o la línea de soporte Técnicos 4251700 Ext 43300. 
            <br>
            <br>
            <b>CLAUSULA SEGUNDA: EL TRABAJADOR</b> reconoce y acepta que el Equipo de Cómputo, sus componentes 
            y accesorios son de propiedad de <b>EL EMPLEADOR</b> y por lo tanto deberá devolverlo, en cualquier
            momento que <b>EL EMPLEADOR</b> lo requiera, así como también a la terminación del contrato de trabajo cualquiera fuere su causa,
            en las mismas condiciones que poseía al momento de la entrega, sin perjuicio del desgaste normal por uso. 
          <br>
          <br>
            <b>PARÁGRAFO:</b> En caso de que <b>EL TRABAJADOR</b> incumpla su obligación de devolver el equipo de computo o sus accesorios
            relacionados en el presente documento en la terminación  de su contrato de trabajo o cuando lo requiere el empleador,
            mediante  el presente escrito autoriza  de manera expresa al <b>EMPLEADOR</b>,  para que el valor total de los mismos, se descuente,
            deduzca y/o compense del valor de la liquidación  final de acreencias laborales al cual tenga derecho <b>EL TRABAJADOR</b> incluyendo 
            el valor  de salarios, prestaciones sociales, vacaciones, intereses de cesantías, pagos de naturaleza extralegal, eventuales 
            indemnizaciones y cualquier otra acreencias a que pueda tener derecho al momento de terminación del contrato de trabajo o por cualquier causa.  
            <br>
            <br>
            <b>CLÁUSULA TERCERA:</b> Teniendo en cuenta que el Equipo de Cómputo, componentes y sus accesorios son otorgados 
            como herramienta de trabajo, <b>EL TRABAJADOR</b> se compromete a utilizarlos única y exclusivamente en las labores 
            que desempeñe como <b>TRABAJADOR</b> al servicio de <b>EL EMPLEADOR</b>
            <br>
            <br>
            <b>CLÁUSULA CUARTA:</b> El Equipo de Cómputo, componentes y accesorios entregados al <b>TRABAJADOR</b> a la firma del presente documento
            se concede como un acto de mera libertad de <b>EL EMPLEADOR</b> y como una herramienta para que desarrolle a cabalidad sus funciones
            <b>EL TRABAJADOR</b>; por lo cual,  las partes de manera expresa reconocen y aceptan que el suministro mencionado en la presente cláusula 
            no constituye salario, ni beneficio legal o extralegal alguno,  así mismo reiteran que no constituyen retribución alguna, 
            ni salario o en dinero o especie, ni tendrán incidencia salarial, prestaciones parafiscal o indemnizatoria, 
            de conformidad por lo establecido por los artículos 15 y 16 de la ley 50 de 1990 y el artículo 17 de la ley 344 de 1996. 
            Por tratarse de herramientas de trabajo que facilita <b>EL EMPLEADOR</b> por mera liberalidad, <b>EL EMPLEADOR</b>, se reserva
            la facultad de modificar, adicionar o suprimir este suministro sin que constituya ningún tipo de desmejora para <b>EL TRABAJADOR</b>, 
            situación que <b>EL TRABAJADOR</b> desde ya acepta.
            <br>
            <br>
            <b>CLÁUSULA QUINTA: EL TRABAJADOR</b> manifiesta que ha sido advertido y acepta expresamente que no debe instalar en los Equipo
            de cómputo suministrados por <b>EL EMPLEADOR</b> ninguna clase de software  licenciado o no ( incluyendo pero no limitando: a Free software, Open source, 
            Licencia GPL (General PublicLicense) o copyright, Software de dominio público, Freeware, Shareware, Adware (Advertising spyware)) 
            de igual forma, se compromete a no alterar la configuración del Equipo de cómputo que se asignó para el desempeño de su labor 
            cumpliendo a cabalidad las políticas de seguridad establecidas por <b>EL EMPLEADOR</b> para garantizar la confidencialidad, 
            integridad y seguridad de la información.
            
          </p>
      </div>
        <article for="condiciones" class="p-2 mt-5 flex">
          <input type="checkbox" name="condiciones" id="" class="w-4" :value="true" v-model="condiciones">
          <label for="condiciones">Sí, acepto las condiciones</label>
        </article>
  
        <div class="mb-3">
          <label for="observaciones" class="form-label">Observaciones:</label>
          <textarea id="observaciones" :class="inputs" v-model="formData.observaciones" rows="3"></textarea>
        </div>
      </div>
  
      <div class="flex flex-wrap mt-5 p-3 gap-4 justify-around">
        <firma ref="signaturePad" firma_d="Firma de quien entrega" :Nombre_de_quien_entrega="formData.NombreRecibe"
         cargo_persona="GESTOR DE SOLUCIONES TECNOLOGICAS(TIMAC)" class="mb-3" />
        <firma ref="signaturePad2" firma_d="Firma de quien recibe" :Nombre_de_quien_entrega="formData.nombres"
          cargo_persona="" class="mb-3" />
      </div>
  
      <div class="flex justify-around p-6 m-2">
        <button type="submit" :class="[botones]" @click="submitForm" :disabled="!condiciones">
          <i :class="[cargar]"></i> Generar PDF
        </button>
        <button type="reset" :class="[botones]" @click="limpiarTodo">
          <i class="fas fa-eraser me-2"></i>Limpiar
        </button>
      </div>
      <a href="#" download="" id="down"></a>
    </div>
  </template>
  
  <script>
  import { mapState, mapMutations } from 'vuex';
  import axios from 'axios';
  import Swal from 'sweetalert2';
  import Firma from './Firma.vue';
  
  export default {
    components: {
      Firma,
    },
    mounted() {
      this.getSession();
      this.cerrarSesionAuto(this.form_data);
    },
    data() {
      return {
        formData: {
          tipo_formulario: 'entrega',
          dispositivo: '',
          concepto:'',
          Tipoescritorio: '',
          numeroCaso: '',
          nombres: '',
          campana: '',
          correoPersonal: '',
          correoJefe: '',
          serialDispositivo: '',
          activoDispositivo: '',
          estadoDispositivo: '',
          marcaDispositivo: '',
          serialDispositivoAnterior:'',
          activoEquipoAnterior: '',
          modeloDispositivo: '',
          marcaMonitor: '',
          modeloMonitor: '',
          Celular:'No',
          IMEI:'',
          IMEI2:'',
          segundoMonitor: 'No',
          serialMonitor2: '',
          activoMonitor2: '',
          estadoMonitor2: '',
          diadema: 'No',
          marcaDiadema: '',
          serialDiadema: '',
          estadoDiadema: '',
          raton: 'No',
          estadoRaton: '',
          teclado: 'No',
          estadoTeclado: '',
          camara: 'No',
          serialMonitor: '',
          activoMonitor: '',
          estadoMonitor: '',
          segundoMonitor: 'No',
          marcaMonitor2:'',
          modeloMonitor2:'',
          serialMonitor2: '',
          activoMonitor2: '',
          observaciones: '',
          nombreTrabajador: '',
          cedula: '',
          expedicionCedula: '',
          NombreRecibe: ''.toUpperCase(),
          firma1: null,
          firma2: null,
        },
        condiciones: false,
        cargar: 'fas fa-file-pdf me-2',
      };
    },
    
    computed: {
      minDate() {
        // Obtén la fecha actual en formato YYYY-MM-DD
        const today = new Date();
        const yyyy = today.getFullYear();
        const mm = String(today.getMonth() + 1).padStart(2, '0');
        const dd = String(today.getDate()).padStart(2, '0');
        return `${yyyy}-${mm}-${dd}`;
      },
    },
  
    methods: {

        ...mapMutations(["getSession", 'mostrarCamps','cerrarSesionAuto','saveForm','loadForm']),
    // Cargar formulario si hay algo
    cargarEntrega(){
      this.loadForm(this.usuario_session[0].cedula);
      this.formData = this.datos_form;
    },

    // Recoger datos y guardar datos
    guardarEntrega(){
      let cedula = this.usuario_session[0].cedula;
      this.saveForm({documento:cedula,form:this.formData});
    },
      async submitForm() {
        if (this.isFormValid) {
          this.generarPDFEntrega();
        } else {
          this.showNotification('warning', 'Por favor, complete todos los campos requeridos.');
        }
        
      },
      
      limpiarTodo() {
        this.formData = {
          tipo_formulario: 'entrega',
          dispositivo: '',
          Tipoescritorio: '',
          numeroCaso: '',
          nombres: '',
          campana: '',
          correoPersonal: '',
          correoJefe: '',
          serialDispositivo: '',
          activoDispositivo: '',
          serialDispositivoAnterior:'',
          activoEquipoAnterior: '',
          estadoDispositivo: '',
          marcaDispositivo: '',
          modeloDispositivo: '',
          marcaMonitor: '',
          modeloMonitor: '',
          Celular:'No',
          IMEI:'',
          IMEI2:'',
          segundoMonitor: 'No',
          serialMonitor2: '',
          activoMonitor2: '',
          estadoMonitor2: '',
          diadema: 'No',
          marcaDiadema: '',
          serialDiadema: '',
          estadoDiadema: '',
          raton: 'No',
          estadoRaton: '',
          teclado: 'No',
          estadoTeclado: '',
          camara: 'No',
          serialMonitor: '',
          activoMonitor: '',
          estadoMonitor: '',
          segundoMonitor: 'No',
          marcaMonitor2:'',
          modeloMonitor2:'',
          serialMonitor2: '',
          activoMonitor2: '',
          observaciones: '',
          nombreTrabajador: '',
          cedula: '',
          expedicionCedula: '',
        };
      },
      generarPDFEntrega: async function () {
        this.cargar = 'fa-solid fa-spinner fa-spin';
        
        this.formData.firma1 = this.$refs.signaturePad.getSignatureDataUrl();
        this.formData.firma2 = this.$refs.signaturePad2.getSignatureDataUrl();
        
        this.showNotification('info', 'Generando PDF...');
        
        await axios.post('/PDF_ETN', this.formData, {
          responseType: 'blob'
        })
        .then((res) => {
          if (res.status == 200) {
            var enlace = document.getElementById('down');
            enlace.download = `${new Date().toISOString().slice(0,10)}_ACTA_ENTREGA_${this.formData.nombres.toUpperCase()}_${this.formData.campana.toUpperCase()}.pdf`;
            enlace.href = URL.createObjectURL(res.data);
            enlace.click();
            URL.revokeObjectURL(enlace.href);
            this.showNotification('success', 'PDF generado con éxito');
          }
        }).catch((err) => {
          console.error(err);
          this.showNotification('error', 'Error al generar el PDF');
        });
        
        this.cargar = 'fas fa-file-pdf me-2';
      },
      
      showNotification(icon, text) {
        Swal.fire({
          icon,
          text,
          toast: true,
          position: 'top-end',
          showConfirmButton: false,
          timer: 3000
        });
      },
      cargarUsuarioSession() {
        try {
          this.formData.NombreRecibe = this.usuario_session[0].nombre_gestor;
  
        } catch (error) {
          // Console.log(error);
        }
      }
    },
    
    computed: {
      isFormValid() {
        // Validación de campos requeridos
        return (
          this.formData.nombres&&
          this.formData.serialDispositivo&&
          this.formData.cedula&&
          this.formData.correoPersonal&&
          this.formData.numeroCaso &&
          this.formData.campana
        );
      },
      ...mapState(["usuario_session", 'lista_operaciones', 'inputs', 'botones', 'color_label', 'datos_form'])
    },
  };
  </script>
  
  <style scoped>
  .form-control,
  .form-select {
    height: calc(1.5em + 0.75rem + 2px);
    padding: 0.375rem 0.75rem;
    font-size: 1rem;
    line-height: 1.5;
    border-radius: 0.25rem;
  }
  
  .form-select {
    padding-right: 2.25rem;
    background-position: right 0.75rem center;
  }
  </style>