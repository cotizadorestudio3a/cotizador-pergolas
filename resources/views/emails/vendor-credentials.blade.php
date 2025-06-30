@component('mail::message')
# ¡Bienvenido, {{ $user->name }}!

Tu cuenta ha sido creada con éxito. Aquí tienes tus credenciales de acceso:

@component('mail::panel')
**Email:** {{ $user->email }}  
**Contraseña temporal:** {{ $plainPassword }}
@endcomponent

> Te recomendamos cambiar tu contraseña después del primer inicio de sesión.

@component('mail::button', ['url' => route('login').'/?email='.$user->email ])
Iniciar sesión
@endcomponent

¡Gracias por formar parte de nuestro equipo!

@endcomponent
