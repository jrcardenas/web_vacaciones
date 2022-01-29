<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}" />

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->

    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap">

    <!-- Styles -->
    <script src="{{ asset('js/moment.min.js') }}" defer></script>

    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
    <!-- Scripts -->

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

    <script src="{{ asset('js/app.js') }}" defer></script>

    <link rel="stylesheet" href="{{ asset('css/fullcalendar.min.css') }}">
    <script src="{{ asset('js/fullcalendar.min.js') }}" defer></script>
    <script src="{{ asset('js/bootstrap.min.js') }}" defer></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>


</head>

<body class="font-sans antialiased">
    <div class="min-h-screen bg-gray-100">
        @include('layouts.navigation')

        <!-- Page Heading -->
        <header class="bg-white shadow">
            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                {{ $header }}
                <div class="container" >
      
      <h1 class="text-center text-primary"><u>Calendario</u></h1>
      
      <input type="hidden" id="idEmpleado" name="idEmpleado" value="{{ Auth::user()->id }}">
      <input type="hidden" id="idJefeEquipo" name="idJefeEquipo" value="{{ Auth::user()->idJefeEquipo }}">
      <input type="hidden" id="name" name="name" value="{{ Auth::user()->name }}">

      <form>
          <fieldset>
              <legend>Elige tipo de evento</legend>
              <label>
                  <input type="Radio" name="evento" value="vacas" checked> Vacaciones
              </label>
              <label>
                  <input type="Radio" name="evento" value="cumple"> Cumpleaños
              </label>
              <label>
                  <input type="Radio" name="evento" value="tarea"> Tarea
              </label>
              <label>
                  <input type="Radio" name="evento" value="reuni"> Reunión
              </label>


          </fieldset>
          <div id="calendar">

          </div>
      </form>
  </div>
  
            </div>
        </header>

        <!-- Page Content -->
        <main>
            {{ $slot }}
        </main>
    </div>
 
</body>

</html>