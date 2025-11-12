<!DOCTYPE html>
<html lang="es" class="h-full">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="h-full bg-cover bg-center bg-no-repeat" style="background-image: url('https://agn.gt/wp-content/uploads/2024/09/WhatsApp-Image-2024-09-15-at-13.44.00.jpeg');">

    <div class="flex min-h-full items-center justify-center px-4 py-12 sm:px-6 lg:px-8 bg-white/8 backdrop-blur-md rounded-xl shadow-xl">
        <div class="w-full max-w-sm space-y-10">
            <div>
                <h2 class="mt-10 text-center text-4xl font-bold tracking-tight text-gray-900">¡Bienvenido!</h2>
                <h3 class="mt-5 text-center tracking-tight text-gray-70">Inicia sesión con tu usuario y contraseña</h3>
            </div>

            <form class="space-y-6" action="procesar_login.php" method="POST">
                <div>
                    <div class="col-span-2">
                        <input name="usuario" type="text" required placeholder="Usuario"
                            class="block w-full rounded-t-md bg-white px-3 py-2 text-base text-gray-900 outline outline-1 outline-gray-300 placeholder:text-gray-400">
                    </div>
                    <div class="-mt-px">
                        <input name="clave" type="password" required placeholder="Contraseña"
                            class="block w-full rounded-b-md bg-white px-3 py-2 text-base text-gray-900 outline outline-1 outline-gray-300 placeholder:text-gray-400">
                    </div>
                </div>

                <div class="flex items-center justify-between">
                    <div class="flex gap-2 items-center">
                        <input id="remember-me" name="remember-me" type="checkbox"
                            class="h-4 w-4 rounded border-gray-300 text-blue-600 focus:ring-blue-600">
                        <label for="remember-me" class="text-sm text-gray-900">Recordarme</label>
                    </div>

                    <div class="text-sm">
                        <a href="#" class="font-semibold text-blue-600 hover:text-blue-500">¿Olvidaste tu contraseña?</a>
                    </div>
                </div>

                <div>
                    <button type="submit"
                        class="flex w-full justify-center rounded-md bg-blue-600 px-3 py-2 text-sm font-semibold text-white hover:bg-blue-500 focus:outline-blue-600 transition">
                        Iniciar Sesión
                    </button>
                </div>
            </form>

            <p class="text-center text-sm text-gray-700">
                ¿No tienes una cuenta?
                <a href="#" class="font-semibold text-blue-600 hover:text-blue-500">Regístrate</a>
            </p>
        </div>
    </div>

</body>

</html>