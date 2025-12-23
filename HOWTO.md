> **DISCLAIMER** Esta es la forma en la que yo desarrollo para VirtualPOL, no es en absoluto la única forma de trabajar aquí ni tiene por que se necesariamente la mejor. Las instrucciones que voy a poner a continuación serán para sistemas con Windows, doy por hecho que si usas Linux no hace falta que te explique como instalar docker o como acceder a una BD :P


# Herramientas
Para poder trabajar en el desarrollo de VP no hacen falta demasiadas herramientas externas, a poco que seas algo mañoso te sirve con un bloc de notas cualquiera, pero voy a listar aquí las herramientas que yo uso actualmente:

* [Docker](https://docs.docker.com/docker-for-windows/install/)
* [DBeaver](https://dbeaver.com/) 
* [GIT](https://git-scm.com/download/win)
* [Visual Studio Code](https://code.visualstudio.com/)
* Linea de comandos

A lo largo de la guía indicaré que se deben lanzar comandos, estos comandos se deben ejecutar en una ventana de comandos (valga la redundancia) y para abrir una basta con pulsar la tecla windows + R y en la ventana que nos aparecerá escribir "cmd" y pulsar enter. Esto nos abrirá una nueva venta negra con letras blancas en la que introduciremos los comandos apropiados.

# Descarga del código fuente

> **NOTA:** Recomiendo trabajar siempre en un directorio en la raíz de la unidad en la que estemos y evitar los espacios en blanco en los nombres de directorio, así que como recomendación abrir una ventana de comandos y escribir:

```
cd \
mkdir proyectos
cd proyectos
```

El código de VP se aloja en GitHub en el repositorio https://github.com/JavierGonzalez/POL. Dicho repositorio es público por lo que cualquier persona puede acceder a él y realizar un "fork" para trabajar en él, para ello simplemente deberemos registrarnos en GitHub y una vez dentro pulsar en el botón de Fork. Esta acción basicamente lo que hará será crearnos una copia del proyecto en nuestro perfil de usuario, una vez hecho nos debería aparecer una pantalla indicando que se ha completado la acción con exito y un enlace a nuestro nuevo respositorio. Por ejemplo, si nuestro usuario es Chiribito, nuestro nuevo repositorio será https://github.com/Chiribito/POL esté será el respositorio con el que trabajaremos a partir de ahora.

Una vez hecho el fork procederemos a descargarnos el código como tal con el siguiente comando:

```
c:\proyectos> git clone https://github.com/Chiribito/POL
```

Este comando nos creará una carpeta "POL" en el directorio donde lo lancemos y dentro de ella copiará todo el código. 