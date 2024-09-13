<template>
  <div class="bg-custom min-h-screen flex items-center justify-center">
    <div class="bg-white bg-opacity-10 p-8 rounded-xl backdrop-blur-md w-96">
      <div class="flex justify-between items-center mb-8">
        <img src="../assets/Logo_Blanco.png" alt="Américas BPS Logo" class="logo">
      </div>
      <form @submit.prevent="login">
        <div class="mb-4 relative">
          <span class="absolute inset-y-0 left-0 flex items-center pl-3">
            <i data-feather="credit-card" class="h-5 w-5 text-gray-600"></i>
          </span>
          <input type="text" v-model="datos_login.cedula" placeholder="Cédula" class="w-full pl-10 pr-3 py-2 bg-white bg-opacity-50 rounded-md text-gray-800 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-purple-600">
        </div>
        <div class="mb-6 relative">
          <span class="absolute inset-y-0 left-0 flex items-center pl-3">
            <i data-feather="lock" class="h-5 w-5 text-gray-600"></i>
          </span>
          <input type="password" v-model="datos_login.password" placeholder="Password" class="w-full pl-10 pr-3 py-2 bg-white bg-opacity-50 rounded-md text-gray-800 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-purple-600">
        </div>
        <button type="submit" class="w-full bg-purple-600 text-white py-2 rounded-md hover:bg-purple-700 transition duration-300">
          <span :class="cargar"></span> {{titulo}}
        </button> 

      </form>
      <p class="text-sm text-white mt-6 text-center"><b>Nota:</b> Tenga en cuenta que para asignar la <b>clave</b> debe escribirla solo una vez en el campo de contraseña, seguido presione <b>iniciar sesión.</b></p>
    </div>
  </div>
</template>

<script>
import axios from 'axios';
import Swal from 'sweetalert2';
import { onMounted } from 'vue';
import feather from 'feather-icons';
import { fas } from '@fortawesome/free-solid-svg-icons';
import { library } from '@fortawesome/fontawesome-svg-core';

library.add(fas);   

export default {
  name: 'Login',
  setup() {
    onMounted(() => {
      feather.replace();
    });
  },
  data() {
    return {
      datos_login: {
        cedula: '',
        password: '',
      },
      titulo: 'Iniciar sesion',
      cargar: '',
      contador: 0,
    }
  },
  methods: {
    async login() {
      this.cargar = 'spinner-border spinner-border-sm';
      this.titulo = '';
      try {
        const res = await axios.post('/Actas_de_responsabilidad/Login/Validate', this.datos_login);
        switch (res.data) {
          case 0:
            Swal.fire({title:'¡Ups!',text:'Este usuario no existe',icon:'warning',toast:true,position: "top-end",timer:2000, showConfirmButton:false});
            break;
          case 1:
            Swal.fire({title:'¡Exitoso!',text:'¡Clave correctamente asignada!',icon:'success',toast:true,position: "top-end",timer:2000, showConfirmButton:false});
            break;
          case 2:
            window.location.href = '/Actas';
            history.replaceState(null, '', '/Actas');
            this.contador = 0;
            break;
          case 5:
            Swal.fire({title:'¡Ups!',text:'¡Este usuario esta bloqueado, contacte al administrador!',icon:'info',toast:true,position: "top-end",timer:2000, showConfirmButton:false});
            break;
          case 4:
            this.contador += 1;
            if(this.contador >= 4){
              try {
                const blockRes = await axios.get(`/Actas_de_responsabilidad/Gestores/BloquearUsuario/${this.datos_login.cedula}`);
                if(blockRes.data){
                  Swal.fire({
                    icon: 'info',
                    text: '¡Tu usuario ha sido bloqueado!'
                  });
                  this.contador = 0;
                }
              } catch (err) {
                console.error(err);
              }
            } else {
              Swal.fire({title:'¡Ups!',text:`¿Eres tu?, Digita nuevamente la contraseña ${this.contador}/3 intentos`,icon:'info',toast:true,position: "top-end",timer:2000, showConfirmButton:false});
            }
            break;      
        }
      } catch (error) {
        console.error(error);
      } finally {
        this.cargar = '';
        this.titulo = 'Iniciar sesion';
      }
    },
    async cerrar_sesion() {
      try {
        const res = await axios.post('/Actas_de_responsabilidad/Login/Destroy', this.datos_login);
        if(res.data){
          window.location.href = '/Actas_de_responsabilidad/Login';
          history.replaceState(null, '', '/Actas_de_responsabilidad/Login');
        }
      } catch (error) {
        console.error(error);
      }
    }
  }
}
</script>

<style scoped>
.bg-custom {
  background-image: url('../assets/5556661.jpg');
  /* background: linear-gradient(0deg, rgba(150,47,164,1) 0%, rgba(92,30,157,1) 100%); */
  /* background-size: cover; */
  background-position: center;
}
</style>