# crud-php-prototipo-refactorizado-3.0
a. Sí el usuario intenta agregar dos materias de igual
nombre debe mostrar un mensaje por pantalla
indicando que esto no es posible.
b. Cuando se intente guardar una relación materia y
estudiante repetida, también debe validarse y dar un
alerta por pantalla de que esa relación ya existe.
c. Actualmente el código de la base de datos tiene
habilitada la opción de borrado en cascada en la
tabla students_subjects (ON DELETE CASCADE): debería
1
deshabilitar esta opción y crear una validación que
muestre por pantalla que no se puede borrar una
materia o un estudiante si este está involucrado en
una relación en la tabla/módulo intermedio
students_subjects.
d. Otra validación necesaria o filtro? -- Cantidad de materias para cursar
