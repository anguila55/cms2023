# Para empezar

1. En terminal hacer pull de la ultima versión del código ``git pull origin main``.
2. En el explorador del OS copiar y pegar carpeta del código (rondasbtbox) y luego cambiar el el nombre por el del evento.
1. Dentro del codigo borrar carpeta .git.
3. ****Base de datos****: en explorador de archivos duplicar la base de datos original  en ``C:\Data`` y cambiarla con el nombre del nuevo evento. Luego en el código,  ir a  *func/config.benvido.php* cambiar el nombre por el nuevo evento en
    ``localhost:C:\Data\coordinador-nombreEvento.gdb``

5. Por ultimo hacer todos los cambios en el front correspondientes al evento.

> NOTA: En C:\xampp7\apache\conf\extra\httpd-vhosts.conf hacer un nuevo virtualHost con el nombre de la carpeta que contiene el evento.

```apacheconf
<VirtualHost *:80>
        DocumentRoot "C:/AppWeb/[NOMBRE DE LA CARPETA]"
        ServerName [Nombre del Evento].localhost
        <Directory "C:/AppWeb/[NOMBRE DE LA CARPETA]">
            AllowOverride All
            Require all granted
            Options Indexes FollowSymLinks
            #If you want to allow access from your internal network
            # For specific ip addresses add one line per ip address
            #Allow from 192.168.0.100
            # For every ip in the subnet, just use the first 3 numbers of the subnet
            #Allow from 192.168.0
            # If you want to allow access to everyone
            #Allow from all
        </Directory>
    </VirtualHost> 
```

## Imágenes

1. Comprimir imágenes (usar: [http://png2jpg.com/es](http://png2jpg.com/es) o [https://compressjpeg.com/es/](https://compressjpeg.com/es/)).
1. ****Banners:**** se cargan desde el backend.
1. ****Logo****,
