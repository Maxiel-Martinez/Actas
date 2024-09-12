@vite(['resources/js/app.js','resources/css/app.css','resources/sass/app.scss'])
<!DOCTYPE html>
<html>
<head>
  <title>Registro de gestores y campañas</title>
  <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
  
</head>
<body style="background-image:url('{{asset('images/5556661.jpg')}}');">
  <div class="bg-white p-3 w-5/6 mx-auto">
    <div style="display:flex;justify-content:space-between;">
      <img src="{{ asset('images/americas.png') }}" alt="Descripción de la imagen" class="logo">
      <div class="p-2">

        <a class="text-purple-800 text-2xl" href="{{route('home')}}"><i class="fa-solid fa-backward"></i>  Volver</a><br>
      </div>
    </div>
    <!-- framework Vue.js -->
    
    <div id="app">
      <h2 class="text-white border-2 text-2xl p-3 bg-purple-950 m-3">Registro de usuario y elementos</h2>
      <div class="grid grid-cols-3 border-2 mb-4 p-3 justify-between">
        <h3 class="mb-3 text-2xl text-white bg-purple-950 col-span-3 p-2 text-center">Detalles de entidad</h3>
        <div class="flex justify-center">
          {{-- Modal gestor --}}
          <modal-gestor/>
          {{-- Modal componente --}}
        </div>
        {{-- Modal campaña --}}
        <div class="flex justify-center">
          
          <modal-camp/>
        </div>

        <div class="flex justify-center">
          
          <modal-componente/>
        </div>
      </div>
      <section class="grid grid-cols-1 text-center p-3 mb-4 border-2">
        <h3 class="text-2xl bg-purple-950 text-white text-center p-2">Copia de correos a usuarios</h3>
        {{-- Copia correos a usuarios --}}
        <copia-correos/>
      </section>
      <div class="p-3 border-2">
        <h2 class="text-2xl border-2 bg-purple-950 text-white p-2">Datos de busqueda</h2>
        {{-- Tabla de busqueda con vue --}}
        <tabla-acciones/>
      </div>
    </div>
    
  </body>
  <script src="{{ asset('js/app.js') }}"></script>
</html>