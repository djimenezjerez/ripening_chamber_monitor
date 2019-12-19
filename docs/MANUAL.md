# MANUAL DE USUARIO DE MONITOREO DE CÁMARAS DE MADURACIÓN

## INGRESO AL SISTEMA

Este sistema es compatible con los navegadores Firefox y Chrome.
La dirección provista por el personal de sistemas es: [http://192.168.1.78](192.168.1.78)

![1_login](./images/1_login.png)

En esta pantalla se deben ingresar las credenciales en los campos de usuario y contraseña, mismas que serán provistas por el personal a cargo de la administración del sistema.

## COMPONENTES DEL SISTEMA

El sistema se compone de 3 partes:

![2_dashboard](./images/2_dashboard.png)

1. Menú principal para navegar entre los módulos de Reportes, Parámetros y Usuarios.
2. Monitoreo de los sensores en tiempo real.
3. Datos de usuario actual.

## MONITOREO EN TIEMPO REAL

### MONITOREO DE TEMPERATURA

![3_temperature](./images/3_temperature.png)

En esta sección se observan los valores actuales de temperatura de cada cámara de maduración y la hora en que dicho dato fue recibido.

### MONITOREO DE HUMEDAD

![4_humidity](./images/4_humidity.png)

En esta sección se observa un histórico de los últimos diez valores de humedad de cada cámara de maduración, el valor actual y la hora en que dicho dato fue recibido.

### MONITOREO DE SENSACIÓN TÉRMICA

![5_hic](./images/5_hic.png)

En esta sección se observa un histórico de los últimos diez valores de sensación térmica de cada cámara de maduración, el valor actual y la hora en que dicho dato fue recibido.

### MONITOREO DE DISPOSITIVOS

![6_device](./images/6_device.png)

En esta sección se observa el estado de la conexión del dispositivo que captura los datos de los sensores instalados en las tres cámaras de maduración.

## REPORTE HISTÓRICO DE DATOS

![7_menu_report](./images/7_menu_report.png)

En este módulo se visualizan los datos capturados para cada magnitud de cada cámara de maduración de acuerdo a un rango de fechas, indicando el valor medido por el sensor, la fecha y la hora en la cual se obtuvo la medición.

Para obtener un listado de los datos capturados se siguen los pasos a continuación:

1. Seleccionar una cámara de maduración.

![8_report_chamber](./images/8_report_chamber.png)

2. Seleccionar una magnitud.

![9_report_magnitude](./images/9_report_magnitude.png)

3. Seleccionar un rango de fechas.

![10_report_date](./images/10_report_date.png)

Con ello se mostrarán los valores capturados en el rango de fechas seleccionado.

En caso de existir datos en este rango se habilitará el botón de descarga del reporte Excel.

![11_report_download](./images/11_report_download.png)

Al hacer clic en este botón se descargara un archivo con el nombre de la cámara seguido del rango de fechas seleccionado.

![12_report_excel](./images/12_report_excel.png)

Este archivo contiene una los valores de cada magnitud en un rango de fechas ordenado de manera ascendente y ubicado en una hoja diferente para cada magnitud.

## GESTIÓN DE AMBIENTES

![13_menu_chamber](./images/13_menu_chamber.png)

En este módulo se visualizan las cámaras de maduración registradas.

Se tienen dos opciones para cada cámara registrada, edición de datos de la cámara y edición de parámetros de captura y límites de cada magnitud.

### LISTADO DE AMBIENTES

![14_chamber_list](./images/14_chamber_list.png)

### EDICIÓN DE DATOS DE UNA CÁMARA

Para cambiar el nombre con el que se visualizarán los datos de una cámara se debe seleccionar el botón de *lápiz* azul ubicado en la columna de **Acciones**

![15_chamber_edit](./images/15_chamber_edit.png)

Esta acción abrirá una ventana de diálogo donde se puede observar cual es el dispositivo que captura los datos de la cámara y se puede cambiar el nombre de la misma.

![16_chamber_name](./images/16_chamber_name.png)

### EDICIÓN DE PARÁMETROS DE CAPTURA

Para activar la captura de datos de una magnitud para cada cámara, cambiar el intervalo con el que se capturan los datos y los límites para el sistema de control visual, se debe seleccionar el botón de *gota* anaranjado ubicado en la columna de **Acciones**

![17_magnitude_edit](./images/17_magnitude_edit.png)

Esta acción abrirá una ventana de diálogo donde se puede activar o desactivar la captura de datos de una o más magnitudes para cada cámara por separado.

![18_magnitude_active](./images/18_magnitude_active.png)

También se puede definir el intervalo de tiempo (en minutos) en el cual los datos serán almacenados en la base de datos, dichos valores serán recuperados en los reportes como se mostró anteriormente.

![19_magnitude_time](./images/19_magnitude_time.png)

Para definir los límites inferior y superior se debe seleccionar una magnitud con la flecha inferior a la derecha que abrirá el formulario de edición de límites.

![20_magnitude_limits](./images/20_magnitude_limits.png)

Una vez editados los valores se debe **GUARDAR** el formulario mediante el botón correspondiente.

![21_magnitude_save](./images/21_magnitude_save.png)

Los cambios se aplican instantáneamente en el dispositivo, por lo cual después de guardar los límites los colores de la pantalla del monitor en tiempo real serán idénticos a los colores de los LEDs del dispositivo instalado en las cámaras de maduración.

## GESTIÓN DE USUARIOS

![22_user_list](./images/22_user_list.png)

### AÑADIR USUARIO

Para agregar usuarios al sistema se debe hacer click en el botón *Más*

![23_user_add](./images/23_user_add.png)


Se debe llenar el formulario y presionar el botón **Guardar**.

![24_user_new](./images/24_user_new.png)

Ahora se debe seleccionar uno de los dos roles:

* RESPONSABLE: Puede cambiar parámetros del sistema como se mencionó anteriormente, además de gestionar usuarios y roles de usuario, restablecer contraseñas de usuarios y realizar las acciones del rol MONITOR que se mencionan a continuación.
* MONITOR: Sólo puede monitorear la captura de datos en tiempo real y descargar reportes históricos.

![25_user_new_role](./images/25_user_new_role.png)

### RESTABLECER CONTRASEÑA DE USUARIO

Para restablecer la contraseña de cualquier usuario en caso de olvido basta con hacer click en el botón de *llave* rojo que se encuentra en la columna de ACCIONES del listado de usuarios.

![26_user_password](./images/26_user_password.png)

Con ello el usuario restablecido podrá ingresar al sistema con la contraseña por defecto, que es el mismo nombre de usuario que se muestra en el listado.

![27_user_password](./images/27_user_password.png)

### EDICIÓN DE DATOS DE USUARIO

Para editar los datos de un usuario se debe hacer click en el botón *lápiz* azul ubicado en la columna ACCIONES del listado de usuarios.

![28_user_edit](./images/28_user_edit.png)

Se debe modificar el formulario de acuerdo a los datos deseados y presionar el botón **Guardar**.

![29_user_active](./images/29_user_active.png)

En este formulario se puede desactivar a un usuario, al cambiar de estado *ACTIVO* a *INACTIVO* el usuario desactivado ya no podrá ingresar al sistema.

Los usuarios desactivados se pueden ubicar en la pestaña de **INACTIVOS**.

![30_user_inactive](./images/30_user_inactive.png)

Para volver a activar un usuario se debe hacer click en el botón *lápiz* azul ubicado en la columna ACCIONES del listado de usuarios. Luego cambiar el switch del estado *INACTIVO* a *ACTIVO* y **GUARDAR**.

![31_user_switch](./images/31_user_switch.png)

Con esto el usuario volverá a la pestaña de *ACTIVOS* del listado de usuarios.

### ASIGNACIÓN DE ROL

Para asignar o cambiar el rol de un usuario, se debe hacer click en el botón *escudo* anaranjado de la columna de ACCIONES del listado de usuarios.

![32_user_role](./images/32_user_role.png)

Ahora se debe asignar uno de los dos roles mencionados anteriormente y presionar el botón **GUARDAR**.

![33_role](./images/33_role.png)

## PERFIL DE USUARIO

El perfil de usuario permite cambiar la contraseña del usuario autenticado. Para ello se debe hacer click en botón con las iniciales del usuario actual en la parte superior derecha de la pantalla.

![34_profile](./images/34_profile.png)

En esta vista se puede cambiar la contraseña presionando el respectivo botón.

![35_profile_button](./images/35_profile_button.png)

Se debe llenar el formulario con la contraseña actual y repetir la contraseña nueva dos veces. El sistema informará de un error en caso de que la contraseña actual no sea la correcta o que los campos de contraseña nueva no coincidan.

![36_change_password](./images/36_change_password.png)

En caso de no existir errores en el formulario, el sistema cerrará la sesión y se deberá ingresar con la nueva contraseña.

![37_changed_password](./images/37_changed_password.png)

## CERRAR SESIÓN

Para salir de la sesión actual bastará con hacer click en la opción *CERRAR SESIÓN* del menú superior derecho.

![38_logout](./images/38_logout.png)

## RECOMENDACIONES

* La primera acción de un nuevo usuario debe ser el cambio de contraseña para evitar el uso indebido de la nueva cuenta.
* Se debe cerrar la sesión cuando no se vaya a hacer uso del sistema.
* Se deben desactivar los usuarios que ya no podrán ingresar al sistema lo antes posible para evitar errores por uso indebido.