/**
*    File        : frontend/js/api/apiFactory.js
*    Project     : CRUD PHP
*    Author      : Tecnologías Informáticas B - Facultad de Ingeniería - UNMdP
*    License     : http://www.gnu.org/licenses/gpl.txt  GNU GPL 3.0
*    Date        : Mayo 2025
*    Status      : Prototype
*    Iteration   : 3.0 ( prototype )
*/

export function createAPI(moduleName, config = {}) 
{
    //construye la URL de acceso al servidor
    const API_URL = config.urlOverride ?? `../../backend/server.php?module=${moduleName}`;    //si existe usa ese valor (izq), si no lo crea (der)

    async function sendJSON(method, data)     //funcion interna para enviar datos al servidor, la usan create(), update() y remove() mas abajo
    {
        //se hace la peticion fetch() (nativa de js) al backend, busca la informacion en la URL de la api
        const res = await fetch(API_URL,{method,    //get, post, put, etc
                                         headers: { 'Content-Type': 'application/json' },    //indica que se está enviando JSON
                                         body: JSON.stringify(data)    //convierte los datos a texto JSON
                                         });

        if (!res.ok) throw new Error(`Error en ${method}`);    //throw es como return, corta el flujo, la diferencia es que throw no devuelve en la funcion
        return await res.json();
    }

    return {    //devuelve un objeto con las 4 funciones basicas que se usan para modificar los estudiantes (siglas de CRUD)
        async create(data)    //Create
        {
            return await sendJSON('POST', data);
        },
        async fetchAll()      //Read
        {
            const res = await fetch(API_URL);    //hace una peticion GET simple y espera la respuesta con await
            if (!res.ok) throw new Error("No se pudieron obtener los datos");
            return await res.json();    //convierte los datos en un objeto jsony los devuelve
        },
        async update(data)    //Update
        {
            return await sendJSON('PUT', data);
        },
        async remove(id)      //Delete
        {
            return await sendJSON('DELETE', { id });    //elimina el id que recibe (un alumno)
        }
    };
}
