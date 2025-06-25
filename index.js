let gobalData=[]
const inputSubmit = document.getElementById("input")
const codigoInput = document.getElementById("code")
const productoInput = document.getElementById("product")
const precioInput = document.getElementById("price")
const cantidadInput = document.getElementById("quantity")
const idInput = document.getElementById("id")

window.addEventListener("DOMContentLoaded", ObtenerDatos())

document.getElementById("form").addEventListener("submit", async(e)=>{
    e.preventDefault();
    const form = document.getElementById("form");
    const formData = new FormData(form);
    console.log("Form Data:", ...formData);
    
    if(inputSubmit.getAttribute("value")==="Registrar"){
        await fetch("./php/index.php", {
            method: "POST",
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            console.log("Success:", data);
        })
        .catch((error) => {
            console.log("Error:", error);
            setAlert("hubo un problema", "error")
        }).finally(ObtenerDatos());
        setAlert("Producto creado")
    }

    if(inputSubmit.getAttribute("value")==="Editar"){
        const data = {};
        formData.forEach((value, key) => {
        data[key] = value;
        });
        await fetch("./php/index.php", {
            method: "PUT",
             headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify(data)
        })
        .then(response => response.json())
        .then(data => {
            console.log("Success:", data);
        })
        .catch((error) => {
            console.log("Error:", error);
        }).finally(ObtenerDatos());
        inputSubmit.setAttribute("value", "Registrar")
        codigoInput.removeAttribute("value")
        productoInput.removeAttribute("value")
        precioInput.removeAttribute("value")
        cantidadInput.removeAttribute("value")
        idInput.removeAttribute("value")
        setAlert("datos actualizado")
    }
})

document.getElementById("form2").addEventListener("submit", async(e)=>{
    e.preventDefault()
    const tbody = document.getElementById("table-body")
    const form = document.getElementById("form2");
    const formData = new FormData(form);
    console.log(formData.get("id"))
    try{
        const response = await fetch(`./php/index.php?id=${formData.get("id")}`, {method:"GET"}).then(res=>res.json())
        console.log(response)
        if(response.length() > 0){
            tbody.innerHTML =""
            response.map(item=>{
            const row = document.createElement("tr");
            row.innerHTML = `
                <td>${item.id}</td>
                <td>${item.producto}</td>
                <td>${item.precio}</td>
                <td>${item.cantidad}</td>
                <td>
                    <button class="edit" onclick="editarProducto(${item.id})" >Editar</button>
                    <button class="delete" onclick="eliminarProducto(${item.id})">Eliminar</button>
                </td>
            `;
            tbody.appendChild(row);
        })
        }
    }catch(error) {
        console.log(error)
        setAlert("Dato no encontrado", "error")
    }
})

// funcion para obtener datos
async function ObtenerDatos () {
    try{
        const tbody = document.getElementById("table-body")
        tbody.innerHTML =""
        const response = await fetch("./php/index.php").then(res=>res.json());
        gobalData = response
        console.log("global Data ", gobalData)
        response.map(item=>{
            const row = document.createElement("tr");
            row.innerHTML = `
                <td>${item.id}</td>
                <td>${item.producto}</td>
                <td>${item.precio}</td>
                <td>${item.cantidad}</td>
                <td>
                    <button class="edit" onclick="editarProducto(${item.id})" >Editar</button>
                    <button class="delete" onclick="eliminarProducto(${item.id})">Eliminar</button>
                </td>
            `;
            tbody.appendChild(row);
        }
        )
        console.log(response)
    }catch (error) {
        console.log("GET ERROR: ", error)
    }
}

// editar productos
async function editarProducto(id) {
    const datosEditar = gobalData.filter(item=>item.id === id)
    
    
    console.log(datosEditar)
    if(datosEditar.length <= 1){
        datosEditar.map(item=>{
            codigoInput.setAttribute("value", item.codigo)
            productoInput.setAttribute("value", item.producto)
            precioInput.setAttribute("value", item.precio)
            cantidadInput.setAttribute("value", item.cantidad)
            idInput.setAttribute("value", item.id)
        })
        await inputSubmit.removeAttribute("value")
        await inputSubmit.setAttribute("value", "Editar")
    }
}

// eliminar productos
async function eliminarProducto(id) {
    await fetch("./php/index.php", {
            method: "DELETE",
             headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify({id: id})
        }).finally(ObtenerDatos());
        setAlert("Datos eliminado")
}

function setAlert(message, type = "success", duration = 2200) {
    const alertDiv = document.getElementById("alert");
    let icon = "";
    if (type === "success") icon = "✅";
    if (type === "error") icon = "❌";
    if (type === "info") icon = "ℹ️";
    alertDiv.innerHTML = `<span class="alert-icon">${icon}</span> ${message}`;
    alertDiv.className = ""; // limpia clases previas
    alertDiv.classList.add(type);
    alertDiv.style.display = "flex";
    alertDiv.style.opacity = "1";
    setTimeout(() => {
        alertDiv.style.opacity = "0";
        setTimeout(() => {
            alertDiv.style.display = "none";
        }, 350);
    }, duration);
}
