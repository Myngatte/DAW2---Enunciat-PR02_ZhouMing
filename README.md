# PROYECTO_RESTAURANTE

## Bienvenidos a "La Comida del Futuro"

Este proyecto es un sistema de gestión para restaurantes o bares, diseñado para ayudar a los camareros a gestionar las mesas y sus asignaciones de forma intuitiva. Es un proyecto grupal del **Grupo DOC BROWN**, como parte de nuestra formación en desarrollo de aplicaciones web.

### Equipo Doctor Brown
- **Zhou, Ming**
- **Alda Cárdenas, Hugo**
- **Peñas Andrea, Erik**
- **Ventura Reynés,Àlex**

---

## Descripción General

La aplicación "La Comida del Futuro" actúa como un TPV (Terminal de Punto de Venta) orientado a los empleados de un restaurante, permitiendo:
- **Asignar** y **desasignar mesas** de manera visual.
- **Visualizar la disposición** actual de las mesas en el restaurante.
- Acceder a un **historial de asignaciones** con detalles de cada reserva o asignación.

## Funcionalidades Principales

1. **Login con Control de Sesión**
   - Acceso seguro mediante un sistema de autenticación para que solo el personal autorizado (pertenecientes a la BBDD) pueda ingresar al sistema. 

2. **Mapa de Navegación Interactivo**
   - Al ingresar, el usuario es recibido con un mapa visual del restaurante dividido en **salas**.
   - Cada sala muestra el **número de mesas disponibles** y está diseñada para ser interactiva.
   - Al seleccionar una sala, se accede a un **mapa detallado de las mesas** en esa área.
   - Las mesas están codificadas por color:
     - **Verde**: Mesa libre y disponible para asignación.
     - **Rojo**: Mesa ocupada.
   - Los camareros pueden:
     - **Asignar una mesa** si está disponible.
     - **Desasignar una mesa** si ya está ocupada.

3. **Historial**
   - Acceso al historial de asignaciones realizadas en el restaurante.
   - Cada registro incluye:
     - **Número de mesa y a qué sala pertenece**.
     - **Fecha y hora de asignación y desasignación** (duración).
     - **Nombre del camarero** que realizó la asignación.
     - **Cliente** asignado a la mesa.
   - Este apartado ofrece **filtros(desplegables)** para acotar los resultados, facilitando la búsqueda de asignaciones específicas.

---

## Estructura del Proyecto

### 1. **Login y Sesión**
   - Módulo seguro para la autenticación de camareros.
   - Se gestionan sesiones activas para mantener la seguridad y privacidad de los datos.

### 2. **Mapa del Restaurante**
   - Cada sala es clicable, y las mesas en cada sala están dispuestas gráficamente para replicar la disposición física del restaurante.
   - Interacción dinámica con las mesas para asignación y desasignación.

### 3. **Historial de Asignaciones**
   - Permite al equipo de gestión revisar el uso de las mesas en el tiempo.
   - Ofrece filtros avanzados para ordenar y buscar asignaciones por mesero, cliente, o duración de la reserva.

---

## Instrucciones de Navegación

1. **Inicio de Sesión**: Al iniciar sesión, el camarero accede al mapa del restaurante.
2. **Seleccionar Sala**: En el mapa principal, elige una sala para ver su disposición de mesas.
3. **Asignar o Desasignar Mesas**: Selecciona una mesa para ver su estado. Si está disponible, asígnala a un cliente; si ya está ocupada, puedes desasignarla.
4. **Consultar Historial**: Desde el menú principal(mapa del restaurante), accede a "Historial" para ver todos los registros de asignaciones y realizar búsquedas detalladas mediante los filtros disponibles.

