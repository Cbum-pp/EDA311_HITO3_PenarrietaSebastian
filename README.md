# Manual de Instalación, Configuración y Revisión del Proyecto - Hito 3

Aplicación web desarrollada en PHP y MySQL para la gestión de clientes, productos y pedidos de una tienda. El proyecto integra operaciones CRUD completas, control de versiones en Git/GitHub, estructuras de datos dinámicas (Lista Doblemente Enlazada implementada desde cero) y el algoritmo de ordenamiento Bubble Sort.

---

## 1. Acceso a la Base de Datos con MySQL Workbench
Para verificar la base de datos directamente en el servidor remoto facilitado por el docente, utilice las siguientes credenciales en MySQL Workbench:

* **Connection Name:** `evaluacion_H3` (o el nombre de su preferencia)
* **Hostname / IP Address:** `10.0.40.85`
* **Port:** `3306`
* **Username:** `u_penarrieta_sebastian`
* **Password:** `Sol#26H8wJ4`
* **Default Schema / Database:** `tienda_penarrieta`

### Pasos para configurar la conexión en MySQL Workbench:
1. Abra **MySQL Workbench**.
2. En la pantalla principal, haga clic en el botón **(+)** junto a *Database Connections*.
3. En la ventana de configuración, ingrese el **Hostname** (`10.0.40.85`), el **Username** (`u_penarrieta_sebastian`) y confirme el puerto `3306`.
4. Haga clic en el botón **Store in Vault...**, ingrese la contraseña `Sol#26H8wJ4` y presione **OK**.
5. Presione **Test Connection** para verificar la comunicación exitosa con el servidor.
6. Guarde con **OK** y haga doble clic en la conexión para explorar las tablas `clientes`, `productos` y `pedidos`.

---

## 2. Descarga del Código desde GitHub y Ubicación Directiva

### Opción A: Mediante Git (Clonación por terminal)
1. Abra la terminal o consola de comandos.
2. Navegue hasta la carpeta pública de su servidor web local:
   * **En XAMPP:** `cd C:\xampp\htdocs\`
3. Ejecute el comando de clonación:
   ```bash
   git clone [https://github.com/Cbum-pp/EDA311_HITO3_PenarrietaSebastian.git](https://github.com/Cbum-pp/EDA311_HITO3_PenarrietaSebastian.git) evaluacion_H3
