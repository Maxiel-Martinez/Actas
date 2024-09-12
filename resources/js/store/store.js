import Vuex from 'vuex';
import axios from 'axios';
import Swal from 'sweetalert2';


const store = new Vuex.Store({
    state:{
        componentes_vuex:[],
        lista_gestores:[],
        usuario_session:[],
        lista_operaciones:[],
        // Datos form sirve para recoger los datos que se guardan en localStorage si desen recuperar formularios guardados.
        datos_form:[],
        name_gestor_session:'',
        // Variables para clases
        inputs:'outline-none ring-0 focus:ring-purple-500 transition duration-300 focus:ring-2 bg-opacity-80 text-slate-500 m-1 p-1',
        botones:'bg-purple-600 hover:bg-purple-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-opacity-50 m-2',
        color_label:'text-slate-900 p-1 mt-2',
        tabla:'outline-none border-none text-center p-3',
    },
    mutations:{
        // Mostrar componentes de manera global
        mostrarComponentes(state){
            axios.get('/Actas_de_responsabilidad/Componentes')
            .then(respuesta=>{
                state.componentes_vuex = respuesta.data;
            })
            .catch(error=>{
                console.log(error);
            });
        },
        // Mostrar gestores de manera global solo a los activos
        mostrarGestores(state){
            axios.get('/Actas_de_responsabilidad/Gestores')
            .then(gestores=>{
                state.lista_gestores = (gestores.data);
            })
            .catch(error=>{
                console.log(error);
            })
        },
        mostrarGestoresAll(state){
            axios.get('/Actas_de_responsabilidad/GestoresAll')
            .then(gestores=>{
                state.lista_gestores = (gestores.data);
            })
            .catch(error=>{
                console.log(error);
            })
        },
        // Saber que usuario inicio sesion para escogerlo en el formulario
        getSession(state){
            axios.get('/Actas_de_responsabilidad/Gestores/Session')
            .then(res=>{               
                state.usuario_session[0] = res.data;
            }).catch(error=>{
                
            })
        },
        // Nombre del gestor con la sesion
        getNameGestor(state){
            axios.get('/Actas_de_responsabilidad/Gestores/Session')
            .then(res=>{               
                state.name_gestor_session = res.data.nombre_gestor;
            }).catch(error=>{
                
            })
        },
        // ID del gestor que inicia sesion
        getIDGestor(state){
            axios.get('/Actas_de_responsabilidad/Gestores/Session')
            .then(res=>{               
                state.name_gestor_session = res.data.id;
            }).catch(error=>{
                
            })
        },
        // Mostrar campañas que esten activas
        mostrarCamps(state){
            axios.get('/Actas_de_responsabilidad/Campanas')
            .then((cam)=>{
                state.lista_operaciones = cam.data;
            })
            .catch((error)=>{
                console.log(error);
            })
        },
        // Mostrar todas las campañas
        mostrarCampsAll(state){
            axios.get('/Actas_de_responsabilidad/CampanasAll')
            .then((cam)=>{
                state.lista_operaciones = cam.data;
            })
            .catch((error)=>{
                console.log(error);
            })
        },
        // Cerrar la sesion despues de un tiempo estimado de inactividad
        cerrarSesionAuto(state, lista){
            let tiempo = null;
            function start(){
                tiempo = setTimeout(()=>{
                    axios.get('/Actas_de_responsabilidad/Login/DestroyAuto')
                    .then(res=>{
                        if(res.data){
                            window.location.href = "/Actas";
                            history.replaceState(null, '', '/Actas');                                    
                        }
                    })
                    .catch(err=>{
                        // console.log(err);
                    });
                },(60000*15)); // La sesion se cierra en 15 minutos
            }
            function reset(){
                clearInterval(tiempo);
                start();
                // console.log('inicia nuevamente!')
            };

            document.addEventListener('mousemove',reset);


        },
        
        // Funcion para guardar formularios por parte del usuario
        saveForm(state, lista){
            var documento = lista.documento;
            let datos = lista.form;
            let mapeo = new Map();
            try{
                // Validar si en el localStorage tiene datos y buscar y reemplzaar los datos del usuario logueado.
                if(localStorage.length !== 0){
                    let local_storage = new Map(Object.entries(JSON.parse(localStorage.getItem('cache_actas'))));
                    // Validar que existe el usuario con un acta guardada
                    if(local_storage.has(documento.toString())){
                        localStorage.clear();
                        // Actualizar los datos de la coleccion
                        local_storage.set(documento.toString(),datos);
                        
                        // Volver a llevar los datos al storage
                        localStorage.setItem('cache_actas',JSON.stringify(Object.fromEntries(local_storage)));

                    }else{
                        local_storage.set(documento,datos);
                        localStorage.setItem('cache_actas',JSON.stringify(Object.fromEntries(local_storage)));
                    }
                }else{
                    // Guardar datos si no encuentra al usuario en la cache crear una coleccion de datos
                    mapeo.set(documento,datos);
                    localStorage.setItem('cache_actas',JSON.stringify(Object.fromEntries(mapeo)));
                    
                }
                Swal.fire({
                    title: '¡Satisfactorio!',
                    text: '¡Se ha guardado el formulario exitosamente!',
                    icon: 'success',
                    toast:true,
                    timer:3000,
                    showConfirmButton:false,
                    position:'top-end'
                });
            }catch(Exception){
                // console.log(Exception);
            }
        },

        // Funcion para cargar un acta guardada anteriormente por el usuario.
        loadForm(state, documento){
            try{
                let local_storage = new Map(Object.entries(JSON.parse(localStorage.getItem('cache_actas'))));
                if(local_storage.has(documento.toString())){
                    state.datos_form = local_storage.get(documento.toString());
                }else{
                    console.log("Not Found");
                }
                Swal.fire({
                    title: '¡Satisfactorio!',
                    text: '¡Se ha cargado el formulario exitosamente!',
                    icon: 'success',
                    toast:true,
                    timer:3000,
                    showConfirmButton:false,
                    position:'top-end'
                });
            }catch(Exception){
                console.log("");
            }
            },
        // Envio de correos para las actas
        sendEmails(state, data){
            axios.post('/Actas/Emails', data)
            .then(respuesta=>{
                if(respuesta.data){
                    Swal.fire({
                        title: '¡Satisfactorio!',
                        text: '¡Se ha enviado el acta a los correos relacionados!',
                        icon: 'success',
                        toast:true,
                        timer:3000,
                        showConfirmButton:false,
                        position:'top-end'
                    });
                }else{
                    alert('Ha ocurrido un error al enviar el correo!');
                }
            })
            .catch(err=>{
                console.log(err);
            });
        },
        // Generar la descarga con los archivos que estan en el servidor
        downloadPdfAgain(state, id) {
            axios.post(`/Actas_de_responsabilidad/Historial/DownloadPDF/${id}`, {}, { responseType: 'blob' })
                .then(res => {
                    // Descargar el pdf desde laravel
                    var link = document.getElementById('tryPdf');
                    link.download = (new Date().getDate().toLocaleString() + '_' + (new Date().getMonth() + 1).toString() + '_' + (new Date().getFullYear()).toString() + '_' + new Date().getTime().toString()) + 'RECUPERADO' + '.pdf';
                    link.href = URL.createObjectURL(res.data);
                    link.click();
                    URL.revokeObjectURL(link.href);
                })
                .catch(err => {
                    console.log(err)
                });

        },
        
    }
});

export default store;